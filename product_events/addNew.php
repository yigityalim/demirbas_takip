<?php
session_start();
require '../helpers.php';
require_once '../Database.php';
if (!session('user')) header('Location: ../login.php');

if (
    post('urun_marka') &&
    post('urun_model') &&
    post('urun_aciklama') &&
    post('urun_verildigi_tarih') &&
    post('urun_fiyati') &&
    post('demirbas_no')
) {

    $db = new Database([
        'host' => 'localhost',
        'driver' => 'mysql',
        'database' => 'demirbas_takip',
        'username' => 'root',
        'password' => 'root'
    ]);
    $personel_id = session('user')->id;
    $urun_marka = post('urun_marka');
    $urun_model = post('urun_model');
    $urun_aciklama = post('urun_aciklama');
    $urun_verildigi_tarih = post('urun_verildigi_tarih');
    $urun_fiyati = (int)post('urun_fiyati');
    $demirbas_no = post('demirbas_no');

    $query = $db
        ->table('urunler')
        ->insert([
            'urun_marka' => $urun_marka,
            'urun_model' => $urun_model,
            'urun_aciklama' => $urun_aciklama,
            'urun_verildigi_tarih' => $urun_verildigi_tarih,
            'urun_fiyati' => $urun_fiyati,
            'personel_id' => $personel_id,
            'demirbas_no' => $demirbas_no,
        ]);
    header('Location: addKasa.php?urun_id=' . $query . '&demirbas_no=' . $demirbas_no);
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
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex flex-col items-center justify-center min-h-screen bg-gray-200" style="font-family: 'Inter', sans-serif;">
<form action="" method="post" class="flex flex-col gap-y-4 rounded-md bg-white max-w-4xl w-full p-12">
    <h4 class="w-full font-bold text-4xl bg-gray-200 px-2 py-2 tex rounded text-gray-700">
        Ürün Ekle
    </h4>
    <label class="flex flex-col gap-y-2">
        <span>Ürün Markası</span>
        <input type="text" name="urun_marka" class="p-2 border border-gray-300">
    </label>
    <label class="flex flex-col gap-y-2">
        <span>Ürün Modeli</span>
        <input type="text" name="urun_model" class="p-2 border border-gray-300 rounded-md">
    </label>
    <label class="flex flex-col gap-y-2">
        <span>Ürün Açıklaması</span>
        <input type="text" name="urun_aciklama" class="p-2 border border-gray-300 rounded-md">
    </label>
    <label class="flex flex-col gap-y-2">
        <span>Ürün Verildiği Tarih</span>
        <input type="text" name="urun_verildigi_tarih" class="p-2 border border-gray-300 rounded-md">
    </label>
    <label class="flex flex-col gap-y-2">
        <span>Ürün Fiyatı</span>
        <input type="number" name="urun_fiyati" class="p-2 border border-gray-300 rounded-md">
    </label>
    <label class="flex flex-col gap-y-2">
        <span>Ürün Demirbaş Numarası</span>
        <input type="text" placeholder="Örn: BD0010" name="demirbas_no" class="p-2 border border-gray-300 rounded-md">
    </label>
    <button type="submit" class="px-4 py-2 mt-4 text-white bg-green-500 rounded-md cursor-pointer hover:bg-green-600">Ekle</button>
    <a href="../index.php?page=1" class="px-4 py-2 mt-4 text-white text-center bg-red-500 rounded-md cursor-pointer hover:bg-red-600">
        Geri Dön
    </a>
</form>
</body>
</html>
