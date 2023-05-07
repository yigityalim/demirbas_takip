<?php
session_start();
require 'helpers.php';
require 'Database.php';
$user = session('user');
if (!session('user')) header('Location: login.php');

$urun_id = get('id');

$db = new Database([
    'host' => 'localhost',
    'driver' => 'mysql',
    'database' => 'demirbas_takip',
    'username' => 'root',
    'password' => 'root'
]);

$kasa = $db
    ->table('kasa')
    ->where('urun_id', $urun_id)
    ->get();

$urun = $db
    ->table('urunler')
    ->where('urun_id', $urun_id)
    ->get();

$thead = ['Marka', 'Model', 'Açıklama', 'Verildiği Tarih', 'Fiyat', 'Kasa Numarası'];

$kasa = array_map(function ($item) {
    if ($item == 'N/A') return 'Eklenmemiş';
    return $item;
}, (array)$kasa);

$kasa = (object)$kasa;

if (post('demirbas_no')) {
    $data = [
        'demirbas_no' => post('demirbas_no'),
        'isletim_sistemi' => post('isletim_sistemi'),
        'ram' => post('ram'),
        'hdd' => post('hdd'),
    ];

    $db->table('kasa')->where('urun_id', $urun_id)->update($data);

    header('Location: ./index.php?page=1');
}
?>

<!doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kasa Bilgileri</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex flex-col items-center justify-center min-h-screen bg-gray-200 md:p-8"
      style="font-family: 'Inter', sans-serif;">
<div
    class="w-full h-screen md:h-full md:max-w-4xl p-4 md:p-8 lg:p-12 xl:p-16 bg-white md:rounded-md shadow-md flex flex-col gap-y-4 items-center justify-start">
    <h4 class="w-full font-bold text-4xl mb-4 bg-gray-200 px-2 py-1 rounded text-gray-700">Personel
        Adı: <?= $user->ad ?></h4>
    <div class="w-full flex flex-col gap-y-4">
        <h4 class="font-bold text-3xl text-gray-700 px-2 py-1 bg-gray-100 rounded">Ürün bilgileri</h4>
        <div class="w-full flex items-center justify-between px-2">
            <span class="font-bold text-gray-700">Ürün ID:</span>
            <span class="px-4 py-1 rounded text-gray-700 bg-gray-500 text-white"><?= $urun->urun_id ?></span>
        </div>
        <div class="w-full flex items-center justify-between px-2">
            <span class="font-bold text-gray-700">Marka:</span>
            <span class="px-4 py-1 rounded text-gray-700 bg-gray-500 text-white"><?= $urun->urun_marka ?></span>
        </div>
        <div class="w-full flex items-center justify-between px-2">
            <span class="font-bold text-gray-700">Ürün Modeli:</span>
            <span class="px-4 py-1 rounded text-gray-700 bg-gray-500 text-white"><?= $urun->urun_model ?></span>
        </div>
        <div class="w-full flex items-center justify-between px-2">
            <span class="font-bold text-gray-700">Ürün Açıklaması:</span>
            <span class="px-4 py-1 rounded text-gray-700 bg-gray-500 text-white"><?= $urun->urun_aciklama ?></span>
        </div>
    </div>
    <div class="w-full h-0.5 bg-gray-200"></div>
    <div class="w-full flex flex-col gap-y-4">
        <h4 class="font-bold text-3xl text-gray-700 px-2 py-1 bg-gray-100 rounded">Kasa bilgileri</h4>
        <form action="" method="post" class="flex flex-col gap-y-4 px-2">
            <div class="w-full flex items-center justify-between">
                <label for="demirbas_no" class="font-bold text-gray-700">Kasa Demirbaş No:</label>
                <input name="demirbas_no" type="text"
                       class="px-2 py-1 border border-gray-300 rounded focus:outline-gray-700" id="demirbas_no"
                       value="<?= $kasa->demirbas_no ?>">
            </div>
            <div class="w-full flex items-center justify-between">
                <label for="isletim_sistemi" class="font-bold text-gray-700">İşletim Sistemi:</label>
                <input name="isletim_sistemi" type="text"
                       class="px-2 py-1 border border-gray-300 rounded focus:outline-gray-700" id="isletim_sistemi"
                       value="<?= $kasa->isletim_sistemi ?>">
            </div>
            <div class="w-full flex items-center justify-between">
                <label for="ram" class="font-bold text-gray-700">RAM:</label>
                <input name="ram" type="text" class="px-2 py-1 border border-gray-300 rounded focus:outline-gray-700"
                       id="ram" value="<?= $kasa->ram ?>">
            </div>
            <div class="w-full flex items-center justify-between">
                <label for="hdd" class="font-bold text-gray-700">HDD (Hard disk):</label>
                <input name="hdd" type="text" class="px-2 py-1 border border-gray-300 rounded focus:outline-gray-700"
                       id="hdd" value="<?= $kasa->hdd ?>">
            </div>
            <input type="submit" value="Güncelle"
                   class="px-4 py-2 bg-green-700 rounded text-white cursor-pointer font-semibold">
        </form>
        <a href="./index.php?page=1"
           class="w-full px-4 py-2 bg-red-700 rounded text-white cursor-pointer font-semibold">
            Geri
        </a>
    </div>
</div>
</body>
</html>
