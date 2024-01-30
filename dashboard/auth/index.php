<!DOCTYPE html>
<html lang="es-VE">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.1">
    <title>Ejecutando...</title>
    <style>
        .created_by {
            position: fixed;
            bottom: 14px;
            right: 16px;
        }
    </style>
</head>
<body>
    <button type="button" onclick="history.back()"> Volver </button><br>

    <div class="created_by">
        <span>creado por </span><span>H&eacute;ctor Barrios</span>
    </div>
</body>
</html>

<?php

/**
 * permitir peticione solo en METODO GET
 * en el caso de ser un metodo distinto imprime un mensaje
 * en formato JSON y termina la ejecucion
*/
if($_SERVER['REQUEST_METHOD'] != "GET") {
    
    echo json_encode(array("status" => "NOT", "form" => "general", "msg" => "Permiso denegado."), true);
    exit;

}

/**
 * inicializa las variables globales de $_SESSION
*/
session_start();

/**
 * itera sobre la variable $_GET y entrega una clave y un valor
 * sentencia si existe la clave email y pasa su valor a solo minusculas
 * tambien sentencia si existe la clave actions de no existir imprime
 * un mensaje en JSON y termina la ejecucion del programa
*/
foreach ($_GET as $key => $value) {
    
    $_GET[$key] = htmlspecialchars( stripslashes( $value ) );
    
    if ($key === 'email') {
        $_GET[$key] = mb_strtolower( htmlspecialchars( stripslashes( $value ) ) );
    }
    
    if ( $key === 'csrf' && $value !== $_SESSION['csrf'] && (!isset($_GET['action'])) && (!isset($_SESSION['hash'])) && (!isset($_SESSION['email'])) ) {
        echo json_encode(array("status" => "NOT", "form" => "general", "msg" => "Permiso denegado."), true);
        header('Location: /auth/');
        exit;
    }
    
}

/**
 * si la variable $_GET['actions'] tiene como valor algo distinto
 * a 'newContact' , 'edit' , 'delete' se ejecuta como verdadero
 * y procede a imprimir NOT y retornar false para detene la ejecucion
*/
if ($_GET['actions'] !== 'newContact' && $_GET['actions'] !== 'edit' && $_GET['actions'] !== 'delete') {
    
    echo 'NOT';
    return false;

}

/**
 * esta sentencia valida la existencia de las variables
 * $_SESSION['email'] y $_GET['email']
 * con alguna de las 2 que exista se ejecuta como true
 * dando acceso a la function func_valid_email la cual 
 * valida la estructura de un email con una comparacion
 * pregmatica usando como pattern una regular expression.
 * retorna true si la estructura del correo es valida o 
 * false si el correo no es valido Ejemplo:
 * usuario@email.ve es valido
 * usuario@email.v no es valido 
 * .usuario@email.ve no es valido 
*/
if (isset($_SESSION['email']) || isset($_GET['email'])) {

    function func_valid_email (string $email) {
        if (!preg_match( '/^[^.\)\(\>\<\@\,\;\"\[\]\Ç\&\%][a-zA-Z0-9]|\d|[!#\$%&\'\*\+\-\/=\?\^_`{\|}~]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/' , $email)) {
            return false;
        }
        return true;
    }

}

/**
 * en caso de existir la variable $_GET['nombre'] se ejecuta
 * como verdadero dando acceso a la function func_valid_name
 * la cual valida la estructura de los nombres.
*/
if (isset($_GET['nombre'])) {

    function func_valid_name (string $name) {
        if (!preg_match( '/[a-zA-Z\s]{2,25}$/' , $name)) {
            return false;
        } 
        return true;
    }

}

/**
 * en caso de existir la variable $_GET['phone'] se ejecuta
 * como verdadero dando acceso a la function func_valid_phone
 * la cual valida la estructura de los numeros telefonicos.
*/
if (isset($_GET['phone'])) {

    function func_valid_phone (int $phone) {
        if (preg_match( '/[+0-9]{10,11}$/' , $phone)) {
            return false;
        }
        return true;
    }

}

/**
 * si la function 'func_valid_email' esta disponible a la 
 * varible $valid_email se le asigna el valor de retorno
 * puede ser true o false
*/
$valid_email = false;
if (function_exists('func_valid_email')) {
    $valid_email = func_valid_email($_SESSION['email']);
}

/**
 * si la function 'func_valid_name' esta disponible a la 
 * varible $valid_name se le asigna el valor de retorno
 * puede ser true o false
*/
$valid_name = false;
if (function_exists('func_valid_name')) {
    $valid_name = func_valid_name($_GET['nombre']);
}

