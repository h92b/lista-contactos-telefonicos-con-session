<!DOCTYPE html>
<html lang="es">
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
    
    if ( $key === 'csrf' && $value !== $_SESSION['csrf'] && (!isset($_GET['actions'])) ) {
        echo json_encode(array("status" => "NOT", "form" => "general", "msg" => "Permiso denegado."), true);
        exit;
    }

}

/**
 * sentencia si la accion a realizar es distinta de registrar un usuario 
 * o de loggear un usuario y valida la existencia de la variable
 * email y password.
 * de ser una accion diferente a de loggear o registrar y no existir las 
 * variables email y password la sentencia imprimira NOT y retornara false
 * deteniedo la ejecucion del programa.
*/
if ($_GET['actions'] !== 'login' && $_GET['actions'] !== 'signup' && (!isset($_GET['email'])) && (!isset($_GET['password'])) ) {
    
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
        if (preg_match( '/^[^.\)\(\>\<\@\,\;\"\[\]\Ç\&\%][a-zA-Z0-9]|\d|[!#\$%&\'\*\+\-\/=\?\^_`{\|}~]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/' , $email)) {
            return true;
        } else {
            return false;
        }
    }

}

/**
 * esta sentencia valida la existencia de las variables
 * $_GET['password']
 * si existe se ejecuta como true dando acceso a la
 * function func_valid_password la cual valida la estructura
 * del password con una comparacion pregmatica usando
 * como pattern una regular expression.
 * retorna true si la estructura es valida o 
 * false si no es valido Ejemplos:
 * Asd123## es valida
 * asd123## no es valida por carecer de un caracteres en mayuscula
 * Asd123# no es valida por ser muy pequena en longitud
*/
if (isset($_GET['password'])) {

    function func_valid_password (string $password) {
        if (preg_match( '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?#&\'\"\;\:\(\)\.\*\+\-\=])[A-Za-z\d@$!%*?#&\'\"\;\:\(\)\.\*\+\-\=]{8,}$/' , $password)) {
            return true;
        } else {
            return false;
        }
    }

}

/**
 * La variable $valid_email se inicia con un valor false
 * si la sentencia se ejetuta y la function func_valid_email
 * es llamada la variable $valid_email podria cambiar su valor
 * a true o mantener en false
*/
$valid_email = false;
(isset($_SESSION['email']) && function_exists('func_valid_email')) ? 
    $valid_email = func_valid_email($_SESSION['email']) : 0 ;

(isset($_GET['email']) && function_exists('func_valid_email')) ? 
    $valid_email = func_valid_email($_GET['email']) : 0 ;

/**
 * La variable $valid_password se inicia con un valor false
 * si la sentencia se ejetuta y la function func_valid_password
 * es llamada la variable $valid_password podria cambiar su valor
 * a true o mantener en false
*/
$valid_password = false;
(isset($_GET['password']) && function_exists('func_valid_password') ) ? 
    $valid_password = func_valid_password($_GET['password']) : 0 ;

/**
 * esta function verifica la existencia de un usuario en la base de datos
 * si encuentra al usuario retorna true de no encontrarlo retorna false
*/
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
        unset($result);        
        return false;
    }

    return true;

}

/**
 * esta function retorna false si el usuario no existe.
 * si el usuario existe retorna su ID.
*/
function id_tables ($key) {

    require __DIR__ .("/auth.php");        
    $data_user = $mysqli->prepare("SELECT id FROM users WHERE key_pub = (?) ");
    $data_user->bind_param("s", $key_pub);
    $key_pub = $key;
    $data_user->execute();
    $result = $data_user->get_result();
    $data_user->close();
    $mysqli->close();

    if (!$result->num_rows) {
        return false;
    }

    $rows = $result->fetch_assoc();
    return $rows['id'];
}

