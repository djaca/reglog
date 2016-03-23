<?php
    use Acme\Hash;
    use Acme\Input;
    use Acme\Redirect;
    use Acme\Token;
    use Acme\User;
    use Valitron\Validator;

    require $_SERVER['DOCUMENT_ROOT'] . '/core/init.php';

    if(!isLoggedIn()) {
        Redirect::to(getenv('BASE_URL'));
    }

    $user = new User();

    if(Input::exists()) {
        if(Token::check(Input::get(getenv('CSRF_KEY')))) {

            $v = new Validator(Input::all());
            $v->rules([
                'required'      => [
                    ['password_old'],
                    ['password'],
                    ['password_confirm'],
                ],
                'lengthMin'     => [
                    ['password_old', 4],
                    ['password', 4],
                    ['password_confirm', 4],
                ],
                'equals'        => [
                    ['password_confirm', 'password']
                ],
            ]);

            if($v->validate()) {
                $identifier = randomString(64);

                if(!Hash::passwordCheck(Input::get('password_old'), $user->userData()->password)) {
                    die(var_dump('ne slaze se'));
                }else {
                    try {
                        $user->update([
                            'password' => Hash::password(Input::get('password'))
                        ]);

                    }catch (Exception $e) {
                        var_dump($e->getMessage());
                    }

                    //  Send email
                    if(sendEmail($user->userData()->email, 'You changed your password', INC_ROOT . '/layouts/email/auth/change.html')) {
                        $user->logout();
                        Redirect::to('index', ['success' => 'You changed your password. You may login with new password']);
                    }else {
                        die(var_dump('Can`t send email'));
                    }
                }
            }else {
                $errors = $v->errors();
            }
        }
    }

?>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/layouts/header.php' ?>
    <div class="col-xs-4">
        <form class="form-horizontal" action="" method="post">
            <div class="form-group">
                <label for="password_old" class="control-label">Old password</label>
                <input type="password" class="form-control" name="password_old" id="password_old" autofocus>
            </div>
            <div class="form-group">
                <label for="password" class="control-label">Password</label>
                <input type="password" class="form-control" name="password" id="password">
            </div>
            <div class="form-group">
                <label for="password_confirm" class="control-label">Confirm password</label>
                <input type="password" class="form-control" name="password_confirm" id="password_confirm">
            </div>
            <div class="form-group">
                <input type="hidden" name="<?= getenv('CSRF_KEY') ?>" value="<?= Token::generate() ?>">
                <button type="submit" class="btn btn-primary">Change</button>
            </div>
        </form>
    </div>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/layouts/footer.php' ?>