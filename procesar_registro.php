<?php

// Reemplaza esto con tu Clave Secreta de reCAPTCHA.
// Por favor, verifica que no haya espacios al principio o al final.
define('SECRET_KEY', '6Lc1Y8MrAAAAAEXnPSS78bTTP0BojTajlBZIfSSl');

// Datos de conexión a la base de datos.
$servername = "localhost";
$username = "ptw44ffr_fiestaquinta2";
$password = "Emmit@cpanel2021";
$dbname = "ptw44ffr_fiestaquinta_db";

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
                'secret' => SECRET_KEY,
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
        $to = 'tu_correo@tudominio.cl';
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
