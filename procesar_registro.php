<?php

// Datos de conexión a la base de datos.
// En un entorno de desarrollo local como XAMPP, el usuario suele ser 'root' y la contraseña está vacía.
$servername = "localhost"; 
$username = "ptw44ffr_fiestaquinta2"; 
$password = "Emmit@cpanel2021"; 
$dbname = "ptw44ffr_fiestaquinta_db"; 

// Intentar la conexión a la base de datos usando PDO para mayor seguridad.
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Establecer el modo de error para que PHP lance excepciones en caso de fallo.
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar si el formulario se envió usando el método POST.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validar y limpiar los datos del formulario para prevenir inyecciones de código.
        $nombre = htmlspecialchars(trim($_POST['nombre']));
        $email = htmlspecialchars(trim($_POST['email']));
        $nombre_banda = htmlspecialchars(trim($_POST['banda']));

        // Preparar la consulta SQL de inserción de forma segura.
        // Esto evita ataques de inyección SQL.
        $stmt = $conn->prepare("INSERT INTO bandas (nombre, email, nombre_banda) VALUES (:nombre, :email, :nombre_banda)");

        // Asignar los valores a los parámetros de la consulta.
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':nombre_banda', $nombre_banda);

        // Ejecutar la consulta para insertar los datos.
        $stmt->execute();

        // Redirigir al usuario a una página de éxito.
        // Es una buena práctica para evitar que el formulario se envíe de nuevo al recargar la página.
        header("Location: gracias.html"); 
        exit();
    }
} catch(PDOException $e) {
    // Si la conexión falla, se mostrará un mensaje de error.
    die("Error de conexión: " . $e->getMessage());
}

?>