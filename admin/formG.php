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
    echo "<br>error <br>";
    exit;
}
//////////////////////////////

if (isset($_SESSION['id_usuario']) && isset($_SESSION['nombre_usuario']) && isset($_SESSION['apellido_usuario'])) {
    $id_adm = $_SESSION['id_usuario'];
    $nombre_adm = $_SESSION['nombre_usuario']; // Obtener el nombre del usuario desde la sesión
    $apellido_adm = $_SESSION['apellido_usuario'];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registrar Graduando</title>

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
                        <a href="panel-up.php"><i class="fa fa-circle-up"></i>Subir Graduandos</a>
                    </li>
                    <li>
                        <a class="active-menu" href="#"><i class="fa fa-circle-plus"></i>Registrar<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a class="active-menu" href="formG.php"><i class="fa fa-graduation-cap"></i>Graduandos</a>
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
                        <h1 class="page-head-line">REGISTRO DE GRADUANDOS</h1>
                        <h1 class="page-subhead-line">En esta sección, puede inscribir a los graduandos en caso de que surgieran eventualidades o imprevistos.</h1>
                    </div>
                </div>
                <!-- /. ROW  -->
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                Registrar Graduando
                            </div>
                            <div class="panel-body">
                                <form role="form" method="POST" action="registrarEst.php">
                                    <div class="form-group">
                                        <label>Tipo Documento</label><br>
                                        <select id="tipo_identificacion" name="tipo_identificacion" class="option-label" required>
                                            <option value="CC">Cédula de Ciudadanía</option>
                                            <option value="TI">Tarjeta de Indetidad</option>
                                            <option value="CE">Cédula de Extranjería</option>
                                            <option value="PA">Pasaporte</option>
                                            <option value="PEP">Permiso Especial de Permanencia</option>
                                        </select>
                                        <p class="help-block">Tipo de documento del graduando.</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Documento</label>
                                        <input id="documento_user" name="documento_user" class="form-control" type="number" min="0" step="1" max="9999999999" required>
                                        <p class="help-block">Documento del graduando.</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Nombre(s)</label>
                                        <input id="nombres" name="nombres" class="form-control" type="text" maxlength="80" required>
                                        <p class="help-block">Nombre(s) del graduando.</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Apellidos</label>
                                        <input id="apellidos" name="apellidos" class="form-control" type="text" maxlength="80" required>
                                        <p class="help-block">Apellidos del graduando.</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Contraseña</label>
                                        <input id="clave" name="clave" class="form-control" type="password" maxlength="80" required>
                                        <p class="help-block">Contraseña (inicialmente será el número de documento)</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Cantidad de Invitados</label>
                                        <input id="cant_inv" name="cant_inv" class="form-control" type="number" min="0" step="1" max="10" required>
                                        <p class="help-block">Cantidad de invitados que tendrá el graduando.</p>
                                    </div>
                                    <div class="form-group" hidden="true">
                                        <label>Perfil</label>
                                        <input id="tipo_rol" name="tipo_rol" class="form-control" type="number" min="0" step="1" value="2" readonly required>
                                        <p class="help-block">Perfil que tendrá el graduando (por defecto este valor siempre estará establecido como '2').</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Correo Personal</label>
                                        <input id="emailPer" name="emailPer" class="form-control" type="email" maxlength="100" required>
                                        <p class="help-block">Correo personal del graduando.</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Correo Institucional</label>
                                        <input id="emailCorp" name="emailCorp" class="form-control" type="email" maxlength="100" required>
                                        <p class="help-block">Correo institucional del graduando.</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Teléfono</label>
                                        <input id="numTel" name="numTel" class="form-control" type="number" min="0" step="1" max="3509999999" required>
                                        <p class="help-block">Número telefónico del graduando.</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Nombre Ceremonia</label>
                                        <input id="id_cerem" name="id_cerem" onkeyup="searchCeremonia()" class="form-control" required>
                                        <p class="help-block">Nombre de la ceremonia a la cual participará el graduando.</p>
                                        <ul id="resultsCeremonia" onclick="selectResultCerem(event)"></ul>

                                        <script>
                                            function searchCeremonia() {
                                                var input = document.getElementById("id_cerem").value.toLowerCase();
                                                if (input === "") {
                                                    document.getElementById("resultsCeremonia").innerHTML = "";
                                                    return;
                                                }

                                                var xmlhttp = new XMLHttpRequest();
                                                xmlhttp.onreadystatechange = function() {
                                                    if (this.readyState === 4 && this.status === 200) {
                                                        document.getElementById("resultsCeremonia").innerHTML = this.responseText;
                                                    }
                                                };
                                                xmlhttp.open("GET", "searchCeremonia.php?searchWordc=" + input, true);
                                                xmlhttp.send();
                                            }

                                            function selectResultCerem(event) {
                                                if (event.target.tagName === "LI") {
                                                    var selectedText = event.target.textContent;
                                                    var selectedCodigo = event.target.getAttribute("NOMBRE");
                                                    document.getElementById("id_cerem").value = selectedCodigo;
                                                    document.getElementById("ceremoniaSeleccionada").value = selectedCodigo;
                                                    document.getElementById("resultsCeremonia").innerHTML = "";
                                                }
                                            }
                                        </script>
                                        <input type="hidden" id="ceremoniaSeleccionada" name="ceremoniaSeleccionada">
                                    </div>

                                    <div class="form-group">
                                        <label>Ciclo</label>
                                        <input id="cicloCeremonia" name="cicloCeremonia" onkeyup="searchCicloA()" class="form-control" type="text" required>
                                        <p class="help-block">Ciclo asignado para la ceremonia.</p>
                                        <ul id="resultCiclo" onclick="selectResultCiclo(event)"></ul>

                                        <script>
                                            function searchCicloA() {
                                                var input = document.getElementById("cicloCeremonia").value.toLowerCase();
                                                if (input === "") {
                                                    document.getElementById("resultCiclo").innerHTML = "";
                                                    return;
                                                }

                                                var xmlhttp = new XMLHttpRequest();
                                                xmlhttp.onreadystatechange = function() {
                                                    if (this.readyState === 4 && this.status === 200) {
                                                        document.getElementById("resultCiclo").innerHTML = this.responseText;
                                                    }
                                                };
                                                xmlhttp.open("GET", "searchCiclo.php?searchWord=" + input, true);
                                                xmlhttp.send();
                                            }

                                            function selectResultCiclo(event) {
                                                if (event.target.tagName === "LI") {
                                                    var selectedText = event.target.textContent;
                                                    var selectedCodigo = event.target.getAttribute("DESCR");
                                                    document.getElementById("cicloCeremonia").value = selectedCodigo;
                                                    document.getElementById("cicloSeleccionado").value = selectedCodigo;
                                                    document.getElementById("resultCiclo").innerHTML = "";
                                                }
                                            }
                                        </script>
                                        <input type="hidden" id="cicloSeleccionado" name="cicloSeleccionado">
                                    </div>

                                    <div class="form-group">
                                        <label>Programa</label>
                                        <input id="programEst" name="programEst" onkeyup="searchUsersEst()" class="form-control" type="text" required>
                                        <p class="help-block">Programa al que perteneció el Graduando.</p>
                                        <ul id="resultsEst" onclick="selectResultEst(event)"></ul>

                                        <script>
                                            function searchUsersEst() {
                                                var input = document.getElementById("programEst").value.toLowerCase();
                                                if (input === "") {
                                                    document.getElementById("resultsEst").innerHTML = "";
                                                    return;
                                                }

                                                var xmlhttp = new XMLHttpRequest();
                                                xmlhttp.onreadystatechange = function() {
                                                    if (this.readyState === 4 && this.status === 200) {
                                                        document.getElementById("resultsEst").innerHTML = this.responseText;
                                                    }
                                                };
                                                xmlhttp.open("GET", "searchProg.php?searchWord=" + input, true);
                                                xmlhttp.send();
                                            }

                                            function selectResultEst(event) {
                                                if (event.target.tagName === "LI") {
                                                    var selectedText = event.target.textContent;
                                                    var selectedCodigo = event.target.getAttribute("NOMBRE");
                                                    document.getElementById("programEst").value = selectedCodigo;
                                                    document.getElementById("programaSeleccionado").value = selectedCodigo;
                                                    document.getElementById("resultsEst").innerHTML = "";
                                                }
                                            }
                                        </script>
                                        <input type="hidden" id="programaSeleccionado" name="programaSeleccionado">
                                    </div>

                                    <button type="submit" class="btn btn-info">Registrar Graduando</button>
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