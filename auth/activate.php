<?php
    use Acme\hash;
    use Acme\Redirect;
    use Acme\User;

    require $_SERVER['DOCUMENT_ROOT'] . '/core/init.php';

    $email = $_GET['email'];
    $hashedIdentifier = Hash::createHash($_GET['identifier']);

    $user = new User();

    if(!$user->find($email) || !Hash::hashCheck($hashedIdentifier, $user->userData()->active_hash)) {
        Redirect::to(404);
    }else {
        $user->activateAccount($user->userData()->id);
        Redirect::to(getenv('BASE_URL'), ['success' => 'Your account has been activated and you can sign in.']);
    }

