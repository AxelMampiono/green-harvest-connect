<?php
    require 'connexion_bdd.php';

    session_start();

    if(!empty($_SESSION['role']) && ($_SESSION['role']=='vendeur' || $_SESSION['role']=='les_deux'))
    {
        if($_SESSION['role']=='les_deux')
        {
            echo 'Vendeur et acheteur';
        } else{
            echo $_SESSION['role'];
        }
    }else{
        header('Location: index.php');

    }

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Vérifier si un fichier a été téléversé
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
                    echo "L'image a été téléversée avec succès.";
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
}



?>
    <!DOCTYPE html>
<html>
<head>
    <title>Upload d'image</title>
</head>
<body>
    <h1>Upload d'image</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="image">Sélectionner une image :</label>
        <input type="file" id="image" name="image" accept="image/*" required>
        <br>
        <input type="submit" value="Envoyer">
    </form>
</body>
</html>
