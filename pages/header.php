<header>
    <div class="headercontainer">
      <div class="title">Camagru</a></div>
      <nav>
        <? if ($_SESSION['logged_user'] !== null): ?>
          <a id="" href="webcam.php">Photobooth</a>
          <a id="" href="mygallery.php">Ma gallerie</a>
          <a id="" href="gallery.php?page=1">Gallerie</a>
          <a id="" href="logout.php">Deconnexion</a>
        <? else: ?>
          <a id="" href="home.php">Se connecter</a>
        <? endif; ?>
      </nav>
    </div>
</header>
