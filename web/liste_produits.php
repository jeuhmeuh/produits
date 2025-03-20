<?php
// Informations de connexion à la base de données
$host = '134.157.152.123';
$db = 'produits_chimiques';
$user = 'testor';
$password = 'testor2000';
$port = '3307';

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
$conn = new mysqli($host, $user, $password, $db, $port);

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
    <title>Liste de produits chimiques</title>
    
    <link rel="stylesheet" href="style.css">
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
    </script>
</head>
<body>
    <?php
    // Afficher les résultats
    if ($result->num_rows > 0) {
        echo "<table id='produitsTable'>";
        echo "<thead><tr>";
        echo "<th>ID<br><input type='text' onkeyup='filterTable()' placeholder='Filtrer...'></th>";
        echo "<th>Produit<br><input type='text' onkeyup='filterTable()' placeholder='Filtrer...'></th>";
        echo "<th>Synonymes<br><input type='text' onkeyup='filterTable()' placeholder='Filtrer...'></th>";
        echo "<th>CAS<br><input type='text' onkeyup='filterTable()' placeholder='Filtrer...'></th>";
       /* echo "<th>CMR<br><input type='checkbox' onchange='filterTable()'></th>";
        echo "<th>Explosif<br><input type='checkbox' onchange='filterTable()'></th>";
        echo "<th>Inflammable<br><input type='checkbox' onchange='filterTable()'></th>";
        echo "<th>Comburant<br><input type='checkbox' onchange='filterTable()'></th>";
        echo "<th>Gaz sous pression<br><input type='checkbox' onchange='filterTable()'></th>";
        echo "<th>Corrosif<br><input type='checkbox' onchange='filterTable()'></th>";
        echo "<th>Toxique<br><input type='checkbox' onchange='filterTable()'></th>";
        echo "<th>Irritant<br><input type='checkbox' onchange='filterTable()'></th>";
        echo "<th>Danger long terme<br><input type='checkbox' onchange='filterTable()'></th>";
        echo "<th>Dangereux milieu aquatique<br><input type='checkbox' onchange='filterTable()'></th>";*/
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
