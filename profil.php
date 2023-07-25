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
    }
    else{
        $is_seller = false;

    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Vérifier si tous les champs ont été remplis
        if (
            isset($_POST['product_name']) && !empty($_POST['product_name']) &&
            isset($_POST['product_type']) && !empty($_POST['product_type']) &&
            isset($_POST['price']) && !empty($_POST['price']) &&
            isset($_POST['sale_period']) && !empty($_POST['sale_period']) &&
            isset($_FILES['image']) && !empty($_FILES['image']['name'])
        ) {
            $errors = [];
            if(strlen($_POST['product_name']) < 3){
                $errors['name'] = "Le champ 'Nom du produit' doit contenir au moins 3 caractères de texte.";
            }
            if(strlen($_POST['product_type']) < 3){
                $errors['type'] = "Le champ 'Type de produits' doit contenir au moins 3 caractères de texte.";
            }
            $price = $_POST['price'];
            $price_pattern = "/^\d+(\.\d{1,2})?$/"; // Format prix : un nombre positif avec éventuellement 2 décimales
            if (!preg_match($price_pattern, $price)) {
                echo "Le format du prix est invalide. Utilisez un nombre positif avec éventuellement 2 décimales.";
            } 
            if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
                // Récupérer les informations sur le fichier
                $file_name = $_FILES["image"]["name"];
                $file_tmp = $_FILES["image"]["tmp_name"];
                $file_size = $_FILES["image"]["size"];
                $file_type = $_FILES["image"]["type"];
        
                // Liste des extensions d'images autorisées
                $allowed_extensions = array("jpg", "jpeg", "png", "gif");
        
                // Vérifier si le type MIME correspond à une image
                if (in_array($file_type, array("image/jpeg", "image/png", "image/gif"))) {
                    // Vérifier si l'extension du fichier correspond à une extension autorisée
                    $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                    if (in_array($file_extension, $allowed_extensions)) {
                        // Déplacer le fichier téléversé vers le dossier de stockage des images
                        $upload_directory = "img/";
                        $new_file_name = uniqid("image_") . "." . $file_extension;
                        $destination = $upload_directory . $new_file_name;
        
                        if (move_uploaded_file($file_tmp, $destination)) {
                            //echo "L'image a été téléversée avec succès.";
                        } else {
                            echo "Erreur lors du téléversement de l'image.";
                        }
                    } else {
                        echo "Extension de fichier non autorisée. Les extensions autorisées sont : " . implode(", ", $allowed_extensions);
                    }
                } else {
                    echo "Le fichier téléversé n'est pas une image valide.";
                }
            } else {
                echo "Aucun fichier téléversé ou erreur lors du téléversement.";
            }
        } else {
            echo "Veuillez remplir tous les champs du formulaire.";
        }
        if (empty($errors)) {
            $currentDateTime = date('Y-m-d H:i:s');
            echo $currentDateTime;
            // Tous les champs sont valides, procéder au traitement du formulaire
            // ...
            // Prépare la requête d'insertion
            $stmt = $conn->prepare("INSERT INTO product (title, type, tarif, fin_vente, pictures, date_publication, id_users) VALUES (:title, :type, :tarif, :fin_de_vente, :pictures, :date_publication, :id_user)");
                
            // Lie les valeurs aux paramètres de la requête
            $stmt->bindParam(':title', $_POST['product_name']);
            $stmt->bindParam(':type', $_POST['product_type']);
            $stmt->bindParam(':tarif', $_POST['price']);
            $stmt->bindParam(':fin_de_vente', $_POST['sale_period']);
            $stmt->bindParam(':pictures', $destination);
            $stmt->bindParam(':date_publication', $currentDateTime);
            $stmt->bindParam(':id_user', $user_id);
    
            // Exécute la requête
            $stmt->execute();
    
            // Redirection vers une autre page après l'insertion réussie
         
            // Redirection vers une autre page après le traitement réussi
            //header("Location: success.php");
            
        }
    }
    
    require 'require/header.php';
?>
   <div class="profil">
   <h1>Profil de l'utilisateur</h1>
   <p><strong>Nom :</strong> <?php echo $user_data['name']; ?></p>
    <p><strong>Prénom:</strong> <?php echo $user_data['firstname']; ?></p>
    <p><strong>Email:</strong> <?php echo $user_data['email']; ?></p>
    <p><strong>Role:</strong> <?php echo $user_data['role']; ?></p>
    <p><strong>Telephone:</strong> <?php echo $user_data['telephone']; ?></p>

    <p><strong>Role:</strong> <?php if($user_data['role']=='les_deux'){ echo 'Vendeur et acheteur';} else echo $user_data['role'] ?></p>

    <?php
    
    if($is_seller == true){
            ?>
            <p><strong>Numero de rue :</strong> <?php echo $seller_address['street_number']; ?></p>
            <p><strong>Nom de rue:</strong> <?php echo $seller_address['street_name']; ?></p>
            <p><strong>Code Postal:</strong> <?php echo $seller_address['postal_code']; ?></p>
            <p><strong>Ville:</strong> <?php echo $seller_address['city']; ?></p>
      <?php  
        }
        elseif($user_data['role'] === 'vendeur' || $user_data['role'] === 'les_deux'){?>
            <p>Ajoutez une adresse dans modifier profil</p>
        <?php
            }?>
        <a href="modification-profil.php">Modifier profil</a>
        <?php
      if($user_data['role'] === 'vendeur' || $user_data['role'] === 'les_deux'){
        ?>
        <form method="post" action="" enctype="multipart/form-data">
            <label for="product_name">Nom du produits :</label>
            <input type="text" id="product_name" name="product_name" required>
            <br>
            <label for="product_type">Type de produits :</label>
            <input type="text" id="product_type" name="product_type" required>
            <br>

            <label for="price">Tarif :</label>
            <input type="text" id="price" name="price" required>
            <br>

            <label for="sale_period">Date de fin de vente :</label>
            <input type="date" id="sale_period" name="sale_period" required>
            <br>

            <label for="image">Photo :</label>
            <input type="file" id="photo" name="image" accept="image/*" required>
            <br>

            <button type="submit">Envoyer</button>
        </form>


    </form>
      </div>
<?php
    
    }

    require 'require/footer.php';
