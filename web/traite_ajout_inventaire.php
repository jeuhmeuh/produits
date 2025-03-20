<?php
// Connexion à la base de données
include 'cfg.php';

// Connexion à la base de données
$conn = new mysqli($host, $user, $password, $db, $port);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}
echo "Connexion réussie<br>";

// Préparer et exécuter l'insertion des données
// Les types de données "isssisissi" indiquent:
// i - Integer (entier)
// s - String (chaîne de caractères)
$stmt = $conn->prepare("INSERT INTO t_inventaire (id_batiment_piece, localisation, id_produit, id_etat_physique, id_conditionement, quantite, id_utilisateur, date_peremption, date_inventaire, id_etat) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
if ($stmt === false) {
    die("Erreur de préparation de la requête: " . $conn->error);
}
echo "Préparation de la requête réussie<br>";

// Vérifier et traiter la valeur de date_peremption
$date_peremption = empty($_POST['date_peremption']) ? null : $_POST['date_peremption'];
//echo "date peremption: " . $date_peremption;

// Lier les paramètres
$stmt->bind_param("isssisissi", 
    $_POST['id_batiment_piece'],  // Integer: ID du bâtiment/pièce
    $_POST['localisation'],       // String: Localisation
    $_POST['id_produit'],         // String: ID du produit
    $_POST['id_etat_physique'],   // String: ID de l'état physique
    $_POST['id_conditionement'],  // Integer: ID du conditionnement
    $_POST['quantite'],           // String: Quantité
    $_POST['id_utilisateur'],     // Integer: ID de l'utilisateur
    $date_peremption,             // String: Date de péremption
    $_POST['date_inventaire'],    // String: Date d'inventaire
    $_POST['id_etat']             // Integer: ID de l'état
);

echo "Paramètres liés avec succès<br>";

// Exécuter la requête et vérifier le succès
if ($stmt->execute()) {
    echo "<h1>Produit ajouté avec succès</h1>";
    header("Refresh:2; url=inventaire.php");
} else {
    echo "Erreur lors de l'ajout du produit: " . $stmt->error;
}

// Fermer la connexion
$stmt->close();
$conn->close();
?>
