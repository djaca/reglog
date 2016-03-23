<?php
    use Acme\Redirect;
    use Acme\User;

    require $_SERVER['DOCUMENT_ROOT'] . '/core/init.php';

    $user = new User();
    $user->logout();

    Redirect::to('index', ['success' => 'You logged out successfully']);