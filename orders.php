<?php
session_start();

// Vérifier que l'utilisateur est connecté
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

// Filtrer les commandes de l'utilisateur connecté
$user_orders = array_filter($orders, function ($order) {
    return $order['user_id'] == $_SESSION['user_id'];
});
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Commandes - Hébergement Web</title>
    <link rel="stylesheet" href="css/orders.css">
</head>

<body>
    <header>
        <div class="container">
            <h1>Mes Commandes</h1>
            <nav>
                <ul>
                    <li><a href="./index.php" class="button">Accueil</a></li>
                    <li><a href="./orders.php" class="button">Mes Commandes</a></li>
                    <li><a href="./profile.php" class="button">Mon Profil</a></li>
                    <li><a href="./logout.php" class="button">Déconnexion</a></li>
                    <?php if ($_SESSION['user_role'] == 'admin'): ?>
                        <li><a href="./admin.php" class="button">Admin Panel</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <div class="container">
            <section class="orders">
                <h2>Historique des Commandes</h2>
                <?php if (!empty($user_orders)): ?>
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>ID de la Commande</th>
                                <th>Plan</th>
                                <th>Date de Commande</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($user_orders as $order): ?>
                                <?php
                                $plan = array_filter($plans, function ($p) use ($order) {
                                    return $p['id'] == $order['plan_id'];
                                });
                                $plan = array_shift($plan);
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($order['id']); ?></td>
                                    <td><?php echo htmlspecialchars($plan['nom_plan']); ?></td>
                                    <td><?php echo htmlspecialchars($order['date_commande']); ?></td>
                                    <td><?php echo htmlspecialchars($order['status']); ?></td>
                                    <td><a href="./order_details.php?order_id=<?php echo htmlspecialchars($order['id']); ?>"
                                            class="button details">Voir Détails</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Vous n'avez passé aucune commande.</p>
                <?php endif; ?>
            </section>
        </div>
    </main>
</body>

</html>