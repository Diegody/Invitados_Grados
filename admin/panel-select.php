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

if (isset($_SESSION['id_usuario']) && isset($_SESSION['nombre_usuario']) && isset($_SESSION['apellido_usuario'])) {
    $id_adm = $_SESSION['id_usuario'];
    $nombre_adm = $_SESSION['nombre_usuario'];
    $apellido_adm = $_SESSION['apellido_usuario'];
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
    <style>
        .responsive-img {
            max-width: 100%;
            width: 100%;
            height: auto;
        }
    </style>
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
                        <a class="active-menu" href="#"><i class="fa fa-table-cells"></i>Tablas<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a class="active-menu" href="panel-select.php"><i class="fa fa-user-graduate"></i>Graduandos</span></a>
                            </li>
                            <li>
                                <a href="panel-select-inv.php"><i class="fa fa-user-tie"></i>Invitados</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="qr.php"><i class="fa fa-qrcode"></i>Descargar QR</a>
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
                        <h1 class="page-head-line">SELECCIONAR </h1>
                        <h1 class="page-subhead-line">Seleccione un filtro para consultar la información de los graduandos.
                        </h1>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel-body">
                            <div class="form-container">
                                <div class="col-xs-12 col-sm-6">
                                    <form method="POST" action="tableG.php" class="form-horizontal">
                                        <div class="form-group">
                                            <label for="opcS1" class="col-xs-12" class="col-xs-12">Seleccione Ciclo: </label>
                                            <div class="col-xs-12">
                                                <select class="form-control" id="opcS1" name="opcS1" style="margin-bottom: 10px;">
                                                    <option value="" selected>SELECCIONE</option>
                                                    <?php
                                                    $consulta = "SELECT STRM, STRM || ' ' || DESCR AS INFO FROM academico.PS_TERM_TBL WHERE ACAD_CAREER = 'PREG' AND WEEKS_OF_INSTRUCT = 16 AND STRM >= 2366 ORDER BY STRM DESC";
                                                    $recordSet = $dbi->Execute($consulta);
                                                    if ($recordSet && !$recordSet->EOF) {
                                                        while (!$recordSet->EOF) {
                                                            echo '<option value="' . $recordSet->fields['STRM'] . '">' . $recordSet->fields['INFO'] . '</option>';
                                                            $recordSet->MoveNext();
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-xs-12">
                                                <button type="submit" class="btn btn-success">Consultar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="col-xs-12 col-sm-6">
                                    <form method="POST" action="tableG.php" class="form-horizontal">
                                        <div class="form-group">
                                            <label for="opcS2" class="col-xs-12">Seleccione Ceremonia: </label>
                                            <div class="col-xs-12">
                                                <select class="form-control" id="opcS2" name="opcS2" style="margin-bottom: 10px;">
                                                    <option value="" selected>SELECCIONE</option>
                                                    <?php
                                                    $consulta = "SELECT ID_CEREMONIA, ID_CEREMONIA || ' ' || NOMBRE AS INFO FROM academico.GI_CEREMONIAS_TB ORDER BY ID_CEREMONIA DESC";
                                                    $recordSet = $dbi->Execute($consulta);
                                                    if ($recordSet && !$recordSet->EOF) {
                                                        while (!$recordSet->EOF) {
                                                            echo '<option value="' . $recordSet->fields['ID_CEREMONIA'] . '">' . $recordSet->fields['INFO'] . '</option>';
                                                            $recordSet->MoveNext();
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-xs-12">
                                                <button type="submit" class="btn btn-success">Consultar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <center>
                                        <img src="assets/img/gradoImg.png" alt="usbbog" class="responsive-img" style="width: 300px; height: 350px; margin-top: 80px;">
                                    </center>
                                </div>
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