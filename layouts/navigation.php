<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?= getenv('BASE_URL') ?>">Authentication</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <?php if(isLoggedIn()) : ?>
            <ul class="nav navbar-nav navbar-right">
                <li <?= stripos($_SERVER['REQUEST_URI'], 'profile.php') ? 'class="active"' : '' ?>><a href="<?= getenv('BASE_URL') ?>user/profile.php"><?= $user->userData()->username ?></a></li>
                <li><a href="<?= getenv('BASE_URL') ?>auth/logout.php">Logout</a></li>
            </ul>
            <?php else : ?>
                <ul class="nav navbar-nav">
                    <li <?= stripos($_SERVER['REQUEST_URI'], 'login.php') ? 'class="active"' : '' ?>><a href="<?= getenv('BASE_URL') ?>auth/login.php">Login</a></li>
                    <li <?= stripos($_SERVER['REQUEST_URI'], 'register.php') ? 'class="active"' : '' ?>><a href="<?= getenv('BASE_URL') ?>auth/register.php">Register</a></li>
                    <li><a href="<?= getenv('BASE_URL') ?>db.php">Reset database</a></li>
                </ul>
            <?php endif; ?>
        </div><!--/.nav-collapse -->
    </div>
</nav>
