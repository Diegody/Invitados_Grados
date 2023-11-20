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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ceremonias</title>

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
                                <a class="active-menu" href="panel-fec.php"><i class="fa fa-calendar-plus"></i>Ceremonias</a>
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
                        <h1 class="page-head-line">CEREMONIAS</h1>
                        <h1 class="page-subhead-line">En esta sección, tiene la opción de establecer las fechas y horas correspondientes para las ceremonias de graduación.
                        </h1>

                    </div>
                </div>
                <!-- /. ROW  -->

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Crear Fecha y Hora
                            </div>
                            <div class="panel-body">
                                <div id="wizard">
                                    <div class="panel-body page-subhead-line2">
                                        <form role="form" method="POST" action="crearCeremonia.php">
                                            <div class="form-group">
                                                <label>ID de la Ceremonia</label>
                                                <input id="id_ceremonia" name="id_ceremonia" class="form-control" type="number" min="0" step="1" max="9999999999" required>
                                                <p class="help-block">Identificador único para cada ceremonia.</p>
                                            </div>
                                            <div class="form-group">
                                                <label>Nombre de la Ceremonia</label>
                                                <input id="nombreCeremonia" name="nombreCeremonia" class="form-control" type="text" maxlength="50">
                                                <p class="help-block">Nombre asignado para la ceremonia.</p>
                                            </div>
                                            <div class="form-group">
                                                <label>Fecha de la Ceremonia</label>
                                                <input id="fecCeremonia" name="fecCeremonia" class="form-control" type="date" required>
                                                <p class="help-block">Fecha asignada para la ceremonia.</p>
                                            </div>
                                            <div class="form-group">
                                                <label>Hora de la Ceremonia</label>
                                                <input id="horaCeremonia" name="horaCeremonia" class="form-control" type="time" required>
                                                <p class="help-block">Hora asignada para la ceremonia.</p>
                                            </div>
                                            <div class="form-group">
                                                <label>Descripción de la Ceremonia</label>
                                                <textarea id="descCeremonia" name="descCeremonia" class="form-control" rows="4" maxlength="200"></textarea>
                                                <p class="help-block">Descripción opcional sobre la ceremonia.</p>
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
                                                <label>Cantidad de Invitados</label>
                                                <input id="cantInv" name="cantInv" class="form-control" type="number" min="0" step="1" max="10" required>
                                                <p class="help-block">Número de invitados que va a tener cada graduando en la Ceremonia.</p>
                                            </div>
                                            <div class="form-group" hidden="true">
                                                <input id="estCeremonia" name="estCeremonia" class="form-control" type="text" value="HABILITADO" require readonly></input>
                                            </div>
                                            <button type="submit" class="btn btn-info">Crear Ceremonía</button>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Eliminar Ceremonia
                                </div>
                                <div class="panel-body">
                                    <div id="wizard">
                                        <div class="panel-body">
                                            <form role="form" method="POST" action="eliminarCeremonia.php">
                                                <div class="form-group">
                                                    <label>ID de la Ceremonia</label>
                                                    <input id="id_deleteC" name="id_deleteC" class="form-control" type="number" min="0" step="1" require>
                                                    <p class="help-block">Se eliminará la ceremonia y sus datos (aplica solo para casos especiales en los cuales no hay graduandos vinculados a esta ceremonia).</p>
                                                </div>
                                                <button type="submit" class="btn btn-danger">Eliminar Ceremonía</button>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        </div> -->

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Ceremonias Creadas
                            </div>
                            <div class="panel-body">
                                <div id="wizard">
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table id="tablaCeremonias" class="table table-striped table-bordered table-hover page-subhead-line2">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Nombre</th>
                                                        <th>Fecha</th>
                                                        <th>Hora</th>
                                                        <th>Descripción</th>
                                                        <th>Invitados</th>
                                                        <th>Estado</th>
                                                        <th>Ciclo</th>
                                                        <th>Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    try {
                                                        $query = "SELECT *  FROM academico.GI_CEREMONIAS_TB";
                                                        $result = $dbi->Execute($query);

                                                        if (!$result) {
                                                            echo 'Error en la consulta: ' . $dbi->ErrorMsg();
                                                        } else {
                                                            while (!$result->EOF) {
                                                                $row = $result->fields;
                                                                echo '<tr>';
                                                                echo '<td>' . htmlspecialchars($row['ID_CEREMONIA']) . '</td>';
                                                                echo '<td>' . htmlspecialchars($row['NOMBRE']) . '</td>';
                                                                echo '<td>' . htmlspecialchars($row['FECHA']) . '</td>';
                                                                echo '<td>' . htmlspecialchars($row['HORA']) . '</td>';
                                                                echo '<td>' . htmlspecialchars($row['DESCRIPCION']) . '</td>';
                                                                echo '<td>' . htmlspecialchars($row['CANT_INV']) . '</td>';
                                                                echo '<td>' . htmlspecialchars($row['ESTADO']) . '</td>';
                                                                echo '<td>' . htmlspecialchars($row['CICLO']) . '</td>';
                                                                echo '<td>'; ?>
                                                                <center>
                                                                    <button type="button" class="btn btn-info edit-button" data-id="<?php echo $row['ID_CEREMONIA']; ?>" data-nombre="<?php echo $row['NOMBRE']; ?>" data-fecha="<?php echo $row['FECHA']; ?>" data-hora="<?php echo $row['HORA']; ?>" data-descripcion="<?php echo $row['DESCRIPCION']; ?>" data-cant="<?php echo $row['CANT_INV']; ?>"><i class="fas fa-pencil-alt"></i></button>


                                                                    <?php
                                                                    $queryy = "SELECT * FROM academico.GI_CEREMONIAS_TB WHERE ID_CEREMONIA = '" . $row['ID_CEREMONIA'] . "'";
                                                                    $resultt = $dbi->Execute($queryy);

                                                                    // Verificamos si obtuvimos algún resultado y si su estado es "HABILITADO"
                                                                    if ($resultt && !$resultt->EOF && $resultt->fields['ESTADO'] == 'HABILITADO') {
                                                                        echo '<button type="button" class="btn btn-danger disable-button" data-id="' . $row['ID_CEREMONIA'] . '"><i class="fas fa-ban"></i></button>';
                                                                    } else {
                                                                        echo '<button type="button" class="btn btn-success enable-button" data-id="' . $row['ID_CEREMONIA'] . '"><i class="fas fa-check"></i></button>';
                                                                    }
                                                                    echo '</td>';

                                                                    ?>
                                                                </center>
                                                    <?php
                                                                echo '</tr>';
                                                                $result->MoveNext();
                                                            }
                                                            $result->Close();
                                                        }
                                                    } catch (Exception $e) {
                                                        echo 'Error: ' . $e->getMessage();
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                            <div id="edit-modal" class="modal">
                                                <div class="modal-content">
                                                    <span class="close">&times;</span>
                                                    <h2><b>Editar Ceremonia</b></h2>
                                                    <form id="edit-form" method="POST" action="editarCeremonia.php">
                                                        <div class="form-group" hidden="true">
                                                            <label>ID de la ceremonia</label>
                                                            <input id="edit-id" name="edit-id" class="form-control" type="number" min="0" step="1" readonly required>
                                                            <p class="help-block">Identificador único para cada ceremonia.</p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Nombre de la Ceremonia</label>
                                                            <input id="edit-nombre" name="edit-nombre" class="form-control" type="text">
                                                            <p class="help-block">Nombre asignado para la ceremonia.</p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Fecha de la Ceremonia</label>
                                                            <input id="edit-fecha" name="edit-fecha" class="form-control" type="date" require>
                                                            <p class="help-block">Fecha asignada para la ceremonia.</p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Hora de la Ceremonia</label>
                                                            <input id="edit-hora" name="edit-hora" class="form-control" type="time" require>
                                                            <p class="help-block">Hora asignada para la ceremonia.</p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Descripción de la Ceremonia</label>
                                                            <textarea id="edit-descripcion" name="edit-descripcion" class="form-control" rows="4" maxlength="200"></textarea>
                                                            <p class="help-block">Descripción opcional sobre la ceremonia.</p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Cantidad de Invitados</label>
                                                            <input id="edit-cant" name="edit-cant" class="form-control" type="number" min="0" step="1" max="10" required>
                                                            <p class="help-block">Número de invitados permitidos en la Ceremonia.</p>
                                                        </div>
                                                        <button type="submit" class="btn btn-info">Editar Ceremonía</button>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.enable-button').click(function() {
                var idCeremonia = $(this).data('id');
                $.ajax({
                    url: 'habilitar_ceremonia.php',
                    type: 'POST',
                    data: {
                        idCeremonia: idCeremonia
                    },
                    success: function(response) {
                        if (response === 'success') {
                            alert('Ceremonia habilitada con éxito.');
                            window.location = 'panel-fec.php'
                            // alert('Ceremonia habilitada con éxito');
                            // Habilitar la fila: quitar la clase 'disabled'
                            $('tr[data-id="' + idCeremonia + '"]').removeClass('disabled');
                            // Habilitar el botón "Deshabilitar" y deshabilitar el botón "Habilitar"
                            $('tr[data-id="' + idCeremonia + '"] .disable-button').prop('disabled', false);
                            $('tr[data-id="' + idCeremonia + '"] .enable-button').prop('disabled', true);
                        } else {
                            alert('Error al habilitar la ceremonia.');
                            window.location = 'panel-fec.php';
                            // alert('Error al habilitar la ceremonia');
                        }
                    }
                });
            });

            $('.disable-button').click(function() {
                var idCeremonia = $(this).data('id');
                $.ajax({
                    url: 'deshabilitar_ceremonia.php',
                    type: 'POST',
                    data: {
                        idCeremonia: idCeremonia
                    },
                    success: function(response) {
                        if (response === 'success') {
                            alert('Ceremonia deshabilitada con éxito.');
                            window.location = 'panel-fec.php'
                            // alert('Ceremonia deshabilitada con éxito');
                            // Deshabilitar la fila: agregar la clase 'disabled'
                            $('tr[data-id="' + idCeremonia + '"]').addClass('disabled');
                            // Habilitar el botón "Habilitar" y deshabilitar el botón "Deshabilitar"
                            $('tr[data-id="' + idCeremonia + '"] .disable-button').prop('disabled', true);
                            $('tr[data-id="' + idCeremonia + '"] .enable-button').prop('disabled', false);
                        } else {
                            alert('Error al deshabilitar la ceremonia.');
                            window.location = 'panel-fec.php';
                            // alert('Error al deshabilitar la ceremonia');
                        }
                    }
                });
            });
        });
    </script>


    <!-- FILTRO-->
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
            $('#tablaCeremonias').DataTable({
                "language": {
                    "url": "assets/js/dataTables.es.js"
                }
            });

        });
    </script>

    <script>
        $(document).ready(function() {
            $(".edit-button").click(function() {
                var id = $(this).data("id");
                var modal = $("#edit-modal");
                var editForm = $("#edit-form");
                var button = $(this);

                var id = button.data("id");
                var nombre = button.data("nombre");
                var fecha = button.data("fecha");
                var hora = button.data("hora");
                var descripcion = button.data("descripcion");
                var cant = button.data("cant");


                var editId = $("#edit-id");
                var editNombre = $("#edit-nombre");
                var editFecha = $("#edit-fecha");
                var editHora = $("#edit-hora");
                var editDescripcion = $("#edit-descripcion");
                var editCant = $("#edit-cant");

                // Configurar el formulario con los datos de la fila
                editId.val(id);
                editNombre.val(nombre);
                editFecha.val(fecha);
                editHora.val(hora);
                editDescripcion.val(descripcion);
                editCant.val(cant);

                modal.css("display", "block");

                // Configurar el formulario con los datos correspondientes
                // Puedes cargar los datos de la ceremonia en el formulario aquí

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
        });
    </script>
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