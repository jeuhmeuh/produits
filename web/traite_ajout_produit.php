<?php
// Informations de connexion à la base de données
include 'cfg.php';

// Chemin du dossier contenant les fichiers PDF
$pdf_path = 'FDS/';

// Créer une connexion à la base de données
$conn = new mysqli($host, $user, $password, $db, $port);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Récupérer les données du formulaire
$produit = $_POST['produit'];
$synonymes = $_POST['synonymes'];
$cas = $_POST['cas'];
$cmr = isset($_POST['cmr']) ? 1 : 0;
$explosif = isset($_POST['explosif']) ? 1 : 0;
$inflammable = isset($_POST['inflammable']) ? 1 : 0;
$comburant = isset($_POST['comburant']) ? 1 : 0;
$gaz_sous_pression = isset($_POST['gaz_sous_pression']) ? 1 : 0;
$corrosif = isset($_POST['corrosif']) ? 1 : 0;
$toxique = isset($_POST['toxique']) ? 1 : 0;
$irritant = isset($_POST['irritant']) ? 1 : 0;
$dange_long_terme = isset($_POST['dange_long_terme']) ? 1 : 0;
$dangereux_milieu_aquatique = isset($_POST['dangereux_milieu_aquatique']) ? 1 : 0;
$id_fds = '';
$phrases_h = $_POST['phrases_h'];

// Gestion de l'upload du fichier PDF
if (!empty($_FILES['fichier_pdf']['name'])) {
    $id_fds = 'FDS_' . $cas . '.pdf';
    $pdf_file = $pdf_path . $id_fds;

    if (move_uploaded_file($_FILES['fichier_pdf']['tmp_name'], $pdf_file)) {
        echo "Le fichier PDF a été téléchargé avec succès.<br>";
    } else {
        die("Échec du téléchargement du fichier PDF.");
    }
}

// Insérer le produit dans la base de données
$sql = "INSERT INTO t_produits (produit, cas, cmr, explosif, inflammable, comburant, gaz_sous_pression, corrosif, toxique, irritant, dange_long_terme, dangereux_milieu_aquatique, id_fds) VALUES ('$produit', '$cas', $cmr, $explosif, $inflammable, $comburant, $gaz_sous_pression, $corrosif, $toxique, $irritant, $dange_long_terme, $dangereux_milieu_aquatique, '$id_fds')";
if ($conn->query($sql) === TRUE) {
    $produit_id = $conn->insert_id;
    echo "Produit ajouté avec succès. ID du produit : " . $produit_id . "<br>";

    // Insérer les synonymes dans la table t_synonyme_produit
    if (!empty($synonymes)) {
        $synonymes_array = explode(',', $synonymes);
        foreach ($synonymes_array as $synonyme) {
            $synonyme = trim($synonyme);
            $sql_synonyme = "INSERT INTO t_synonyme_produit (id_produit, synonyme) VALUES ($produit_id, '$synonyme')";
            if ($conn->query($sql_synonyme) !== TRUE) {
                echo "Erreur lors de l'ajout du synonyme : " . $conn->error . "<br>";
            }
        }
    }

    // Insérer les phrases de risques dans la table t_danger_produit
    if (!empty($phrases_h)) {
        foreach ($phrases_h as $codeH) {
            $sql_danger = "INSERT INTO t_danger_produits (id_produit, danger) VALUES ($produit_id, '$codeH')";
            if ($conn->query($sql_danger) !== TRUE) {
                echo "Erreur lors de l'ajout de la phrase de risque : " . $conn->error . "<br>";
            }
        }
    }

} else {
    echo "Erreur lors de l'ajout du produit : " . $conn->error;
}

// Fermer la connexion
$conn->close();
?>
