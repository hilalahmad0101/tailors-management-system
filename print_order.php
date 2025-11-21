<?php
require_once 'config.php';
checkLogin();

$orderId = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header('Location: list_orders.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ur" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پرنٹ آرڈر #<?= $order['id'] ?> - SK Fabrics & Tailors</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;700&display=swap');

        body {
            font-family: 'Noto Nastaliq Urdu', serif;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white;
            }

            .print-page {
                page-break-after: always;
            }
        }
    </style>
</head>

<body class="bg-gray-100 p-4">
    <!-- Print Actions - No Print -->
    <div class="no-print max-w-4xl mx-auto mb-4 flex gap-4">
        <button onclick="window.print()" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-bold text-xl">
            🖨️ پرنٹ کریں
        </button>
        <a href="list_orders.php" class="flex-1 bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 font-bold text-xl text-center">
            📋 واپس فہرست میں
        </a>
        <a href="add_order.php" class="flex-1 bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 font-bold text-xl text-center">
            ➕ نیا آرڈر
        </a>
    </div>

    <!-- Print Content -->
    <div class="max-w-4xl mx-auto bg-white shadow-2xl rounded-lg print-page">
        <div class="p-8">
            <!-- Header -->
            <div class="text-center mb-8 border-b-4 border-purple-600 pb-6">
                <div class="flex justify-center items-center mb-4">
                    <div class="w-24 h-24 rounded-full border-4 border-gray-800 flex items-center justify-center">
                        <span class="text-4xl font-bold">SK</span>
                    </div>
                </div>
                <h1 class="text-4xl font-bold text-purple-600 mb-2">SK FABRICS & TAILORS</h1>
                <p class="text-xl text-gray-700">Sarfaraz Khan: 📞 0333-1212290</p>
                <p class="text-lg text-gray-600 mt-2">آرڈر نمبر: <span class="font-bold">#<?= $order['id'] ?></span></p>
            </div>

            <!-- Customer Info -->
            <div class="grid grid-cols-2 gap-6 mb-8 bg-purple-50 p-6 rounded-lg">
                <div class="text-xl">
                    <span class="font-bold text-gray-700">نام:</span>
                    <span class="text-purple-800 font-bold"><?= htmlspecialchars($order['customer_name']) ?></span>
                </div>
                <div class="text-xl">
                    <span class="font-bold text-gray-700">فون:</span>
                    <span class="text-purple-800 font-bold font-mono"><?= htmlspecialchars($order['phone']) ?></span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8">
                <!-- Measurements Column -->
                <div>
                    <h3 class="text-2xl font-bold text-purple-600 mb-4 border-b-2 border-purple-600 pb-2">
                        📏 پیمائش
                    </h3>
                    <div class="space-y-3">
                        <?php
                        $measurements = [
                            'lambai' => 'لمبائی',
                            'bazu' => 'بازو',
                            'tera' => 'تیرہ',
                            'collar' => 'کالر',
                            'chati' => 'چھاتی',
                            'kamar' => 'کمر',
                            'daman' => 'دامن',
                            'shalwar' => 'شلوار',
                            'pancha' => 'پانچہ'
                        ];

                        foreach ($measurements as $key => $label):
                            if (!empty($order[$key])):
                        ?>
                                <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                                    <span class="text-lg font-bold text-gray-700"><?= $label ?>:</span>
                                    <span class="text-xl font-bold text-purple-700"><?= htmlspecialchars($order[$key]) ?></span>
                                </div>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </div>
                </div>

                <!-- Styles Column -->
                <div>
                    <h3 class="text-2xl font-bold text-purple-600 mb-4 border-b-2 border-purple-600 pb-2">
                        ✂️ سٹائل اور آپشنز
                    </h3>
                    <div class="space-y-3">
                        <?php
                        $styles = [
                            ['type' => 'collar_type', 'custom' => 'collar_custom', 'label' => 'کالر'],
                            ['type' => 'cuff_type', 'custom' => 'cuff_custom', 'label' => 'کف'],
                            ['type' => 'pocket_type', 'custom' => 'pocket_custom', 'label' => 'جیب'],
                            ['type' => 'daman_style', 'custom' => 'daman_style_custom', 'label' => 'دامن سٹائل'],
                            ['type' => 'kurta_type', 'custom' => 'kurta_custom', 'label' => 'کرتہ']
                        ];

                        foreach ($styles as $style):
                            $value = $order[$style['type']];
                            $custom = $order[$style['custom']];
                            if (!empty($value) || !empty($custom)):
                        ?>
                                <div class="bg-purple-50 p-3 rounded-lg border border-purple-200">
                                    <span class="font-bold text-gray-700"><?= $style['label'] ?>:</span>
                                    <span class="text-purple-800 font-bold">
                                        <?= htmlspecialchars($value) ?>
                                        <?= $custom ? ' - ' . htmlspecialchars($custom) : '' ?>
                                    </span>
                                </div>
                        <?php
                            endif;
                        endforeach;
                        ?>

                        <!-- Checkbox Options -->
                        <div class="mt-6">
                            <h4 class="font-bold text-lg text-gray-700 mb-3">اضافی خدمات:</h4>
                            <div class="space-y-2">
                                <?php
                                $options = [
                                    'rang_button' => 'رنگ بٹن',
                                    'design_button' => 'ڈیزائن بٹن',
                                    'design_silai' => 'ڈیزائن سلائی',
                                    'double_silai' => 'ڈبل سلائی',
                                    'maghzi_lagani' => 'مغزی لگانی',
                                    'band_ko_ghara' => 'بندکوغاڑہ',
                                    'chamak_dhaga' => 'چمک دھاگہ',
                                    'guldozi' => 'گلدوزی'
                                ];

                                foreach ($options as $key => $label):
                                    if ($order[$key]):
                                ?>
                                        <div class="flex items-center gap-2">
                                            <span class="text-green-600 text-xl">✓</span>
                                            <span class="font-bold text-gray-700"><?= $label ?></span>
                                        </div>
                                <?php
                                    endif;
                                endforeach;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <?php if (!empty($order['notes'])): ?>
                <div class="mt-8 bg-yellow-50 p-6 rounded-lg border-2 border-yellow-400">
                    <h3 class="text-xl font-bold text-gray-800 mb-3">📝 خاص نوٹس:</h3>
                    <p class="text-lg text-gray-700 leading-relaxed"><?= nl2br(htmlspecialchars($order['notes'])) ?></p>
                </div>
            <?php endif; ?>

            <!-- Footer -->
            <div class="mt-8 pt-6 border-t-2 border-gray-300 flex justify-between items-center">
                <div class="text-gray-600">
                    <p class="text-lg">تاریخ: <span class="font-bold"><?= date('d/m/Y', strtotime($order['created_at'])) ?></span></p>
                    <p class="text-lg">وقت: <span class="font-bold"><?= date('h:i A', strtotime($order['created_at'])) ?></span></p>
                </div>
                <div class="text-center">
                    <p class="text-gray-600 mb-2">دستخط</p>
                    <div class="border-b-2 border-gray-400 w-48"></div>
                </div>
            </div>

            <div class="mt-6 text-center text-gray-500 text-sm">
                <p>شکریہ آپ کا! ہماری خدمات استعمال کرنے کے لیے</p>
            </div>
        </div>
    </div>
</body>

</html>