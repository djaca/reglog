<?php

    namespace Acme;

    class Cookie
    {
        /**
         * Check if cookie exists.
         *
         * @param $name
         * @return bool
         */
        public static function exists($name)
        {
            return (isset($_COOKIE[$name])) ? true : false;
        }

        /**
         * Get cookie.
         *
         * @param $name
         * @return mixed
         */
        public static function get($name)
        {
            return $_COOKIE[$name];
        }

        /**
         * Set cookie.
         *
         * @param $name
         * @param $value
         * @param $expiry
         * @return bool
         */
        public static function put($name, $value, $expiry)
        {
            if(setCookie($name, $value, $expiry, '/', null, null, true)) {
                return true;
            }
            return false;
        }

        /**
         * Delete cookie.
         *
         * @param $name
         */
        public static function delete($name)
        {
            self::put($name, '', time() - 1);
        }
    }