<?php
// Fonction pour lire les choix depuis le fichier
function readChoices($filename) {
    if (!file_exists($filename)) {
        return [0, 0]; // Valeurs par défaut si le fichier n'existe pas
    }

    $contents = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return array_map('intval', $contents);
}

// Fonction pour écrire les choix dans le fichier
function writeChoices($filename, $choices) {
    $contents = implode(PHP_EOL, $choices);
    file_put_contents($filename, $contents);
}

$filename = 'choices.txt';
$choices = readChoices($filename);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les valeurs du formulaire
    $choice1 = isset($_POST['choice1']) ? 1 : 0;
    $choice2 = isset($_POST['choice2']) ? 1 : 0;
    $choices = [$choice1, $choice2];

    // Écrire les nouvelles valeurs dans le fichier
    writeChoices($filename, $choices);
}
?>

<!DOCTYPE html>
<html lang="en">
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
                <label for="choice2">en cours d'acuiqition</label>
            </div>
            <div class="button-container">
                <button type="submit">Sauvegarder</button>
            </div>
        </form>
    </div>
</body>
</html>
