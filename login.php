<?php
session_start();

$dataFile = 'data/data.json';
$data = json_decode(file_get_contents($dataFile), true);

if ($data === null) {
    die('Erreur lors du chargement des données JSON');
}

$users = $data['utilisateurs'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = array_filter($users, function ($u) use ($email) {
        return $u['email'] === $email;
    });

    if (empty($user)) {
        die('Email ou mot de passe incorrect.');
    }
    $user = array_shift($user);

    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        header("Location: index.php");
        exit();
    } else {
        die('Email ou mot de passe incorrect.');
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Hébergement Web</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="form-container">
        <h1>Connexion</h1>
        <form method="post">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Se connecter">
        </form>
        <p>Pas encore de compte ? <a href="./register.php">Inscrivez-vous</a></p>
    </div>
</body>

</html>