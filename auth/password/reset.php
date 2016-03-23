<?php
    use Acme\Hash;
    use Acme\Input;
    use Acme\Redirect;
    use Acme\User;


    require $_SERVER['DOCUMENT_ROOT'] . '/core/init.php';

    if(Input::exists('get')) {
        $hashedIdentifier = Hash::createHash($_GET['identifier']);

        $user = new User();

        if(!$user->find(Input::get('email')) || !Hash::hashCheck($hashedIdentifier, $user->userData()->recover_hash)) {
            Redirect::to(404);
        }else {
            // random password.
            $randomPassword = randomString(6);
            try {
                $user->update([
                    'password' => Hash::password($randomPassword),
                    'recover_hash' => null
                ]);
            }catch (Exception $e) {
                die(var_dump($e->getMessage()));
            }

            // Send email
            if(sendEmail($user->userData()->email,
                'Password recover',
                INC_ROOT . '/layouts/email/auth/new_password.html',
                [
                    'password' => $randomPassword
                ])) {
                $user->logout();
                Redirect::to('index', ['success' => 'We emailed password instructions to ' . Input::get('email') . '.']);
            }else {
                die(var_dump('Can`t send email'));
            }
        }
    }else {
        Redirect::to(404);
    }