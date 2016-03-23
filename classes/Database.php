<?php

    namespace Acme;

    use medoo;
    use PDOException;

    class Database
    {
        protected $_db;

        /**
         * Connect to database.
         *
         * Database constructor.
         */
        public function __construct()
        {
            try {
                // Initialize Database
                $this->_db = new medoo([
                    'database_type' => getenv('DB_TYPE'),
                    'database_name' => getenv('DB_DATABASE'),
                    'server' => getenv('DB_HOST'),
                    'username' => getenv('DB_USERNAME'),
                    'password' => getenv('DB_PASSWORD'),
                    'charset' => getenv('DB_CHARSET')
                ]);
            }catch(PDOException $e) {
                die($e->getMessage());
            }
        }
    }