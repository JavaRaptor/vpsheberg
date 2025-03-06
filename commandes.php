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

$plans = $data['plans_hebergement'];
$orders = &$data['commandes'];
$users = $data['utilisateurs'];

if (isset($_GET['plan_id'])) {
    $plan_id = $_GET['plan_id'];
    $plan = array_filter($plans, function ($p) use ($plan_id) {
        return $p['id'] == $plan_id;
    });

    if (empty($plan)) {
        die("Plan non trouvé.");
    }
    $plan = array_shift($plan);
} else {
    die("Plan non spécifié.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];

    // Générer un identifiant unique pour la commande
    $order_id = uniqid();

    $orders[] = [
        'id' => $order_id,
        'user_id' => $user_id,
        'plan_id' => $plan_id,
        'date_commande' => date('Y-m-d H:i:s'),
        'status' => 'En attente'
    ];

    file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT));

    header("Location: ./confirmation.php?order_id=" . $order_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commander - Hébergement Web</title>
    <link rel="stylesheet" href="css/commandes.css">
</head>

<body>
    <div class="container">
        <header>
            <h1>Commander le Plan</h1>
        </header>
        <main>
            <div class="plan-details">
                <h2><?php echo htmlspecialchars($plan['nom_plan']); ?></h2>
                <p><strong>Prix :</strong> <?php echo htmlspecialchars($plan['prix']); ?> €</p>
                <p><?php echo htmlspecialchars($plan['details']); ?></p>
            </div>
            <form method="post" class="order-form">
                <input type="submit" value="Passer commande">
            </form>
        </main>
    </div>
</body>

</html>