<?php
session_start();

// Vérifier que l'utilisateur est connecté et qu'il est administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ./login.php");
    exit();
}

$dataFile = 'data/data.json';

// Charger les données JSON
$data = json_decode(file_get_contents($dataFile), true);
if ($data === null) {
    die('Erreur lors du chargement des données JSON');
}

$orders = $data['commandes'];
$plans = $data['plans_hebergement'];
$users = $data['utilisateurs'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    $orderFound = false;

    // Mettre à jour le statut de la commande
    foreach ($orders as &$order) {
        if ($order['id'] == $order_id) {
            $order['status'] = $new_status;
            $orderFound = true;
            break;
        }
    }

    if ($orderFound) {
        // Sauvegarder les données modifiées
        $data['commandes'] = $orders;
        if (file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT)) === false) {
            die('Erreur lors de l\'enregistrement des données JSON');
        }

        header("Location: ./admin.php");
        exit();
    } else {
        echo "Commande non trouvée.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Commandes</title>
    <link rel="stylesheet" href="css/admin.css">
</head>

<body>
    <header>
        <div class="container">
            <h1>Gestion des Commandes</h1>
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

    <main class="container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Utilisateur</th>
                    <th>Plan</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Modifier</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order) {
                    $user = array_filter($users, function ($u) use ($order) {
                        return $u['id'] == $order['user_id'];
                    });
                    $user = array_shift($user);

                    $plan = array_filter($plans, function ($p) use ($order) {
                        return $p['id'] == $order['plan_id'];
                    });
                    $plan = array_shift($plan);
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['nom']); ?></td>
                        <td><?php echo htmlspecialchars($plan['nom_plan']); ?></td>
                        <td><?php echo htmlspecialchars($order['date_commande']); ?></td>
                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                        <td>
                            <form method="post" class="status-form">
                                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['id']); ?>">
                                <select name="status" class="status-select">
                                    <option value="En attente" <?php if ($order['status'] === 'En attente')
                                        echo 'selected'; ?>>En attente</option>
                                    <option value="Traitement" <?php if ($order['status'] === 'Traitement')
                                        echo 'selected'; ?>>Traitement</option>
                                    <option value="Complété" <?php if ($order['status'] === 'Complété')
                                        echo 'selected'; ?>>
                                        Complété</option>
                                    <option value="Annulé" <?php if ($order['status'] === 'Annulé')
                                        echo 'selected'; ?>>Annulé
                                    </option>
                                </select>
                                <button type="submit" class="update-button">Mettre à jour</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>
</body>

</html>