<?php

    namespace Acme;

    use DateTime;
    use Exception;

    class User extends Database
    {
        private $_userData;
        private $_sessionName;
        private $_cookieName;
        private $_isLoggedIn;

        /**
         * User constructor.
         * @param null $user
         */
        function __construct($user = null)
        {
            parent::__construct();

            $this->_sessionName = getenv('AUTH_SESSION');
            $this->_cookieName = getenv('AUTH_REMEMBER');

            if(!$user) {
                if(Session::exists($this->_sessionName)) {
                    $user = Session::get($this->_sessionName);

                    if($this->find($user)) {
                        $this->_isLoggedIn = true;
                    }else {
                        $this->logout();
                    }
                }
            }else {
                $this->find($user);
            }
        }

        /**
         * Login.
         *
         * @param null $username
         * @param null $password
         * @param bool $remember
         * @return bool
         */
        public function login($username = null, $password = null, $remember = false)
        {
            if(!$username && !$password && $this->exists()) {
                Session::put($this->_sessionName, $this->userData()->id);
            }else {
                $user = $this->find($username);

                if($user && $this->userData()->active == true) {
                    if(password_verify($password, $this->userData()->password)) {
                        Session::put($this->_sessionName, $this->userData()->id);
                        if($remember) {
                            $rememberIdentifier = randomString(128);
                            $rememberToken = randomString(128);

                            $week = new DateTime('+1 week');

                            $this->updateRememberCredentials($this->userData()->id, $rememberIdentifier, Hash::createHash($rememberToken));
                            Cookie::put($this->_cookieName, "{$rememberIdentifier}___{$rememberToken}", $week->getTimestamp());
                        }
                        return true;
                    }
                    return false;
                }
            }
            return false;
        }

        /**
         * Find user.
         *
         * @param null $user
         * @return bool
         */
        public function find($user = null)
        {

            if($user) {
                if(is_numeric($user)) {
                    $data = $this->_db->get('users', '*', ['id' => $user]);
                }else {
                    $data = $this->_db->get('users', '*', [
                        "OR" => [
                            'username' => $user,
                            'email' => $user,
                        ]
                    ]);
                }

                if($data) {
                    $this->_userData = $data;
                    return true;
                }
            }
            return false;
        }

        /**
         * Create new User.
         *
         * @param $fields
         * @return array
         */
        public function create($fields)
        {
            return $this->_db->insert('users', $fields);
        }

        /**
         * Update User.
         *
         * @param $fields
         * @return int
         */
        public function update($fields)
        {
            return $this->_db->update('users', $fields, ['id' => $this->userData()->id]);
        }

        /**
         * Find user by identifier.
         *
         * @param $identifier
         * @return bool
         */
        public function findByIdentifier($identifier)
        {
            $user = $this->_db->get('users', '*', ['remember_identifier' => $identifier]);

            if(!$user) {
                return false;
            }else {
                $this->_userData = $user;
                return true;
            }
        }

        /**
         * Activate account.
         *
         * @param $id
         */
        public function activateAccount($id)
        {
            $this->_db->update('users', [
                'active' 		=> true,
                'active_hash' 	=> null
            ], ['id' => $id]);
        }

        /**
         * For validation, check if is unique from database.
         *
         * @param $field
         * @param $value
         * @return bool
         */
        public function checkUnique($field, $value)
        {
            if($this->_db->select('users', $field, [$field => $value])) {
                return true;
            }
            return false;
        }

        /**
         * Insert user permission.
         *
         * @param $lastInsertId
         * @return bool
         */
        public function insertUserPermission($lastInsertId)
        {
            $this->_db->insert('users_permissions', [
                'user_id' => $lastInsertId,
                'is_admin' => false
            ]);

            return true;
        }

        /**
         * Update remember credentials for "remember me" option.
         *
         * @param $id
         * @param $identifier
         * @param $token
         */
        private function updateRememberCredentials($id, $identifier, $token)
        {
            $this->_db->update('users', [
                'remember_identifier' => $identifier,
                'remember_token' => $token
            ], ['id' => $id]);
        }

        /**
         * Remove remember credentials.
         *
         * @param $id
         */
        public function removeRememberCredentials($id)
        {
            $this->updateRememberCredentials($id, null, null);
        }

        /**
         * Logout.
         */
        public function logout()
        {
            $this->removeRememberCredentials($this->userData()->id);

            Session::delete($this->_sessionName);
            Cookie::delete($this->_cookieName);
        }

        /**
         * Check if user exists.
         *
         * @return bool
         */
        public function exists()
        {
            return (!empty($this->_userData)) ? true : false;
        }

        /**
         * Get user data.
         *
         * @return mixed
         */
        public function userData()
        {
            return $this->_userData;
        }

    }