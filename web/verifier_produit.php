<?php
// Informations de connexion à la base de données
include 'cfg.php';

// Créer une connexion à la base de données
$conn = new mysqli($host, $user, $password, $db, $port);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Récupérer les données du formulaire
$type = $_POST['type'];
$value = $_POST['value'];

// Préparer la requête SQL
if ($type === 'produit') {
    $sql = "SELECT COUNT(*) AS count FROM t_produits WHERE produit = ?";
} elseif ($type === 'cas') {
    $sql = "SELECT COUNT(*) AS count FROM t_produits WHERE cas = ?";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $value);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$response = ['exists' => $row['count'] > 0];
echo json_encode($response);

// Fermer la connexion
$conn->close();
?>
