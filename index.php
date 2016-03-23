<?php
    use Acme\User;

    require $_SERVER['DOCUMENT_ROOT'] . '/core/init.php';

    if(isLoggedIn()) {
        $user = new User();
    }

?>

<?php require $_SERVER['DOCUMENT_ROOT'] . '/layouts/header.php' ?>
    <h3 class="text-center">Registration/Login system</h3>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/layouts/footer.php' ?>