/**
 * si la function 'func_valid_phone' esta disponible a la 
 * varible $valid_phone se le asigna el valor de retorno
 * puede ser true o false
*/
$valid_phone = false;
if (function_exists('func_valid_phone')) {
    $valid_phone = func_valid_phone($_GET['phone']);
}

/**
 * verifica que el usuario existe y que el email puede ser desencriptado
*/
if (isset($_SESSION['hash'])) {

    function verify($key) {

        require __DIR__ .("/auth.php");
        
        $data_user = $mysqli->prepare("SELECT AES_DECRYPT(email, ?) as email FROM users WHERE key_pub = (?) ");
        $data_user->bind_param("ss", $email, $key_pub);
        $email = 'admin@prueba.com';
        $key_pub = $key;
        $data_user->execute();
        $result = $data_user->get_result();
        $data_user->close();
        
        $mysqli->close();
        
        if (!$result->num_rows) {
            return false;
        }

        $rows = $result->fetch_assoc();

        if (!strlen($rows['email'])) {
            return false;
        }

        return true;

    }

}

/**
 * inserta un valor en la tabla corespondiente al usuario loggeado
*/
if ($valid_name && $valid_phone) {

    function newContact($valid_email) {
        
        require __DIR__ .("/auth.php");

        $email = ($valid_email) ? $_SESSION['email'] : 'admin@prueba.com' ;

        $data_contact = $mysqli->prepare("INSERT INTO `".$_SESSION['id_table']."` ( name, phone, uuid )
        VALUES ( AES_ENCRYPT(?, '$email'), AES_ENCRYPT(?, '$email'), UUID_SHORT() ) ");
        $data_contact->bind_param("si", $name, $phone);

        $name = $_GET['nombre'];
        $phone = $_GET['phone'];

        $data_contact->execute();
        $result = $mysqli->insert_id;
        $data_contact->close();

        $mysqli->close();

        if ($result) {
            echo 'Se inserto un nuevo valor.';
        } else {
            echo 'No se inserto ningun valor.';
        }

    }

}

/**
 * actualiza el valor seleccionado de una columna en una base de datos
*/
if ($valid_name && $valid_phone) {

    function editContact($valid_email) {

        require __DIR__ .("/auth.php");

        $email = ($valid_email) ? $_SESSION['email'] : 'admin@prueba.com' ;

        $data_contact = $mysqli->prepare("UPDATE `".$_SESSION['id_table']."` SET name = AES_ENCRYPT(?, '$email'), phone = AES_ENCRYPT(?, '$email') WHERE id = ? ");
        $data_contact->bind_param("sis", $name, $phone, $id);

        $name = $_GET['nombre'];
        $phone = $_GET['phone'];
        $id = $_GET['id'];

        $data_contact->execute();
        
        $result = $data_contact->affected_rows;
        
        $data_contact->close();

        $mysqli->close();

        if ($result) {
            echo 'Se edito correctamente la informacion.';
        } else {
            echo 'Ne se pudo editar la informacion.';
        }

    }

}

/**
 * elimina una columna seleccionada de la tabla
*/
function deleteContact () {
    
    require __DIR__ .("/auth.php");
    
    $data_contact = $mysqli->prepare("DELETE FROM `".$_SESSION['id_table']."` WHERE id = ? ");
    $data_contact->bind_param("i", $id);
    $id = $_GET['id'];
    $data_contact->execute();

    $data_contact = $mysqli->prepare("SELECT id FROM `".$_SESSION['id_table']."` WHERE id = ? ");
    $data_contact->bind_param("i", $id);
    $id = $_GET['id'];
    $data_contact->execute();
    $result = $data_contact->get_result();

    $data_contact->close();
    
    $mysqli->close();

    if ($result->num_rows) {
        echo 'No se elimino.';
    } else {
        echo 'Se elimino correctamente.';
    }

}

/**
 * si la function 'verify' esta disponible a la 
 * varible $valid_user se le asigna el valor de retorno
 * puede ser true o false
*/
$valid_user = false;
if (function_exists('verify')) {
    $valid_user = verify($_SESSION['hash']);
}

/**
 * en el caso de cumplirse las condiciones llama a la function corespondiente
 * las cuales se le pasa como parametro la variables $valid_email que almacena
 * un boolean true o false
 * a excepcion de deleteContact que no se le pasa ningun parametro
*/
if (isset($_SESSION['id_table'])) {
    ($_GET['actions'] === 'newContact' && $valid_user && $valid_name && $valid_phone )&&newContact($valid_email);
    ($_GET['actions'] === 'edit' && $valid_user && $valid_name && $valid_phone )&&editContact($valid_email);
    ($_GET['actions'] === 'delete' && $valid_user )&&deleteContact();
} else {
    echo 'Ocurrio un error inesperado.';
}

