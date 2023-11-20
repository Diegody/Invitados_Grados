<?php
session_start();
ob_start();
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

include("../../bases_datos/adodb/adodb.inc.php");
include("../../bases_datos/usb_defglobales.inc");

// $usu = '';
// $cla = '';
// $usu = $_SESSION["usuario"];
// $cla = $_SESSION['clave'];

// $usu = strtr($usu, "Ññ?", "Ñ");

$dbi = NewADOConnection("$motor_odb1");
$dbi->Connect($base_db1, $usuario_db1, $contra_db1);
if (!$dbi) {
    echo "<br>error <br>";
    exit;
}

$consulta  = "SELECT * FROM academico.DDI_PARTICIPANTES_TB P WHERE 
EXISTS(SELECT * FROM academico.DDI__PARTICIPANTES_GRUPOS_INVESTIGACION_TB WHERE EMPLID = P.EMPLID) OR 
EXISTS(SELECT * FROM academico.DDI_PARTICIPANTES_SEMILLEROS_TB WHERE EMPLID = P.EMPLID) OR
EXISTS(SELECT * FROM academico.DDI__PARTICIPANTES_PROYECTOS_TB WHERE EMPLID = P.EMPLID)";
$ejecutar_consulta = $dbi->Execute($consulta);
$contar_consulta = $ejecutar_consulta->RecordCount();

//////////////////////////////
$dato = $_POST['id_deleteC'];

// Verifica si el dato está definido y no está vacío
if (!isset($dato) || empty($dato)) {
    echo "<script> alert('ID de ceremonia no válido.'); window.location= 'panel-fec.php' </script>";
    exit; // Termina el script
}

$sql = "DELETE FROM academico.GI_CEREMONIAS_TB WHERE ID_CEREMONIA = '$dato'";
$result = $dbi->Execute($sql);

if ($result) {
    // Verifica si se afectó algún registro
    if ($dbi->Affected_Rows() > 0) {
        echo "<script> alert('Ceremonia {$dato} eliminada de manera correcta.'); window.location= 'panel-fec.php' </script>";
    } else {
        echo "<script> alert('No se encontró la ceremonia {$dato}.'); window.location= 'panel-fec.php' </script>";
    }
} else {
    echo "<script> alert('Error al eliminar la ceremonia {$dato}: {$dbi->ErrorMsg()}'); window.location= 'panel-fec.php' </script>";
}

$dbi->Close();

