<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Authentication</title>

    <!-- CSS -->
    <link href="<?= getenv('BASE_URL') ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= getenv('BASE_URL') ?>assets/css/styles.css" rel="stylesheet">

    <!-- JS -->
    <script src="<?= getenv('BASE_URL') ?>assets/js/jquery.min.js"></script>
    <script src="<?= getenv('BASE_URL') ?>assets/js/bootstrap.min.js"></script>
</head>
<body>
<?php require INC_ROOT . '/layouts/navigation.php'?>
<div class="container">
    <div class="row">
        <?= displayMessage(); ?>
        <?php
            if(isset($errors)) {
                echo displayErrors($errors);
            }
        ?>
