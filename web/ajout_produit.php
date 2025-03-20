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
    <title>Ajouter un produit</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .checkbox-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        .error {
            color: red;
        }
    </style>
    <script>
        function validateForm() {
            const produit = document.getElementById('produit').value.trim();
            const cas = document.getElementById('cas').value.trim();
            const fichierPdf = document.getElementById('fichier_pdf').value;

            if (!produit) {
                alert("Le champ 'Produit' est obligatoire.");
                return false;
            }
            if (!cas) {
                alert("Le champ 'CAS' est obligatoire.");
                return false;
            }
            if (fichierPdf) {
                const pdfExtension = fichierPdf.split('.').pop().toLowerCase();
                if (pdfExtension !== 'pdf') {
                    alert("Seuls les fichiers PDF sont autorisés.");
                    return false;
                }
            }
            // Renommer le fichier PDF
            if (fichierPdf) {
                const newPdfName = `FDS_${cas}.pdf`;
                document.getElementById('fichier_pdf').setAttribute('data-newname', newPdfName);
            }
            return true;
        }

        function renamePdfFile(event) {
            const input = event.target;
            const newPdfName = input.getAttribute('data-newname');
            if (newPdfName) {
                const file = input.files[0];
                const dataTransfer = new DataTransfer();
                const renamedFile = new File([file], newPdfName, { type: file.type });
                dataTransfer.items.add(renamedFile);
                input.files = dataTransfer.files;
            }
        }

        function checkExistence(type, value) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'verifier_produit.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    const errorMessage = document.getElementById(`${type}_error`);
                    if (response.exists) {
                        errorMessage.textContent = `${type === 'produit' ? 'Produit' : 'CAS'} déjà existant.`;
                    } else {
                        errorMessage.textContent = '';
                    }
                }
            };
            xhr.send(`type=${type}&value=${encodeURIComponent(value)}`);
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('produit').addEventListener('input', (event) => {
                checkExistence('produit', event.target.value);
            });

            document.getElementById('cas').addEventListener('input', (event) => {
                checkExistence('cas', event.target.value);
            });

            document.getElementById('fichier_pdf').addEventListener('change', renamePdfFile);
        });
    </script>
</head>
<body>
    <div class="navbar">
        <a href="index.html">Accueil</a>
        <a href="inventaire.php">Inventaire</a>
        <a href="produits.php">Produits </a>
        <a href="ajout_produit.php" class="active">Ajout de Produit</a>
        <a href="gestion_batiments.php" >Gestion des Bâtiments</a>
    </div>
    <h2>Ajouter un produit</h2>
    <form action="traite_ajout_produit.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm();">
        <label for="produit">Produit :</label>
        <input type="text" id="produit" name="produit" required>
        <span id="produit_error" class="error"></span>

        <label for="synonymes">Synonymes :</label>
        <input type="text" id="synonymes" name="synonymes">

        <br><br><label for="cas">CAS/UFI :</label>
        <input type="text" id="cas" name="cas" required>
        <span id="cas_error" class="error"></span><br>

        <br>
		<img src="imgs/cmr.gif" alt="CMR" class="small-image">
		<label for="cmr">CMR :</label>
        <input type="checkbox" id="cmr" name="cmr">
		
		<img src="imgs/explosif.gif" alt="explosif" class="small-image">
        <label for="explosif">Explosif :</label>
        <input type="checkbox" id="explosif" name="explosif">
		<img src="imgs/inflammable.gif" alt="inflammable" class="small-image">
        <label for="inflammable">Inflammable :</label>
        <input type="checkbox" id="inflammable" name="inflammable">
		<img src="imgs/comburant.gif" alt="comburant" class="small-image">
        <label for="comburant">Comburant :</label>
        <input type="checkbox" id="comburant" name="comburant">
		<img src="imgs/gaz_sous_pression.gif" alt="gaz_sous_pression" class="small-image">
        <label for="gaz_sous_pression">Gaz sous pression :</label>
        <input type="checkbox" id="gaz_sous_pression" name="gaz_sous_pression">
		
		<img src="imgs/corrosif.gif" alt="corrosif" class="small-image">
        <label for="corrosif">Corrosif :</label>
        <input type="checkbox" id="corrosif" name="corrosif">

		<img src="imgs/toxique.gif" alt="toxique" class="small-image">
        <label for="toxique">Toxique :</label>
        <input type="checkbox" id="toxique" name="toxique">
		
		<img src="imgs/irritant.gif" alt="irritant" class="small-image">
        <label for="irritant">Irritant :</label>
        <input type="checkbox" id="irritant" name="irritant">

		<img src="imgs/danger_long_terme.gif" alt="danger_long_terme" class="small-image">
        <label for="dange_long_terme">Danger long terme :</label>
        <input type="checkbox" id="dange_long_terme" name="dange_long_terme">

		<img src="imgs/danger_millieu_aquatique.gif" alt="danger_millieu_aquatique" class="small-image">
        <label for="dangereux_milieu_aquatique">Dangereux milieu aquatique :</label>
        <input type="checkbox" id="dangereux_milieu_aquatique" name="dangereux_milieu_aquatique"><br><br>

        <label for="phrases_h">Phrases de risques (HXXX) :</label>
        <div class="checkbox-grid">
            <?php
            // Connexion à la base de données
			include 'cfg.php';

            $conn = new mysqli($host, $user, $password, $db, $port);
            $sql = "SELECT id, codeH, phrase FROM t_phrasesH ORDER BY codeH ASC ";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<label><input type='checkbox' name='phrases_h[]' value='" . $row['codeH'] . "'> " . $row['codeH'] . " - " . $row['phrase'] . "</label>";
                }
            }

            $conn->close();
            ?>
        </div><br>

        <label for="fichier_pdf">Fichier PDF FDS:</label>
        <input type="file" id="fichier_pdf" name="fichier_pdf" accept="application/pdf" ><br>

        <input type="submit" value="Ajouter" style="font-size: 20px; padding: 10px 20px;">

    </form>
</body>
</html>
