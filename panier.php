<?php
require_once 'config/db.php';
require_once 'config/session.php';

// Initialisation du panier si inexistant
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Ajouter au panier
if (isset($_GET['add'])) {
    $id = (int)$_GET['add'];
    if ($id > 0) {
        if (!isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] = 1;
        } else {
            $_SESSION['cart'][$id]++;
        }
    }
    header('Location: panier.php');
    exit;
}

// Supprimer du panier
if (isset($_GET['remove'])) {
    $id = (int)$_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header('Location: panier.php');
    exit;
}

// Mettre à jour les quantités
if (isset($_POST['update_cart'])) {
    if (isset($_POST['qty']) && is_array($_POST['qty'])) {
        foreach ($_POST['qty'] as $id => $qty) {
            $id = (int)$id;
            $qty = (int)$qty;
            if ($qty <= 0) {
                unset($_SESSION['cart'][$id]);
            } else {
                $_SESSION['cart'][$id] = $qty;
            }
        }
    }
    header('Location: panier.php');
    exit;
}

// Récupérer les produits du panier depuis la BDD
$cart_products = [];
$total = 0;
if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $placeholders = str_repeat('?,', count($ids) - 1) . '?';
    $stmt = $pdo->prepare("SELECT * FROM produits WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $cart_products = $stmt->fetchAll();
    
    foreach ($cart_products as $p) {
        $total += $p['prix'] * $_SESSION['cart'][$p['id']];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier - RDOC</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-orbitron { font-family: 'Orbitron', sans-serif; }
        .text-primary { color: #5CC4E5; }
        .bg-primary { background-color: #5CC4E5; }
    </style>
</head>
<body class="bg-black text-white min-h-screen flex flex-col">
    <?php include 'includes/header.php'; ?>
    
    <main class="flex-grow py-24 px-4 sm:px-8 max-w-7xl mx-auto w-full">
        <h1 class="text-4xl sm:text-5xl font-orbitron font-bold mb-12 text-primary">Votre Panier</h1>
        
        <?php if (empty($cart_products)): ?>
            <div class="text-center py-20 bg-white/5 rounded-3xl border border-white/10 backdrop-blur-xl">
                <p class="text-gray-400 text-xl mb-8">Votre panier est actuellement vide.</p>
                <a href="produit.php" class="inline-block bg-primary text-black px-10 py-4 rounded-full font-bold transition hover:scale-105 shadow-[0_0_20px_rgba(92,196,229,0.3)]">Découvrir nos robots</a>
            </div>
        <?php else: ?>
            <form action="panier.php" method="POST" class="space-y-8">
                <div class="bg-white/5 rounded-3xl overflow-hidden border border-white/10 backdrop-blur-xl">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white/10 text-gray-400 uppercase text-xs tracking-widest font-orbitron">
                                    <th class="p-6">Produit</th>
                                    <th class="p-6">Prix</th>
                                    <th class="p-6">Quantité</th>
                                    <th class="p-6">Total</th>
                                    <th class="p-6"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                <?php foreach ($cart_products as $p): ?>
                                <tr>
                                    <td class="p-6 flex items-center gap-6">
                                        <div class="w-20 h-20 bg-white/10 rounded-xl overflow-hidden flex items-center justify-center p-2">
                                            <img src="assets/images/<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['nom']); ?>" class="max-w-full max-h-full object-contain">
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-lg"><?php echo htmlspecialchars($p['nom']); ?></span>
                                            <span class="text-gray-500 text-sm">Disponibilité immédiate</span>
                                        </div>
                                    </td>
                                    <td class="p-6 text-gray-400"><?php echo number_format($p['prix'], 2, ',', ' '); ?> €</td>
                                    <td class="p-6">
                                        <input type="number" name="qty[<?php echo $p['id']; ?>]" value="<?php echo $_SESSION['cart'][$p['id']]; ?>" min="1" class="bg-white/5 border border-white/20 rounded-lg px-4 py-2 w-24 text-center focus:border-primary outline-none transition">
                                    </td>
                                    <td class="p-6 font-bold text-primary"><?php echo number_format($p['prix'] * $_SESSION['cart'][$p['id']], 2, ',', ' '); ?> €</td>
                                    <td class="p-6 text-right">
                                        <a href="panier.php?remove=<?php echo $p['id']; ?>" class="text-red-400 hover:text-red-300 transition p-2 inline-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="grid lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2">
                        <button type="submit" name="update_cart" class="border border-white/20 px-8 py-4 rounded-full font-bold hover:bg-white/5 transition flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Mettre à jour le panier
                        </button>
                    </div>
                    
                    <div class="bg-white/5 p-8 rounded-3xl border border-white/10 backdrop-blur-xl">
                        <h2 class="text-2xl font-orbitron font-bold mb-8">Récapitulatif</h2>
                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Sous-total</span>
                                <span><?php echo number_format($total, 2, ',', ' '); ?> €</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Livraison</span>
                                <span class="text-green-400">Gratuite</span>
                            </div>
                            <div class="border-t border-white/10 pt-4 mt-4">
                                <div class="flex justify-between text-2xl font-bold">
                                    <span>Total</span>
                                    <span class="text-primary"><?php echo number_format($total, 2, ',', ' '); ?> €</span>
                                </div>
                            </div>
                        </div>
                        <a href="commander.php" class="block w-full text-center bg-primary text-black px-8 py-4 rounded-full font-bold hover:scale-[1.02] transition shadow-[0_0_30px_rgba(92,196,229,0.4)]">Passer la commande</a>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </main>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>
