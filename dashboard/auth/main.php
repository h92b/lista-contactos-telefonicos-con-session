<?php

/**
 * permitir peticione solo en METODO GET
 * en el caso de ser un metodo distinto imprime un mensaje
 * en formato JSON y termina la ejecucion
*/
if($_SERVER['REQUEST_METHOD'] != 'GET') {
    echo json_encode(array("status" => "NOT", "form" => "general", "msg" => "Permiso denegado."), true);
    exit;
}

/**
 * inicializa las variables globales de $_SESSION
*/
session_start();

/**
 * si no existen las variables $_SESSION['files_csrf'] y $_GET['files_csrf']
 * se ejecuta la sentencia imprimiendo un mensaje de ('permiso denegado') y
 * redirigiendo a la url /auth/
*/
if (!(isset($_SESSION['files_csrf']) && isset($_GET['files_csrf']))) {
    echo json_encode(array("status" => "NOT", "form" => "general", "msg" => "Permiso denegado."), true);
    header('Location: /auth/');
    exit;
}

/**
 * si la variable $_GET['files_csrf'] es distinta de $_SESSION['files_csrf']
 * se ejecuta la sentencia imprimiendo un mensaje de ('permiso denegado') y
 * redirigiendo a la url /auth/
*/
if ($_GET['files_csrf'] !== $_SESSION['files_csrf']) {
    echo json_encode(array("status" => "NOT", "form" => "general", "msg" => "Permiso denegado."), true);
    header('Location: /auth/');
    exit;
}

/**
 * la variable $csrf almacena un valor hexadecimal de 32 longitud
 * para luego almacenarlo en la session
*/
$csrf = bin2hex(random_bytes(16));
$_SESSION['csrf'] = $csrf;

/**
 * 
*/
$order = '[name:ASC]'; // valor por defecto
$name_order = '[name:ASC]'; // valor por defecto
$phone_order = '[phone:ASC]'; // valor por defecto

/**
 * la variable $name_order obtiene el valor contrario almacenado en
 * $name_order_array
*/
$name_order_array = ['[name:ASC]','[name:DESC]'];
if (in_array($_GET['orderBy'], $name_order_array)) {
    $name_order = implode(' ', array_diff($name_order_array, [$_GET['orderBy']]));
    $phone_order = '[phone:ASC]';
}

/**
 * la variable $phone_order obtiene el valor contrario almacenado en
 * $phone_order_array
*/
$phone_order_array = ['[phone:ASC]','[phone:DESC]'];
if (in_array($_GET['orderBy'], $phone_order_array)) {
    $phone_order = implode(' ', array_diff($phone_order_array, [$_GET['orderBy']]));
    $name_order = '[name:ASC]';
}

/**
 * 
*/
$input_name_checked = ''; // valor por defecto
$input_phone_checked = ''; // valor por defecto

if (isset($_GET['orderBy'])) {
    $order = $_GET['orderBy'];
    if ($order === '[name:ASC]') {
        $order = 'nombre ASC';
        $input_name_checked = 'checked';
    } elseif ($order === '[name:DESC]') {
        $order = 'nombre DESC';
        $input_name_checked = 'checked';
    } elseif ($order === '[phone:ASC]') {
        $order = 'telefono ASC';
        $input_phone_checked = 'checked';
    } elseif ($order === '[phone:DESC]') {
        $order = 'telefono DESC';
        $input_phone_checked = 'checked';
    }
}

if ($order === '[name:ASC]') {
    $order = 'nombre ASC';
}

/**
 * obtiene todos los datos de la base de datos corespondiente
*/
require __DIR__ .("/auth.php");
$data_contact = $mysqli->prepare("SELECT AES_DECRYPT(name, ?) as nombre, AES_DECRYPT(phone, ?) as telefono, id FROM `".$_SESSION['id_table']."` ORDER BY ".$order." ");
$data_contact->bind_param("ss", $email, $email);
$email = $_SESSION['email'];

$data_contact->execute();
$result = $data_contact->get_result();
$data_contact->close();
$mysqli->close();

$name = 'No hay contactos aun...';

// itera sobre los resultos obtenidos de la consulta a la base de datos
// creando una variable la cual almacena los datos iterados junto con formato
// HTML para luego ser impresos en el documento HTML
$i = 0;
while ($rows = $result->fetch_assoc()) {
    if ($name === 'No hay contactos aun...') {
        $name = '';
    }
    $i++;
    $name .= '<tr><td>'.$rows['nombre'].'</td><td>'.$rows['telefono'].'</td><td><button><a href="#actions=[ampliar;'.$rows['nombre'].';'.$rows['telefono'].';'.$rows['id'].']" class="ampliar">ampliar</a></button></td><td><button><a href="auth/?actions=delete&id='.$rows['id'].'">borrar</a></button></td></tr>';
}
$total = $i;

