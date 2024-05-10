<?php
   require_once('functions.php');
   require_once('config.php');
?>

<nav>
  <div class="container-fluid">
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav mx-auto">
        <?php foreach ($menu as $key => $item) { ?>
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" 
                href="<?php echo $item; ?>">
                <?php echo $key; ?>
              </a>
            </li>
        <?php } ?>
      </ul>
      <?php if (is_logged_in()) { ?>
        <?php $_SESSION['role']; ?>
        <br>
        <a class="btn-primary" href="./fooldal/logout.php">Kijelentkezés</a>
      <?php } else { ?>
        <br>
        <a class="btn-login" href="../bejelentkezes/login.php" style="margin-top: 5px;">Bejelentkezés</a>
      <?php } ?>
    </div>
  </div>
</nav>
