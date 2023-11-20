<?php
session_start();
ob_start();

$timeout_duration = 600;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header('Location: logout.php');
    exit();
}

$_SESSION['last_activity'] = time();

include("../../bases_datos/adodb/adodb.inc.php");
include("../../bases_datos/usb_defglobales.inc");

$dbi = NewADOConnection("$motor_odb1");
$dbi->Connect($base_db1, $usuario_db1, $contra_db1);

if (!$dbi) {
    die("Error de conexión: " . $dbi->ErrorMsg());
}

$documento_inv = $_POST['documento_inv'];

$consulta = "SELECT N.EPOCH AS CODIGO_QR FROM academico.GI_INVITADOS_TB I INNER JOIN academico.GI_INVITACIONES_TB N ON I.ID_REGISTRO = N.ID_REGISTRO WHERE I.DOCUMENTO_INV = '$documento_inv'";
$resultado = $dbi->Execute($consulta);

if ($resultado && !$resultado->EOF) {
    // Obtener la ruta completa del archivo QR
    $qr_path = '/var/www/academia/invitados_grados/grado/temp/qr' . $resultado->fields['CODIGO_QR'] . '.png';

    // Verificar que el archivo exista antes de descargarlo
    if (file_exists($qr_path)) {
        // Establecer las cabeceras para la descarga del archivo
        header('Content-Type: image/png'); // Ajusta según el tipo de archivo
        header('Content-Disposition: attachment; filename="' . basename($qr_path) . '"');

        // Leer el archivo y enviarlo al cliente
        readfile($qr_path);

        // Terminar la ejecución después de la descarga
        exit();
    } else {
        // El archivo no existe en la ubicación
        http_response_code(404);
        echo "El archivo QR no pudo ser encontrado en la ruta: $qr_path";
    }
} else {
    http_response_code(404);
    echo "No se encontró un QR asociado a ese número de documento.";
}

$dbi->Close();
?>
