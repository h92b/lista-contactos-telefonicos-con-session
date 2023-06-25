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
    <title>Iniciar | Proyecto PHP CRUD</title>
    <link rel="stylesheet" href="./css/index.css">
</head>
<body>

    <main>
        <section>
            <h2>iniciar cuenta</h2>
            <form id="sing" action="/auth/auth/index.php" method="get">
                <div>
                    <label for="email">Correo Electr&oacute;nico</label>
                    <input type="text" name="email" id="email" placeholder="Ej. usuario@email.com">
                </div>
                <span></span>
                <div>
                    <label for="password">Clave</label>
                    <input type="password" name="password" id="password" placeholder="Ej. Qwe123!!">
                </div>
                <span></span>
                <div>
                    <button type="submit" name="actions" id="actions" value="login">Enviar</button>
                </div>
                <div>
                    <a id="toggleForm" href="./page-signup.php">Registrar</a>
                </div>
                <div>
                    <input type="hidden" name="csrf" id="csrf" value="<?=$csrf?>">
                </div>
            </form>
        </main>
    </section>
    
    <div class="created_by">
        <span>creado por </span><span>H&eacute;ctor Barrios</span>
    </div>

    <script src="./js/index.js"></script>
</body>
</html>