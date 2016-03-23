<?php

    namespace Acme;

    class Redirect
    {
        /**
         * Redirect with optional flash message.
         *
         * @param null $location
         * @param null $data
         */
        public static function to($location = null, $data = null)
        {
            if($location) {
                if(is_numeric($location)) {
                    switch($location) {
                        case 404:
                            header('HTTP/1.0 404 Not found');
                            include INC_ROOT . '/inc/errors/404.php';
                            exit();
                            break;
                        default:
                            header('HTTP/1.0 404 Not found');
                            include INC_ROOT . '/inc/errors/404.php';
                            exit();
                            break;
                    }
                }

                if(!is_string($location)) {
                    $location = $_SERVER['SCRIPT_NAME'];
                }

                if($data && is_array($data)) {
                    foreach ($data as $level => $msg) {
                        Session::flash($level, $msg);
                    }
                }

                if($location === getenv('BASE_URL')) {
                    header('Location: ' . getenv('BASE_URL'));
                    exit();
                }

                header('Location: ' . getenv('BASE_URL') . $location . '.php');
                exit();
            }
        }
    }