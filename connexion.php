<?php 
require 'connexion_bdd.php';

session_start();


// Vérifiez si la session contient certaines données (par exemple, 'user_id' et 'user_email')
if (!empty($_SESSION['user_id']) && !empty($_SESSION['user_email'])) {
    // La session est remplie avec les données nécessaires
    // Redirigez l'utilisateur vers la page souhaitée après connexion
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérification des identifiants dans la base de données
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      
        // Vérification du mot de passe
        if ($user['password']==$password) {
            // Authentification réussie, enregistre les informations de l'utilisateur en session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Redirection vers la page de succès ou votre page d'accueil
            /*echo '<pre>';
print_r($_SESSION);
echo '</pre>';*/
            header("Location: profil.php");
            exit();
        } else {
            // Mot de passe incorrect, affiche un message d'erreur
            $error = "Mot de passe incorrect. Veuillez réessayer.";
        }
    } else {
        // Utilisateur non trouvé, affiche un message d'erreur
        $error = "L'adresse email saisie n'existe pas.";
    }
}
require 'require/header.php';
?>
<div class="connexion">
        <div class="container">
            <h2>connexion</h2>
            
            <form method="POST" action="">
        <label for="email">Email :</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Mot de passe :</label>
        <input type="password" name="password" id="password" required>

        <input type="submit" value="Se connecter">
    </form>
            
           
        </div>
    </div>

    <?php
    require 'require/footer.php';
