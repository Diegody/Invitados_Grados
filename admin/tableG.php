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
    <title>Graduandos</title>

    <!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/b00d11522a.js" crossorigin="anonymous"></script>
    <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!--CUSTOM BASIC STYLES-->
    <link href="assets/css/basic.css" rel="stylesheet" />
    <!--CUSTOM MAIN STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-head-line">Graduandos</h1>
                        <h1 class="page-subhead-line">En esta sección, puede observar a los graduandos ya registrados, junto con la información relacionada a su correspondiente ceremonia.</h1>

                    </div>
                </div>
                <!-- /. ROW  -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Graduandos Actuales
                                <a href="panel-select.php" style="float: right;"><button class="btn btn-success">Volver</button></a>
                            </div>
                            <div class="panel-body">
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table id="tablaGrados" class="table table-striped table-bordered table-hover page-subhead-line2">
                                            <thead>
                                                <tr>
                                                    <th>ID Relación</th>
                                                    <th>Tipo</th>
                                                    <th>Documento</th>
                                                    <th>Graduando</th>
                                                    <th>Facultad</th>
                                                    <th>Programa</th>
                                                    <th>ID Ceremonia</th>
                                                    <th>Ceremonia</th>
                                                    <th>Fecha</th>
                                                    <th>Hora</th>
                                                    <th>Ciclo</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $opcionSeleccionada1 = isset($_POST['opcS1']) ? $_POST['opcS1'] : null;
                                                $opcionSeleccionada2 = isset($_POST['opcS2']) ? $_POST['opcS2'] : null;
                                                try {
                                                    $query1 = "SELECT R.ID_RELACION, G.TIPO_IDENTIFICACION, R.DOCUMENTO_GRADUANDO, G.NOMBRES || ' ' || G.APELLIDOS AS GRADUANDO, V.FACULTAD, V.NOMBRE AS PROGRAMA, C.ID_CEREMONIA, C.NOMBRE AS CEREMONIA, C.FECHA, C.HORA, G.CICLO FROM academico.GI_RELACION_TB R 
                                                        JOIN academico.GI_GRADUANDOS_TB G ON R.DOCUMENTO_GRADUANDO = G.DOCUMENTO_GRADUANDO
                                                        JOIN academico.V_PLANES V ON R.ID_VPLANES = V.PLAN_ASIS JOIN academico.GI_CEREMONIAS_TB C ON C.ID_CEREMONIA = R.ID_CEREMONIA WHERE G.PERFIL = '2' AND G.CICLO = '$opcionSeleccionada1' OR C.ID_CEREMONIA = '$opcionSeleccionada2'";
                                                    $result1 = $dbi->Execute($query1);

                                                    if (!$result1) {
                                                        echo 'Error: ' . $dbi->ErrorMsg();
                                                    } else {
                                                        while (!$result1->EOF) {
                                                            $row1 = $result1->fields;
                                                ?>
                                                            <tr data-id="<?php echo $row1['DOCUMENTO_GRADUANDO']; ?>">
                                                                <td><?php echo $row1['ID_RELACION']; ?></td>
                                                                <td><?php echo $row1['TIPO_IDENTIFICACION']; ?></td>
                                                                <td><?php echo $row1['DOCUMENTO_GRADUANDO']; ?></td>
                                                                <td><?php echo $row1['GRADUANDO']; ?></td>
                                                                <td><?php echo $row1['FACULTAD']; ?></td>
                                                                <td><?php echo $row1['PROGRAMA']; ?></td>
                                                                <td><?php echo $row1['ID_CEREMONIA']; ?></td>
                                                                <td><?php echo $row1['CEREMONIA']; ?></td>
                                                                <td><?php echo $row1['FECHA']; ?></td>
                                                                <td><?php echo $row1['HORA']; ?></td>
                                                                <td><?php echo $row1['CICLO']; ?></td>
                                                                <td>
                                                                    <center>
                                                                        <button data-id="<?php echo $row1['DOCUMENTO_GRADUANDO']; ?>" class="btn btn-danger del-btn">
                                                                            <i class="fa-solid fa-trash" style="color: #FFFF;"></i>
                                                                        </button>
                                                                    </center>
                                                                </td>
                                                            </tr>
                                                <?php
                                                            $result1->MoveNext();
                                                        }
                                                    }
                                                } catch (Exception $e) {
                                                    echo 'Error: ' . $e->getMessage();
                                                }
                                                ?>
                                            </tbody>
                                        </table>
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
        <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->

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
                $('#tablaGrados').DataTable({
                    "columnDefs": [{
                        "targets": [0], // Índice de la columna a ocultar (empezando desde 0)
                        "visible": false // Oculta la columna
                    }],
                    "language": {
                        "url": "assets/js/dataTables.es.js"
                    },
                    dom: 'Bfrtip',
                    buttons: [{
                            extend: 'excel',
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5, 7, 8, 9, 10]
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
                            title: 'Datos Exportados de Graduandos'
                        }
                    ]
                });

                $(".edit-button").click(function() {
                    var modal = $("#edit-modal");
                    var editForm = $("#edit-form");
                    var button = $(this);

                    var tipo = button.data("tipo");
                    var rel = button.data("rel");
                    var doc = button.data("doc");
                    var nombre = button.data("nombre");
                    var apellido = button.data("apellido");
                    var fac = button.data("fac");
                    var prog = button.data("prog");
                    var cer = button.data("cer");

                    var editTipo = $("#edit-tipo");
                    var editRel = $("#edit-rel");
                    var editDoc = $("#edit-doc");
                    var editNombre = $("#edit-nombre");
                    var editApellido = $("#edit-apellido");
                    var editFac = $("#edit-fac");
                    var editProg = $("#edit-prog");
                    var editCer = $("#edit-cer");


                    editTipo.val(tipo);
                    editRel.val(rel);
                    editDoc.val(doc);
                    editNombre.val(nombre);
                    editApellido.val(apellido);
                    editFac.val(fac);
                    editProg.val(prog);
                    editCer.val(cer);

                    modal.css("display", "block");
                    editDoc.val(doc);
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

            });

            $('.del-btn').click(function(e) {
                e.preventDefault(); // Prevenir el comportamiento por defecto del botón
                var documento = $(this).attr('data-id'); // Obtener el valor del atributo data-id
                eliminarEstudiante(documento);

                function eliminarEstudiante(documento) {
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¿Deseas eliminar este graduando?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#5cb85c',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, Eliminar!',
                        cancelButtonText: 'No, Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: 'POST',
                                url: 'eliminarEst.php',
                                data: {
                                    'documento': documento
                                },
                                success: function(response) {
                                    var jsonResponse = JSON.parse(response);
                                    if (jsonResponse.success) {
                                        Swal.fire({
                                            title: '¡Éxito!',
                                            text: jsonResponse.message,
                                            icon: 'success',
                                            confirmButtonColor: '#5bc0de',
                                            confirmButtonText: 'De acuerdo'
                                        });
                                        // Elimina la fila
                                        $(`tr[data-id="${documento}"]`).remove();
                                    } else {
                                        Swal.fire('¡Error!', jsonResponse.message, 'error');
                                    }
                                },
                                error: function() {
                                    Swal.fire('¡Error!', 'Hubo un error en la solicitud.', 'error');
                                }
                            });
                        }
                    });
                }
            });

            $('#edit-form').submit(function(e) {
                e.preventDefault();

                // Puedes hacer validaciones adicionales aquí si lo deseas

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¿Deseas editar la información del graduando?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, Editar!',
                    cancelButtonText: 'No, Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Usar AJAX para enviar los datos
                        $.ajax({
                            type: "POST",
                            url: "editarUser.php",
                            data: $('#edit-form').serialize(),
                            success: function(response) {
                                var data = JSON.parse(response);
                                if (data.success) {
                                    Swal.fire('¡Éxito!', data.message, 'success').then(() => {
                                        $('#edit-form')[0].reset(); // Resetear formulario
                                    });
                                } else {
                                    Swal.fire('¡Error!', data.message, 'error');
                                }
                            },
                            error: function(error) {
                                Swal.fire('¡Error!', 'Ocurrió un error al enviar los datos.', 'error');
                            }
                        });
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

</html>