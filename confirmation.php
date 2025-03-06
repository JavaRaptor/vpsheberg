<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.php");
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

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Trouver la commande
    $order = array_filter($orders, function ($o) use ($order_id) {
        return isset($o['id']) && $o['id'] == $order_id;
    });

    if (empty($order)) {
        die("Commande non trouvée.");
    }
    $order = array_shift($order);

    // Trouver l'utilisateur
    $user = array_filter($users, function ($u) use ($order) {
        return isset($u['id']) && $u['id'] == $order['user_id'];
    });
    $user = array_shift($user);

    // Trouver le plan
    $plan = array_filter($plans, function ($p) use ($order) {
        return isset($p['id']) && $p['id'] == $order['plan_id'];
    });
    $plan = array_shift($plan);
} else {
    die("Commande non spécifiée.");
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Commande - Hébergement Web</title>
    <link rel="stylesheet" href="css/confirmation.css">
</head>

<body>
    <div class="container">
        <header>
            <h1>Confirmation de Commande</h1>
        </header>
        <main>
            <div class="confirmation">
                <h2>Merci pour votre commande, <?php echo htmlspecialchars($user['nom']); ?> !</h2>
                <div class="order-details">
                    <p><strong>Plan Commandé :</strong> <?php echo htmlspecialchars($plan['nom_plan']); ?></p>
                    <p><strong>Date de Commande :</strong> <?php echo htmlspecialchars($order['date_commande']); ?></p>
                    <p><strong>Statut de la Commande :</strong> <?php echo htmlspecialchars($order['status']); ?></p>
                </div>
                <a href="./index.php" class="button">Retour à l'accueil</a>
            </div>
        </main>
    </div>
</body>

</html>