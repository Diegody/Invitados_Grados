<?php
session_start();
ob_start();
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

$timeout_duration = 1800;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header('Location: logout.php');
    exit();
}

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
    $id_est = $_SESSION['id_usuario'];
    $nombre_est = $_SESSION['nombre_usuario'];
    $apellido_est = $_SESSION['apellido_usuario'];
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Invitados</title>
    <link rel="manifest" href="../manifest.json">
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
    <!-- SWEEETALERT2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- Estilos-->
    <style>
        #tablaInvitados {
            font-size: 11px;
            /* Tamaño de fuente más pequeño */

            /* Ancho de la tabla reducido */
            /* Otros estilos personalizados que desees aplicar */
        }



        .btn-smaller {
            font-size: 8px;
            /* Tamaño de fuente más pequeño para los botones */
        }

        .contenedor-botones {
            display: flex;
            flex-direction: row;
        }

        @media screen and (max-width: 300px) {
            .contenedor-botones {
                flex-direction: column;

            }
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
                <a class="navbar-brand" href="index.php">BIENVENIDO GRADUANDO</a>
            </div>
            <div class="header-right">
                <div class="inner-text">
                    <i class="fa-solid fa-user-check" style="color: #ffffff;"></i><?php echo " " . $nombre_est . " " . $apellido_est; ?>
                    <br />
                    <small><i class="fa-solid fa-hashtag" style="color: #ffffff;"></i><?php echo " " . $id_est; ?></small>
                </div>
            </div>
        </nav>
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li>
                        <div class="user-img-div">
                            <center><img src="https://filosofia.net/cdf/uds/usb.png" class="img-thumbnail" /></center>>
                        </div>

                    </li>
                    <li>
                        <a href="index.php"><i class="fa fa-globe"></i>Inicio</a>
                    </li>
                    <li>
                        <a href="registrar.php"><i class="fa fa-circle-plus"></i>Registrar</a>
                    </li>
                    <li>
                        <a class="active-menu" href="invitados.php"><i class="fa fa-user-tie"></i>Invitados</a>
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
                        <h1 class="page-subhead-line">En esta sección, puede visualizar la lista de invitados que ha registrado, junto con la información de la ceremonia correspondiente.
                            De igual manera, puede realizar la edición o eliminación del invitado si no está seguro de la información ingresada.
                        </h1>

                    </div>
                </div>
                <!-- /. ROW  -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Invitados Actuales
                            </div>
                            <div class="panel-body">
                                <div id="wizard">
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <form method="post" action="correo.php">
                                                <ul class="list-inline">
                                                    <li>
                                                        <button type="button" class="btn btn-info btn-smaller" disabled>
                                                            <i class="fas fa-pencil-alt" style="pointer-events: none;"></i>
                                                        </button>
                                                        Editar Invitado
                                                    </li>
                                                    <li>
                                                        <button class="btn btn-danger btn-smaller" disabled>
                                                            <i class="fa-solid fa-trash" style="color: #FFFF; pointer-events: none;"></i>
                                                        </button>
                                                        Eliminar Invitado
                                                    </li>
                                                    <li>
                                                        <button type="button" class="btn btn-success btn-smaller" disabled>
                                                            <i class="fa-solid fa-paper-plane" style="pointer-events: none;"></i>
                                                        </button>
                                                        Enviar invitación
                                                    </li>
                                                </ul>
                                                <center>
                                                    <table id="tablaInvitados" class="table table-striped table-bordered table-hover page-subhead-line2">
                                                        <thead>
                                                            <tr>
                                                                <th class="relacion-column">ID Registro</th>
                                                                <th>Tipo</th>
                                                                <th>Documento</th>
                                                                <th>Nombre(s)</th>
                                                                <th>Apellidos</th>
                                                                <th>Correo</th>
                                                                <th>Ceremonia</th>
                                                                <th>Fecha</th>
                                                                <th>Hora</th>
                                                                <th class="relacion-column">Relación</th>
                                                                <th>Acción</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            <?php
                                                            $consulta = "SELECT I.ID_REGISTRO, I.TIPO_IDENTIFICACION, I.DOCUMENTO_INV, I.NOMBRES, I.APELLIDOS, I.CORREO, C.NOMBRE || ' ' || V.NOMBRE AS CEREMONIA, C.FECHA, C.HORA, R.ID_RELACION
                                                            FROM academico.GI_INVITADOS_TB I JOIN academico.GI_RELACION_TB R ON I.ID_RELACION = R.ID_RELACION JOIN academico.GI_CEREMONIAS_TB C ON R.ID_CEREMONIA = C.ID_CEREMONIA
                                                            JOIN academico.GI_GRADUANDOS_TB G ON G.DOCUMENTO_GRADUANDO = R.DOCUMENTO_GRADUANDO JOIN academico.V_PLANES V ON V.PLAN_ASIS = R.ID_VPLANES WHERE G.DOCUMENTO_GRADUANDO = '$id_est'";

                                                            try {
                                                                $result = $dbi->Execute($consulta);

                                                                if (!$result) {
                                                                    echo 'Error: ' . $dbi->ErrorMsg();
                                                                } else {
                                                                    while (!$result->EOF) {
                                                                        $row = $result->fields;
                                                                        echo '<tr>';
                                                                        echo '<td class="relacion-column">' . $row['ID_REGISTRO'] . '</td>';
                                                                        echo '<td>' . $row['TIPO_IDENTIFICACION'] . '</td>';
                                                                        echo '<td>' . $row['DOCUMENTO_INV'] . '</td>';
                                                                        echo '<td>' . $row['NOMBRES'] . '</td>';
                                                                        echo '<td>' . $row['APELLIDOS'] . '</td>';
                                                                        echo '<td>' . $row['CORREO'] . '</td>';
                                                                        echo '<td>' . $row['CEREMONIA'] . '</td>';
                                                                        echo '<td>' . $row['FECHA'] . '</td>';
                                                                        echo '<td>' . $row['HORA'] . '</td>';
                                                                        echo '<td class="relacion-column">' . $row['ID_RELACION'] . '</td>';
                                                                        echo '<td>';
                                                                        $queryy = "SELECT * FROM academico.GI_INVITACIONES_TB WHERE ID_REGISTRO = {$row['ID_REGISTRO']}";
                                                                        $resultt = $dbi->Execute($queryy);
                                                                        $cont = $resultt && $resultt->RecordCount();
                                                                        if ($cont > 0) {
                                                                            echo '<center>Enviado <i class="fa-solid fa-circle-check" style="color: #30a211;"></i></center>';
                                                                        } else {
                                                            ?><center>
                                                                                <?php
                                                                                echo '<div class="contenedor-botones">';
                                                                                echo '<button type="button" class="btn btn-info edit-button btn-smaller" 
                                                                                data-tipo="' . $row['TIPO_IDENTIFICACION'] . '" 
                                                                                data-reg="' . $row['ID_REGISTRO'] . '" 
                                                                                data-doc="' . $row['DOCUMENTO_INV'] . '" 
                                                                                data-nombre="' . $row['NOMBRES'] . '" 
                                                                                data-apellido="' . $row['APELLIDOS'] . '" 
                                                                                data-email="' . $row['CORREO'] . '" 
                                                                                data-ceremonia="' . $row['CEREMONIA'] . '" 
                                                                                data-fecha="' . $row['FECHA'] . '" 
                                                                                data-hora="' . $row['HORA'] . '">
                                                                                <i class="fas fa-pencil-alt"></i>
                                                                            </button>';

                                                                                echo ' <button data-id="' . $row['DOCUMENTO_INV'] . '" data-reg="' . $row['ID_REGISTRO'] . '" class="btn btn-danger del-btn btn-smaller">
                                                                            <i class="fa-solid fa-trash" style="color: #FFFF;"></i>
                                                                        </button>';

                                                                                echo '  <button type="button" class="btn btn-success enviar-btn btn-smaller" data-info="' . $row['DOCUMENTO_INV'] . ',' . $row['ID_RELACION'] . ',' . $row['ID_REGISTRO'] . '"><i class="fa-solid fa-paper-plane"></i></button>';
                                                                                ?></center><?php
                                                                                        }
                                                                                        echo '</div>';
                                                                                        echo '</td>';
                                                                                        echo '</tr>';
                                                                                        $result->MoveNext();
                                                                                    }
                                                                                }
                                                                            } catch (Exception $e) {
                                                                                echo 'Error: ' . $e->getMessage();
                                                                            }
                                                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </center>
                                            </form>
                                            <div id="edit-modal" class="modal">
                                                <div class="modal-content">
                                                    <span class="close">&times;</span>
                                                    <h2><b>Editar Invitado</b></h2>
                                                    <form id="edit-form" method="POST" action="editarInv.php">
                                                        <div class="form-group">
                                                            <label>Tipo Documento</label><br>
                                                            <select id="edit-tipo" name="edit-tipo" class="option-label" required>
                                                                <option value="CC">Cédula de Ciudadanía</option>
                                                                <option value="TI">Tarjeta de Indetidad</option>
                                                                <option value="CE">Cédula de Extranjería</option>
                                                                <option value="PA">Pasaporte</option>
                                                                <option value="PEP"> Permiso Especial de Permanencia</option>
                                                            </select>
                                                            <p class="help-block">Tipo de Documento del Invitado.</p>
                                                        </div>
                                                        <div class="form-group" hidden="true">
                                                            <label>ID Registro</label>
                                                            <input id="edit-reg" name="edit-reg" class="form-control" type="number" min="0" step="1" readonly required>
                                                            <p class="help-block">Documento del Graduando.</p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Documento</label>
                                                            <input id="edit-doc" name="edit-doc" class="form-control" type="number" min="0" step="1" required>
                                                            <p class="help-block">Documento del Graduando.</p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Nombre(s)</label>
                                                            <input id="edit-nombre" name="edit-nombre" class="form-control" type="text" required>
                                                            <p class="help-block">Nombres del Graduando.</p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Apellidos</label>
                                                            <input id="edit-apellido" name="edit-apellido" class="form-control" type="text" required>
                                                            <p class="help-block">Apellidos del Graduando.</p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Correo</label>
                                                            <input id="edit-email" name="edit-email" class="form-control" type="text" require>
                                                            <p class="help-block">Correo del Invitado.</p>
                                                        </div>
                                                        <button type="submit" class="btn btn-info">Editar Invitado</button>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var celdasRelacion = document.querySelectorAll(".relacion-column");
            for (var i = 0; i < celdasRelacion.length; i++) {
                celdasRelacion[i].style.display = "none";
            }
        });

        document.querySelectorAll('.enviar-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                let info = this.getAttribute('data-info');
                let currentButton = this; // Referencia al botón actual

                Swal.fire({
                    title: '¿Enviar Invitación?',
                    text: "Una vez enviada la invitación no se podrán realizar cambios.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#5bc0de',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, Enviar',
                    cancelButtonText: 'No, Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Usar AJAX para enviar los datos
                        $.post("correo.php", {
                            enviar: info
                        }, function(response) {
                            if (response === "success") {
                                // Cambiar el botón por el icono de "check"
                                currentButton.outerHTML = '<center>Enviado <i class="fa-solid fa-circle-check" style="color: #30a211;"></i></center>';
                                Swal.fire('¡Enviado!', 'El correo se envió con éxito', 'success').then(() => {
                                    location.reload(); // Refrescar toda la página después del alert
                                });
                            } else {
                                Swal.fire('¡Error!', 'Intente más tarde o contáctese con soporte.', 'error');
                            }
                        });


                    }
                });
            });
        });
        $(document).ready(function() {
            $(".edit-button").click(function() {
                var modal = $("#edit-modal");
                var editForm = $("#edit-form");
                var button = $(this);

                var tipo = button.data("tipo");
                var reg = button.data("reg");
                var doc = button.data("doc");
                var nombre = button.data("nombre");
                var apellido = button.data("apellido");
                var email = button.data("email");

                var editTipo = $("#edit-tipo");
                var editReg = $("#edit-reg");
                var editDoc = $("#edit-doc");
                var editNombre = $("#edit-nombre");
                var editApellido = $("#edit-apellido");
                var editEmail = $("#edit-email");


                editTipo.val(tipo);
                editReg.val(reg);
                editDoc.val(doc);
                editNombre.val(nombre);
                editApellido.val(apellido);
                editEmail.val(email);

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
            e.preventDefault();
            var btn = $(this);
            var documento = btn.attr('data-id');
            var registro = btn.attr('data-reg');
            eliminarEstudiante(documento, registro);


            function eliminarEstudiante(documento, registro) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¿Deseas eliminar este invitado?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, Eliminar!',
                    cancelButtonText: 'No, Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: 'eliminarInv.php',
                            data: {
                                'documento': documento,
                                'registro': registro
                            },
                            success: function(response) {
                                var jsonResponse = JSON.parse(response);
                                if (jsonResponse.success) {
                                    Swal.fire('¡Éxito!', jsonResponse.message, 'success').then(() => {
                                        location.reload(); // Refrescar toda la página después del alert
                                    });;
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
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let editForm = document.getElementById("edit-form");

            editForm.addEventListener("submit", function(event) {
                event.preventDefault(); // Evitar el comportamiento predeterminado de recargar la página.

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¿Quieres actualizar los datos del graduando?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, actualizar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si se confirma, enviar el formulario usando AJAX.
                        sendData();
                    }
                });
            });

            function sendData() {
                let formData = new FormData(editForm);

                fetch('editarInv.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Aquí debes manejar la respuesta del servidor, por ejemplo:
                        if (data.success) {
                            Swal.fire('¡Actualizado!', 'Los datos del invitado han sido actualizados.', 'success').then(() => {
                                location.reload(); // Refrescar toda la página después del alert
                            });
                        } else {
                            Swal.fire('Error', 'Hubo un error al actualizar. Por favor intenta nuevamente.', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Hubo un error al enviar los datos. Por favor intenta nuevamente.', 'error');
                    });
            }
        });
    </script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="assets/js/jquery.metisMenu.js"></script>
    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>


</body>

</html>