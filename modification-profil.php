<?php 
    require 'connexion_bdd.php';
    session_start();
    if (empty($_SESSION['user_id']) || empty($_SESSION['user_email'])) {
        // Redirigez l'utilisateur vers la page de connexion si non connecté
        header('Location: connexion.php');
        exit();
    }
    $user_id = $_SESSION['user_id'];

    // Requête pour récupérer les informations de l'utilisateur à partir de la table "user"
    $query = "SELECT * FROM users WHERE id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    // Vérifiez si la requête a réussi
    if ($stmt->rowCount() > 0) {
        // Récupérer les données de l'utilisateur
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "Aucune information d'utilisateur trouvée.";
    }
    $query = "SELECT * FROM adresses WHERE seller_id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    // Vérifiez si l'adresse existe
    if ($stmt->rowCount() > 0) {
        $seller_address = $stmt->fetch(PDO::FETCH_ASSOC);
        $is_seller = true;
        $seller_address=true;
    }
    else{
        $is_seller = false;
        $seller_address=false;


    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer les données soumises du formulaire
        $name = $_POST['name'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $street_number= $_POST['street_number'];
        $street_name= $_POST['street_name'];
        $postal_code= $_POST['postal_code'];
        $city= $_POST['city'];
        $country= $_POST['country'];

        
        // Requête pour mettre à jour les informations de l'utilisateur dans la table "users"
        $update_query = "UPDATE users SET name = :name, firstname = :username, email = :email, role = :role WHERE id = :user_id";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bindParam(':name', $name);
        $update_stmt->bindParam(':username', $username);
        $update_stmt->bindParam(':email', $email);
        $update_stmt->bindParam(':role', $role);
        $update_stmt->bindParam(':user_id', $user_id);
        $_SESSION['role']=$role;
        if($seller_address==true){
            $update_address_query = "UPDATE adresses SET street_number = :street_number, street_name = :street_name, postal_code = :postal_code, city = :city, country = :country WHERE seller_id = :seller_id";
            $update_address_stmt = $conn->prepare($update_address_query);
            $update_address_stmt->bindParam(':street_number', $street_number);
            $update_address_stmt->bindParam(':street_name', $street_name);
            $update_address_stmt->bindParam(':postal_code', $postal_code);
            $update_address_stmt->bindParam(':city', $city);
            $update_address_stmt->bindParam(':country', $country);
            $update_address_stmt->bindParam(':seller_id', $user_id);
            $update_address_stmt->execute();

        }
        elseif($seller_address==false){
            $insert_address_query = "INSERT INTO adresses (street_number, street_name, postal_code, city, country, seller_id) VALUES (:street_number, :street_name, :postal_code, :city, :country, :seller_id)";
            $insert_address_stmt = $conn->prepare($insert_address_query);
            $insert_address_stmt->bindParam(':street_number', $street_number);
            $insert_address_stmt->bindParam(':street_name', $street_name);
            $insert_address_stmt->bindParam(':postal_code', $postal_code);
            $insert_address_stmt->bindParam(':city', $city);
            $insert_address_stmt->bindParam(':country', $country);
            $insert_address_stmt->bindParam(':seller_id', $user_id);

            // Exécuter la requête d'insertion de l'adresse
            $insert_address_stmt->execute();
        }

        // Exécuter la requête de mise à jour
        if ($update_stmt->execute()) {
            // Rediriger vers la page de profil après la mise à jour réussie
            header('Location: profil.php');
            exit();
        } else {
            // Erreur lors de la mise à jour, afficher un message d'erreur
            echo "Une erreur s'est produite lors de la mise à jour des informations.";
        }
    }
    require 'require/header.php';
?>

<div class="modif_profil">
    <h1>Modifier Profil</h1>
    <form action="" method="post">
        <label for="name">Nom complet:</label>
        <input type="text" id="name" name="name" value="<?php echo $user_data['name']; ?>" required>
        <br>

        <label for="username">Nom d'utilisateur:</label>
        <input type="text" id="username" name="username" value="<?php echo $user_data['firstname']; ?>" required>
        <br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $user_data['email']; ?>" required>
        <br>

        <label for="role">Rôle:</label>
        <select id="role" name="role">
            <option value="vendeur" <?php if ($user_data['role'] === 'vendeur') echo 'selected'; ?>>Vendeur</option>
            <option value="acheteur" <?php if ($user_data['role'] === 'acheteur') echo 'selected'; ?>>Acheteur</option>
            <option value="les_deux" <?php if ($user_data['role'] === 'les_deux') echo 'selected'; ?>>Les deux</option>
        </select>

        <br>
          
        <h2>Adresse du Vendeur</h2>
        <label for="street_number">Numéro de rue:</label>
    <input type="text" id="street_number" name="street_number" value="<?php echo $is_seller ? $seller_address['street_number'] : ''; ?>" onblur="checkEmptyAndFetchAddress(this, 'street_number')">

    <label for="street_name">Nom de rue:</label>
    <input type="text" id="street_name" name="street_name" value="<?php echo $is_seller ? $seller_address['street_name'] : ''; ?>" onblur="checkEmptyAndFetchAddress(this, 'street_name')">

    <label for="postal_code">Code postal:</label>
    <input type="text" id="postal_code" name="postal_code" value="<?php echo $is_seller ? $seller_address['postal_code'] : ''; ?>" onblur="checkEmptyAndFetchAddress(this, 'postal_code')">

    <label for="city">Ville:</label>
    <input type="text" id="city" name="city" value="<?php echo $is_seller ? $seller_address['city'] : ''; ?>" onblur="checkEmptyAndFetchAddress(this, 'city')">

    <label for="country">Pays:</label>
    <input type="text" id="country" name="country" value="<?php echo $is_seller ? $seller_address['country'] : ''; ?>" onblur="checkEmptyAndFetchAddress(this, 'country')">

    <input type="submit" value="Enregistrer les modifications">

    </form>
</div>

    <?php
    require 'require/footer.php';
