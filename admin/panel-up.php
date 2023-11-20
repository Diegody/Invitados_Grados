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
    echo "Error en la conexión a la base de datos";
    exit;
}

//////////////////////////////

if (isset($_SESSION['id_usuario']) && isset($_SESSION['nombre_usuario']) && isset($_SESSION['apellido_usuario'])) {
    $id_adm = $_SESSION['id_usuario'];
    $nombre_adm = $_SESSION['nombre_usuario'];
    $apellido_adm = $_SESSION['apellido_usuario'];
}

$consulta1 = "SELECT CANT_INV FROM academico.GI_ESTUDIANTES_GRADUADOS_TB WHERE DOCUMENTO_ESTUDIANTE = '$id_adm'";
$cantidad = $dbi->GetOne($consulta1, array($id_adm));

if ($cantidad !== false) {
    // echo "Cantidad: " . $cantidad;
} else {
    // echo "No se encontraron resultados.";
}

$consulta2 = "SELECT COUNT(*) AS cantidad_estudiantes FROM academico.GI_ESTUDIANTES_GRADUADOS_TB;";
$cantidad2 = $dbi->GetOne($consulta2, array($id_adm));

if ($cantidad2 !== false) {
    // echo "Cantidad: " . $cantidad;
} else {
    // echo "No se encontraron resultados.";
}
?>

<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Subir Graduandos</title>
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
                            <a class="active-menu" href="panel-up.php"><i class="fa fa-circle-up"></i>Subir Graduandos</a>
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
            <!-- /. NAV SIDE  -->
            <div id="page-wrapper">
                <div id="page-inner">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="page-head-line">SUBIR GRADUANDOS</h1>
                            <h1 class="page-subhead-line">En esta sección, puede cargar de manera masiva la información de los graduandos y asignarles sus respectivas ceremonias.
                            </h1>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="jumbotron">
                                <a href="assets/fonts/PlantillaSubirGraduandos.xlsx" download>
                                    <button class="btn btn-info page-subhead-lineButton">Descargar Plantilla</button>
                                </a>
                                <h4 class="page-subhead-line2"><br>
                                    Por favor, elija el archivo CSV que contiene la información de los graduandos. Es esencial que cada candidato tenga
                                    especificada al menos una ceremonia en la que participará. De no ser así, el registro del candidato no se efectuará adecuadamente.<br><br>Ejemplo:
                                </h4>


                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead class="page-subhead-line2">
                                            <tr>
                                                <th>Documento</th>
                                                <th>Nombres</th>
                                                <th>Apellidos</th>
                                                <th>Clave</th>
                                                <th>Perfil</th>
                                                <th>Correo Personal</th>
                                                <th>Correo Institucional</th>
                                                <th>Teléfono</th>
                                                <th>ID Ceremonia</th>
                                                <th>ID Programa</th>
                                                <th>Ciclo</th>
                                            </tr>
                                        </thead>
                                        <tbody class="page-subhead-line2">
                                            <tr>
                                                <td>1000001</td>
                                                <td>Otto Oliver</td>
                                                <td>Oswald Harper</td>
                                                <td>1000001</td>
                                                <td>2</td>
                                                <td>OttoHarp@email.com</td>
                                                <td>OttoHarp@edu.co</td>
                                                <td>123456789</td>
                                                <td>9001</td>
                                                <td>B0001</td>
                                                <td>202301</td>
                                            </tr>
                                            <tr>
                                                <td>1000002</td>
                                                <td>Juan Henry</td>
                                                <td>Perez Edwards</td>
                                                <td>1000002</td>
                                                <td>2</td>
                                                <td>JuanPer@email.com</td>
                                                <td>JuanPer1@edu.co</td>
                                                <td>987654321</td>
                                                <td>9002</td>
                                                <td>B0002</td>
                                                <td>202301</td>
                                            </tr>
                                            <tr>
                                                <td>1000003</td>
                                                <td>Emily Jane</td>
                                                <td>Smith Lennox</td>
                                                <td>1000003</td>
                                                <td>2</td>
                                                <td>LennoxMart@email.com</td>
                                                <td>LennoxMart@edu.co</td>
                                                <td>753196248</td>
                                                <td>9003</td>
                                                <td>B0003</td>
                                                <td>202302</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <form action="procesar.php" method="POST" enctype="multipart/form-data">
                                    <h4 class="page-subhead-line2" for="csvFile">Selecciona un archivo CSV:</h4>
                                    <input type="file" id="archivo_csv" name="archivo_csv" accept=".csv"><br>
                                    <input type="submit" class="btn btn-success page-subhead-lineButton" name="submit" value="Subir Archivo CSV">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /. PAGE INNER  -->
            </div>
            <!-- /. PAGE WRAPPER  -->
        </div>
        <!-- /. WRAPPER  -->
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