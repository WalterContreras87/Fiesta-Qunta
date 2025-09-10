<?php
// --- INICIO: Cargar variables de entorno desde .env ---
$env_vars = [];
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($key, $value) = explode('=', $line, 2);
        $env_vars[trim($key)] = trim($value);
    }
}
// --- FIN: Cargar variables de entorno ---
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiesta Quinta</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>

    <div class="hero-header">
        <img src="fiestaquinta_logo_original_paleta.png" alt="Logo Fiesta Quinta" class="hero-logo-img">
        <h1 class="main-logo-text">FiestaQuinta.cl</h1>
        <p class="main-slogan-text">El ritmo del puerto a un solo clic</p>
        <a href="#registro" class="btn-cta">Quiero registrar mi banda</a>
    </div>

    <section class="beneficios">
        <h3>�Por qu� unirte a Fiesta Quinta?</h3>
        <div class="beneficios-grid">
            <div class="card">
                <i class="fas fa-calendar-alt"></i>
                <h4>Consigue m�s shows</h4>
                <p>Conecta con eventos y aumenta tus oportunidades.</p>
            </div>
            <div class="card">
                <i class="fas fa-bullhorn"></i>
                <h4>Promociona tu m�sica</h4>
                <p>Haz que m�s gente conozca tu sonido.</p>
            </div>
            <div class="card">
                <i class="fas fa-handshake"></i>
                <h4>Gestiona tu negocio</h4>
                <p>Organiza contratos y fechas f�cilmente.</p>
            </div>
            <div class="card">
                <i class="fas fa-mobile-alt"></i>
                <h4>Todo en un solo lugar</h4>
                <p>Administra tu banda de forma simple y r�pida.</p>
            </div>
        </div>
    </section>

    <section class="formulario-registro" id="registro">
        <h3>Reg�strate y recibe novedades</h3>
        <form action="procesar_registro.php" method="post">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" placeholder="Nombre completo" required>

            <label for="email">Correo Electr�nico</label>
            <input type="email" id="email" name="email" placeholder="nombre@ejemplo.com" required>

            <label for="banda">Nombre de tu banda/orquesta</label>
            <input type="text" id="banda" name="banda" placeholder="Nombre de tu banda" required>

            <div class="g-recaptcha" data-sitekey="<?php echo $env_vars['RECAPTCHA_SITE_KEY'] ?? ''; ?>"></div>

            <button type="submit">Reg�strate ahora</button>
        </form>
    </section>

    <footer>
        &copy; 2025 FiestaQuinta.cl - Todos los derechos reservados.
    </footer>
</body>
</html>