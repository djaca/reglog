<?php

    namespace Acme;

    class Token
    {
        /**
         * Generate token.
         *
         * @return mixed
         */
        public static function generate() {
            return Session::put(getenv('CSRF_KEY'), randomString());
        }

        /**
         * Check if token exists.
         *
         * @param $token
         * @return bool
         */
        public static function check($token)
        {
            $tokenName = getenv('CSRF_KEY');

            if(Session::exists($tokenName) && $token === Session::get($tokenName)) {
                Session::delete($tokenName);
                return true;
            }

            return false;
        }
    }