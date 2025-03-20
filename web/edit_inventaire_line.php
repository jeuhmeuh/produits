<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inventaire</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #333;
            overflow: hidden;
        }
        .navbar a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }
        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }
        .navbar a.active {
        background-color: #aaa; /* Couleur de surbrillance pour le lien actif */
        color: white;
                         }
    </style>
</head>

<body>
    <div class="navbar">
        <a href="index.html">Acceuil</a>
        <a href="inventaire.php"class="active">Inventaire</a>
        <a href="produits.php">Produits</a>
        <a href="ajout_produit.php">Ajout de Produit</a>
        <a href="gestion_batiments.php">Gestion des Bâtiments</a>
    </div>
    <h2>edition item inventaire</h2>
<?php
    include 'cfg.php';
    $id= $_GET['id'];
       // Connexion à la base de données
        $conn = new mysqli($host, $user, $password, $db, $port);

        // Vérifier la connexion
        if ($conn->connect_error) {
            die("Connexion échouée: " . $conn->connect_error);
        }

        // Récupérer les données de la table t_inventaire avec jointures
$sql = "SELECT i.id,i.id_batiment_piece, bp.batiment, bp.lieu, i.localisation, i.id_produit, p.produit, p.cas, 
               i.id_etat_physique, ep.etat AS etat_physique, i.id_conditionement, c.nom AS conditionement, 
               i.quantite, i.id_utilisateur, u.nom, u.prenom, i.date_peremption, i.date_inventaire, 
               i.id_etat, e.etat AS etat_final
        FROM t_inventaire i
        JOIN t_batiment_piece bp ON i.id_batiment_piece = bp.id
        JOIN t_produits p ON i.id_produit = p.id
        JOIN t_etat_physique ep ON i.id_etat_physique = ep.id
        JOIN t_conditionement c ON i.id_conditionement = c.id
        JOIN users.t_utilisateurs u ON i.id_utilisateur = u.id
        JOIN t_etat e ON i.id_etat = e.id
		WHERE i.id = 1
        ORDER BY i.id_batiment_piece, i.id_produit 
		";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
   echo "<form action='edit_inventaire_line.php' method='get'>";
    while ($row = $result->fetch_assoc()) {
       
       // echo "<td><input type='submit' value='" . $row['id'] . "' name='id' /></td>";


        echo $row['batiment'] ;
   echo "<option value='" . $row['id'] . "'>" . $row['batiment'] . " - " . $row['lieu'] . "</option>";
        echo $row['lieu'] ;
        echo $row['localisation'];
        // Lien vers la page "produits.php" avec la valeur du produit
        echo  $row['produit'] ;
        echo  $row['cas'];
        echo $row['etat_physique'] ;
        echo  $row['conditionement'] ;
        echo $row['quantite'];
        echo  $row['nom'] . " " . $row['prenom'];
        echo  $row['date_peremption'] ;
        echo  $row['date_inventaire'] ;
        echo  $row['etat_final'] ;
     
    }
    echo "</form>";
} else {
    echo "<tr><td colspan='10'>Aucun produit trouvé</td></tr>";
}

    ?>
