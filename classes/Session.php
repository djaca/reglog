<?php

    namespace Acme;

    class Session
    {
        /**
         * Check if session exists.
         *
         * @param $name
         * @return bool
         */
        public static function exists($name)
        {
            return (isset($_SESSION[$name])) ? true : false;
        }
        /**
         * Get session.
         *
         * @param $name
         * @return mixed
         */
        public static function get($name)
        {
            return $_SESSION[$name];
        }


        /**
         * Set session.
         *
         * @param $name
         * @param $value
         * @return mixed
         */
        public static function put($name, $value)
        {
            return $_SESSION[$name] = $value;
        }



        /**
         * Delete session.
         *
         * @param $name
         */
        public static function delete($name)
        {
            if(self::exists($name)) {
                unset($_SESSION[$name]);
            }
        }

        /**
         * Flash message.
         *
         * @param $name
         * @param string $string
         * @return string
         */
        public static function flash($name, $string = '')
        {
            if(self::exists($name)) {
                $session = self::get($name);
                self::delete($name);
                return $output = '<div class="alert alert-' . $name . ' fade in">
                            <span class="close" data-dismiss="alert">&times;</span>' . $session . '</div>';
            }else {
                self::put($name, $string);
            }
        }
    }