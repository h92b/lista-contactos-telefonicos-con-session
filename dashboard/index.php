<?php
include __DIR__ .('/auth/main.php');
?>
<!DOCTYPE html>
<html lang="es-VE">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.1">
    <title>Dashboard | proyecto PHP CRUD</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <h1>Dashboard</h1>
    <div>
        <a href="/sing-out/index.php">Salir</a>
    </div>
    <main>
        <section>
            <form action="/dashboard/auth/index.php" method="get">
                <h3>Nuevo Contacto</h3>
                <div>
                    <label for="nombre">Nombre</label>
                    <input type="text" name="nombre" id="nombre" placeholder="Ej. Juan Delgado">
                </div>
                <span></span>
                <div>
                    <label for="phone">Telefono</label>
                    <input type="number" name="phone" id="phone" placeholder="Ej. 04120000000">
                </div>
                <span></span>
                <div>
                    <button type="submit" name="actions" value="newContact">Crear</button>
                </div>
                <div>
                    <input type="hidden" name="csrf" value="<?=$csrf?>">
                </div>
            </form>
        </section>
        <section>
            <span>Contactos Total: <?=$total?></span>
            <table>
                <thead>
                    <tr>
                        <th>
                            <input type="radio" name="orderBy" id="orderByName" value="<?=$name_order?>" <?=$input_name_checked?> onclick="window.location = '?files_csrf=<?=$_GET['files_csrf']?>&orderBy='+this.value">
                            <label for="orderByName"> Nombre</label>
                        </th>
                        <th>
                            <input type="radio" name="orderBy" id="orderByPhone" value="<?=$phone_order?>" <?=$input_phone_checked?>  onclick="window.location = '?files_csrf=<?=$_GET['files_csrf']?>&orderBy='+this.value">
                            <label for="orderByPhone"> Telefono</label>
                        </th>
                        <th colspan="2">accion</th>
                    </tr>
                </thead>
                <tbody>
                    <?=$name?>
                </tbody>
            </table>
        </section>
    </main>

    <div class="modal">
        <form id="formEdit" action="/dashboard/auth/index.php" method="get">
            <h2>Editar Contacto</h2>
            <div>
                <label for="nameEdit">Nombre</label>
                <input type="text" name="nombre" id="nameEdit" placeholder="Ej. Juan Delgado">
            </div>
            <span></span>
            <div>
                <label for="phoneEdit">Telefono</label>
                <input type="number" name="phone" id="phoneEdit" placeholder="Ej. 04120000000">
            </div>
            <span></span>
            <div>
                <button name="actions" id="edit" value="edit">Editar</button>
                <button type="button" id="close">Cerrar</button>
                <input type="hidden" name="id" id="id" value="">
                <input type="hidden" name="csrf" value="<?=$csrf?>">
            </div>
        </form>
    </div>

    <div class="created_by">
        <span>creado por </span><span>H&eacute;ctor Barrios</span>
    </div>
    
    <script src="js/index.js"></script>
</body>
</html>
