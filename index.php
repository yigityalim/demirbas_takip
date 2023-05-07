<?php
session_start();
require __DIR__ . '/helpers.php';
require 'Database.php';
if (!session('user')) header('Location: login.php');

$page = get('page', 0);

$db = new Database([
    'host' => 'localhost',
    'driver' => 'mysql',
    'database' => 'demirbas_takip',
    'username' => 'root',
    'password' => 'root'
]);

$user = $db
    ->table('personel')
    ->where('id', '=', session('user')->id)
    ->get();

$urunler = $db
    ->table('urunler')
    ->where('personel_id', '=', session('user')->id)
    ->getAll();

$kasa = $db
    ->table('kasa')
    ->where('id', '=', session('user')->id)
    ->get();

$thead = ['Marka', 'Model', 'Açıklama', 'Verildiği Tarih', 'Ürün Fiyatı', ''];
$kasa = null;

if (post('exit')):
    $_SESSION['user'] = null;
    session_destroy();
    header('Location: login.php');
endif;

if (post('update_data')):
    $data = [
        'ad' => post('ad'),
        'soyad' => post('soyad'),
        'sicil_no' => post('sicil_no'),
        'unvan' => post('unvan'),
        'bolum' => post('bolum'),
        'email' => post('email'),
        'password' => post('password'),
        'oda_numarasi' => post('oda_numarasi'),
        'ise_baslama_tarihi' => post('ise_baslama_tarihi'),
        'notlar' => post('notlar'),
    ];
    if ($data):
        $query = $db
            ->table('personel')
            ->where('id', session('user')->id)
            ->update($data);
    endif;
endif;

if (post('handle_update')):
    $data = [
        'urun_marka' => post('marka'),
        'urun_model' => post('model'),
        'urun_fiyati' => post('fiyat'),
        'urun_aciklama' => post('aciklama'),
        'urun_verildigi_tarih' => date('Y-m-d'),
        'personel_id' => session('user')->id
    ];
    if ($data):
        $query = $db
            ->table('urunler')
            ->where('urun_id', post('urun_id'))
            ->update($data);
    endif;
endif;

if (post('urun_delete')):
$db->table('kasa')
    ->where('urun_id', post('urun_id'))
    ->delete();

$db->table('urunler')
    ->where('urun_id', post('urun_id'))
    ->delete();
endif;

if (post('urun_ekle')):
    $db->table('urun')
        ->insert([
            'urun_marka' => post('urun_marka'),
            'urun_model' => post('urun_model'),
            'urun_aciklama' => post('urun_aciklama'),
            'urun_verildigi_tarih' => post('urun_verildigi_tarih'),
            'urun_fiyati' => post('urun_fiyati'),
            'personel_id' => session('user')->id,
            'demirbas_no' => 'DB00' . rand(10, 99),
        ]);
endif;

if (post('urun_delete')):
    $db->table('kasa')
        ->where('urun_id', post('urun_delete'))
        ->delete();
    $db->table('urunler')
        ->where('urun_id', post('urun_delete'))
        ->delete();
endif;

?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Anasayfa</title>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<body class="flex flex-col items-center justify-center min-h-screen bg-gray-200 md:p-8"
      style="font-family: 'Inter', sans-serif;">
