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
?>

<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Cantidad Invitados</title>

        <!-- BOOTSTRAP STYLES-->
        <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <!-- FONTAWESOME STYLES-->
        <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!--CUSTOM BASIC STYLES-->
        <link href="assets/css/basic.css" rel="stylesheet" />
        <!--CUSTOM MAIN STYLES-->
        <link href="assets/css/custom.css" rel="stylesheet" />
        <!-- GOOGLE FONTS-->
        <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <!-- DATATABLES-->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
        </link>
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        </link>
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
                            <a class="active-menu" href="#"><i class="fa fa-code"></i>Parámetros <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="panel-fec.php"><i class="fa fa-calendar-plus"></i>Ceremonias</a>
                                </li>
                                <li>
                                    <a class="active-menu" href="panel-select-cant.php"><i class="fa fa-edit"></i>Cantidad Invitados</a>
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
                            <h1 class="page-head-line">CANTIDAD DE INVITADOS</h1>
                            <h1 class="page-subhead-line">En esta sección, puede gestionar la cantidad de invitados, permitiendo que cada graduando tenga un número específico de invitados asignado.
                            </h1>

                        </div>
                    </div>
                    <!-- /. ROW  -->

                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Actualizar Cantidad de Invitados por Cada Graduando
                                    <a href="panel-select-cant.php" style="float: right;"><button class="btn btn-success">Volver</button></a>
                                </div>
                                <div class="panel-body">
                                    <div id="wizard">
                                        <div class="panel-body">
                                            <div class="table-responsive">
                                                <table id="tablaInvitadosx" class="table table-striped table-bordered table-hover page-subhead-line2">
                                                    <thead>
                                                        <tr>
                                                            <th>Relación</th>
                                                            <th>Documento</th>
                                                            <th>Graduando</th>
                                                            <th>ID Ceremonia</th>
                                                            <th>Ceremonia</th>
                                                            <th>ID Programa</th>
                                                            <th>Programa</th>
                                                            <th>Ciclo</th>
                                                            <th>Invitados</th>
                                                            <th>Acción</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $opcionSeleccionada1 = isset($_POST['opcS1']) ? $_POST['opcS1'] : null;
                                                        $opcionSeleccionada2 = isset($_POST['opcS2']) ? $_POST['opcS2'] : null;
                                                        try {
                                                            $query = "SELECT R.ID_RELACION, R.DOCUMENTO_GRADUANDO, G.NOMBRES || ' ' || G.APELLIDOS AS GRADUANDO, R.ID_CEREMONIA, C.NOMBRE AS CEREMONIA, V.PLAN_ASIS AS ID_PROGRAMA, V.NOMBRE AS PROGRAMA, G.CICLO, R.CANT_INV FROM
                                                            academico.GI_RELACION_TB R JOIN academico.GI_GRADUANDOS_TB G ON G.DOCUMENTO_GRADUANDO = R.DOCUMENTO_GRADUANDO 
                                                            JOIN academico.GI_CEREMONIAS_TB C ON C.ID_CEREMONIA = R.ID_CEREMONIA JOIN academico.V_PLANES V ON V.PLAN_ASIS = R.ID_VPLANES WHERE G.PERFIL = '2' AND G.CICLO = '$opcionSeleccionada1' OR C.ID_CEREMONIA = '$opcionSeleccionada2'";
                                                            $result = $dbi->Execute($query);

                                                            if (!$result) {
                                                                echo 'Error: ' . $dbi->ErrorMsg();
                                                            } else {
                                                                while (!$result->EOF) {
                                                                    $row = $result->fields;
                                                                    echo '<tr>';
                                                                    echo '<td>' . $row['ID_RELACION'] . '</td>';
                                                                    echo '<td>' . $row['DOCUMENTO_GRADUANDO'] . '</td>';
                                                                    echo '<td>' . $row['GRADUANDO'] . '</td>';
                                                                    echo '<td>' . $row['ID_CEREMONIA'] . '</td>';
                                                                    echo '<td>' . $row['CEREMONIA'] . '</td>';
                                                                    echo '<td>' . $row['ID_PROGRAMA'] . '</td>';
                                                                    echo '<td>' . $row['PROGRAMA'] . '</td>';
                                                                    echo '<td>' . $row['CICLO'] . '</td>';
                                                                    echo '<td>' . $row['CANT_INV'] . '</td>';
                                                                    echo '<td>' ?>
                                                                    <center>
                                                                        <button type="button" class="btn btn-success edit-button" data-tipo="<?php echo $row['ID_RELACION']; ?>" data-id="<?php echo $row['DOCUMENTO_GRADUANDO']; ?>" data-nombre="<?php echo $row['GRADUANDO']; ?>" data-apellido="<?php echo $row['ID_CEREMONIA']; ?>" data-correo="<?php echo $row['CEREMONIA']; ?>" data-id_est="<?php echo $row['CANT_INV']; ?>"><i class="fas fa-pencil-alt"></i></button>
                                                                    </center>
                                                                    </td>
                                                        <?php

                                                                    echo '</tr>';
                                                                    $result->MoveNext();
                                                                }
                                                            }
                                                        } catch (PDOException $e) {
                                                            echo 'Error: ' . $e->getMessage();
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                                <div id="edit-modal" class="modal page-subhead-line2">
                                                    <div class="modal-content ">
                                                        <span class="close">&times;</span>
                                                        <h3><b>Editar Cantidad de Invitados por Ceremonia y Graduando</b></h3>
                                                        <form id="edit-form" method="POST" action="cantidadInv.php">
                                                            <div class="form-group" hidden="true">
                                                                <label>ID Relación</label>
                                                                <input id="edit-tipo" name="edit-tipo" class="form-control" type="number" min="0" step="1" readonly required>
                                                                <p class="help-block">Identificador único de la relación.</p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Documento Graduando</label>
                                                                <input id="edit-id" name="edit-id" class="form-control" type="number" min="0" step="1" readonly required>
                                                                <p class="help-block">Documento del graduando.</p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Nombre(s)</label>
                                                                <input id="edit-nombre" name="edit-nombre" class="form-control" type="text" readonly>
                                                                <p class="help-block">Nombres del graduando.</p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>ID Ceremonia</label>
                                                                <input id="edit-apellido" name="edit-apellido" class="form-control" type="text" readonly>
                                                                <p class="help-block">Identificador único de la ceremonia.</p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Ceremonia</label>
                                                                <input id="edit-correo" name="edit-correo" class="form-control" type="email" readonly require>
                                                                <p class="help-block">Nombre de la ceremonia a la cual el graduando está vinculado.</p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Cantidad de Invitados</label>
                                                                <input id="edit-id_est" name="edit-id_est" class="form-control" type="number" min="0" step="1" require>
                                                                <p class="help-block">Cantidad de invitados permitidos para el graduando en la ceremonia.</p>
                                                            </div>
                                                            <button type="submit" class="btn btn-success">Editar Cantidad</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
            <!-- FILTRO: MOSTRAR DE DATOS EN BASE A UNA CANTIDAD -->
            <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
            <script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
            <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
            <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
            <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
            <script>
                $(document).ready(function() {
                    $.noConflict();
                    $('#tablaInvitadosx').DataTable({
                        "language": {
                            "url": "assets/js/dataTables.es.js"
                        },
                        "columnDefs": [{
                            "targets": [0], // Índice de la columna a ocultar (empezando desde 0)
                            "visible": false // Oculta la columna
                        }]
                    });
                    $('#tablaCantidady').DataTable({
                        "language": {
                            "url": "assets/js/dataTables.es.js"
                        }
                    });
                });
            </script>

            <!-- PASAR LOS DATOS AL FORMULARIO MODAL -->
            <script>
                $(document).ready(function() {
                    $(".edit-button").click(function() {
                        var id = $(this).data("id");
                        var modal = $("#edit-modal");
                        var editForm = $("#edit-form");
                        var button = $(this);

                        var tipo = button.data("tipo");
                        var id = button.data("id");
                        var nombre = button.data("nombre");
                        var apellido = button.data("apellido");
                        var correo = button.data("correo");
                        var id_est = button.data("id_est");
                        var id_rel = button.data("id_rel");


                        var editTipo = $("#edit-tipo");
                        var editId = $("#edit-id");
                        var editNombre = $("#edit-nombre");
                        var editApellido = $("#edit-apellido");
                        var editCorreo = $("#edit-correo");
                        var editId_est = $("#edit-id_est");
                        var editId_rel = $("#edit-id_rel");

                        editTipo.val(tipo);
                        editId.val(id);
                        editNombre.val(nombre);
                        editApellido.val(apellido);
                        editCorreo.val(correo);
                        editId_est.val(id_est);
                        editId_rel.val(id_rel);

                        modal.css("display", "block");
                        editId.val(id);
                        modal.css("display", "block");
                    });

                    $(".close").click(function() {
                        var modal = $("#edit-modal");
                        modal.css("display", "none");
                    });

                    $(window).click(function(event) {
                        var modal = $("#edit-modal");
                        if (event.target == modal[0]) {
                            modal.css("display", "none");
                        }
                    });

                    $(".close").click(function() {
                        var modal = $("#edit-modal2");
                        modal.css("display", "none");
                    });

                    $(window).click(function(event) {
                        var modal = $("#edit-modal2");
                        if (event.target == modal[0]) {
                            modal.css("display", "none");
                        }
                    });
                });
            </script>
            <script>
                $(document).ready(function() {
                    $(".botonEdit").click(function() {
                        // var idE = $(this).data("datoId");
                        var modalx = $("#modalE");
                        var editFormx = $("#formE");
                        var buttonx = $(this);

                        var idE = buttonx.data("datoid");
                        var nombreE = buttonx.data("datonombre");
                        var cantE = buttonx.data("datocant");

                        var EId = $("#datoId");
                        var ENombre = $("#datoNombre");
                        var ECant = $("#datoCant");

                        EId.val(idE);
                        ENombre.val(nombreE);
                        ECant.val(cantE);

                        modalx.css("display", "block");
                    });

                    $(".close").click(function() {
                        var modalx = $("#modalE");
                        modalx.css("display", "none");
                    });

                    $(window).click(function(event) {
                        var modalx = $("#modalE");
                        if (event.target == modalx[0]) {
                            modalx.css("display", "none");
                        }
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

</php>