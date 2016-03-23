<?php

    use Acme\Hash;
    use Acme\Input;
    use Acme\Redirect;
    use Acme\Token;
    use Acme\User;
    use Valitron\Validator;

    require $_SERVER['DOCUMENT_ROOT'] . '/core/init.php';;

    if(Input::exists()) {
        if(Token::check(Input::get(getenv('CSRF_KEY')))) {

            $v = new Validator(Input::all());

            $v->rule('required', ['email']);
            $v->rule('email', ['email']);

            if($v->validate()) {
                $user = new User();
                if(!$user->find(Input::get('email'))) {
                    $errors['email'][] = 'Can`t find user with that email.';
                }else {
                    $identifier = randomString(64);

                    try {
                        $user->update([
                            'recover_hash' => Hash::createHash($identifier)
                        ]);
                    }catch (Exception $e) {
                        die(var_dump($e->getMessage()));
                    }

                    //  Send email
                    if(sendEmail(Input::get('email'),
                        'Recover your password',
                        INC_ROOT . '/layouts/email/auth/recover.html',
                        [
                            'identifier' => $identifier,
                            'page' => 'auth/password/reset.php'
                        ])) {
                        Redirect::to('index', ['success' => 'We have emailed instructions for password recovery']);
                    }else {
                        die(var_dump('Can`t send email'));
                    }
                }

            }else {
                $errors = $v->errors();
                die(var_dump($errors));
            }
        }
    }


?>

<?php require $_SERVER['DOCUMENT_ROOT'] . '/layouts/header.php' ?>
    <div class="col-xs-4">
        <form class="form-horizontal" action="" method="post">
            <div class="form-group">
                <p>Enter your email to start your password recovery...</p>
                <label for="email" class="control-label">Email:</label>
                <input type="text" class="form-control" name="email" id="email" value="<?= !empty(Input::get('email')) ? Input::get('email') : '' ?>" autofocus>
            </div>
            <div class="form-group">
                <input type="hidden" name="<?= getenv('CSRF_KEY') ?>" value="<?= Token::generate() ?>">
                <button type="submit" class="btn btn-primary">Reset password</button>
            </div>
        </form>
    </div>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/layouts/footer.php'?>