<div
    class="w-full h-screen md:h-full md:max-w-7xl p-4 md:p-8 lg:p-12 xl:p-16 bg-white md:rounded-md shadow-md flex flex-col gap-y-4 items-center justify-start">
    <div
        class="w-full text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
        <h1 class="text-4xl font-bold text-gray-700">Demirbaş Takip Sistemi</h1>
        <form action="" method="get">
            <ul class="flex flex-wrap justify-center md:justify-start -mb-px">
                <li class="mr-2">
                    <label for="page0"
                           class="transition cursor-pointer inline-block p-4 text-gray-600 border-b-2 border-transparent rounded-t-lg<?= $page == 0 ? ' hover:text-blue-600 hover:border-blue-600 !border-blue-600 ' : 'hover:border-blue-400 hover:text-blue-400' ?>"
                    >Genel</label>
                    <?php if ($page != 0): ?>
                        <input type="submit"
                               value="0"
                               id="page0"
                               name="page"
                               class="hidden not-user "/>
                    <?php endif; ?>
                </li>
                <li class="mr-2">
                    <label for="page1"
                           class="transition cursor-pointer inline-block p-4 text-gray-600 border-b-2 border-transparent rounded-t-lg<?= $page == 1 ? ' hover:text-blue-600 hover:border-blue-600 !border-blue-600 ' : 'hover:border-blue-400 hover:text-blue-400' ?>"
                    >Donanım</label>
                    <?php if ($page != 1): ?>
                        <input type="submit"
                               value="1"
                               id="page1"
                               name="page"
                               class="hidden not-user "/>
                    <?php endif; ?>
                </li>
            </ul>
        </form>
    </div>
    <?php if ($page == 0): ?>
        <div class="flex w-full h-full items-center justify-between flex-col gap-y-4">
            <div class="flex w-full items-center justify-between">
                <h4 class="w-full font-bold text-4xl mb-4 bg-gray-200 px-2 py-1 rounded text-gray-700">
                    Hoşgeldin, <?= $user->ad ?></h4>
                <?php if (!empty($user->picture)): ?>
                    <img class="w-24 object-cover rounded-full" src="<?= $user->picture ?>" alt="">
                <?php endif; ?>
            </div>
            <form class="w-full" action="" method="post">
                <table class="w-2/3">
                    <tbody class="w-full flex flex-col gap-y-4 items-start justify-between">
                        <tr class="w-full flex items-center justify-between">
                            <td><label class="text-lg font-semibold" for="ad">Ad:</label></td>
                            <td><input class="px-2 py-1 border border-gray-300 rounded focus:outline-gray-700" type="text"
                                       id="ad" name="ad" value="<?= $user->ad; ?>"></td>
                        </tr>
                        <tr class="w-full flex items-center justify-between">
                            <td><label class="text-lg font-semibold" for="soyad">Soyad:</label></td>
                            <td><input class="px-2 py-1 border border-gray-300 rounded focus:outline-gray-700" type="text"
                                       id="soyad" name="soyad" value="<?= $user->soyad; ?>"></td>
                        </tr>
                        <tr class="w-full flex items-center justify-between">
                            <td><label class="text-lg font-semibold" for="sicil_no">Sicil no:</label></td>
                            <td><input class="px-2 py-1 border border-gray-300 rounded focus:outline-gray-700" type="text"
                                       id="sicil_no" name="sicil_no" value="<?= $user->sicil_no; ?>"></td>
                        </tr>
                        <tr class="w-full flex items-center justify-between">
                            <td><label class="text-lg font-semibold" for="unvan">Ünvan:</label></td>
                            <td><input class="px-2 py-1 border border-gray-300 rounded focus:outline-gray-700" type="text"
                                       id="unvan" name="unvan" value="<?= $user->unvan ?>"></td>
                        </tr>
                        <tr class="w-full flex items-center justify-between">
                            <td><label class="text-lg font-semibold" for="bolum">Bölüm:</label></td>
                            <td><input class="px-2 py-1 border border-gray-300 rounded focus:outline-gray-700" type="text"
                                       id="bolum" name="bolum" value="<?= $user->bolum; ?>"></td>
                        </tr>
                        <tr class="w-full flex items-center justify-between">
                            <td><label class="text-lg font-semibold" for="email">Email:</label></td>
                            <td><input class="px-2 py-1 border border-gray-300 rounded focus:outline-gray-700" type="text"
                                       id="email" name="email" value="<?= $user->email ?>"></td>
                        </tr>
                        <tr class="w-full flex items-center justify-between">
                            <td><label class="text-lg font-semibold" for="password">Şifre:</label></td>
                            <td><input class="px-2 py-1 border border-gray-300 rounded focus:outline-gray-700" type="text"
                                       id="password" name="password" value="<?= $user->password; ?>"></td>
                        </tr>
                        <tr class="w-full flex items-center justify-between">
                            <td><label class="text-lg font-semibold" for="oda_numarasi">Oda Numarası:</label></td>
                            <td><input class="px-2 py-1 border border-gray-300 rounded focus:outline-gray-700" type="text"
                                       id="oda_numarasi" name="oda_numarasi"
                                       value="<?= $user->oda_numarasi; ?>">
                            </td>
                        </tr>
                        <tr class="w-full flex items-center justify-between">
                            <td><label class="text-lg font-semibold" for="ise_baslama_tarihi">İşe Başlama Tarihi:</label>
                            </td>
                            <td><input class="px-2 py-1 border border-gray-300 rounded focus:outline-gray-700" type="text"
                                       id="ise_baslama_tarihi" name="ise_baslama_tarihi"
                                       value="<?= $user->ise_baslama_tarihi; ?>"></td>
                        </tr>
                        <tr class="flex flex-col">
                            <td id="toggleNotlar" class="text-lg font-semibold cursor-pointer flex items-center gap-x-2"
                                onclick="toggleTextArea()">
                                Notlar
                                <span id="icon" class="material-symbols-outlined">expand_more</span>
                            </td>
                            <td>
                                <label for="notlar">
                                    <textarea
                                        placeholder="Notlarınızı buraya yazabilirsiniz."
                                        class="bg-gray-100 resize-none p-1 hidden rounded"
                                        name="notlar"
                                        id="notlar"
                                        cols="30"
                                        rows="5"><?= $user->notlar; ?></textarea>
                                </label>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <input name="update_data" type="submit" value="Güncelle" class="disabled:opacity-50 transition btn-update px-4 py-2 mt-4 text-white bg-green-500 rounded-md cursor-pointer hover:bg-green-600">
            </form>
            <form action="yukle.php" method="post" enctype="multipart/form-data" class="w-full m-0">
                <label for="image" class="px-4 py-2 bg-blue-800 rounded text-white cursor-pointer hover:bg-blue-700">Resim
                    Seç</label>
                <input id="image" type="file" name="image" class="hidden p-2 border border-gray-300 rounded-md">
                <button type="submit" class="px-2 rounded bg-yellow-400 px-4 py-2">Yükle</button>
            </form>
            <form action="" class="w-full m-0">
                <input name="exit" type="submit" value="Çıkış Yap"
                       class="not-user px-4 py-2 text-white bg-red-500 rounded-md cursor-pointer hover:bg-red-600">
            </form>
        </div>
    <?php else: ?>
        <?php if (!empty($urunler)): ?>
            <div class="w-full h-full flex flex-col gap-y-4">
                <div class="flex w-full items-center justify-between">
                    <h4 class="w-full font-bold text-4xl mb-4 bg-gray-200 px-2 py-1 rounded text-gray-700">
                        Hoşgeldin, <?= $user->ad ?></h4>
                    <?php if (!empty($user->picture)): ?>
                        <img class="w-24 object-cover rounded-full" src="<?= $user->picture ?>" alt="">
                    <?php endif; ?>
                </div>
                <div class="overflow-scroll md:overflow-auto">
                    <div class="flex flex-col">
                        <div class="">
                            <div class="align-middle inline-block min-w-full">
                                <div class="overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead>
                                        <tr>
                                            <?php foreach ($thead as $key => $value): ?>
                                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider"
                                                    scope="col"><?= $value ?></th>
                                            <?php endforeach; ?>
                                        </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                        <?php foreach ($urunler as $urun): ?>
                                            <tr class="odd:bg-gray-100">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $urun->urun_marka ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $urun->urun_model ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $urun->urun_aciklama ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $urun->urun_verildigi_tarih ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                                    colspan="2"><?= $urun->urun_fiyati ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <div class="flex items-center gap-x-2 justify-end h-full">
                                                        <button id="showProduct<?= $urun->urun_id ?>"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#showProduct"
                                                                class="inline-flex font-bold items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm text-white bg-blue-400 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                            Göster
                                                        </button>
                                                        <form action="" method="post" class="m-0">
                                                            <input type="hidden" name="urun_delete" value="<?= $urun->urun_id ?>">
                                                            <button type="submit"
                                                                    class="inline-flex font-bold items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm text-white bg-red-400 hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                                Sil
                                                            </button>
                                                        </form>
                                                        <form method="get" action="kasa-bilgileri.php" class="m-0">
                                                            <input type="hidden" name="id" value="<?= $urun->urun_id ?>">
                                                            <button type="submit"
                                                                    class="inline-flex font-bold items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm text-white bg-green-400 hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                                Kasa Bilgileri
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal fade" id="showProduct" tabindex="-1" aria-labelledby="showProduct"
                                     aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="modal-title fs-5" id="modal-label"></div>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body"></div>
                                            <div class="modal-footer"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="showKasa" tabindex="-1" aria-labelledby="showKasa"
                                     aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Ürün Bilgileri</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body"></div>
                                            <div class="modal-footer"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="products_events/addNew.php"
                   class="w-full block p-2 mt-4 text-white bg-gray-500 rounded-md cursor-pointer hover:bg-gray-600 transition font-bold">Ürün
                    Ekle</a>
            </div>
        <?php else: ?>
            <h4 class="w-full font-bold text-4xl mb-4 bg-gray-200 px-2 py-1 rounded text-gray-700">Henüz ürün
                eklenmemiş</h4>
            <form action="" method="post"
                  class="flex flex-col gap-y-4 rounded-md min-w-full">
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
                    <input type="text" name="urun_aciklama"
                           class="p-2 border border-gray-300 rounded-md">
                </label>
                <label class="flex flex-col gap-y-2">
                    <span>Ürün Verildiği Tarih</span>
                    <input type="text" name="urun_verildigi_tarih"
                           class="p-2 border border-gray-300 rounded-md">
                </label>
                <label class="flex flex-col gap-y-2">
                    <span>Ürün Fiyatı</span>
                    <input type="number" name="urun_fiyati"
                           class="p-2 border border-gray-300 rounded-md">
                </label>
                <input type="submit" name="urun_ekle" value="Ekle" class="px-4 py-2 mt-4 text-white bg-green-500 rounded-md cursor-pointer hover:bg-green-600">
            </form>
        <?php endif; ?>
    <?php endif; ?>
