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
    <title>Registrar Usuario</title>

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
                        <a class="active-menu" href="#"><i class="fa fa-circle-plus"></i>Registrar<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="formG.php"><i class="fa fa-graduation-cap"></i>Graduandos</a>
                            </li>
                            <li>
                                <a class="active-menu" href="formU.php"><i class="fa fa-key"></i>Usuarios</a>
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
                        <h1 class="page-head-line">REGISTRO DE USUARIOS</h1>
                        <h1 class="page-subhead-line">En esta sección, puede registrar a los usuarios según los roles designados para administrar o utilizar el servicio de invitaciones a las ceremonias de graduación.</h1>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            Registrar Usuarios
                        </div>
                        <div class="panel-body">
                            <form role="form" method="POST" action="registrarUser.php">
                                <div class="form-group">
                                    <label>Tipo Documento</label><br>
                                    <select id="tipo_identificacion" name="tipo_identificacion" class="option-label" required>
                                        <option value="CC">Cédula de Ciudadanía</option>
                                        <option value="TI">Tarjeta de Indetidad</option>
                                        <option value="CE">Cédula de Extranjería</option>
                                        <option value="PA">Pasaporte</option>
                                        <option value="PEP">Permiso Especial de Permanencia</option>
                                    </select>
                                    <p class="help-block">Tipo de Documento del Usuario.</p>
                                </div>
                                <div class="form-group">
                                    <label>Documento</label>
                                    <input id="documento_user" name="documento_user" class="form-control" type="number" min="0" step="1" max="9999999999" required>
                                    <p class="help-block">Documento del usuario.</p>
                                </div>
                                <div class="form-group">
                                    <label>Nombre(s)</label>
                                    <input id="nombres" name="nombres" class="form-control" type="text" maxlength="80" required>
                                    <p class="help-block">Nombre(s) del usuario.</p>
                                </div>
                                <div class="form-group">
                                    <label>Apellidos</label>
                                    <input id="apellidos" name="apellidos" class="form-control" type="text" maxlength="80" required>
                                    <p class="help-block">Apellidos del usuario.</p>
                                </div>
                                <div class="form-group">
                                    <label>Contraseña</label>
                                    <input id="clave" name="clave" class="form-control" type="password" maxlength="80" required>
                                    <p class="help-block">Contraseña del usuario</p>
                                </div>
                                <div class="form-group">
                                    <label>Perfil</label>
                                    <select id="tipo_rol" name="tipo_rol" class="option-label" required>
                                        <option value="1">Administrador</option>
                                        <option value="3">Seguridad</option>
                                    </select>
                                    <p class="help-block">Perfil que tendrá el usuario (recuerde que dependiendo del perfil, el usuario tendrá más o menos privilegios).</p>
                                </div>
                                <button type="submit" class="btn btn-danger">Registrar Usuario</button>

                            </form>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                Usuarios Creados
                            </div>
                            <div class="panel-body">
                                <div id="wizard">
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table id="tablaCeremonias" class="table table-striped table-bordered table-hover page-subhead-line2">
                                                <thead>
                                                    <tr>
                                                        <th>Tipo</th>
                                                        <th>Documento</th>
                                                        <th>Nombre(s)</th>
                                                        <th>Apellidos</th>
                                                        <th>Clave</th>
                                                        <th>Perfil</th>
                                                        <th>Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    try {
                                                        $query = "SELECT * FROM academico.GI_GRADUANDOS_TB WHERE PERFIL = '1' OR PERFIL = '3'";
                                                        $result = $dbi->Execute($query);

                                                        if (!$result) {
                                                            echo 'Error en la consulta: ' . $dbi->ErrorMsg();
                                                        } else {
                                                            while (!$result->EOF) {
                                                                $row = $result->fields;
                                                                echo '<tr>';
                                                                echo '<td>' . htmlspecialchars($row['TIPO_IDENTIFICACION']) . '</td>';
                                                                echo '<td>' . htmlspecialchars($row['DOCUMENTO_GRADUANDO']) . '</td>';
                                                                echo '<td>' . htmlspecialchars($row['NOMBRES']) . '</td>';
                                                                echo '<td>' . htmlspecialchars($row['APELLIDOS']) . '</td>';
                                                                echo '<td>' . htmlspecialchars($row['CLAVE']) . '</td>';
                                                                if ($row['PERFIL'] == '1') {
                                                                    echo '<td>ADMINISTRADOR</td>';
                                                                } elseif ($row['PERFIL'] == '3') {
                                                                    echo '<td>SEGURIDAD</td>';
                                                                } else {
                                                                    echo '<td>' . htmlspecialchars($row['PERFIL']) . '</td>';
                                                                }
                                                                '</td>';
                                                                echo '<td>'; ?>
                                                                <center>
                                                                    <button type="button" class="btn btn-info edit-button" data-tipo="<?php echo $row['TIPO_IDENTIFICACION']; ?>" data-id="<?php echo $row['DOCUMENTO_GRADUANDO']; ?>" data-nombre="<?php echo $row['NOMBRES']; ?>" data-apellido="<?php echo $row['APELLIDOS']; ?>" data-clave="<?php echo $row['CLAVE']; ?>" data-perfil="<?php echo $row['PERFIL']; ?>">
                                                                        <i class="fas fa-pencil-alt"></i></button>

                                                                    <button data-id="<?php echo $row['DOCUMENTO_GRADUANDO']; ?>" class="btn btn-danger del-btn">
                                                                        <i class="fa-solid fa-trash" style="color: #FFFF;"></i>
                                                                    </button>
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
                                                    <h2><b>Editar Usuario</b></h2>
                                                    <form id="edit-form" method="POST" action="editarUser.php">
                                                        <div class="form-group">
                                                            <label>Tipo Documento</label><br>
                                                            <select id="tipo_identificacionE" name="tipo_identificacionE" class="option-label" required>
                                                                <option value="CC">Cédula de Ciudadanía</option>
                                                                <option value="TI">Tarjeta de Indetidad</option>
                                                                <option value="CE">Cédula de Extranjería</option>
                                                                <option value="PA">Pasaporte</option>
                                                                <option value="PEP">Permiso Especial de Permanencia</option>
                                                            </select>
                                                            <p class="help-block">Tipo de Documento del Usuario.</p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Documento</label>
                                                            <input id="documento_userE" name="documento_userE" class="form-control" type="number" min="0" step="1" required readonly>
                                                            <p class="help-block">Documento del usuario.</p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Nombre(s)</label>
                                                            <input id="nombresE" name="nombresE" class="form-control" type="text" maxlength="80" required>
                                                            <p class="help-block">Nombre(s) del usuario.</p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Apellidos</label>
                                                            <input id="apellidosE" name="apellidosE" class="form-control" type="text" maxlength="80" required>
                                                            <p class="help-block">Apellidos del usuario.</p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Contraseña</label>
                                                            <input id="claveE" name="claveE" class="form-control" type="password" maxlength="80" required>
                                                            <p class="help-block">Contraseña del usuario</p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Perfil</label>
                                                            <select id="tipo_rolE" name="tipo_rolE" class="option-label" required>
                                                                <option value="1">Administrador</option>
                                                                <option value="3">Seguridad</option>
                                                            </select>
                                                            <p class="help-block">Perfil que tendrá el usuario (recuerde que dependiendo del perfil, el usuario tendrá más o menos privilegios).</p>
                                                        </div>
                                                        <button type="submit" class="btn btn-info">Editar Usuario</button>
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
                var clave = button.data("clave");
                var perfil = button.data("perfil");

                var editTipo = $("#tipo_identificacionE");
                var editId = $("#documento_userE");
                var editNombre = $("#nombresE");
                var editApellido = $("#apellidosE");
                var editClave = $("#claveE");
                var editPerfil = $("#tipo_rolE");

                // Configurar el formulario con los datos de la fila
                editTipo.val(tipo);
                editId.val(id);
                editNombre.val(nombre);
                editApellido.val(apellido);
                editClave.val(clave);
                editPerfil.val(perfil);

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

        $('.del-btn').click(function(e) {
            e.preventDefault(); // Prevenir el comportamiento por defecto del botón
            var documento = $(this).attr('data-id'); // Obtener el valor del atributo data-id
            eliminarUsuario(documento);

            function eliminarUsuario(documento) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¿Deseas eliminar este usuario?",
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
                            url: 'eliminarUser.php',
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
                                    }).then(() => {
                                        location.reload(); // Refrescar toda la página después del alert
                                    });

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
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="assets/js/jquery.metisMenu.js"></script>
    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>


</body>

</html>