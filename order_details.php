<?php
session_start();

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$dataFile = 'data/data.json';
$data = json_decode(file_get_contents($dataFile), true);

if ($data === null) {
    die('Erreur lors du chargement des données JSON');
}

$orders = $data['commandes'];
$plans = $data['plans_hebergement'];
$users = $data['utilisateurs'];

// Vérifier si un ID de commande est spécifié
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Trouver la commande
    $order = array_filter($orders, function ($o) use ($order_id) {
        return $o['id'] == $order_id && $o['user_id'] == $_SESSION['user_id'];
    });

    if (empty($order)) {
        die("Commande non trouvée.");
    }

    $order = array_shift($order);

    // Trouver le plan correspondant à la commande
    $plan = array_filter($plans, function ($p) use ($order) {
        return $p['id'] == $order['plan_id'];
    });

    $plan = array_shift($plan);

} else {
    die("ID de commande non spécifié.");
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Commande - Hébergement Web</title>
    <link rel="stylesheet" href="css/order_details.css">
</head>

<body>
    <header>
        <div class="container">
            <h1>Détails de la Commande</h1>
            <nav>
                <ul>
                    <li><a href="./index.php" class="button">Accueil</a></li>
                    <li><a href="./orders.php" class="button">Mes Commandes</a></li>
                    <li><a href="./profile.php" class="button">Mon Profil</a></li>
                    <li><a href="./logout.php" class="button logout">Déconnexion</a></li>
                    <?php if ($_SESSION['user_role'] == 'admin'): ?>
                        <li><a href="./admin.php" class="button">Admin Panel</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <div class="container">
            <section class="order-details">
                <h2>Commande #<?php echo htmlspecialchars($order['id']); ?></h2>
                <p><strong>Plan : </strong><?php echo htmlspecialchars($plan['nom_plan']); ?></p>
                <p><strong>Prix : </strong><?php echo htmlspecialchars($plan['prix']); ?>€ / mois</p>
                <p><strong>Date de Commande : </strong><?php echo htmlspecialchars($order['date_commande']); ?></p>
                <p><strong>Statut : </strong><?php echo htmlspecialchars($order['status']); ?></p>
            </section>
        </div>
    </main>
</body>

</html>