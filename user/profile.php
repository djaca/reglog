<?php
    use Acme\Redirect;
    use Acme\User;

    require $_SERVER['DOCUMENT_ROOT'] . '/core/init.php';

    if(!isLoggedIn()) {
        Redirect::to(getenv('BASE_URL'));
    }

    $user = new User();
?>

<?php require $_SERVER['DOCUMENT_ROOT'] . '/layouts/header.php' ?>

    <p>Username: <?= e($user->userData()->username); ?></p>
    <p>Email: <?= e($user->userData()->email); ?></p>
    <p>Full name: <?= e($user->userData()->first_name) . ' ' . e($user->userData()->last_name); ?></p>
    <p><a href="<?= getenv('BASE_URL') ?>user/update.php">Update profile</a></p>
    <p><a href="<?= getenv('BASE_URL') ?>user/change_password.php">Change password</a></p>

<?php require $_SERVER['DOCUMENT_ROOT'] . '/layouts/footer.php' ?>