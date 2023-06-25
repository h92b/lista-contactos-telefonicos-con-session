<?php
session_start();
$csrf = bin2hex(random_bytes(16));
$_SESSION['csrf'] = $csrf;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.1">
    <title>Registrar | Proyecto PHP CRUD</title>
    <link rel="stylesheet" href="./css/index.css">
</head>
<body>

    <main>
        <section>
            <h2>Signup</h2>
            <form action="/auth/auth/index.php" method="get">
                <div>
                    <label for="email">Correo Electr&oacute;nico</label>
                    <input type="text" name="email" id="email" placeholder="Email">
                </div>
                <span></span>
                <div>
                    <label for="password">Clave</label>
                    <input type="password" name="password" id="password" placeholder="Password">
                </div>
                <span></span>
                <div>
                    <button type="submit" name="actions" value="signup">Enviar</button>
                </div>
                <div>
                    <a href="./">Iniciar</a>
                </div>
                <div>
                    <input type="hidden" name="csrf" value="<?=$csrf?>">
                </div>
            </form>
        </section>
    </main>
        
    <div class="created_by">
        <span>creado por </span><span>H&eacute;ctor Barrios</span>
    </div>

    <script src="./js/index.js"></script>
</body>
</html>