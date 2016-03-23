<?php

    namespace Acme;

    class Hash
    {
        /**
         * Create password based on input.
         *
         * @param $password
         * @return bool|string
         */
        public static function password($password)
        {
            return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
        }

        /**
         * Check passwords.
         *
         * @param $password
         * @param $hash
         * @return bool
         */
        public static function passwordCheck($password, $hash)
        {
            return password_verify($password, $hash);
        }

        /**
         * Create hash.
         *
         * @param $input
         * @return string
         */
        public static function createHash($input)
        {
            return hash('sha256', $input);
        }

        /**
         * Check hashes.
         *
         * @param $knownHash
         * @param $userHash
         * @return bool
         */
        public static function hashCheck($knownHash, $userHash)
        {
//            return hash_equals($knownHash, $userHash);
            return ($knownHash === $userHash);
        }

        /**
         * Create unique string.
         *
         * @return string
         */
        public static function unique()
        {
            return self::createHash(uniqid());
        }
    }