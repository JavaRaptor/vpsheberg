<?php
session_start();

// Vérifiez que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$dataFile = 'data/data.json';
$data = json_decode(file_get_contents($dataFile), true);

if ($data === null) {
    die('Erreur lors du chargement des données JSON');
}

$users = $data['utilisateurs'];

// Trouver l'utilisateur courant
$user = array_filter($users, function ($u) {
    return $u['id'] == $_SESSION['user_id'];
});
$user = array_shift($user);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - Hébergement Web</title>
    <link rel="stylesheet" href="css/profile.css">
</head>

<body>
    <header>
        <div class="container">
            <h1>Mon Profil</h1>
            <nav>
                <ul>
                    <li><a href="./index.php" class="button">Accueil</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="./orders.php" class="button">Mes Commandes</a></li>
                        <li><a href="./profile.php" class="button">Mon Profil</a></li>
                        <li><a href="./logout.php" class="button logout">Déconnexion</a></li>
                        <?php if ($_SESSION['user_role'] == 'admin'): ?>
                            <li><a href="./admin.php" class="button">Admin Panel</a></li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <div class="container">
            <section class="profile">
                <h2>Informations du Profil</h2>
                <div class="profile-info">
                    <p><strong>Nom :</strong> <?php echo htmlspecialchars($user['nom']); ?></p>
                    <p><strong>Email :</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><strong>Rôle :</strong> <?php echo htmlspecialchars($user['role']); ?></p>
                </div>
                <div class="profile-actions">
                    <a href="./edit_profile.php" class="button">Modifier Profil</a>
                </div>
            </section>
        </div>
    </main>
</body>

</html>