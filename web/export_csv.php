<?php
// Informations de connexion à la base de données
include 'cfg.php';

// Créer une connexion à la base de données
$conn = new mysqli($host, $user, $password, $db, $port);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Requête SQL pour la table t_inventaire avec les synonymes, les dangers et les valeurs des colonnes spécifiques
$sql = "
SELECT 
    i.id, 
    bp.batiment, 
    bp.lieu, 
    i.localisation, 
    p.produit, 
    p.cas, 
    ep.etat AS etat_physique, 
    c.nom AS conditionement, 
    i.quantite, 
    u.nom, 
    u.prenom, 
    i.date_peremption, 
    i.date_inventaire, 
    e.etat AS etat_final,
    GROUP_CONCAT(DISTINCT t_synonyme_produit.synonyme SEPARATOR ', ') AS synonymes,
    GROUP_CONCAT(DISTINCT t_danger_produits.danger SEPARATOR ', ') AS dangers,
    p.cmr, 
    p.explosif, 
    p.inflammable, 
    p.comburant, 
    p.gaz_sous_pression, 
    p.corrosif, 
    p.toxique, 
    p.irritant, 
    p.dange_long_terme, 
    p.dangereux_milieu_aquatique
FROM 
    t_inventaire i
JOIN 
    t_batiment_piece bp ON i.id_batiment_piece = bp.id
JOIN 
    t_produits p ON i.id_produit = p.id
JOIN 
    t_etat_physique ep ON i.id_etat_physique = ep.id
JOIN 
    t_conditionement c ON i.id_conditionement = c.id
JOIN 
    users.t_utilisateurs u ON i.id_utilisateur = u.id
JOIN 
    t_etat e ON i.id_etat = e.id
LEFT JOIN 
    t_synonyme_produit ON p.id = t_synonyme_produit.id_produit
LEFT JOIN 
    t_danger_produits ON p.id = t_danger_produits.id_produit
WHERE 
    i.id_etat = 1
GROUP BY 
    i.id, 
    bp.batiment, 
    bp.lieu, 
    i.localisation, 
    p.produit, 
    p.cas, 
    ep.etat, 
    c.nom, 
    i.quantite, 
    u.nom, 
    u.prenom, 
    i.date_peremption, 
    i.date_inventaire, 
    e.etat
ORDER BY 
    i.ID,
    i.id_batiment_piece, 
    i.id_produit";

$result = $conn->query($sql);

// Créer un fichier CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=inventaire.csv');
$output = fopen('php://output', 'w');

// Écrire l'en-tête du fichier CSV (incluant les nouvelles colonnes)
fputcsv($output, array('ID', 'Bâtiment', 'Lieu', 'Localisation', 'Produit', 'CAS', 'État Physique', 'Conditionnement', 'Quantité', 'Nom Utilisateur', 'Prénom Utilisateur', 'Date Péremption', 'Date Inventaire', 'État Final', 'Synonymes', 'Dangers', 'CMR', 'Explosif', 'Inflammable', 'Comburant', 'Gaz sous pression', 'Corrosif', 'Toxique', 'Irritant', 'Danger long terme', 'Dangereux milieu aquatique'));

// Écrire les données dans le fichier CSV
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        fputcsv($output, array(
            $row['id'], 
            $row['batiment'], 
            $row['lieu'], 
            $row['localisation'], 
            $row['produit'], 
            $row['cas'], 
            $row['etat_physique'], 
            $row['conditionement'], 
            $row['quantite'], 
            $row['nom'], 
            $row['prenom'], 
            $row['date_peremption'], 
            $row['date_inventaire'], 
            $row['etat_final'], 
            $row['synonymes'], 
            $row['dangers'],
            $row['cmr'], 
            $row['explosif'], 
            $row['inflammable'], 
            $row['comburant'], 
            $row['gaz_sous_pression'], 
            $row['corrosif'], 
            $row['toxique'], 
            $row['irritant'], 
            $row['dange_long_terme'], 
            $row['dangereux_milieu_aquatique']
        ));
    }
}

// Fermer la connexion
$conn->close();
?>
