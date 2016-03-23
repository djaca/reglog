<?php

    namespace Acme;

    class Input
    {
        /**
         * Checks if request exists as POST or GET.
         *
         * @param string $type
         * @return bool
         */
        public static function exists($type = 'post')
        {
            switch($type) {
                case 'post':
                    return (!empty($_POST)) ? true : false;
                    break;
                case 'get':
                    return (!empty($_GET)) ? true : false;
                    break;
                default:
                    return false;
                    break;
            }
        }

        /**
         * Get input.
         *
         * @param $item
         * @return string
         */
        public static function get($item) {
            if(isset($_POST[$item])) {
                return $_POST[$item];
            }elseif(isset($_GET[$item])) {
                return $_GET[$item];
            }
            return '';
        }

        public static function all()
        {
            if(isset($_POST)) {
                return $_POST;
            }elseif(isset($_GET)) {
                return $_GET;
            }
            return '';
        }
    }