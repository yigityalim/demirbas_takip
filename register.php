<?php
session_start();
require __DIR__ . '/helpers.php';

if (session('user')) header('Location: index.php');

$db = new Database([
    'host' => 'localhost',
    'driver' => 'mysql',
    'database' => 'demirbas_takip',
    'username' => 'root',
    'password' => 'root'
]);

if (post('password') && post('password2')) {
    $name = post('name');
    $surname = post('surname');
    $sicil = post('sicil');
    $oda = post('oda');
    $unvan = post('unvan');
    $bolum = post('bolum');
    $mail = post('eposta');
    $date = post('date');
    $password = post('password');
    $notlar = "";
    $db->table('personel')
        ->insert([
            'ad' => $name,
            'soyad' => $surname,
            'sicil_no' => $sicil,
            'oda_no' => $oda,
            'unvan' => $unvan,
            'bolum' => $bolum,
            'email' => $mail,
            'ise_baslama_tarihi' => $date,
            'notlar' => $notlar,
            'sifre' => $password
        ]);
    header('Location: login.php?page=0');
}
?>
<!doctype html>
<html lang="tr" class="w-full h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kayıt Ol</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-zinc-400 md:bg-zinc-700 w-full h-full flex flex-col text-white items-center justify-center">
<form action="" method="post"
      class="md:max-w-lg mx-auto w-full flex flex-col items-center justify-center p-4 bg-zinc-400 md:rounded">
    <h1 class="w-full text-4xl font-bold text-start text-zinc-700">Kayıt Ol</h1>
    <div class="flex gap-x-2 w-full">
        <div class="w-full flex flex-col gap-y-2 items-start justify-start mt-4">
            <label class="text-zinc-700 text-lg" for="name">Ad</label>
            <input class="w-full p-2 rounded bg-zinc-300 text-zinc-700" type="text" name="name" id="name">
        </div>
        <div class="w-full flex flex-col gap-y-2 items-start justify-start mt-4">
            <label class="text-zinc-700 text-lg" for="surname">Soyad</label>
            <input class="w-full p-2 rounded bg-zinc-300 text-zinc-700" type="text" name="surname" id="surname">
        </div>
    </div>
    <div class="flex gap-x-2 w-full">
        <div class="w-full flex flex-col gap-y-2 items-start justify-start mt-4">
            <label class="text-zinc-700 text-lg" for="sicil">Sicil Numarası</label>
            <input class="w-full p-2 rounded bg-zinc-300 text-zinc-700" type="number" name="sicil" id="sicil">
        </div>
        <div class="w-full flex flex-col gap-y-2 items-start justify-start mt-4">
            <label class="text-zinc-700 text-lg" for="oda">Oda Numarası</label>
            <input class="w-full p-2 rounded bg-zinc-300 text-zinc-700" type="number" name="oda" id="oda">
        </div>
    </div>
    <div class="flex gap-x-2 w-full">
        <div class="w-full flex flex-col gap-y-2 items-start justify-start mt-4">
            <label class="text-zinc-700 text-lg" for="unvan">Ünvan</label>
            <input class="w-full p-2 rounded bg-zinc-300 text-zinc-700" type="text" name="unvan" id="unvan">
        </div>
        <div class="w-full flex flex-col gap-y-2 items-start justify-start mt-4">
            <label class="text-zinc-700 text-lg" for="bolum">Bölüm</label>
            <input class="w-full p-2 rounded bg-zinc-300 text-zinc-700" type="text" name="bolum" id="bolum">
        </div>
    </div>
    <div class="flex gap-x-2 w-full">
        <div class="w-full flex flex-col gap-y-2 items-start justify-start mt-4">
            <label class="text-zinc-700 text-lg" for="eposta">E-Posta</label>
            <input class="w-full p-2 rounded bg-zinc-300 text-zinc-700" type="email" name="eposta" id="eposta">
        </div>
        <div class="w-full flex flex-col gap-y-2 items-start justify-start mt-4">
            <label class="text-zinc-700 text-lg" for="date">İşe Başlama Tarihi</label>
            <input class="w-full p-2 rounded bg-zinc-300 text-zinc-700" required type="date" name="date" id="date">
        </div>
    </div>
    <div class="flex gap-x-2 w-full">
        <div class="w-full flex flex-col gap-y-2 items-start justify-start mt-4">
            <label class="text-zinc-700 text-lg" for="password">Şifre</label>
            <input class="w-full p-2 rounded bg-zinc-300 text-zinc-700" required type="password" name="password"
                   id="password">
        </div>
        <div class="w-full flex flex-col gap-y-2 items-start justify-start mt-4">
            <label class="text-zinc-700 text-lg" for="password2">Şifre Tekrar</label>
            <input class="w-full p-2 rounded bg-zinc-300 text-zinc-700" required type="password" name="password2"
                   id="password2">
        </div>
    </div>
    <button class="w-full p-2 rounded bg-blue-700 text-white mt-4" type="submit">Kayıt Ol</button>
    <a href="login.php" class="w-full p-2 rounded bg-zinc-600 text-white text-center mt-4">Giriş Yap</a>
</form>
</body>
</html>