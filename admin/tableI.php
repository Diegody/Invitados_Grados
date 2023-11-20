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
                                <a href="panel-select.php"><i class="fa fa-user-graduate"></i>Graduandos</span></a>
                            </li>
                            <li>
                                <a class="active-menu" href="panel-select-inv.php"><i class="fa fa-user-tie"></i>Invitados</a>
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
                        <h1 class="page-head-line">INVITADOS</h1>
                        <h1 class="page-subhead-line">En esta sección, puede visualizar los invitados actuales y su información asociada.</h1>

                    </div>
                </div>
                <!-- /. ROW  -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Invitados Actuales
                                <a href="panel-select-inv.php" style="float: right;"><button class="btn btn-success">Volver</button></a>
                            </div>
                            <div class="panel-body">
                                <div id="wizard">

                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table id="tablaInvitados" class="table table-striped table-bordered table-hover page-subhead-line2">
                                                <thead>
                                                    <tr>
                                                        <th>Tipo</th>
                                                        <th>Documento Invitado</th>
                                                        <th>Invitado</th>
                                                        <th>Correo</th>
                                                        <th>Documento Graduando</th>
                                                        <th>Graduando</th>
                                                        <th>ID Ceremonia</th>
                                                        <th>Ceremonia</th>
                                                        <th>Programa</th>
                                                        <th>Fecha</th>
                                                        <th>Hora</th>
                                                        <th>Relación</th>
                                                        <th>Ciclo</th>
                                                        <th>Registro</th>
                                                        <th>Invitación</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $opcionSeleccionada1 = isset($_POST['opcS1']) ? $_POST['opcS1'] : null;
                                                    $opcionSeleccionada2 = isset($_POST['opcS2']) ? $_POST['opcS2'] : null;
                                                    try {
                                                        $query = "SELECT I.TIPO_IDENTIFICACION, I.DOCUMENTO_INV, I.NOMBRES || ' ' || I.APELLIDOS AS INVITADO, I.CORREO, R.DOCUMENTO_GRADUANDO, 
                                                            G.NOMBRES || ' ' || G.APELLIDOS AS GRADUANDO, C.ID_CEREMONIA, C.NOMBRE AS CEREMONIA, V.NOMBRE AS PROGRAMA, C.FECHA, C.HORA, I.ID_RELACION, I.ID_REGISTRO, G.CICLO
                                                            FROM academico.GI_INVITADOS_TB I JOIN academico.GI_RELACION_TB R ON I.ID_RELACION = R.ID_RELACION 
                                                            JOIN academico.GI_GRADUANDOS_TB G ON G.DOCUMENTO_GRADUANDO =  R.DOCUMENTO_GRADUANDO JOIN academico.GI_CEREMONIAS_TB C ON C.ID_CEREMONIA = R.ID_CEREMONIA 
                                                            JOIN academico.V_PLANES V ON V.PLAN_ASIS = R.ID_VPLANES WHERE G.CICLO = '$opcionSeleccionada1' OR C.ID_CEREMONIA = '$opcionSeleccionada2'";
                                                        $result = $dbi->Execute($query);

                                                        if (!$result) {
                                                            echo 'Error: ' . $dbi->ErrorMsg();
                                                        } else {
                                                            while (!$result->EOF) {
                                                                $row = $result->fields;
                                                                echo '<tr>';
                                                                echo '<td>' . $row['TIPO_IDENTIFICACION'] . '</td>';
                                                                echo '<td>' . $row['DOCUMENTO_INV'] . '</td>';
                                                                echo '<td>' . $row['INVITADO'] . '</td>';
                                                                echo '<td>' . $row['CORREO'] . '</td>';
                                                                echo '<td>' . $row['DOCUMENTO_GRADUANDO'] . '</td>';
                                                                echo '<td>' . $row['GRADUANDO'] . '</td>';
                                                                echo '<td>' . $row['ID_CEREMONIA'] . '</td>';
                                                                echo '<td>' . $row['CEREMONIA'] . '</td>';
                                                                echo '<td>' . $row['PROGRAMA'] . '</td>';
                                                                echo '<td>' . $row['FECHA'] . '</td>';
                                                                echo '<td>' . $row['HORA'] . '</td>';
                                                                echo '<td>' . $row['ID_RELACION'] . '</td>';
                                                                echo '<td>' . $row['CICLO'] . '</td>';
                                                                echo '<td>' . $row['ID_REGISTRO'] . '</td>';
                                                                echo '<td>';
                                                    ?>
                                                                <center>
                                                                    <!-- <button type="button" class="btn btn-info edit-button" data-tipo="<?php echo $row['TIPO_IDENTIFICACION']; ?>" data-id="<?php echo $row['DOCUMENTO_INV']; ?>" data-nombre="<?php echo $row['NOMBRES']; ?>" data-apellido="<?php echo $row['APELLIDOS']; ?>" data-correo="<?php echo $row['CORREO']; ?>" data-id_est="<?php echo $row['DOCUMENTO_GRADUANDO']; ?>" data-id_rel="<?php echo $row['ID_RELACION']; ?>"><i class="fas fa-pencil-alt"></i></button> -->
                                                                    <?php
                                                                    $queryy = "SELECT * FROM academico.GI_INVITACIONES_TB WHERE ID_REGISTRO = {$row['ID_REGISTRO']}";
                                                                    $resultt = $dbi->Execute($queryy);
                                                                    $cont = $resultt && $resultt->RecordCount();
                                                                    if ($cont > 0) {
                                                                        echo '<i class="fa-solid fa-circle-check" style="color: #30a211;"></i>';
                                                                    } else {
                                                                        echo '<i class="fa-solid fa-circle-pause" style="color: #db0606;"></i>';
                                                                    }
                                                                    echo '</td>';
                                                                    ?>
                                                                </center><?php
                                                                            ?><?php

                                                                                    $result->MoveNext();
                                                                                }
                                                                            }
                                                                        } catch (PDOException $e) {
                                                                            echo 'Error: ' . $e->getMessage();
                                                                        }

                                                                        echo '</tr>'; ?>
                                                </tbody>
                                                <h4 class="page-subhead-lineButton"><i class="fa-solid fa-circle-check" style="color: #30a211;"></i> Invitación Enviada | <i class="fa-solid fa-circle-pause" style="color: #db0606;"></i> Invitación Sin Enviar</h4>

                                            </table>

                                        </div>
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
    <!-- /. footer  -->


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
            // Setup - add a text input to each footer cell
            $('#example thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#example thead');

            var table = $('#example').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
                initComplete: function() {
                    var api = this.api();

                    // For each column
                    api
                        .columns()
                        .eq(0)
                        .each(function(colIdx) {
                            // Set the header cell to contain the input element
                            var cell = $('.filters th').eq(
                                $(api.column(colIdx).header()).index()
                            );
                            var title = $(cell).text();
                            $(cell).html('<input type="text" placeholder="' + title + '" />');

                            // On every keypress in this input
                            $(
                                    'input',
                                    $('.filters th').eq($(api.column(colIdx).header()).index())
                                )
                                .off('keyup change')
                                .on('change', function(e) {
                                    // Get the search value
                                    $(this).attr('title', $(this).val());
                                    var regexr = '({search})'; //$(this).parents('th').find('select').val();

                                    var cursorPosition = this.selectionStart;
                                    // Search the column for that value
                                    api
                                        .column(colIdx)
                                        .search(
                                            this.value != '' ?
                                            regexr.replace('{search}', '(((' + this.value + ')))') :
                                            '',
                                            this.value != '',
                                            this.value == ''
                                        )
                                        .draw();
                                })
                                .on('keyup', function(e) {
                                    e.stopPropagation();

                                    $(this).trigger('change');
                                    $(this)
                                        .focus()[0]
                                        .setSelectionRange(cursorPosition, cursorPosition);
                                });
                        });
                },
            });
        });
        ////////
        $(document).ready(function() {
            $.noConflict();
            $('#tablaInvitados').DataTable({
                "language": {
                    "url": "assets/js/dataTables.es.js"
                },
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excel',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 7, 8, 9, 10, 12]
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 7, 8, 9, 10]
                        },
                        customize: function(doc) {
                            doc.styles.tableHeader = {
                                fillColor: '#ff7e02',
                                color: 'black',
                                alignment: 'center'
                            };
                        },
                        title: 'Datos Exportados de Invitados'
                    }
                ],
                "initComplete": function() {
                    this.api().columns().every(function() {
                        var column = this;
                        $('input', this.footer()).on('keyup change clear', function() {
                            if (column.search() !== this.value) {
                                var searchTerm = removeAccents(this.value);
                                column.search(searchTerm).draw();
                            }
                        });
                    });
                },
                "columnDefs": [{
                    "targets": [6, 11, 13], // Índice de la columna a ocultar (empezando desde 0)
                    "visible": false // Oculta la columna
                }]
            });
        });
    </script>

    <!-- /. FOOTER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->

    <!-- METISMENU SCRIPTS -->
    <script src="assets/js/jquery.metisMenu.js"></script>
    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
</body>

</html>