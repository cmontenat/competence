<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #000;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        .container {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            text-align: center;
        }

        h2 {
            color: #fff;
            margin-bottom: 20px;
        }

        .button-container {
            display: flex;
            flex-direction: row;
            gap: 15px;
            margin-bottom: 20px;
            justify-content: center;
        }

        button {
            background-color: #f00;
            color: #fff;
            border: none;
            padding: 8px;
            border-radius: 8px;
            cursor: pointer;
            width: 100px;
        }

        button:hover {
            background-color: #d00;
        }
    </style>
</head>
<body>
    <h1>Inscription</h1>
    <form method="post" action="avant-index.php">
        <label for="prenom">Prénom:</label>
        <input type="text" id="prenom" name="prenom" required>
        <button type="submit">Inscription</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $prenom = trim($_POST['prenom']);
        $filePath = 'choices.txt';

        // Vérifier si le fichier existe et est lisible
        if (file_exists($filePath) && is_readable($filePath)) {
            $content = file_get_contents($filePath);
            $pattern = '/\[' . preg_quote($prenom, '/') . ',\s*\d,\s*\d\]/';

            if (preg_match($pattern, $content)) {
                echo "<p>Le prénom <strong>$prenom</strong> est déjà pris.</p>";
            } else {
                $newEntry = "[$prenom, 0, 0]";
                file_put_contents($filePath, $content . PHP_EOL . $newEntry);
                $_SESSION['prenom'] = $prenom; // Stocker le prénom dans la session
                header('Location: index.php'); // Rediriger vers index.php
                exit();
            }
        } else {
            echo "<p>Erreur : Impossible de lire le fichier $filePath.</p>";
        }
    }
    ?>
</body>
</html>
