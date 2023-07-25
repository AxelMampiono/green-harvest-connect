<?php
require 'connexion_bdd.php';
session_start();

$query = "SELECT * FROM product";
$stmt = $conn->prepare($query);
$stmt->execute();
require 'require/header.php';
// Compteur pour déterminer quand commencer une nouvelle ligne
$count = 0;
?>
<div class="achat">
    <h1>Annonces</h1>
    <?php
if ($stmt->rowCount() > 0) {
    // Parcourir les résultats
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Récupérer les valeurs de chaque enregistrement
        $nomProduit = $row['title'];
        $tarif = $row['tarif'];
        $type = $row['type'];
        $img=$row['pictures'];
        $id=$row['id'];
        $id_user=$row['id_users'];

        
        ?>
        <div class="card">
        <img src="<?php echo $img; ?>" alt="Image du produit 1" class="img_card">  <div class="card-details">
    <h3><?php echo $nomProduit; ?></h3>
    <p><?php echo $tarif; ?></p>
    <p><?php echo $type; ?></p>
    <a href="detail.php?id=<?php echo $id; ?>" class="button"><p>Plus</p></a>
  </div>
</div><?php
    }
}?>
</div>

<?php
    require 'require/footer.php';

