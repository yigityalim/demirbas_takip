<?php
session_start();
require 'helpers.php';
require 'Database.php';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
    // Yükleme için gerekli ayarlar
    $target_dir = "img/"; // Resimlerin kaydedileceği klasör
    $target_file = $target_dir . basename($_FILES["image"]["name"]); // Dosyanın yolu ve adı
    $uploadOk = 1; // Yükleme işlemi başarılı mı?

    $acceptedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    // Dosya türü kontrolü
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if (!in_array($imageFileType, $acceptedExtensions)) {
        echo "Sadece JPG, JPEG, PNG & GIF dosyaları yüklenebilir.";
        $uploadOk = 0;
    }

    // Dosya boyutu kontrolü (1MB)
    if ($_FILES["image"]["size"] > 1000000) {
        echo "Dosya boyutu 1MB'dan küçük olmalıdır.";
        $uploadOk = 0;
    }

    // Yükleme işlemi başarılıysa
    if ($uploadOk == 1) {
        // Dosya kaydetme işlemi
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {

            $db = new Database([
                'host' => 'localhost',
                'driver' => 'mysql',
                'database' => 'demirbas_takip',
                'username' => 'root',
                'password' => 'root'
            ]);

            $res = $db->table('personel')
                ->where('id', session('user')->id)
                ->update([
                    'picture' => $target_file
                ]);

            if (!$res) die("Resim yükleme işlemi başarısız.");
            echo "Resim başarıyla yüklendi.";
            header('Location: index.php');
        }
    }
}