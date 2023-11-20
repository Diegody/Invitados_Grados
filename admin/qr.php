<?php
session_start();
ob_start();
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

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
    header('Location: error.php');
    exit;
}

//////////////////////////////

if (isset($_SESSION['id_usuario']) && isset($_SESSION['nombre_usuario']) && isset($_SESSION['apellido_usuario']) && isset($_SESSION['apellido_usuario'])) {
    $id_adm = $_SESSION['id_usuario'];
    $nombre_adm = $_SESSION['nombre_usuario'];
    $apellido_adm = $_SESSION['apellido_usuario'];
    $estado = $_SESSION['estado'];
}
?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Panel</title>

    <!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!--CUSTOM BASIC STYLES-->
    <link href="assets/css/basic.css" rel="stylesheet" />
    <!--CUSTOM MAIN STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">PANEL ADMINISTRADOR</a>
            </div>
            <div class="header-right">
                <div class="inner-text">
                    <i class="fa-solid fa-user-check" style="color: #ffffff;"></i><?php echo " " . $nombre_adm . " " . $apellido_adm; ?>
                    <br />
                    <small><i class="fa-solid fa-hashtag" style="color: #ffffff;"></i><?php echo " " . $id_adm; ?></small>
                </div>
            </div>
        </nav>
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li>
                        <div class="user-img-div">
                            <center><img src="https://filosofia.net/cdf/uds/usb.png" class="img-thumbnail" /></center>
                        </div>
                    </li>
                    <li>
                        <a href="index.php"><i class="fa fa-globe"></i>Inicio</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-code"></i>Parámetros <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="panel-fec.php"><i class="fa fa-calendar-plus"></i>Ceremonias</a>
                            </li>
                            <li>
                                <a href="panel-select-cant.php"><i class="fa fa-edit"></i>Cantidad Invitados</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="panel-up.php"><i class="fa fa-circle-up"></i>Subir Graduandos</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-circle-plus"></i>Registrar<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="formG.php"><i class="fa fa-graduation-cap"></i>Graduandos</a>
                            </li>
                            <li>
                                <a href="formU.php"><i class="fa fa-key"></i>Usuarios</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-table-cells"></i>Tablas<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="panel-select.php"><i class="fa fa-user-graduate"></i>Graduandos</span></a>
                            </li>
                            <li>
                                <a href="panel-select-inv.php"><i class="fa fa-user-tie"></i>Invitados</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a class="active-menu" href="qr.php"><i class="fa fa-qrcode"></i>Descargar QR</a>
                    </li>
                    <li>
                        <a href="instructivo.php"><i class="fa fa-circle-info"></i>Instructivo</a>
                    </li>
                    <li>
                        <a href="../login.php"><i class="fa fa-sign-in"></i>Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </nav>

        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-head-line">DESCARGAR CÓDIGO QR</h1>
                        <h1 class="page-subhead-line">En esta sección podrá descargar los códigos QR de los invitados en caso de que algún graduando reporte que no le llegó el correo electrónico a su invitado.
                        </h1>
                    </div>
                </div>
                <!-- /. ROW  -->
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                Descargar QR por Invitado
                            </div>
                            <div class="panel-body">
                                <form id="qrForm" role="form">
                                    <div class="form-group">
                                        <label>Número documento invitado</label>
                                        <input class="form-control" type="number" name="documento_inv" min="0" max="999999999" step="1" required>
                                        <p class="help-block">Documento invitado.</p>
                                    </div>
                                    <button type="button" class="btn btn-info" id="btnDescargar">Descargar QR</button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="footer-sec">
        <table align=center width=100% border=0 style="border-top-style:solid;border-top-width:3px;border-top-color:#971a21;">
            <tr>
                <td align=center style='font-size: 13px;color: #fff;margin:0px;'>
                    Universidad de San Buenaventura, sede Bogot&aacute; </td>
            </tr>
            <tr>
                <td align=center style='font-size: 13px;color: #fff;margin:0px;'>
                    Carrera 8 H # 172 - 20 | PBX: (571) 667 1090 | L&iacute;nea directa: 01 8000 125 151 | E-mail: informacion@usbbog.edu.co </td>
            </tr>
            <tr>
                <td align=center style='font-size: 13px;color: #fff;margin:0px;'>
                    Copyright &copy; <?= date('Y') ?> Universidad de San Buenaventura, sede Bogot&aacute;. Todos los derechos reservados. </td>
            </tr>
            <tr>
                <td>
        </table>
    </div>
    <!-- /. FOOTER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <script>
        $(document).ready(function() {
            $("#btnDescargar").click(function() {
                // Obtener el número de documento del formulario
                var numero_documento = $("#qrForm [name='documento_inv']").val();

                // Realizar una solicitud Fetch al servidor para descargar el QR
                fetch("qrBack.php", {
                        method: "POST",
                        body: new URLSearchParams({
                            documento_inv: numero_documento
                        }),
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.blob();
                    })
                    .then(blob => {
                        // Crear un objeto URL para el blob
                        var blobUrl = URL.createObjectURL(blob);

                        // Crear un enlace invisible y hacer clic en él para iniciar la descarga
                        var a = document.createElement('a');
                        a.href = blobUrl;
                        a.download = `Código QR ${numero_documento}.png`;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);

                        // Mostrar SweetAlert2 con mensaje de éxito
                        Swal.fire({
                            icon: 'success',
                            title: 'QR Descargado',
                            text: 'El QR se ha descargado exitosamente.',
                        });
                    })
                    .catch(error => {
                        // Mostrar SweetAlert2 con mensaje de error
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No existe un código QR para este invitado. Intente nuevamente.',
                        });

                        // Este código se ejecutará si hay un error en la solicitud
                        console.log(error);
                    });
            });
        });
    </script>

    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="assets/js/jquery.metisMenu.js"></script>
    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
</body>

</html>