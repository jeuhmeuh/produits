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
	    <script>
function filterTable(event) {
    const input = event.target;
    const filter = input.value;
    const regex = new RegExp(filter, 'i'); // 'i' pour une correspondance insensible à la casse
    const table = input.closest('table');
    const rows = table.getElementsByTagName('tr');
    const colIndex = Array.from(input.parentNode.parentNode.children).indexOf(input.parentNode);

    for (let i = 1; i < rows.length; i++) {
        const cell = rows[i].getElementsByTagName('td')[colIndex];
        if (cell) {
            const cellValue = cell.textContent || cell.innerText;
            if (regex.test(cellValue)) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    }
}


    </script>
</head>
<body>
    <div class="navbar">
        <a href="index.html">Accueil</a>
        <a href="inventaire.php"class="active">Inventaire</a>
        <a href="produits.php">Produits</a>
        <a href="ajout_produit.php">Ajout de Produit</a>
        <a href="gestion_batiments.php">Gestion des Bâtiments</a>
    </div>
    <h1>Inventaire des produits chimiques</h1>

    <!-- Formulaire pour ajouter un produit -->
    <h2>Ajouter un produit dans l'inventaire</h2>
    <form action="traite_ajout_inventaire.php" method="post">
        <label for="id_batiment_piece">Bâtiment/Pièce:</label>
        <select id="id_batiment_piece" name="id_batiment_piece" required>
        <option value="">Sélectionnez un Bâtiment/piece</option> <!-- Option par défaut vide -->
            <?php
           include 'cfg.php';

            $conn = new mysqli($host, $user, $password, $db, $port);

            // Vérifier la connexion
            if ($conn->connect_error) {
                die("Connexion échouée: " . $conn->connect_error);
            }

            // Préparer et exécuter la requête pour t_batiment_piece
            $stmt = $conn->prepare("SELECT id, batiment, lieu FROM t_batiment_piece ORDER BY batiment, lieu ASC");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['batiment'] . " - " . $row['lieu'] . "</option>";
                }
            } else {
                echo "<option value=''>Aucun bâtiment trouvé</option>";
            }

            $stmt->close();
            ?>
        </select>

        <label for="localisation">Localisation/infos:</label>
        <input type="text" id="localisation" name="localisation" required>
        <br><br>
        <label for="id_produit">Produit-CAS/UFI:</label>
        <select id="id_produit" name="id_produit" required>
        <option value="">Sélectionnez un produit</option> <!-- Option par défaut vide -->
            <?php
            // Préparer et exécuter la requête pour t_produits
            $stmt = $conn->prepare("SELECT id, produit, cas FROM t_produits ORDER BY produit, cas ASC");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['produit'] . " - " . $row['cas'] . "</option>";
                }
            } else {
                echo "<option value=''>Aucun produit trouvé</option>";
            }

            $stmt->close();
            ?>
        </select>

        <label for="id_etat_physique">État Physique:</label>
        <select id="id_etat_physique" name="id_etat_physique" required>
        <option value="">Sélectionnez un etat physique</option> <!-- Option par défaut vide -->
            <?php
            // Préparer et exécuter la requête pour t_etat_physique
            $stmt = $conn->prepare("SELECT id, etat FROM t_etat_physique ORDER BY etat ASC");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['etat'] . "</option>";
                }
            } else {
                echo "<option value=''>Aucun état physique trouvé</option>";
            }

            $stmt->close();
            ?>
        </select>

        <label for="id_conditionement">Conditionnement:</label>
        <select id="id_conditionement" name="id_conditionement" required>
        <option value="">Sélectionnez un conditionement</option> <!-- Option par défaut vide -->
            <?php
            // Préparer et exécuter la requête pour t_conditionement
            $stmt = $conn->prepare("SELECT id, nom FROM t_conditionement ORDER BY nom ASC");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['nom'] . "</option>";
                }
            } else {
                echo "<option value=''>Aucun conditionnement trouvé</option>";
            }

            $stmt->close();
            ?>
        </select>

        <label for="quantite">Quantité:</label>
        <input type="text" id="quantite" name="quantite" required>
        <br><br>
        <label for="id_utilisateur">Utilisateur:</label>
        <select id="id_utilisateur" name="id_utilisateur" required>
        <option value="">Sélectionnez un utilisateur</option> <!-- Option par défaut vide -->
            <?php
            // Connexion à la base de données users
            $db_users = 'users';

            $conn_users = new mysqli($host, $user, $password, $db_users, $port);

            // Vérifier la connexion
            if ($conn_users->connect_error) {
                die("Connexion échouée à la base users: " . $conn_users->connect_error);
            }

            // Préparer et exécuter la requête pour t_utilisateurs
            $stmt = $conn_users->prepare("SELECT id, CONCAT(nom, ' ', prenom) AS nom_prenom FROM t_utilisateurs ORDER BY nom, prenom ASC");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['nom_prenom'] . "</option>";
                }
            } else {
                echo "<option value=''>Aucun utilisateur trouvé</option>";
            }

            $stmt->close();
            $conn_users->close();
            ?>
        </select>

        <label for="date_peremption">Date de Péremption:</label>
        <input type="date" id="date_peremption" name="date_peremption">

        <label for="date_inventaire">Date d'Inventaire:</label>
        <input type="date" id="date_inventaire" name="date_inventaire" required>

        <label for="id_etat">État:</label>
        <select id="id_etat" name="id_etat" required>
        <option value="">Sélectionnez un etat</option> <!-- Option par défaut vide -->
            <?php
            // Préparer et exécuter la requête pour t_etat
            $stmt = $conn->prepare("SELECT id, etat FROM t_etat ORDER BY etat ASC");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['etat'] . "</option>";
                }
            } else {
                echo "<option value=''>Aucun état trouvé</option>";
            }

            $stmt->close();
            $conn->close();
            ?>
        </select>

        <br><br><input type="submit" value="Ajouter Produit">
    </form>


    <!-- Script pour pré-remplir la date d'inventaire -->
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var dateInput = document.getElementById('date_inventaire');
			var localisationInput = document.getElementById('localisation');
			localisationInput.value='-';
            var today = new Date();
            var day = String(today.getDate()).padStart(2, '0');
            var month = String(today.getMonth() + 1).padStart(2, '0'); // Les mois commencent à 0
            var year = today.getFullYear();
            var todayString = year + '-' + month + '-' + day;
            dateInput.value = todayString;
        });
    </script>
    
    <!-- Affichage de la liste des produits dans l'inventaire -->
    <h2>Liste des produits dans l'inventaire</h2> 
	<button onclick="window.location.href='export_csv.php'">Exporter en CSV</button>
	<br><br>
	<h3><center>Champs de recherche:      .* pour représenter zéro ou plusieurs caractères., . pour un caractère unique.</center></h3>

    <table id="inventoryTable" border="1">
            <thead>
                <tr>
                <th>ID<br><input type="text" oninput="filterTable(event)" placeholder="Filtrer" class="search-input"></th>
                <th>Bâtiment/Pièce<br><input type="text" oninput="filterTable(event)" placeholder="Filtrer" class="search-input"></th>
                <th>Localisation/infos<br><input type="text" oninput="filterTable(event)" placeholder="Filtrer" class="search-input"></th>
                <th>Produit<br><input type="text" oninput="filterTable(event)" placeholder="Filtrer" class="search-input"></th>
                <th>CAS/UFI<br><input type="text" oninput="filterTable(event)" placeholder="Filtrer" class="search-input"></th>
                <th>État Physique<br><input type="text" oninput="filterTable(event)" placeholder="Filtrer" class="search-input"></th>
                <th>Conditionnement<br><input type="text" oninput="filterTable(event)" placeholder="Filtrer" class="search-input"></th>
                <th>Quantité<br><input type="text" oninput="filterTable(event)" placeholder="Filtrer" class="search-input"></th>
                <th>Utilisateur<br><input type="text" oninput="filterTable(event)" placeholder="Filtrer" class="search-input"></th>
                <th>Date de Péremption<br><input type="text" oninput="filterTable(event)" placeholder="Filtrer" class="search-input"></th>
                <th>Date d'Inventaire<br><input type="text" oninput="filterTable(event)" placeholder="Filtrer" class="search-input"></th>
                <th>État<br><input type="text" oninput="filterTable(event)" placeholder="Filtrer" class="search-input">
                </tr>
            </thead>
        <?php
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
		WHERE i.id_etat = 1
        ORDER BY i.id_batiment_piece, i.id_produit
		";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['batiment'] . " - " . $row['lieu'] . "</td>";
        echo "<td>" . $row['localisation'] . "</td>";
        // Lien vers la page "produits.php" avec la valeur du produit
        echo "<td><a href='produits.php?produit=" . urlencode($row['produit']) . "'>" . $row['produit'] . "</a></td>";
        echo "<td>" . $row['cas'] . "</td>";
        echo "<td>" . $row['etat_physique'] . "</td>";
        echo "<td>" . $row['conditionement'] . "</td>";
        echo "<td>" . $row['quantite'] . "</td>";
        echo "<td>" . $row['nom'] . " " . $row['prenom'] . "</td>";
        echo "<td>" . $row['date_peremption'] . "</td>";
        echo "<td>" . $row['date_inventaire'] . "</td>";
        echo "<td>" . $row['etat_final'] . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='10'>Aucun produit trouvé</td></tr>";
}


// Fermer la connexion
$conn->close();
?>
    </table>
</body>
</html>
