<?php
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
                'alpha'     => [
                    ['first_name'],
                    ['last_name'],
                ],
                'lengthMin' => [
                    ['first_name', 4],
                    ['last_name', 4],
                ],
            ]);

            if($v->validate()) {
                $userUpdate = $user->update([
                    'first_name'    => Input::get('first_name'),
                    'last_name'     => Input::get('last_name')
                ]);

                if($userUpdate === 0) {
                    Redirect::to('user/profile', ['info' => 'Nothing is changed.']);
                }

                Redirect::to('user/profile', ['success' => 'Profile updated.']);
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
                <label for="first_name" class="control-label">First name</label>
                <input type="text" class="form-control" name="first_name" id="first_name" value="<?= isset($user->userData()->first_name) ? $user->userData()->first_name : '' ?>">
            </div>
            <div class="form-group">
                <label for="last_name" class="control-label">Last name</label>
                <input type="text" class="form-control" name="last_name" id="last_name" value="<?= isset($user->userData()->last_name) ? $user->userData()->last_name : '' ?>">
            </div>
            <div class="form-group">
                <input type="hidden" name="<?= getenv('CSRF_KEY') ?>" value="<?= Token::generate() ?>">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/layouts/footer.php' ?>