<?php
session_start();

$dataFile = 'data/data.json';

// Chargez les données JSON et vérifiez qu'elles existent et qu'elles ont été correctement parsées.
$data = json_decode(file_get_contents($dataFile), true);

if ($data === null) {
    die('Erreur lors du chargement des données JSON');
}

// Vérifiez que 'plans_hebergement' existe dans le tableau des données et qu'il n'est pas vide.
if (isset($data['plans_hebergement']) && !empty($data['plans_hebergement'])) {
    $plans = $data['plans_hebergement'];
} else {
    // Si le tableau des plans est vide ou non défini, définissez une valeur par défaut ou affichez un message d'erreur.
    $plans = [];
    echo 'Aucun plan d\'hébergement disponible pour le moment.';
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Plans d'Hébergement</title>
    <link rel="stylesheet" href="css/test.css">
</head>

<body>
    <header>
        <div class="container">
            <h1>Nos Plans d'Hébergement Web</h1>
            <nav>
                <ul>
                    <li><a href="./index.php" class="button">Accueil</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="./orders.php" class="button">Mes Commandes</a></li>
                        <li><a href="./profile.php" class="button">Mon Profil</a></li>
                        <li><a href="./logout.php" class="button">Déconnexion</a></li>
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