/**
 * si el if se ejecuta como true da paso a la function login.
 * la function login da las credenciales necesaria para que
 * el usuario tenga acceso a la pagina del Dashboard
 * verifica que usuario esten ingresando un email y password
 * correctos de no ser retorna ('Email o contrasena incorecta.')
 * pide el ID de la table en la base de datos perteneciente al
 * usuario de no existir retorna ('Email o contrasena incorecta.')
*/
if ($_GET['actions'] === 'login') {
        
    function login($key) {

        // verifica que las credenciales sean diferentes a las de la base de datos.
        if (!verify($key)) {
            echo 'Email o contrasena incorecta.';
            return false;
        }
        
        $_SESSION['email'] = $_GET['email'];
        
        // llama a la function id_tables para obtener el valor de la tabla perteneciente al usuario.
        $id = id_tables($key);

        // la variable $id de ser false ejecuta la sentencia if
        if (!$id) {
            echo 'Email o contrasena incorecta.';
            return false;
        }

        $_SESSION['id_table'] = $id;
        $_SESSION['email'] = $_GET['email'];
        $_SESSION['files_csrf'] = bin2hex(random_bytes(16));
        $_SESSION['hash'] = $key;
        $url = '/dashboard/?files_csrf='. $_SESSION['files_csrf'] . '&orderBy=[name:ASC]';
        header("Location: $url");
        
    }

}

/**
 * si el if se ejecuta como true da paso a la function signup.
 * la function signup da las credenciales necesaria para que
 * el usuario tenga acceso a la pagina del Dashboard y crea los
 * recurso necesario el almacenamiento de los contactos.
 * verifica que el usuario a registrar no exista en la base de datos
 * si el usuario existe imprime ('El usuario ya existe.') y retorna
 * false para detener el programa.
*/
if ($_GET['actions'] === 'signup') {
        
    function signup($key) {
        
        // verifica que el usuario existe
        if (verify($key)) {
            echo 'El usuario ya existe.';
            return false;
        }
        
        // guarda los datos del usuario en la base de datos
        require __DIR__ .("/auth.php");
        $data_user = $mysqli->prepare("INSERT INTO users ( email, password, id, key_pub)
        VALUES (AES_ENCRYPT(?, 'admin@prueba.com'), ?, UUID_SHORT(), ?) ");
        $data_user->bind_param("sss", $email, $password, $key_pub);
        $email = $_GET['email'];
        $password = password_hash($_GET['password'], PASSWORD_DEFAULT);
        $key_pub = $key;
        $data_user->execute();
        $data_user->close();
        $mysqli->close();

        // verifica que los datos no se guardaron el la base de datos
        if (!verify($key)) {
            echo 'No se pudo crear al usuario ' . $_GET['email'];
        }

        // llama a la function id_tables para que me retorne el valor ID de la table
        $id = id_tables($key);

        // si la variable $id tiene valor false se ejecuta la sentencia if
        if (!$id) {
            echo 'Ocurrio un error al crear los recursos.';
            return false;
        }

        // crea la tabla para almacenar los contactos
        $sql_create_table = "CREATE TABLE IF NOT EXISTS `$id` (
            id int NOT NULL AUTO_INCREMENT,
            name blob NOT NULL,
            phone blob NOT NULL,
            uuid varchar(30) NOT NULL,
            PRIMARY KEY(id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci";
        require __DIR__ .("/auth.php");
        $result = $mysqli->query($sql_create_table);
        $mysqli->close();
        
        $_SESSION['id_table'] = $id;
        $_SESSION['email'] = $_GET['email'];
        $_SESSION['files_csrf'] = bin2hex(random_bytes(16));
        $_SESSION['hash'] = $key;
        $url = '/dashboard/?files_csrf='. $_SESSION['files_csrf'] . '&orderBy=[name:ASC]';
        header("Location: $url");

    }

}

/**
 * la variable local $data almacena un array creado
 * con las variables globales $_GET['email'] y $_GET['password']
*/
$data = array(
    $_GET['email'],
    $_GET['password']
);

/**
 * se almacena un hash 256 en la variable local $key
 * hash creado a partir del array $data
*/
$key = hash('SHA256', implode(':', $data));

/**
 * en el caso de cumplirse las condiciones llama a la function login o signup
 * las cuales se le pasa como parametro la variables $key que almacena un hash 256
*/
($_GET['actions'] === 'login' && function_exists('login') && $valid_email && $valid_password)&&login($key);
($_GET['actions'] === 'signup' && function_exists('signup') && $valid_email && $valid_password)&&signup($key);
