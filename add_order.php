<?php
require_once 'config.php';
checkLogin();

$stmt = $pdo->query("SELECT MAX(id) as max_id FROM orders");
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$nextSerialNumber = ($result['max_id'] ?? 0) + 1;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO orders (
        customer_name, phone, lambai, bazu, tera, collar, chati, kamar, daman, shalwar, pancha,
        rang_button, design_button, design_silai, double_silai, maghzi_lagani, band_ko_ghara, 
        chamak_dhaga, guldozi, collar_type, collar_custom, cuff_type, cuff_custom, 
        pocket_type, pocket_custom, daman_style, daman_style_custom, kurta_type, kurta_custom, notes
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->execute([
        $_POST['name'],
        $_POST['phone'],
        $_POST['lambai'] ?? '',
        $_POST['bazu'] ?? '',
        $_POST['tera'] ?? '',
        $_POST['collar_measure'] ?? '',
        $_POST['chati'] ?? '',
        $_POST['kamar'] ?? '',
        $_POST['daman'] ?? '',
        $_POST['shalwar'] ?? '',
        $_POST['pancha'] ?? '',
        isset($_POST['rang_button']) ? 1 : 0,
        isset($_POST['design_button']) ? 1 : 0,
        isset($_POST['design_silai']) ? 1 : 0,
        isset($_POST['double_silai']) ? 1 : 0,
        isset($_POST['maghzi_lagani']) ? 1 : 0,
        isset($_POST['band_ko_ghara']) ? 1 : 0,
        isset($_POST['chamak_dhaga']) ? 1 : 0,
        isset($_POST['guldozi']) ? 1 : 0,
        $_POST['collar'] ?? '',
        $_POST['collar_custom'] ?? '',
        $_POST['cuff'] ?? '',
        $_POST['cuff_custom'] ?? '',
        $_POST['pocket'] ?? '',
        $_POST['pocket_custom'] ?? '',
        $_POST['daman_style'] ?? '',
        $_POST['daman_style_custom'] ?? '',
        $_POST['kurta'] ?? '',
        $_POST['kurta_custom'] ?? '',
        $_POST['notes'] ?? ''
    ]);

    $lastId = $pdo->lastInsertId();
    header('Location: print_order.php?id=' . $lastId);
    exit;
}
?>
<!DOCTYPE html>
<html lang="ur" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نیا آرڈر - SK Fabrics & Tailors</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?= getCommonStyles() ?>
</head>

