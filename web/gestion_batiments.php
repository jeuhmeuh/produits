<?php
include 'cfg.php';

// Connexion à la base de données
$conn = new mysqli($host, $user, $password, $db, $port);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

// Ajouter une valeur
if (isset($_POST['add'])) {
    $batiment = $_POST['batiment'];
    $lieu = $_POST['lieu'];

    // Vérification si le couple de valeurs existe déjà
    $check_sql = "SELECT * FROM t_batiment_piece WHERE batiment = '$batiment' AND lieu = '$lieu'";
    $result = $conn->query($check_sql);

    if ($result->num_rows == 0) {
        $sql = "INSERT INTO t_batiment_piece (batiment, lieu) VALUES ('$batiment', '$lieu')";
        
        if ($conn->query($sql) === TRUE) {
            echo "Nouvel enregistrement ajouté avec succès";
        } else {
            echo "Erreur : " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Le couple de valeurs existe déjà.";
    }
}

// Supprimer une valeur
if (isset($_POST['delete'])) {
    /*$id = $_POST['id'];
    
    $sql = "DELETE FROM t_batiment_piece WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        echo "Enregistrement supprimé avec succès";
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }*/
	echo "suppresion de lignes inactive";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion de t_batiment_piece</title>
    <link rel="stylesheet" type="text/css" href="style.css">
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
    <script>
        function confirmDeletion() {
            return confirm("Êtes-vous sûr de vouloir supprimer cet enregistrement ?");
        }
    </script>
</head>
<body>
    <div class="navbar">
        <a href="index.html">Accueil</a>
        <a href="inventaire.php">Inventaire</a>
        <a href="produits.php">Produits</a>
        <a href="ajout_produit.php">Ajout de Produit</a>
        <a href="gestion_batiments.php" class="active">Gestion des Bâtiments</a>
		<a href="plan_batiments.html" target="_blank" >Afficher le plan des bâtiments dans un nouvel onglet</a>
    </div>
    <h1>Ajouter une valeur</h1>
    <form method="post">
        Bâtiment : <input type="text" name="batiment" required><br>
        Lieu : <input type="text" name="lieu" required><br>
        <input type="submit" name="add" value="Ajouter">
    </form>

    <h1>Supprimer une valeur</h1>
    <form method="post" onsubmit="return confirmDeletion();">
        ID : <input type="number" name="id" required>
        <input type="submit" name="delete" value="Supprimer">
    </form>

    <h1>Aperçu des enregistrements actuels</h1>
    <?php
    // Requête SQL mise à jour pour trier les résultats par batiment puis par lieu en ordre ascendant
    $sql = "SELECT * FROM t_batiment_piece ORDER BY batiment ASC, lieu ASC";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo "<table><tr><th>ID</th><th>Bâtiment</th><th>Lieu</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["id"]. "</td><td>" . $row["batiment"]. "</td><td>" . $row["lieu"]. "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "0 résultats";
    }
    ?>
</body>
</html>

<?php
// Fermer la connexion
$conn->close();
?>
