<?php
session_start();

// Fonction pour lire les choix depuis le fichier pour un prénom donné
function readChoices($filename, $prenom) {
    if (!file_exists($filename)) {
        return [0, 0]; // Valeurs par défaut si le fichier n'existe pas
    }

    $contents = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($contents as $line) {
        $data = json_decode($line, true);
        if ($data[0] === $prenom) {
            return [$data[1], $data[2]];
        }
    }
    return [0, 0]; // Valeurs par défaut si le prénom n'est pas trouvé
}

// Fonction pour écrire les choix dans le fichier pour un prénom donné
function writeChoices($filename, $prenom, $choices) {
    if (!file_exists($filename)) {
        file_put_contents($filename, "");
    }

    $contents = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $newContents = [];
    $found = false;

    foreach ($contents as $line) {
        $data = json_decode($line, true);
        if ($data[0] === $prenom) {
            $data[1] = $choices[0];
            $data[2] = $choices[1];
            $found = true;
        }
        $newContents[] = json_encode($data);
    }

    if (!$found) {
        $newContents[] = json_encode([$prenom, $choices[0], $choices[1]]);
    }

    file_put_contents($filename, implode(PHP_EOL, $newContents) . PHP_EOL);
}

if (!isset($_SESSION['prenom'])) {
    header('Location: avant-index.php');
    exit();
}

$prenom = $_SESSION['prenom'];
$filename = 'choices.txt';
$choices = readChoices($filename, $prenom);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les valeurs du formulaire
    $choice1 = isset($_POST['choice1']) ? 1 : 0;
    $choice2 = isset($_POST['choice2']) ? 1 : 0;
    $choices = [$choice1, $choice2];

    // Écrire les nouvelles valeurs dans le fichier
    writeChoices($filename, $prenom, $choices);
    header('Location: index.php'); // Rediriger pour éviter la resoumission du formulaire
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire avec choix</title>
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
    <h2>Formulaire avec Choix</h2>
    <div class="container">
        <form method="POST" action="">
            <div>
                <input type="checkbox" name="choice1" id="choice1" <?php echo $choices[0] ? 'checked' : ''; ?>>
                <label for="choice1">Acquise</label>
            </div>
            <div>
                <input type="checkbox" name="choice2" id="choice2" <?php echo $choices[1] ? 'checked' : ''; ?>>
                <label for="choice2">En cours d'acquisition</label>
            </div>
            <div class="button-container">
                <button type="submit">Sauvegarder</button>
            </div>
        </form>
        <p>Bonjour, <strong><?php echo htmlspecialchars($prenom); ?></strong>!</p>
        <a href="avant-index.php">Retour à l'inscription</a>
    </div>
</body>
</html>

