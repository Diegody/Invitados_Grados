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
    <title>Registrar</title>
    <link rel="manifest" href="../manifest.json">
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
    <!-- SWEEETALERT2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
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
                        <a class="active-menu" href="registrar.php"><i class="fa fa-circle-plus"></i>Registrar</a>
                    </li>
                    <li>
                        <a href="invitados.php"><i class="fa fa-user-tie"></i>Invitados</a>
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
                        <h1 class="page-head-line">REGISTRO DE INVITADOS</h1>
                        <h1 class="page-subhead-line">En esta sección, puede ingresar la información correspondiente al invitado.
                        </h1>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                Registrar Invitado
                            </div>
                            <div class="panel-body">
                                <form role="form" method="POST" action="registrarInv.php" id="registro-form">
                                    <div class="form-group">
                                        <label>Ceremonia.</label><br>
                                        <select id="opcionRel" name="opcionRel" class="option-label" required>
                                            <?php
                                            $sql = "SELECT DISTINCT R.ID_RELACION,  C.NOMBRE || ' / ' || C.FECHA || ' ' || C.HORA || ' / ' || V.NOMBRE AS INFO, R.CANT_INV FROM academico.GI_RELACION_TB R JOIN academico.V_PLANES V 
                                                ON V.PLAN_ASIS = R.ID_VPLANES JOIN academico.GI_CEREMONIAS_TB C ON C.ID_CEREMONIA = R.ID_CEREMONIA WHERE DOCUMENTO_GRADUANDO ='$id_est'";
                                            $result = $dbi->Execute($sql);

                                            if ($result) {
                                                while (!$result->EOF) {
                                                    echo '<option value="' . $result->fields['ID_RELACION'] . '">' . $result->fields['INFO'] . '</option>';
                                                    $result->MoveNext();
                                                }
                                                $result->Close();
                                            } else {
                                                echo "Error en la consulta: " . $dbi->ErrorMsg();
                                            }
                                            ?>
                                        </select>
                                        <label id="cupos">La cantidad de invitados disponibles para esta ceremonia es de <span class="numero-cupo">
                                                <?php
                                                $sql = "SELECT DISTINCT R.ID_RELACION,  C.NOMBRE || ' / ' || C.FECHA || ' ' || C.HORA || ' / ' || V.NOMBRE AS INFO, R.CANT_INV,
                                                    (SELECT cant_inv FROM academico.GI_RELACION_TB where id_relacion = R.ID_RELACION )-(Select count(*) from ACADEMICO.gi_invitados_tb where id_relacion = R.ID_RELACION)RESTA
                                                    FROM academico.GI_RELACION_TB R JOIN academico.V_PLANES V 
                                                    ON V.PLAN_ASIS = R.ID_VPLANES JOIN academico.GI_CEREMONIAS_TB C ON C.ID_CEREMONIA = R.ID_CEREMONIA WHERE DOCUMENTO_GRADUANDO ='$id_est' order by R.ID_RELACION desc ";
                                                $resultado = $dbi->Execute($sql);
                                                if ($resultado) {
                                                    echo $resultado->fields['RESTA'];
                                                } else {
                                                    echo '0';
                                                }
                                                $dbi->Close();
                                                ?>
                                            </span></label>

                                        <p class="help-block">Ceremonia a la cual el invitado participará.</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Tipo de documento del invitado</label><br>
                                        <select id="tipo_identificacion" name="tipo_identificacion" class="option-label" required>
                                            <option value="CC">Cédula de Ciudadanía</option>
                                            <option value="TI">Tarjeta de Indetidad</option>
                                            <option value="CE">Cédula de Extranjería</option>
                                            <option value="PA">Pasaporte</option>
                                            <option value="PEP">Permiso Especial de Permanencia</option>
                                        </select>
                                        <p class="help-block">Tipo de documento del invitado.</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Documento del Invitado</label>
                                        <input id="documento_inv" name="documento_inv" class="form-control" type="number" min="0" step="1" max="9999999999" required>
                                        <p class="help-block">Documento del Invitado.</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Nombre(s) del invitado</label>
                                        <input id="nombres" name="nombres" class="form-control" type="text" maxlength="80" required>
                                        <p class="help-block">Nombre(s) del invitado.</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Apellidos del invitado</label>
                                        <input id="apellidos" name="apellidos" class="form-control" type="text" maxlength="80" required>
                                        <p class="help-block">Apellidos del invitado.</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Correo personal del invitado</label>
                                        <input id="correo" name="correo" class="form-control" type="email" maxlength="80" required>
                                        <p class="help-block">Correo personal del invitado.</p>
                                    </div>

                                    <button type="submit" class="btn btn-info" id="submit-btn">Registrar Invitado</button>
                                </form>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        document.getElementById('opcionRel').addEventListener('change', function() {
            const selectedRelacion = this.value
            $.post('cupos.php', {
                id_relacion: selectedRelacion
            }, function(response) {
                console.log(response);
                document.getElementById('cupos').innerHTML = 'La cantidad de invitados disponibles para esta ceremonia es de <span class="numero-cupo">' + response.cant_inv + '</span>';
            });
        });


        function validarFormulario() {
            let campos = ['tipo_identificacion', 'documento_inv', 'nombres', 'apellidos', 'correo', 'opcionRel'];

            for (let i = 0; i < campos.length; i++) {
                let campo = document.getElementById(campos[i]);
                if (!campo || campo.value.trim() === "") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Todos los campos son requeridos!',
                    });
                    return false;
                }
            }
            return true;
        }

        $('#registro-form').on('submit', function(event) {
            event.preventDefault(); // Prevenir el envío inmediato del formulario
            if (!validarFormulario()) {
                return; // Si la validación falla, salimos y no mostramos la confirmación
            }

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Deseas registrar al invitado?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#5bc0de',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, Registrar!',
                cancelButtonText: 'No, Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: 'registrarInv.php',
                        data: $(this).serialize(), // Enviar todos los campos del formulario
                        success: function(data) {
                            if (data.success) {
                                Swal.fire({
                                    title: '¡Éxito!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonColor: '#5bc0de',
                                    confirmButtonText: 'De acuerdo'
                                }).then(() => {
                                    $('#registro-form')[0].reset();
                                });

                                const selectedRelacion = document.getElementById('opcionRel').value;
                                $.post('cupos.php', {
                                    id_relacion: selectedRelacion
                                }, function(response) {
                                    console.log(response);
                                    const newCantInv = parseInt(response.cant_inv);
                                    document.getElementById('cupos').innerHTML = 'La cantidad de invitados disponibles para esta ceremonia es de <span class="numero-cupo">' + newCantInv + '</span>';
                                });

                            } else {
                                Swal.fire('¡Error!', data.message, 'error');
                                // Verificar si hay una alerta específica y mostrarla
                                if (data.alert) {
                                    Swal.fire('¡Alerta!', data.alert, 'warning');
                                }
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            Swal.fire('¡Error!', 'El invitado ya está registrado en la ceremonia seleccionada.', 'error');
                        }
                    });
                }
            });
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