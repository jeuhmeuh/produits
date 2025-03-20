<link rel="stylesheet" href="style.css">
<?php
// Vérification de la présence du fichier cfg.php
if (file_exists("cfg.php")) {
    die("Installation déjà effectuée. Le fichier cfg.php existe.Pour une nouvelle installation supprimez ce fichier et supprimer la base de données Produits");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $host = $_POST["host"];
    $port = $_POST["port"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $dbname = $_POST["dbname"];
    $sqlFile = "produits_chimiques.sql"; // Chemin vers votre fichier SQL

    // Connexion à MariaDB
    $conn = new mysqli($host, $username, $password, "", $port);

    if ($conn->connect_error) {
        die("Échec de la connexion : " . $conn->connect_error);
    }

    // Création de la base de données
    if (!$conn->query("CREATE DATABASE IF NOT EXISTS `$dbname`")) {
        die("Erreur lors de la création de la base de données : " . $conn->error);
    }

    $conn->select_db($dbname);

    // Importation du fichier SQL
    if (file_exists($sqlFile)) {
        $queries = file_get_contents($sqlFile);
        if (!$conn->multi_query($queries)) {
            die("Erreur lors de l'importation du fichier SQL : " . $conn->error);
        }
    } else {
        die("Le fichier SQL n'existe pas.");
    }

    // Enregistrement des paramètres dans cfg.php
    $configContent = "<?php\n";
    $configContent .= "\$host = '$host';\n";
    $configContent .= "\$port = $port;\n";
    $configContent .= "\$username = '$username';\n";
    $configContent .= "\$password = '$password';\n";
    $configContent .= "\$dbname = '$dbname';\n";

    if (!file_put_contents("cfg.php", $configContent)) {
        die("Erreur lors de la création du fichier de configuration.");
    }

    echo "Installation réussie.";
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Installateur</title>
</head>
<body>
<H2>Installation du système d'inventaire de produits chimiques :</H2>
<br>
<?php if (file_exists("cfg.php")) {
    die("Installation déjà effectuée. Le fichier cfg.php existe.Pour une nouvelle installation supprimez ce fichier et supprimer la base de données Produits");
}?>
<br>
    <form method="POST">
        <label>Hôte :</label>
        <input type="text" name="host" value="127.0.0.1"required>
		(habituellement 127.0.0.1)<br>
        <label>Port :</label>
        <input type="number" name="port" value=3306 required>
		(port d'accès à MariaDB 3306 par defaut)
		<br>
        <label>Identifiant :</label>
        <input type="text" name="username" required><br>
        <label>Mot de passe :</label>
        <input type="password" name="password" required><br>
        <label>Nom de la base de données :</label>
        <input type="text" name="dbname" required>
		(nom souhaité pour la base de donées)
		<br>
        <button class="styled-button" type="submit">Installer</button>
    </form>
</body>
</html>