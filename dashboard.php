<?php
require_once 'config.php';
checkLogin();

// Get statistics
$stmt = $pdo->query("SELECT COUNT(*) as total FROM orders");
$totalOrders = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as today FROM orders WHERE DATE(created_at) = CURDATE()");
$todayOrders = $stmt->fetch()['today'];

$stmt = $pdo->query("SELECT COUNT(*) as week FROM orders WHERE YEARWEEK(created_at) = YEARWEEK(NOW())");
$weekOrders = $stmt->fetch()['week'];
?>
<!DOCTYPE html>
<html lang="ur" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ڈیش بورڈ - SK Fabrics & Tailors</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?= getCommonStyles() ?>
</head>

<body class="p-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="card p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full border-4 border-purple-600 flex items-center justify-center">
                        <span class="text-2xl font-bold text-purple-600">SK</span>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">SK FABRICS & TAILORS</h1>
                        <p class="text-gray-600">خوش آمدید، <?= htmlspecialchars($_SESSION['username']) ?></p>
                    </div>
                </div>
                <a href="logout.php" class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600">
                    لاگ آؤٹ
                </a>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="card p-6 text-center">
                <div class="text-5xl font-bold text-purple-600 mb-2"><?= $totalOrders ?></div>
                <div class="text-gray-600 text-xl">کل آرڈرز</div>
            </div>
            <div class="card p-6 text-center">
                <div class="text-5xl font-bold text-green-600 mb-2"><?= $todayOrders ?></div>
                <div class="text-gray-600 text-xl">آج کے آرڈرز</div>
            </div>
            <div class="card p-6 text-center">
                <div class="text-5xl font-bold text-blue-600 mb-2"><?= $weekOrders ?></div>
                <div class="text-gray-600 text-xl">اس ہفتے کے آرڈرز</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card p-6">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">فوری رسائی</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="add_order.php" class="btn-primary text-white p-6 rounded-lg text-center text-xl font-bold hover:shadow-lg">
                    ➕ نیا آرڈر شامل کریں
                </a>
                <a href="list_orders.php" class="bg-gradient-to-r from-blue-500 to-blue-700 text-white p-6 rounded-lg text-center text-xl font-bold hover:shadow-lg">
                    📋 تمام آرڈرز دیکھیں
                </a>
            </div>
        </div>
    </div>
</body>

</html>