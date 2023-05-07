<?php
session_start();
require __DIR__ . '/helpers.php';
require 'Database.php';
if (session('user')) header('Location: index.php?page=0');
$kontrol = false;

if (post('email') && post('password')) {
    $email = post('email');
    $password = post('password');

    $db = new Database([
        'host' => 'localhost',
        'driver' => 'mysql',
        'database' => 'demirbas_takip',
        'username' => 'root',
        'password' => 'root'
    ]);

    $user = $db
        ->table('personel')
        ->where([
            'email' => $email,
            'password' => $password
        ])
        ->get();
    if ($user) {
        $_SESSION['user'] = $user;
        printData($_SESSION['user']);
        header('Location: index.php?page=0');
    } else {
        $kontrol = true;
    }
}
?>
<!doctype html>
<html lang="tr" class="w-full h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Giriş Yap</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-zinc-600 w-full h-full flex items-center justify-center flex-col">
<form action="" method="post"
      class="lg:max-w-lg mx-auto w-full flex flex-col items-center justify-center p-4 bg-zinc-400 rounded">
    <h1 class="w-full text-4xl font-bold text-start text-zinc-700">Giriş Yap</h1>
    <div class="w-full flex flex-col gap-y-2 items-start justify-start mt-4">
        <label class="text-zinc-700 text-lg" for="email">E-Posta</label>
        <input class="w-full p-2 rounded bg-zinc-300 text-zinc-700" type="email" name="email" id="email">
    </div>
    <div class="w-full flex flex-col gap-y-2 items-start justify-start mt-4">
        <label class="text-zinc-700 text-lg" for="password">Şifre</label>
        <input class="w-full p-2 rounded bg-zinc-300 text-zinc-700" type="password" name="password" id="password">
    </div>
    <input
        class="w-full p-2 mt-4 rounded bg-blue-600 text-zinc-300 disabled:opacity-50"
        type="submit"
        value="Giriş Yap"
    >
    <a href="register.php" class="block w-full p-2 mt-4 rounded bg-gray-600 text-zinc-300 text-center">
        Kayıt Ol
    </a>
    <?php if ($kontrol): ?>
        <p class="text-red-500 mt-3 text-lg">Kullanıcı adı veya şifre hatalı</p>
    <?php endif; ?>
</form>
</body>
</html>