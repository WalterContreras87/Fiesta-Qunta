<?php

// --- INICIO: Cargar variables de entorno desde .env ---
// Esta parte lee el archivo .env y carga su contenido en un array.
$env_vars = [];
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // Ignora comentarios
        list($key, $value) = explode('=', $line, 2);
        $env_vars[trim($key)] = trim($value);
    }
}
// --- FIN: Cargar variables de entorno ---

// 1. Acceder a las claves de reCAPTCHA y la base de datos
// Usa las variables de entorno para obtener los datos
$recaptcha_secret_key = $env_vars['RECAPTCHA_SECRET_KEY'] ?? '';
$db_username = $env_vars['DB_USERNAME'] ?? '';
$db_password = $env_vars['DB_PASSWORD'] ?? '';
$db_name = $env_vars['DB_NAME'] ?? '';

// Datos de conexión a la base de datos.
$servername = "localhost";
$username = $db_username;
$password = $db_password;
$dbname = $db_name;

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // --- INICIO: VERIFICACIÓN DE RECAPTCHA CON CURL ---
        if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
            $captcha_response = $_POST['g-recaptcha-response'];
            $verify_url = 'https://www.google.com/recaptcha/api/siteverify';

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $verify_url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query([
                'secret' => $recaptcha_secret_key,
                'response' => $captcha_response
            ]));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($curl);
            curl_close($curl);

            $check = json_decode($result);

            if (!$check->success) {
                die("Error: La verificación del reCAPTCHA falló. Por favor, inténtalo de nuevo.");
            }

        } else {
            die("Error: Por favor, completa el reCAPTCHA.");
        }
        // --- FIN: VERIFICACIÓN DE RECAPTCHA CON CURL ---

        // Validar y limpiar los datos del formulario.
        $nombre = htmlspecialchars(trim($_POST['nombre']));
        $email = htmlspecialchars(trim($_POST['email']));
        $nombre_banda = htmlspecialchars(trim($_POST['banda']));

        $stmt = $conn->prepare("INSERT INTO bandas (nombre, email, nombre_banda) VALUES (:nombre, :email, :nombre_banda)");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':nombre_banda', $nombre_banda);
        $stmt->execute();

        // Código de la notificación por email
        // El correo de destino también debería ir en el .env
        $to = $env_vars['MAIL_TO'] ?? 'tu_correo@tudominio.cl';
        $subject = '¡Nuevo registro de banda en FiestaQuinta!';
        $message = "Se ha registrado una nueva banda:\n\n";
        $message .= "Nombre del contacto: " . $nombre . "\n";
        $message .= "Correo electrónico: " . $email . "\n";
        $message .= "Nombre de la banda: " . $nombre_banda . "\n";
        $headers = 'From: contacto@fiestaquinta.cl' . "\r\n" .
                    'Reply-To: ' . $email . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();
        mail($to, $subject, $message, $headers);

        header("Location: gracias.html");
        exit();
    }
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>