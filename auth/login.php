<?php
    use Acme\Input;
    use Acme\Redirect;
    use Acme\Token;
    use Acme\User;
    use Valitron\Validator;

    require $_SERVER['DOCUMENT_ROOT'] . '/core/init.php';

    if(Input::exists()) {
        if(Token::check(Input::get('token'))) {

            $v = new Validator(Input::all());
            $v->rules([
                'required' => [
                    ['username'],
                    ['password'],
                ]
            ]);

            if($v->validate()) {

                $remember = (Input::get('remember') === 'on') ? true : false;

                $user = new User();

                // Try to login
                if($user->login(Input::get('username'), Input::get('password'), $remember)) {
                    Redirect::to(getenv('BASE_URL'), ['success' => 'You are now signed in']);
                }else {
                    Redirect::to('auth/login', ['danger' => 'Could not log you in!']);
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
                <label for="username" class="control-label">Username</label>
                <input type="text" class="form-control" name="username" id="username" value="<?= !empty(Input::get('username')) ? Input::get('username') : '' ?>" autofocus>
            </div>
            <div class="form-group">
                <label for="password" class="control-label">Password</label>
                <input type="password" class="form-control" name="password" id="password">
            </div>
            <div class="form-group">
                <label for="remember">
                    <input type="checkbox" name="remember" id="remember"> Remember me
                </label>
                <a class="pull-right" href="<?= getenv('BASE_URL') ?>auth/password/recover.php">Lost password?</a>
            </div>
            <div class="form-group">
                <input type="hidden" name="token" value="<?= Token::generate() ?>">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>
    </div>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/layouts/footer.php' ?>