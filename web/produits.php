<?php
// Informations de connexion à la base de données
include 'cfg.php';

// Chemin du dossier contenant les images et les fichiers PDF
$img_path = 'imgs/';
$pdf_path = 'FDS/';

// Tableau associatif pour les images
$images = [
    'explosif' => 'explosif.gif',
    'inflammable' => 'inflammable.gif',
    'comburant' => 'comburant.gif',
    'gaz_sous_pression' => 'gaz_sous_pression.gif',
    'corrosif' => 'corrosif.gif',
    'toxique' => 'toxique.gif',
    'irritant' => 'irritant.gif',
    'dange_long_terme' => 'danger_long_terme.gif',
    'dangereux_milieu_aquatique' => 'danger_millieu_aquatique.gif',
    'cmr' => 'cmr.gif'
];

// Créer une connexion à la base de données
$conn = new mysqli($host, $username, $password, $dbname, $port);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Requête SQL pour récupérer la liste des produits chimiques avec leurs synonymes
$sql = "
SELECT t_produits.*, GROUP_CONCAT(t_synonyme_produit.synonyme SEPARATOR ', ') AS synonymes
FROM t_produits
LEFT JOIN t_synonyme_produit ON t_produits.id = t_synonyme_produit.id_produit
GROUP BY t_produits.id
ORDER BY t_produits.produit ASC";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <title>Liste de produits chimiques</title>
    
    <link rel="stylesheet" href="style.css">
        <div class="navbar">
        <a href="index.html">Accueil</a>
        <a href="inventaire.php">Inventaire</a>
        <a href="produits.php"class="active">Produits </a>
        <a href="ajout_produit.php">Ajout de Produit</a>
        <a href="gestion_batiments.php">Gestion des Bâtiments</a>
    </div>
    <center><H2>Liste de produits chimiques (ceci n'est PAS un inventaire!) </H2></center>
    <script>
        function filterTable() {
            const table = document.getElementById("produitsTable");
            const tr = table.getElementsByTagName("tr");
            const inputs = document.querySelectorAll("thead input");

            for (let i = 1; i < tr.length; i++) {
                let showRow = true;
                for (let j = 0; j < inputs.length; j++) {
                    const input = inputs[j];
                    const td = tr[i].getElementsByTagName("td")[j];
                    if (input.type === "text" && td) {
                        const txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(input.value.toUpperCase()) === -1) {
                            showRow = false;
                            break;
                        }
                    } else if (input.type === "checkbox" && td) {
                        
                        if (input.checked && !td.innerHTML.includes('img src')) {
                            showRow = false;
                            break;
                        }
                    }
                }
                tr[i].style.display = showRow ? "" : "none";
            }
        }
    // Appeler filterTable() lors du chargement de la page
    window.onload = function() {
	filterTable();}
    
    </script>
</head>
<body>
    <?php
	// Récupérer la valeur du produit depuis l'URL
    $produit = isset($_GET['produit']) ? $_GET['produit'] : '';
    // Afficher les résultats
    if ($result->num_rows > 0) {
        echo "<table id='produitsTable'>";
        echo "<thead><tr>";
        echo "<th>ID<br><input type='text' onkeyup='filterTable()' placeholder='Filtrer...'></th>";
        //echo "<th>Produit<br><input type='text' onkeyup='filterTable()' placeholder='Filtrer...'></th>";
        echo "<th>Produit<br><input type='text' onkeyup='filterTable()' placeholder='Filtrer...' value='" . htmlspecialchars($produit) . "'></th>";
		echo "<th>Synonymes<br><input type='text' onkeyup='filterTable()' placeholder='Filtrer...'></th>";
        echo "<th>CAS/UFI<br><input type='text' onkeyup='filterTable()' placeholder='Filtrer...'></th>";
 
		echo "<th class='narrow-column'>CMR<br><input type='checkbox' onchange='filterTable()'></th>";
		echo "<th class='narrow-column'>Explosif<br><input type='checkbox' onchange='filterTable()'></th>";
		echo "<th class='narrow-column'>Inflammable<br><input type='checkbox' onchange='filterTable()'></th>";
		echo "<th class='narrow-column'>Comburant<br><input type='checkbox' onchange='filterTable()'></th>";
		echo "<th class='narrow-column'>Gaz sous pression<br><input type='checkbox' onchange='filterTable()'></th>";
		echo "<th class='narrow-column'>Corrosif<br><input type='checkbox' onchange='filterTable()'></th>";
		echo "<th class='narrow-column'>Toxique<br><input type='checkbox' onchange='filterTable()'></th>";
		echo "<th class='narrow-column'>Irritant<br><input type='checkbox' onchange='filterTable()'></th>";
		echo "<th class='narrow-column'>Danger long terme<br><input type='checkbox' onchange='filterTable()'></th>";
		echo "<th class='narrow-column'>Dangereux milieu aquatique<br><input type='checkbox' onchange='filterTable()'></th>";

        echo "<th>ID FDS<br><input type='text' onkeyup='filterTable()' placeholder='Filtrer...'></th>";
        echo "</tr></thead><tbody>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['produit'] . "</td>";
            echo "<td>" . $row['synonymes'] . "</td>";
            echo "<td>" . $row['cas'] . "</td>";
            // Afficher les images si la valeur est 1, sinon afficher une case vide
            foreach (['cmr', 'explosif', 'inflammable', 'comburant', 'gaz_sous_pression', 'corrosif', 'toxique', 'irritant', 'dange_long_terme', 'dangereux_milieu_aquatique'] as $col) {
                echo "<td>";
                if ((int)$row[$col] === 1) {
                    echo "<img src='" . $img_path . $images[$col] . "' alt='" . $col . "'>";
                } else {
                    echo "&nbsp;";
                }
                echo "</td>";
            }
            // Rendre la valeur de la colonne "ID FDS" cliquable pour afficher le PDF correspondant
            echo "<td>";
            if (!empty($row['id_fds'])) {
                $pdf_file = $pdf_path . $row['id_fds'];
                echo "<a href='" . $pdf_file . "' target='_blank'>" . $row['id_fds'] . "</a>";
            } else {
                echo "&nbsp;";
            }
            echo "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "Aucun produit trouvé.";
    }

    // Fermer la connexion
    $conn->close();
    ?>
</body>
</html>
