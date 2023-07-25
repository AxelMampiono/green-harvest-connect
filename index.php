<?php 
    require 'connexion_bdd.php';
    session_start();
    $query = "SELECT * FROM product ORDER BY date_publication DESC LIMIT 3";
    $stmt = $conn->prepare($query);
$stmt->execute();
  

    require 'require/header.php';
?>
   
    <div class="bienvenue">
        <h1 class="titre">Green Harvest Connect</h1>
        <p class="description">Green Harvest Connect : Cultivez, partagez, échangez, connectez-vous à la communauté du potager !</p>
        <a href="inscription.php" class="button">Inscrivez vous</a>
    </div>
    <div class="qui-nous-sommes">
      <h2 class="title">Qui nous sommes</h2>
      <p class="description">Green Harvest Connect est une plateforme communautaire en ligne qui vise à faciliter la vente et l'échange de produits issus du jardinage et du potager. Notre projet met en relation les petits jardiniers avec des acheteurs locaux intéressés par des produits frais et locaux. Notre objectif est de créer une communauté dynamique où les jardiniers peuvent partager leurs connaissances, échanger des conseils et des astuces, tout en favorisant les échanges et les transactions à petite échelle. Rejoignez-nous sur Green Harvest Connect pour cultiver, partager, échanger et vous connecter à la communauté florissante du potager.</p>
  </div>
    <div class="vente">
        <h2>Découvrez le trésor caché des potagers particuliers en un simple clic !</h2>
        <div class="card-container">
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
    <a href="detail.php?id=<?php echo $id; ?>" class="button"><p>Button</p></a>
  </div>
</div><?php
    }
} else {
    echo "Aucun enregistrement trouvé.";
}?>
            
            
          
            
            
            
          </div>
          <a href="" class="button">Button</a>

    </div>
    
    
      
      

<?php
    require 'require/footer.php';
