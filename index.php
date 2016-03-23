<?php
    use Acme\User;

    require $_SERVER['DOCUMENT_ROOT'] . '/core/init.php';

    if(isLoggedIn()) {
        $user = new User();
    }

?>

<?php require $_SERVER['DOCUMENT_ROOT'] . '/layouts/header.php' ?>
    <h3 class="text-center">Registration / Login project</h3>

    <p>This is basic Registration / Login project. If you want to try it, you must create tables in database. First, <strong>create database</strong>, then rename <strong>.env.example</strong> to <strong>.env</strong> and populate it. Finally click <a href="db.php">this</a> link to create those tables.</p>
    <p>Change medoo.php, line 631, from PDO::FETCH_ASSOC to PDO::FETCH_OBJ!! </p>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/layouts/footer.php' ?>