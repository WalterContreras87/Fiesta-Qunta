<?php

// Datos de conexi�n a la base de datos.
// En un entorno de desarrollo local como XAMPP, el usuario suele ser 'root' y la contrase�a est� vac�a.
$servername = "localhost"; 
$username = "ptw44ffr_fiestaquinta2"; 
$password = "Emmit@cpanel2021"; 
$dbname = "ptw44ffr_fiestaquinta_db"; 

// Intentar la conexi�n a la base de datos usando PDO para mayor seguridad.
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Establecer el modo de error para que PHP lance excepciones en caso de fallo.
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar si el formulario se envi� usando el m�todo POST.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validar y limpiar los datos del formulario para prevenir inyecciones de c�digo.
        $nombre = htmlspecialchars(trim($_POST['nombre']));
        $email = htmlspecialchars(trim($_POST['email']));
        $nombre_banda = htmlspecialchars(trim($_POST['banda']));

        // Preparar la consulta SQL de inserci�n de forma segura.
        // Esto evita ataques de inyecci�n SQL.
        $stmt = $conn->prepare("INSERT INTO bandas (nombre, email, nombre_banda) VALUES (:nombre, :email, :nombre_banda)");

        // Asignar los valores a los par�metros de la consulta.
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':nombre_banda', $nombre_banda);

        // Ejecutar la consulta para insertar los datos.
        $stmt->execute();

        // Redirigir al usuario a una p�gina de �xito.
        // Es una buena pr�ctica para evitar que el formulario se env�e de nuevo al recargar la p�gina.
        header("Location: gracias.html"); 
        exit();
    }
} catch(PDOException $e) {
    // Si la conexi�n falla, se mostrar� un mensaje de error.
    die("Error de conexi�n: " . $e->getMessage());
}

?>