<body class="p-4">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="card p-4 mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">نیا آرڈر شامل کریں</h1>
            <div class="flex gap-2">
                <a href="dashboard.php" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                    ڈیش بورڈ
                </a>
                <a href="list_orders.php" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    آرڈرز
                </a>
            </div>
        </div>

        <!-- Order Form -->
        <form method="POST" class="card p-6">
            <h2 class="text-xl font-bold mb-6 text-purple-600">معلومات</h2>

            <!-- Customer Info -->
            <div class="grid grid-cols-3 gap-6 mb-8">
                <div>
                    <label class="block text-lg font-bold mb-2">نمبر</label>
                    <input type="text" name="" required readonly value="<?php echo $nextSerialNumber?>"
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
                </div>
                <div>
                    <label class="block text-lg font-bold mb-2">نام *</label>
                    <input type="text" name="name" required
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
                </div>
                <div>
                    <label class="block text-lg font-bold mb-2">فون نمبر *</label>
                    <input type="text" name="phone" required
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8">
                <!-- Measurements Column -->
                <div class="space-y-4">
                    <h3 class="text-xl font-bold text-purple-600 mb-4">پیمائش</h3>

                    <div class="flex items-center gap-4">
                        <label class="w-32 font-bold">لمبائی</label>
                        <input type="text" name="lambai" class="flex-1 px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
                    </div>

                    <div class="flex items-center gap-4">
                        <label class="w-32 font-bold">بازو</label>
                        <input type="text" name="bazu" class="flex-1 px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
                    </div>

                    <div class="flex items-center gap-4">
                        <label class="w-32 font-bold">تیرہ</label>
                        <input type="text" name="tera" class="flex-1 px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
                    </div>

                    <div class="flex items-center gap-4">
                        <label class="w-32 font-bold">کالر</label>
                        <input type="text" name="collar_measure" class="flex-1 px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
                    </div>

                    <div class="flex items-center gap-4">
                        <label class="w-32 font-bold">چھاتی</label>
                        <input type="text" name="chati" class="flex-1 px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
                    </div>

                    <div class="flex items-center gap-4">
                        <label class="w-32 font-bold">کمر</label>
                        <input type="text" name="kamar" class="flex-1 px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
                    </div>

                    <div class="flex items-center gap-4">
                        <label class="w-32 font-bold">دامن</label>
                        <input type="text" name="daman" class="flex-1 px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
                    </div>

                    <div class="flex items-center gap-4">
                        <label class="w-32 font-bold">شلوار</label>
                        <input type="text" name="shalwar" class="flex-1 px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
                    </div>

                    <div class="flex items-center gap-4">
                        <label class="w-32 font-bold">پانچہ</label>
                        <input type="text" name="pancha" class="flex-1 px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
                    </div>

                    <h4 class="text-lg font-bold text-gray-700 mt-6 mb-2">اضافی آپشنز</h4>

                    <div class="space-y-2">
                        <label class="flex items-center gap-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" name="rang_button" value="1" class="w-5 h-5">
                            <span class="font-bold">رنگ بٹن</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" name="design_button" value="1" class="w-5 h-5">
                            <span class="font-bold">ڈیزائن بٹن</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" name="design_silai" value="1" class="w-5 h-5">
                            <span class="font-bold">ڈیزائن سلائی</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" name="double_silai" value="1" class="w-5 h-5">
                            <span class="font-bold">ڈبل سلائی</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" name="maghzi_lagani" value="1" class="w-5 h-5">
                            <span class="font-bold">مغزی لگانی</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" name="band_ko_ghara" value="1" class="w-5 h-5">
                            <span class="font-bold">بندکوغاڑہ</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" name="chamak_dhaga" value="1" class="w-5 h-5">
                            <span class="font-bold">چمک دھاگہ</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" name="guldozi" value="1" class="w-5 h-5">
                            <span class="font-bold">گلدوزی</span>
                        </label>
                    </div>
                </div>

                <!-- Styles Column -->
                <div class="space-y-4">
                    <h3 class="text-xl font-bold text-purple-600 mb-4">سٹائل</h3>

                    <div>
                        <label class="block font-bold mb-2">کالر</label>
                        <div class="flex gap-3">
                            <select name="collar" class="w-1/2 px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
                                <option value="">منتخب کریں</option>
                                <option value="ہاف بین">ہاف بین</option>
                                <option value="گول بین">گول بین</option>
                                <option value="چورس بین">چورس بین</option>
                            </select>
                            <input type="text" name="collar_custom" placeholder="اپنا سٹائل"
                                class="w-1/2 px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold mb-2">کف</label>
                        <div class="flex gap-3">
                            <select name="cuff" class="w-1/2 px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
                                <option value="">منتخب کریں</option>
                                <option value="گول کف">گول کف</option>
                                <option value="چاک پٹی">چاک پٹی</option>
                                <option value="کاج">کاج</option>
                            </select>
                            <input type="text" name="cuff_custom" placeholder="اپشن"
                                class="w-1/2 px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold mb-2">جیب</label>
                        <div class="flex gap-3">
                            <select name="pocket" class="w-1/2 px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
                                <option value="">منتخب کریں</option>
                                <option value="شلوار جیب">شلوار جیب</option>
                                <option value="کرتہ پٹی">کرتہ پٹی</option>
                                <option value="سائیڈ پاکٹ">سائیڈ پاکٹ</option>
                                <option value="پاکٹ">پاکٹ</option>
                            </select>
                            <input type="text" name="pocket_custom" placeholder="اپشن"
                                class="w-1/2 px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold mb-2">دامن سٹائل</label>
                        <div class="flex gap-3">
                            <select name="daman_style" class="w-1/2 px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
                                <option value="">منتخب کریں</option>
                                <option value="گول بازوکنی">گول بازوکنی</option>
                                <option value="سادہ پٹی">سادہ پٹی</option>
                                <option value="گول بازو">گول بازو</option>
                            </select>
                            <input type="text" name="daman_style_custom" placeholder="اپنا سٹائل"
                                class="w-1/2 px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold mb-2">کرتہ</label>
                        <div class="flex gap-3">
                            <select name="kurta" class="w-1/2 px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
                                <option value="">منتخب کریں</option>
                                <option value="گول دامن">گول دامن</option>
                                <option value="چورس دامن">چورس دامن</option>
                            </select>
                            <input type="text" name="kurta_custom" placeholder="اپشن"
                                class="w-1/2 px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold mb-2">نوٹس</label>
                        <textarea name="notes" rows="6"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none"></textarea>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <button type="submit" class="btn-primary w-full text-white text-xl font-bold py-4 rounded-lg">
                    💾 محفوظ کریں اور پرنٹ کریں
                </button>
            </div>
        </form>
    </div>
</body>

</html>