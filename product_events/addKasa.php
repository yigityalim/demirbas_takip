<?php
session_start();
require '../helpers.php';
require_once '../Database.php';
if (!session('user')) header('Location: ../login.php');

$urun_id = get('urun_id');

$db = new Database([
    'host' => 'localhost',
    'driver' => 'mysql',
    'database' => 'demirbas_takip',
    'username' => 'root',
    'password' => 'root'
]);

if (
    post('isletim_sistemi') &&
    post('ram') &&
    post('hdd')
) {

    $data = [
        'demirbas_no' => get('demirbas_no'),
        'isletim_sistemi' => post('isletim_sistemi'),
        'ram' => post('ram'),
        'hdd' => post('hdd'),
        'urun_id' => $urun_id,
    ];

    $db->table('kasa')
        ->insert($data);

    header('Location: ../index.php?page=1');
}
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ürün Ekle</title>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0"/>
    <link rel="stylesheet" href="../../reset.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex flex-col items-center justify-center min-h-screen bg-gray-200"
      style="font-family: 'Inter', sans-serif;">
<form action="" method="post" class="flex flex-col gap-y-4 rounded-md bg-white max-w-4xl w-full p-12">
    <h4 class="w-full font-bold text-4xl mb-4 bg-gray-200 px-2 py-1 rounded text-gray-700">
        Ürünün Kasa Bilgilerini Giriniz
    </h4>
    <label class="flex flex-col gap-y-2">
        <span>İşletim Sistemi</span>
        <input type="text" name="isletim_sistemi" class="p-2 border border-gray-300 rounded-md">
    </label>
    <label class="flex flex-col gap-y-2">
        <span>RAM</span>
        <input type="text" name="ram" class="p-2 border border-gray-300 rounded-md">
    </label>
    <label class="flex flex-col gap-y-2">
        <span>HDD (Harddisk)</span>
        <input type="text" name="hdd" class="p-2 border border-gray-300 rounded-md">
    </label>
    <button type="submit" class="px-4 py-2 mt-4 text-white bg-green-500 rounded-md cursor-pointer hover:bg-green-600">
        Ekle
    </button>
    <a href="./addNew.php"
       class="px-4 py-2 mt-4 text-white text-center bg-red-500 rounded-md cursor-pointer hover:bg-red-600"
    >
        Geri Dön
    </a>
</form>
</body>
</html>
