<?php
session_start();
ob_start();
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

include("../../bases_datos/adodb/adodb.inc.php");
include("../../bases_datos/usb_defglobales.inc");


$dbi = NewADOConnection("$motor_odb1");
$dbi->Connect($base_db1, $usuario_db1, $contra_db1);

if (!$dbi) {
    echo "Error en la conexión a la base de datos";
    exit;
}

//////////////////////////////

if (isset($_SESSION['id_usuario']) && isset($_SESSION['nombre_usuario']) && isset($_SESSION['apellido_usuario'])) {
    $id_adm = $_SESSION['id_usuario'];
    $nombre_adm = $_SESSION['nombre_usuario'];
    $apellido_adm = $_SESSION['apellido_usuario'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idCeremonia'])) {
    $idCeremonia = $_POST['idCeremonia'];

    try {
        // Ejemplo de consulta SQL para actualizar el estado de la ceremonia
        $query = "UPDATE academico.GI_CEREMONIAS_TB SET ESTADO = 'DESHABILITADO' WHERE ID_CEREMONIA = '$idCeremonia'";

        $result = $dbi->Execute($query, array($idCeremonia));

        if (!$result) {
            echo 'error';
        } else {
            $query2 = "UPDATE academico.GI_RELACION_TB SET ESTADO = 'DESHABILITADO' WHERE ID_CEREMONIA = '$idCeremonia'";
            $result2 = $dbi->Execute($query2, array($idCeremonia));

            if ($result2) {
                echo 'success';
            } else {
                echo "error";  
            }
        }

        $dbi->Close();
    } catch (exception $e) {
        echo 'error';
    }
} else {
    echo 'error';
}
