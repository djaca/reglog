<?php
    use Acme\Hash;
    use Acme\Input;
    use Acme\Redirect;
    use Acme\Token;
    use Acme\User;
    use Valitron\Validator;

    require $_SERVER['DOCUMENT_ROOT'] . '/core/init.php';

    if(Input::exists()) {
        if(Token::check(Input::get(getenv('CSRF_KEY')))) {

            $v = new Validator(Input::all());

            //  Custom rule for unique values in database
            $v->addRule('unique', function($field, $value, array $params, array $fields) {
                $user = new User();
                return !$user->checkUnique($field, $value);
            }, ' already exists.');

            $v->rules([
                'required'  => [
                    ['username'],
                    ['email'],
                    ['password'],
                    ['password_confirm'],
                ],
                'email'     => [
                    ['email'],
                ],
                'unique'    => [
                    ['username'],
                    ['email'],
                ],
                'alphaNum'  => [
                    ['username'],
                ],
                'lengthMin' => [
                    ['username', 6],
                    ['password', 8],
                ],
                'equals'    => [
                    ['password_confirm', 'password']
                ],
            ]);

            $v->labels([
                'username'          => 'Username',
                'email'             => 'Email address',
                'password'          => 'Password',
                'password_confirm'  => 'Confirm password',
            ]);

            if($v->validate()) {
                $user = new User();
                $identifier = randomString(64);
                try {
                    $lastInsertId = $user->create([
                        'email'         => Input::get('email'),
                        'username'      => Input::get('username'),
                        'password'      => Hash::password(Input::get('password')),
                        'active'        => false,
                        'active_hash'   => Hash::createHash($identifier)
                    ]);

                    $user->insertUserPermission($lastInsertId);
                }catch (Exception $e) {
                    die(var_dump($e->getMessage()));
                }

                //  Send email
                if(sendEmail(Input::get('email'),
                    'Thanks for registering',
                    INC_ROOT . '/layouts/email/auth/registered.html',
                    [
                        'identifier' => $identifier,
                        'page' => 'auth/activate.php'
                    ])) {
                    Redirect::to(getenv('BASE_URL'), ['success' => 'You registered successfully. Email confirmation sent.']);
                }else {
                    die(var_dump('Can`t send email'));
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
                <label for="email" class="control-label">Email:</label>
                <input type="text" class="form-control" name="email" id="email" value="<?= !empty(Input::get('email')) ? Input::get('email') : '' ?>" autofocus>
            </div>
            <div class="form-group">
                <label for="username" class="control-label">Username</label>
                <input type="text" class="form-control" name="username" id="username" value="<?= !empty(Input::get('username')) ? Input::get('username') : '' ?>">
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
                <button type="submit" class="btn btn-primary">Register</button>
            </div>
        </form>
    </div>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/layouts/footer.php' ?>