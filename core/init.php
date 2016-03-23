<?php
    use Acme\Cookie;
    use Acme\Hash;
    use Acme\Session;
    use Acme\User;

    session_start();

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    define('INC_ROOT', dirname(__DIR__));

    require dirname(__DIR__) . '/vendor/autoload.php';

    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();

    $dotenv = new Dotenv\Dotenv(INC_ROOT);
    $dotenv->load();

    // Initialize Database
    $database = new medoo([
        'database_type' => getenv('DB_TYPE'),
        'database_name' => getenv('DB_DATABASE'),
        'server' => getenv('DB_HOST'),
        'username' => getenv('DB_USERNAME'),
        'password' => getenv('DB_PASSWORD'),
        'charset' => getenv('DB_CHARSET')
    ]);

    if(Cookie::exists(getenv('AUTH_REMEMBER')) && !Session::exists(getenv('AUTH_SESSION')))
    {
        $data = Cookie::get(getenv('AUTH_REMEMBER'));
        $credentials = explode('___', $data);

        if(!empty(trim($data)) || count($credentials) === 2) {
            $identifier = $credentials[0];
            $token = Hash::createHash($credentials[1]);

            $user = new User();

            if($user->findByIdentifier($identifier)) {
                if(Hash::hashCheck($token, $user->userData()->remember_token)) {
                    $user->login();
                }else {
                    $user->removeRememberCredentials($user->userData()->id);
                }
            }
        }
    }