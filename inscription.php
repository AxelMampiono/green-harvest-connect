<?php 
require 'connexion_bdd.php';
require 'fonction.php';
session_start();

// Vérifiez si la session contient certaines données (par exemple, 'user_id' et 'user_email')
if (!empty($_SESSION['user_id']) && !empty($_SESSION['user_email'])) {
    // La session est remplie avec les données nécessaires
    // Redirigez l'utilisateur vers la page souhaitée après connexion
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = []; // Initialise un tableau pour stocker les éventuelles erreurs de validation
    if (isset($_POST['telephone']) && !empty($_POST['telephone'])){
$phoneNumber = $_POST["telephone"];

    // Supprimer tous les caractères qui ne sont pas des chiffres
    $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

    // Vérifier si le numéro de téléphone a une longueur de 10 chiffres
    if (strlen($phoneNumber) === 10) 
    {
        // Vérifier si le numéro de téléphone commence par 06 ou 07 (pour les mobiles)
        if (preg_match('/^(06|07)/', $phoneNumber)) 
        {
        } else 
        {
            $errors['phone'] = "Le numéro de téléphone doit commencer par 06 ou 07 (pour les mobiles).";
        }
    } else {
        $errors['phone'] = "Le numéro de téléphone doit contenir 10 chiffres.";
    }

    }
    
    

    // Validation du champ 'name'
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = $_POST['name'];
        
        // Vérification de la longueur minimale du nom (au moins 3 caractères de texte)
        if (strlen($name) < 3) {
            $errors['name'] = "Le champ 'Nom' doit contenir au moins 3 caractères de texte.";
        }
    } else {
        $errors['name'] = "Le champ 'Nom' est requis.";
    }

    // Validation du champ 'firstname'
    if (isset($_POST['firstname']) && !empty($_POST['firstname'])) {
        $firstname = $_POST['firstname'];
        
        // Vérification de la longueur minimale du prénom (au moins 3 caractères de texte)
        if (strlen($firstname) < 3) {
            $errors['firstname'] = "Le champ 'Prénom' doit contenir au moins 3 caractères de texte.";
        }
    } else {
        $errors['firstname'] = "Le champ 'Prénom' est requis.";
    }

    // Validation du champ 'email'
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $email = $_POST['email'];
        
        // Vérification de la validité de l'email à l'aide de la fonction filter_var
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "L'email saisi n'est pas valide.";
        } else {
            // Vérification si l'email existe déjà
            $stmt = $conn->prepare("SELECT email FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $existingEmail = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($existingEmail) {
                $errors['email'] = "L'email existe déjà. Vous serez diriger vers la page de connexion";
                header("refresh:5;url=connexion.php");

            }
        }
    } else {
        $errors['email'] = "Le champ 'Email' est requis.";
    }

    // Validation du champ 'password'
    if (isset($_POST['password']) && !empty($_POST['password'])) {
        $password = $_POST['password'];
        
        // Vérification de la complexité du mot de passe à l'aide d'une regex
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
            $errors['password'] = "Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre, un caractère spécial (@$!%*?&) et avoir une longueur minimale de 8 caractères.";
        }
    } else {
        $errors['password'] = "Le champ 'Mot de passe' est requis.";
    }
    if (isset($_POST['role']) && !empty($_POST['role'])) {
        $role = $_POST['role'];
    }

    // Vérifie s'il y a des erreurs de validation
    if (empty($errors)) {
        // Tous les champs sont valides, procéder au traitement du formulaire
        // ...
        // Prépare la requête d'insertion
        $stmt = $conn->prepare("INSERT INTO users (name, firstname, email, telephone, password, role) VALUES (:name, :firstname, :email, :telephone, :password, :role)");
            
        // Lie les valeurs aux paramètres de la requête
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telephone', $phoneNumber);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);

        
        // Redirection vers une autre page après l'insertion réussie
        header("Location: connexion.php");
        exit();
        // Redirection vers une autre page après le traitement réussi
        //header("Location: success.php");
        
    }
}
    require 'require/header.php';
?>
   
  
    <div class="inscription">
        <div class="container">
            <h2>Inscription</h2>
            
            <form method="POST" action="">
                

                <label for="name">Nom :</label>
                <input type="text" name="name" id="name" required>
                <?php if (isset($errors['name'])): ?>
                <p class="error-message"><?php echo $errors['name']; ?></p>
            <?php endif; ?>
                <label for="firstname">Prenom :</label>
                <input type="text" name="firstname" id="firstname" required>
                <?php if (isset($errors['firstname'])): ?>
                <p class="error-message"><?php echo $errors['firstname']; ?></p>
            <?php endif; ?>
                <label for="email">Email :</label>
                <input type="email" name="email" id="email" required>
                <?php if (isset($errors['email'])): ?>
                <p class="error-message"><?php echo $errors['email']; ?></p>
            <?php endif; ?>
                <label for="password">Mot de passe :</label>
                <input type="password" name="password" id="password" required>
                <?php if (isset($errors['password'])): ?>
                <p class="error-message"><?php echo $errors['password']; ?></p>
            <?php endif; ?>
            <label for="phone">Telephone :</label>
                <input type="number" name="telephone" id="telephone" required>
                <?php if (isset($errors['phone'])): ?>
                <p class="error-message"><?php echo $errors['phone']; ?></p>
            <?php endif; ?>
                <label for="role">Rôle :</label>
                <select name="role" id="role">
                    <option value="vendeur">Vendeur</option>
                    <option value="acheteur">Acheteur</option>
                    <option value="les_deux">Les deux</option>
                </select>

                <button type="submit">S'inscrire</button>
            </form>
            
           
        </div>
    </div>


<?php
    require 'require/footer.php';
