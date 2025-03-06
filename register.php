<?php
$dataFile = 'data/data.json';
$data = json_decode(file_get_contents($dataFile), true);

if ($data === null) {
    die('Erreur lors du chargement des données JSON');
}

$users = &$data['utilisateurs'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    foreach ($users as $user) {
        if ($user['email'] === $email) {
            die('Un compte avec cet email existe déjà.');
        }
    }

    $user_id = count($users) + 1;
    $users[] = [
        'id' => $user_id,
        'nom' => $nom,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_BCRYPT),
        'role' => 'client',
    ];

    file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT));

    header("Location: ./login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Hébergement Web</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="form-container">
        <h1>Inscription</h1>
        <form method="post">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required>
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="S'inscrire">
        </form>
        <p>Déjà un compte ? <a href="./login.php">Connectez-vous</a></p>
    </div>
</body>

</html>