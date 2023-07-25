<?php
if (isset($_GET['id'])) {
    $id_product = $_GET['id'];
    // Utilisez la variable $id comme vous le souhaitez
} else {
}
require 'connexion_bdd.php';
session_start();
$query = "SELECT * FROM product WHERE id = :product_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':product_id', $id_product);
$stmt->execute();
if ($stmt->rowCount() == 1) {
    // Récupérer les valeurs de l'enregistrement
    $product_data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Maintenant, vous pouvez accéder aux valeurs du produit
    $product_name = $product_data['title'];
    $price = $product_data['tarif'];
    $id_user=$product_data['id_users'];
    $img=$product_data['pictures'];

  
} 
$query = "SELECT * FROM users WHERE id = :id_user";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id_user', $id_user);
$stmt->execute();
if ($stmt->rowCount() == 1) {
    // Récupérer les valeurs de l'enregistrement
    $users_data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Maintenant, vous pouvez accéder aux valeurs du produit
    $name = $users_data['name'];
    $firstname = $users_data['firstname'];
    $telephone = '+33' . substr($users_data['telephone'], -9);

} 
$query = "SELECT * FROM adresses WHERE seller_id = :id_user";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id_user', $id_user);
$stmt->execute();
if ($stmt->rowCount() == 1) {
    // Récupérer les valeurs de l'enregistrement
    $adresse_data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Maintenant, vous pouvez accéder aux valeurs du produit
    $numero_rue = $adresse_data['street_number'];
    $nom_rue = $adresse_data['street_name'];
    $cp = $adresse_data['postal_code'];
    $ville = $adresse_data['city'];

    
} 
require 'require/header.php'
?>

<div class="detail">
    <h1>Detail</h1>
    <div class="card">
            <img src="<?php echo $img; ?>" alt="Image du produit 1" class="img_card">  <div class="card-details">
        <h3><?php echo $product_name  ?></h3>
        <p><?php echo $price; ?></p>
        <p><?php echo $name .' ' . $firstname;; ?></p>
        <a href="tel:<?php echo $telephone; ?>"><p>Contactez lz vendeur par telephone</p></a>

        <a href="https://maps.google.com/maps?q=<?php echo urlencode($numero_rue . ' ' . $nom_rue . ', ' . $cp . ' ' . $ville); ?>" target="_blank">Voir sur la carte</a>
    <br>

        <a href="achat.php" class="button"><p>Retour</p></a>
    </div>
</div>

</div>
<?php
    require 'require/footer.php';
