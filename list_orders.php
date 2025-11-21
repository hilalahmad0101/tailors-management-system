<?php
require_once 'config.php';
checkLogin();

// Handle delete
if (isset($_GET['delete']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $deleteId = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->execute([$deleteId]);
    $_SESSION['message'] = 'آرڈر کامیابی سے حذف ہو گیا!';
    header('Location: list_orders.php');
    exit;
}

$search = $_GET['search'] ?? '';
$page = intval($_GET['page'] ?? 1);
$perPage = 20;
$offset = ($page - 1) * $perPage;

if ($search) {
    $searchTerm = '%' . $search . '%';
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE customer_name LIKE ? OR phone LIKE ? ORDER BY created_at DESC LIMIT $perPage OFFSET $offset");
    $stmt->execute([$searchTerm, $searchTerm]);
    
    $countStmt = $pdo->prepare("SELECT COUNT(*) as total FROM orders WHERE customer_name LIKE ? OR phone LIKE ?");
    $countStmt->execute([$searchTerm, $searchTerm]);
} else {
    $stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT $perPage OFFSET $offset");
    
    $countStmt = $pdo->query("SELECT COUNT(*) as total FROM orders");
}

$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalOrders = $countStmt->fetch()['total'];
$totalPages = ceil($totalOrders / $perPage);
?>
<!DOCTYPE html>
<html lang="ur" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>آرڈرز کی فہرست - SK Fabrics & Tailors</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?= getCommonStyles() ?>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            animation: fadeIn 0.3s;
        }
        
        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            animation: slideIn 0.3s;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body class="p-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="card p-4 mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">آرڈرز کی فہرست (<?= $totalOrders ?>)</h1>
            <div class="flex gap-2">
                <a href="dashboard.php" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                    ڈیش بورڈ
                </a>
                <a href="add_order.php" class="btn-primary text-white px-4 py-2 rounded-lg">
                    ➕ نیا آرڈر
                </a>
            </div>
        </div>

        <!-- Success Message -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="card p-4 mb-6 bg-green-100 border-2 border-green-500">
                <p class="text-green-800 font-bold text-center text-lg"><?= $_SESSION['message']; unset($_SESSION['message']); ?></p>
            </div>
        <?php endif; ?>

        <!-- Search -->
        <div class="card p-6 mb-6">
            <form method="GET" class="flex gap-4">
                <input type="text" name="search" placeholder="نام یا فون نمبر سے تلاش کریں"
                    class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none"
                    value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn-primary text-white px-8 py-3 rounded-lg font-bold">
                    🔍 تلاش
                </button>
                <?php if ($search): ?>
                    <a href="list_orders.php" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600">
                        صاف کریں
                    </a>
                <?php endif; ?>
                </button>
            </form>
        </div>
        <!--    </form>-->
        <!--</div>-->

        <!-- Orders Table -->
        <div class="card p-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-purple-600 to-purple-800 text-white">
                            <th class="px-4 py-4 text-center">سیریل نمبر</th>
                            <th class="px-4 py-4 text-right">آرڈر نمبر</th>
                            <th class="px-6 py-4 text-right">نام</th>
                            <th class="px-6 py-4 text-right">فون</th>
                            <th class="px-6 py-4 text-right">تاریخ</th>
                            <th class="px-6 py-4 text-center">ایکشن</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $serialNumber = $offset + 1;
                        foreach ($orders as $index => $order): 
                        ?>
                            <tr class="border-b hover:bg-purple-50 transition">
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-block bg-purple-100 text-purple-800 font-bold px-3 py-1 rounded-full">
                                        <?= $serialNumber++ ?>
                                    </span>
                                </td>
                                <td class="px-4 py-4 font-bold text-gray-600">#<?= $order['id'] ?></td>
                                <td class="px-6 py-4 font-bold"><?= htmlspecialchars($order['customer_name']) ?></td>
                                <td class="px-6 py-4 font-mono"><?= htmlspecialchars($order['phone']) ?></td>
                                <td class="px-6 py-4"><?= date('d/m/Y h:i A', strtotime($order['created_at'])) ?></td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex gap-2 justify-center">
                                        <a href="print_order.php?id=<?= $order['id'] ?>" 
                                            class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 font-bold">
                                            🖨️ پرنٹ
                                        </a>
                                        <button onclick="confirmDelete(<?= $order['id'] ?>, '<?= htmlspecialchars($order['customer_name']) ?>')"
                                            class="inline-block bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 font-bold">
                                            🗑️ حذف
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($orders)): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 text-xl">
                                    کوئی آرڈر نہیں ملا
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="flex justify-center gap-2 mt-6">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?>" 
                            class="px-4 py-2 rounded-lg font-bold <?= $i == $page ? 'bg-purple-600 text-white' : 'bg-gray-200 hover:bg-gray-300' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content bg-white rounded-2xl shadow-2xl p-8 max-w-md mx-4">
            <div class="text-center mb-6">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-4xl">⚠️</span>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">کیا آپ واقعی حذف کرنا چاہتے ہیں؟</h2>
                <p class="text-gray-600 text-lg">یہ عمل واپس نہیں ہو سکتا</p>
            </div>
            
            <div class="bg-red-50 border-2 border-red-200 rounded-lg p-4 mb-6">
                <p class="text-gray-700"><span class="font-bold">کسٹمر:</span> <span id="customerName" class="text-red-700 font-bold"></span></p>
                <p class="text-gray-700"><span class="font-bold">آرڈر نمبر:</span> <span id="orderId" class="text-red-700 font-bold"></span></p>
            </div>
            
            <form id="deleteForm" method="POST" class="flex gap-3">
                <button type="button" onclick="closeModal()" 
                    class="flex-1 bg-gray-300 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-400 font-bold text-lg">
                    منسوخ کریں
                </button>
                <button type="submit" 
                    class="flex-1 bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 font-bold text-lg">
                    حذف کریں
                </button>
            </form>
        </div>
    </div>

    <script>
        function confirmDelete(id, name) {
            document.getElementById('deleteModal').classList.add('active');
            document.getElementById('customerName').textContent = name;
            document.getElementById('orderId').textContent = '#' + id;
            document.getElementById('deleteForm').action = '?delete=' + id;
        }
        
        function closeModal() {
            document.getElementById('deleteModal').classList.remove('active');
        }
        
        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>
</html>