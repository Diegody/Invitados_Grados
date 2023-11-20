<?php
session_start();
ob_start();
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

include("../../bases_datos/adodb/adodb.inc.php");
include("../../bases_datos/usb_defglobales.inc");

date_default_timezone_set('America/Bogota');
$fecha_actual = date("d-m-Y h:i:s");
$fechaHora = DateTime::createFromFormat('d-m-Y h:i:s', $fecha_actual);
$fechaHora->add(new DateInterval('PT4M49S'));
$nuevaFechaHora = $fechaHora->format('d-m-Y h:i:s');

$dbi = NewADOConnection("$motor_odb1");
$dbi->Connect($base_db1, $usuario_db1, $contra_db1);
//////////////////////////////

// Tiempo EPOCH actual
$epochTime = time();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id_reg = (isset($_POST['id'])) ? $_POST['id'] : "";
        $documento_inv = (isset($_POST['documento'])) ? $_POST['documento'] : "";
        $contenido = $epochTime .  $documento_inv . $id_reg;
        
        $queryy = "SELECT * FROM academico.GI_INVITACIONES_TB WHERE ID_REGISTRO = '$id_reg'";
        $resultt = $dbi->Execute($queryy);
        $cont = $resultt && $resultt->RecordCount();
        
        if ($cont == 0) {
            $sql1 = "INSERT INTO academico.GI_INVITACIONES_TB(ID_REGISTRO, ESTADO, FECHA, EPOCH) VALUES ('$id_reg', 'INACTIVO', TO_DATE('$nuevaFechaHora', 'DD-MM-YYYY HH24:MI:SS'), '$contenido')";
            $params1 = array($id_reg, $nuevaFechaHora, $contenido);
            $resultado1 = $dbi->Execute($sql1, $params1);
            
            echo json_encode(array("success" => true, "message" => "Estado del invitado {$documento_inv} inhabilitado de manera correcta."));
            exit; // Finaliza el script después de enviar la respuesta
        } else {
            $sql2 = "UPDATE academico.GI_INVITACIONES_TB SET ESTADO = 'INACTIVO', FECHA = TO_DATE('$nuevaFechaHora', 'DD-MM-YYYY HH24:MI:SS') WHERE ID_REGISTRO = '$id_reg'";
            $params2 = array($id_reg);
            $resultado2 = $dbi->Execute($sql2, $params2);
            
            echo json_encode(array("success" => true, "message" => "Estado del invitado {$documento_inv} inhabilitado(C) de manera correcta"));
            exit; 
        }
    } catch (exception $e) {
        echo json_encode(array("success" => false, "message" => $e->getMessage()));
        exit; 
    }
}
$dbi->Close();
