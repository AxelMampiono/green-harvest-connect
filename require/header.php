<!DOCTYPE html>
<html>
<head>
    <title>Mon template</title>
    <link rel="icon" type="image/png" href="img/6cf2158213424b17b56bbbe435165952__1_-removebg-preview.png">

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
</head>
<body>
    <header>
        <img src="img/logo5.jpg" alt="Description de l'image" class="logo">
        <nav class="navbar">
          <ul>
            <li><a href="index.php" id="lien">Accueil</a></li>
            <li><a href="achat.php" id="lien">Achat</a></li>

          <?php
              // Vérifiez si l'utilisateur est connecté
              if (!empty($_SESSION['user_id']) && !empty($_SESSION['user_email'])) {
            ?>
              <!-- Affichez ces liens si l'utilisateur est connecté -->
              <li><a href="profil.php">Profil</a></li>
              <li><a href="deconnexion.php">Déconnexion</a></li>
            <?php
              } else {
            ?>
              <!-- Affichez ces liens si l'utilisateur n'est pas connecté -->
              <li><a href="inscription.php">Inscription</a></li>
              <li><a href="connexion.php">Connexion</a></li>
            <?php
              }
            ?>
          </ul>
          <span class="material-symbols-outlined" id="menu_hamburger">
            menu
            </span>
        </nav>
    </header>
      
    <main>