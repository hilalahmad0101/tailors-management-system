<?php
require_once 'config.php';

// If already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'غلط صارف نام یا پاس ورڈ';
    }
}
?>
<!DOCTYPE html>
<html lang="ur" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لاگ ان - SK Fabrics & Tailors</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?= getCommonStyles() ?>
</head>

<body class="flex items-center justify-center p-4">
    <div class="card w-full max-w-md p-8">
        <div class="text-center mb-8">
            <div class="w-24 h-24 mx-auto rounded-full border-4 border-purple-600 flex items-center justify-center mb-4">
                <span class="text-4xl font-bold text-purple-600">SK</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">SK FABRICS & TAILORS</h1>
            <p class="text-gray-600 mt-2">Sarfaraz Khan: 📞 0333-1212290</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div>
                <label class="block text-lg font-bold mb-2 text-gray-700">صارف نام</label>
                <input type="text" name="username" required
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
            </div>

            <div>
                <label class="block text-lg font-bold mb-2 text-gray-700">پاس ورڈ</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-purple-600 focus:outline-none">
            </div>

            <button type="submit" class="btn-primary w-full text-white text-xl font-bold py-3 rounded-lg">
                لاگ ان کریں
            </button>
        </form>
    </div>
</body>

</html>