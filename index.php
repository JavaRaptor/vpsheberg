<?php
session_start();

$dataFile = 'data/data.json';
$data = json_decode(file_get_contents($dataFile), true);

if ($data === null) {
    die('Erreur lors du chargement des données JSON');
}

$plans = $data['plans_hebergement'];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Hébergement Web</title>
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <header>
        <div class="container">
            <h1>Bienvenue sur notre site d'hébergement web</h1>
            <nav>
                <ul>
                    <li><a href="./index.php" class="button">Accueil</a></li>
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <li><a href="./login.php" class="button">Login</a></li>
                    <?php endif; ?>
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
            <h2>Choisissez un plan adapté à vos besoins</h2>
            <div class="plans">
                <?php if (!empty($plans)): ?>
                    <?php foreach ($plans as $plan): ?>
                        <div class="plan">
                            <h3><?php echo htmlspecialchars($plan['nom_plan']); ?></h3>
                            <p class="price"><?php echo htmlspecialchars($plan['prix']); ?>€ / mois</p>
                            <ul class="details">
                                <?php
                                // Assume $plan['details'] is a comma-separated list
                                $details = explode(',', $plan['details']);
                                foreach ($details as $detail) {
                                    echo "<li>" . htmlspecialchars(trim($detail)) . "</li>";
                                }
                                ?>
                            </ul>
                            <a href="./commandes.php?plan_id=<?php echo htmlspecialchars($plan['id']); ?>" class="button order">
                                Commander
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun plan d'hébergement disponible pour le moment. Veuillez revenir plus tard.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>

</html>