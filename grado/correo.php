<?php
session_start();
// error_reporting(E_ALL);
// ini_set('display_errors', '1');
include("../../bases_datos/adodb/adodb.inc.php");
include("../../bases_datos/usb_defglobales.inc");


$dbi = NewADOConnection("$motor_odb1");
$dbi->Connect($base_db1, $usuario_db1, $contra_db1);
if (!$dbi) {
    echo "<br>error <br>";
    exit;
}

if (!$dbi) {
    echo "Error de conexión a la base de datos";
    exit;
}

// Tiempo EPOCH actual
$epochTime = time();

if (isset($_SESSION['id_usuario']) && isset($_SESSION['nombre_usuario']) && isset($_SESSION['apellido_usuario'])) {
    $id_est = $_SESSION['id_usuario'];
    $nombre_est = $_SESSION['nombre_usuario'];
    $apellido_est = $_SESSION['apellido_usuario'];
}

if (isset($_POST['enviar'])) {
    list($documento_inv, $relacion, $id_reg) = explode(',', $_POST['enviar']);

    $query = "SELECT I.NOMBRES, I.APELLIDOS, I.CORREO, C.FECHA, C.HORA FROM academico.GI_INVITADOS_TB I JOIN academico.GI_RELACION_TB R ON R.ID_RELACION = I.ID_RELACION
    JOIN academico.GI_CEREMONIAS_TB C ON C.ID_CEREMONIA = R.ID_CEREMONIA WHERE I.DOCUMENTO_INV = '$documento_inv' AND R.ID_RELACION = '$relacion' AND I.ID_REGISTRO = '$id_reg'";
    $result = $dbi->Execute($query);


    /////////////////////////////////////////////////////////////////

    //Agregamos la librería para genera códigos QR
    require "phpqrcode/qrlib.php";

    // Se crea una carpeta temporal para guardar las imágenes generadas 
    $dir = '/var/www/academia/invitados_grados/grado/temp/';

    if (!file_exists($dir)) {
        // Si la carpeta no existe, intenta crearla
        if (!mkdir($dir, 0755, true)) {
            // No se pudo crear la carpeta, muestra un mensaje de error
            $textErrorFold = "No se pudo crear la carpeta temporal. Verifica los permisos en el servidor.";
            exit;
        }
    }

    // Se verifica si la carpeta tiene permisos de lectura/escritura
    if (is_writable($dir) && is_readable($dir)) {
        // Parámetros de Configuración
        $tamaño = 4; // Tamaño de Pixel
        $level = 'H'; // Precisión Baja
        $framSize = 2; // Tamaño en blanco
        $contenido = $documento_inv . $epochTime . $id_reg;
        $qr = 'qr' . $contenido . '.png';

        $filename = $dir . $qr;

        $sql = "INSERT INTO academico.GI_INVITACIONES_TB (ID_REGISTRO, ESTADO, EPOCH) VALUES('$id_reg', 'ACTIVO', '$contenido')";
        $res = $dbi->Execute($sql);

        QRcode::png($contenido, $filename, $level, $tamaño, $framSize);

        $nombre = "Ceremonia de Grados";
        $remitente = "reg.grados@usbbog.edu.co";
        $asunto = "Invitación ceremonia de graduación";

        // Bucle para enviar correos a cada destinatario
        while (!$result->EOF) {
            $row = $result->fields;
            $destinatario = $row['CORREO'];
            $nombreInv = $row['NOMBRES'];
            $apellidoInv = $row['APELLIDOS'];
            $fecha = $row['FECHA'];
            $hora = $row['HORA'];
            $CodigoQR = "<img src='https://academia.usbbog.edu.co/invitados_grados/grado/temp/$qr' alt='Código QR de Invitación' width='400' height='400'>";

            // Cabeceras para indicar que el contenido es HTML
            $cabeceras = "MIME-Version: 1.0" . "\r\n";
            $cabeceras .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $cabeceras .= "From: $nombre <$remitente>" . "\r\n";
            $cabeceras .= "Bcc: tec.analista@usbbog.edu.co" . "\r\n";

            // Mensaje en formato HTML
            $mensaje = "
            <html>
                <head>
                    <title>Invitación ceremonia de graduación - $contenido</title>
                </head>
                <body>
                    <h4>Cordial saludo señor(a) $nombreInv $apellidoInv.</h4>
                    <p>Este <b>QR</b> es la invitación personal e intransferible con la cual
                        usted podrá ingresar por una única vez a la ceremonia de graduación de $nombre_est $apellido_est, la cual se llevará a cabo el día <b>$fecha</b> a las <b>$hora</b>.</p>
                    <p>Por favor seguir las siguientes recomendaciones: </p>
                    <ul>
                        <li type='disc'>Ser puntuales (preferiblemente estar ½ hora antes de la ceremonia).</li>
                        <li type='disc'>No se permite el ingreso a niños menores de 10 años de edad.</li>
                        <li type='disc'>Se recomienda mantener los celulares y equipos electrónicos en modo silencio.</li>
                        <li type='disc'>Los fotógrafos autorizados se encuentran dentro de la Universidad NO afuera.</li>
                        <li type='disc'>El servicio de parqueadero está sujeto a disponibilidad, por tanto, la Universidad
                            no se hace responsable de los vehículos parqueados fuera de las instalaciones.</li>
                        <li type='disc'>Si no cuenta con un equipo móvil para presentar su invitación puede traerla impresa y entregarla a la
                            entrada del auditorio para ser validada por el personal de logística.</li>
                    </ul>
                    <p>Agradecemos su colaboración.</p>
                    <center>
                        $CodigoQR
                    </center>
                    <br>
                    <br>
                    Cordialmente,
                    <br>
                    <br>
                    <span style=' font-style: normal; font-weight: normal; font-size: 10pt;'>
                        <table width='646' style='font-family: Roboto, Tahoma, Helvetica, Arial, sans-serif; font-size: 10pt;'>
                            <tr>
                                <td>
                                    <img src='https://academia.usbbog.edu.co/imagenes_correo/escudo%20firma.png' alt='Escudo'
                                        style='cursor: pointer; min-height: auto; min-width: auto;'>
                                </td>
                                <td>
                                    <span
                                        style='color: rgb(0, 0, 0)    !important; color: rgb(0, 0, 0); font-weight:bold;'>Grados</span><br
                                        aria-hidden='true'>
                                    <span
                                        style='color: rgb(87, 87, 86) !important; color: rgb(87, 87, 86); font-weight:bold;'>Notificación
                                        automática</span><br aria-hidden='true'>
                                    <span style='font-weight:bold;'>Unidad de Registro Académico</span><br aria-hidden='true'>
                                    <span style='color: rgb(29, 29, 27) !important; color: rgb(29, 29, 27); font-weight: normal;'>
                                        <img src='https://academia.usbbog.edu.co/imagenes_correo/linea%20firma.png' alt='linea'
                                            style='cursor: pointer; min-height: auto; min-width: auto;' width='83 px' height='5 px'><br
                                            aria-hidden='true'>
                                    </span>
                                    <span>
                                        <img src='https://academia.usbbog.edu.co/imagenes_correo/Telfono%20firma.png' alt='Telefono'
                                            style='cursor: pointer; min-height: auto; min-width: auto;' width='18 px' height='15 px'>
                                        PBX: 601 667 1090 Ext:4201<br aria-hidden='true'>
                                        <img src='https://academia.usbbog.edu.co/imagenes_correo/correo%20firma.png' alt='Correo'
                                            style='cursor: pointer; min-height: auto; min-width: auto;' width='18 px' height='11 px'>
                                        reg.grados@usbbog.edu.co<br aria-hidden='true'>
                                        <img src='https://academia.usbbog.edu.co/imagenes_correo/direccion%20firna.png' alt='Direccion'
                                            style='cursor: pointer; min-height: auto; min-width: auto;' width='18 px' height='19 px'>
                                        Carrera 8H # 172-20<br aria-hidden='true'>
                                    </span>
                                    <span style='color:rgb(127,127,127) !important; color:rgb(127,127,127); font-size: 12pt;'>
                                        <a href='https://www.usbbog.edu.co'
                                            style='text-decoration:none !important; text-decoration:none; color:rgb(102,102,102) !important; color:rgb(102,102,102); '><span
                                                style='text-decoration:none !important; text-decoration:none; color:rgb(102,102,102) !important; color:rgb(102,102,102);'>www.<span
                                                    style='color: rgb(239, 125, 0) !important; color: rgb(239, 125, 0);'>usbbog</span>.edu.co</span></a>
                                    </span>
                                </td>
                                <td>
                                    <img src='https://academia.usbbog.edu.co/imagenes_correo/arbol%20firma.png' alt='Acreditacion'
                                        style='cursor: pointer; min-height: auto; min-width: auto;'>
                                </td>
                                <td width='34'>
                                    <a href='https://es-la.facebook.com/usbdebogota/' target='_blank' rel='noopener noreferrer'><img
                                            src='https://academia.usbbog.edu.co/imagenes_correo/facebook%20firma.png' alt='facebook'
                                            style='min-height: auto; min-width: auto;' width='37 px' height='27 px'></a>
                                    <a href='https://instagram.com/usbbog?utm_medium=copy_link' target='_blank'
                                        rel='noopener noreferrer'><img
                                            src='https://academia.usbbog.edu.co/imagenes_correo/isntagram%20firma.png' alt='Instagram'
                                            style='min-height: auto; min-width: auto;' width='36 px' height='27 px'></a>
                                    <a href='https://twitter.com/usbbog' target='_blank' rel='noopener noreferrer'><img
                                            src='https://academia.usbbog.edu.co/imagenes_correo/twiter%20firma.png' alt='Twitter'
                                            style='min-height: auto; min-width: auto;' width='36 px' height='27 px'></a>
                                    <a href='https://www.youtube.com/c/SanBuenaBogotá' target='_blank' rel='noopener noreferrer'><img
                                            src='https://academia.usbbog.edu.co/imagenes_correo/youtube%20frima.png' alt='Youtube'
                                            style='min-height: auto; min-width: auto;' width='36 px' height='27 px'></a>
                                </td>
                            </tr>
                        </table>
                    </span>
                    <br aria-hidden='true'>
                </body>
            </html>";

            if (mail($destinatario, $asunto, $mensaje, $cabeceras)) {
                echo "success"; // Enviar una respuesta simple en caso de éxito
            }

            $result->MoveNext();
        }
    } else {
        if (mail($destinatario, $asunto, $mensaje, $cabeceras)) {
            echo "$textError Error al enviar el correo a $destinatario. Intente más tarde o contáctese con soporte.";
        }
    }
}
