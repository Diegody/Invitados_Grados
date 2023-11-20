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

<!DOCTYPE php>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Invitados</title>

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
    <!-- SWEETALERT2-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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
                <a class="navbar-brand" href="index.php">PANEL SEGURIDAD</a>
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
                        <a href="lectorqr.php"><i class="fa fa-qrcode"></i>Lector QR</a>
                    </li>
                    <li>
                        <a class="active-menu" href="invitados.php"><i class="fa fa-table"></i>Invitados</a>
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
                        <h1 class="page-head-line">INVITADOS REGISTRADOS</h1>
                        <h1 class="page-subhead-line">En este apartado se podrá vizualizar los invitados que pertenecerán a las ceremonias, en la cual también se verá reflejada la respectiva ceremonia</h1>

                    </div>
                </div>
                <!-- /. ROW  -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Graduandos Actuales
                            </div>
                            <div class="panel-body">

                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table id="tablaInvitados2" class="table table-striped table-bordered table-hover page-subhead-line2">
                                            <thead>
                                                <tr>
                                                    <th>Registro</th>
                                                    <th>Documento Invitado</th>
                                                    <th>Invitado</th>
                                                    <th>Ceremonia</th>
                                                    <th>Fecha</th>
                                                    <th>Hora</th>
                                                    <th>Documento Graduando</th>
                                                    <th>Graduando</th>
                                                    <th>Ingreso</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                try {
                                                    $query = "SELECT I.ID_REGISTRO, I.DOCUMENTO_INV, I.NOMBRES || ' ' || I.APELLIDOS AS INVITADO, C.NOMBRE || ' ' || V.NOMBRE AS CEREMONIA, C.FECHA, C.HORA, R.DOCUMENTO_GRADUANDO, G.NOMBRES || ' ' || G.APELLIDOS AS GRADUANDO FROM academico.GI_INVITADOS_TB I
                                                        JOIN academico.GI_RELACION_TB R ON I.ID_RELACION = R.ID_RELACION JOIN academico.GI_CEREMONIAS_TB C ON C.ID_CEREMONIA = R.ID_CEREMONIA 
                                                        JOIN academico.GI_GRADUANDOS_TB G ON G.DOCUMENTO_GRADUANDO = R.DOCUMENTO_GRADUANDO JOIN academico.V_PLANES V ON V.PLAN_ASIS = R.ID_VPLANES WHERE G.PERFIL = '2'";
                                                    $result = $dbi->Execute($query);

                                                    if (!$result) {
                                                        echo 'Error: ' . $dbi->ErrorMsg();
                                                    } else {
                                                        while (!$result->EOF) {
                                                            $row = $result->fields;
                                                            echo '<tr>';
                                                            echo '<td>' . $row['ID_REGISTRO'] . '</td>';
                                                            echo '<td>' . $row['DOCUMENTO_INV'] . '</td>';
                                                            echo '<td>' . $row['INVITADO'] . '</td>';
                                                            echo '<td>' . $row['CEREMONIA'] . '</td>';
                                                            echo '<td>' . $row['FECHA'] . '</td>';
                                                            echo '<td>' . $row['HORA'] . '</td>';
                                                            echo '<td>' . $row['DOCUMENTO_GRADUANDO'] . '</td>';
                                                            echo '<td>' . $row['GRADUANDO'] . '</td>';
                                                            echo '<td>'; ?>
                                                            <center>
                                                                <?php
                                                                $queryy = "SELECT * FROM academico.GI_INVITACIONES_TB WHERE ID_REGISTRO = {$row['ID_REGISTRO']}";
                                                                $resultt = $dbi->Execute($queryy);
                                                                echo '<div class="button-icon-wrapper">';
                                                                if ($resultt && !$resultt->EOF && $resultt->fields['ESTADO'] == 'INACTIVO') {
                                                                    echo '<i class="fa-solid fa-circle-check" style="color: #30a211;"></i>';
                                                                } else {
                                                                    echo '<button type="button" data-id="' . $row['ID_REGISTRO'] . '" data-documento="' . $row['DOCUMENTO_INV'] . '" class="btn btn-warning change-button"><i class="fa-solid fa-repeat" style="color: #000000;"></i></button>';
                                                                    echo ' <i class="fa-solid fa-circle-pause" style="color: #db0606;"></i>';
                                                                }
                                                                echo '</div>';
                                                                ?>
                                                            </center><?php
                                                                        echo '</tr>';
                                                                        $result->MoveNext();
                                                                    }
                                                                }
                                                            } catch (PDOException $e) {
                                                                echo 'Error: ' . $e->getMessage();
                                                            }
                                                                        ?>
                                            </tbody>
                                            <h4 class="page-subhead-line2"><i class="fa-solid fa-circle-check" style="color: #30a211;"></i> Invitado Ingresado ﾠﾠ<i class="fa-solid fa-circle-pause" style="color: #db0606;"></i> Invitado NO Ingresado ﾠﾠ<button type="button" class="btn btn-warning"><i class="fa-solid fa-repeat" style="color: #000000;"></i></button> Permitir Acceso Invitado</h4>
                                        </table>
                                    </div>
                                </div>
                            </div>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
            $('#tablaInvitados2').DataTable({
                "language": {
                    "url": "assets/js/dataTables.es.js"
                },
                "columnDefs": [{
                    "targets": [0], // Índice de la columna a ocultar (empezando desde 0)
                    "visible": false
                }]
            });

            $(".change-button").click(function() {
                var id_registro = $(this).data('id');
                var documento_inv = $(this).data('documento');

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¿Permitir el acceso del invitado a la universidad?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#5cb85c',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, Cambiar!',
                    cancelButtonText: 'No, Cancelar!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si el usuario confirma, ejecuta el código de AJAX.

                        // Desaparecer el contenedor del botón y mostrar el ícono inmediatamente.
                        $(this).closest('.button-icon-wrapper').hide();
                        $('<i class="fa-solid fa-circle-check" style="color: #30a211;"></i>').insertAfter($(this).closest('.button-icon-wrapper'));

                        $.ajax({
                            url: 'estado_invitacion.php',
                            type: 'POST',
                            data: {
                                'id': id_registro,
                                'documento': documento_inv
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: '¡Éxito!',
                                        text: response.message,
                                        icon: 'success',
                                        confirmButtonColor: '#5bc0de',
                                        confirmButtonText: 'De acuerdo'
                                    });
                                } else {
                                    Swal.fire(
                                        'Error',
                                        response.message,
                                        'error'
                                    );
                                    // Si hay un error, vuelve a mostrar el contenedor del botón y oculta el nuevo ícono.
                                    $(".change-button[data-id='" + id_registro + "']").closest('.button-icon-wrapper').show();
                                    $(".change-button[data-id='" + id_registro + "']").closest('.button-icon-wrapper').next(".fa-solid.fa-circle-check").remove();
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                Swal.fire(
                                    'Error',
                                    'Error al procesar la solicitud: ' + errorThrown,
                                    'error'
                                );
                                // Si hay un error, vuelve a mostrar el contenedor del botón y oculta el nuevo ícono.
                                $(".change-button[data-id='" + id_registro + "']").closest('.button-icon-wrapper').show();
                                $(".change-button[data-id='" + id_registro + "']").closest('.button-icon-wrapper').next(".fa-solid.fa-circle-check").remove();
                            }
                        });
                    }
                });
            });
        });
    </script>
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