</div>


<!-- Modal & Script -->
<?php if ($page == 1): ?>
    <script>
        <?php foreach ($urunler as $urun):?>
        document.getElementById("showProduct<?= $urun->urun_id ?>").addEventListener("click", function () {
            document.getElementById("modal-label").innerHTML = `
<div class="flex w-full flex-col">
<h4 class="w-full font-bold text-4xl mb-4 bg-gray-200 px-2 py-1 rounded text-gray-700"> Ürün Bilgileri </h4>
<span class="font-bold">Marka:</span> <?= $urun->urun_marka ?>
</div>`;
            document.querySelector("#showProduct .modal-body").innerHTML = `
<form action="" method="post" class="flex flex-col gap-y-4">
<form action="" method="post" class="flex flex-col gap-y-4">
<h4 class="w-full font-bold text-3xl bg-gray-200 px-2 py-1 rounded text-gray-700"> Personel Adı: <?= $user->ad ?> </h4>
<div class="form-group">
<label for="marka">Marka:</label>
<input name="marka" type="text" class="form-control" id="marka" value="<?= $urun->urun_marka ?>">
</div>
<div class="form-group">
<label for="model">Model:</label>
<input name="model" type="text" class="form-control" id="model" value="<?= $urun->urun_model ?>">
</div>
<div class="form-group">
<label for="aciklama">Açıklama:</label>
<textarea name="aciklama" class="form-control" id="aciklama"><?= $urun->urun_aciklama ?></textarea>
</div>
<div class="form-group">
<label for="tarih">Verildiği Tarih:</label>
<input name="tarih" type="text" class="form-control" id="tarih" value="<?= $urun->urun_verildigi_tarih ?>">
</div>
<div class="form-group">
<label for="fiyat">Fiyatı:</label>
<input type="hidden" name="urun_id" value="<?= $urun->urun_id ?>">
<input name="fiyat" type="number" class="form-control" id="fiyat" value="<?= $urun->urun_fiyati ?>">
</div>
<input name="handle_update" type="submit" value="Güncelle" class="px-4 py-2 bg-green-700 rounded text-white">
</form>
`;
            document.querySelector('#showProduct .modal-footer').innerHTML = '<button type="button" class="px-4 py-2 bg-red-400 rounded text-white w-full" data-bs-dismiss="modal">Kapat</button>';
        });
        <?php endforeach; ?></script>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
<script>
    const icon = document.querySelector('#toggleNotlar #icon')
    const textarea = document.querySelector('#toggleNotlar + td #notlar')
    const toggleTextArea = () => {
        textarea.classList.toggle('hidden')
        icon.innerHTML = icon.innerHTML === 'expand_more' ? 'expand_less' : 'expand_more'
    }
</script>
</body>
</html>
