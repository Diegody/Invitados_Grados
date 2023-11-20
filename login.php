<?php
session_start();
ob_start();
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

include("../bases_datos/adodb/adodb.inc.php");
include("../bases_datos/usb_defglobales.inc");


$dbi = NewADOConnection("$motor_odb1");
$dbi->Connect($base_db1, $usuario_db1, $contra_db1);

if (!$dbi) {
    echo "Error en la conexión a la base de datos";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];

    //$consulta = "SELECT * FROM academico.GI_GRADUANDOS_TB WHERE DOCUMENTO_GRADUANDO = '$usuario' AND CLAVE = '$contrasena'";
    $consulta = "SELECT * FROM academico.GI_GRADUANDOS_TB G INNER JOIN academico.GI_RELACION_TB R ON R.DOCUMENTO_GRADUANDO = G.DOCUMENTO_GRADUANDO WHERE G.DOCUMENTO_GRADUANDO = '$usuario' AND G.CLAVE = '$contrasena' AND R.ESTADO = 'HABILITADO'";

    $resultado = $dbi->Execute($consulta, array($usuario, $contrasena));

    if ($resultado && !$resultado->EOF) {
        $fila = $resultado->fields;

        $_SESSION['id_usuario'] = $fila['DOCUMENTO_GRADUANDO'];
        $_SESSION['nombre_usuario'] = $fila['NOMBRES'];
        $_SESSION['apellido_usuario'] = $fila['APELLIDOS'];
        $_SESSION['estado'] = $fila['ESTADO'];

        if ($fila['PERFIL'] === '1') {
            header("Location: admin/index.php");
            exit;
        } elseif ($fila['PERFIL'] === '2') {
            header("Location: grado/index.php");
            exit;
        } elseif ($fila['PERFIL'] === '3') {
            header("Location: control/index.php");
            exit;
        } else {
            echo "Perfil desconocido";
        }
    } else {
        $mensaje_error = " Credenciales invalidas o usuario inactivo. Vuelva a intentarlo.";
    }

    $dbi->close();
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Iniciar Sesión</title>

    <!-- BOOTSTRAP STYLES-->
    <link href="admin/assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="admin/assets/css/font-awesome.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <!-- <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' /> -->

</head>

<body style="background-color: #E2E2E2;">
    <div class="container">
        <div class="row text-center " style="padding-top:100px;">
            <div class="col-md-12">
                <img src="admin/assets/img/usb.png" />
            </div>
        </div>
        <div class="row ">
            <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
                <div class="panel-body">
                    <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <hr />
                        <h5>Ingresa tus credenciales para iniciar sesión</h5>
                        <br />
                        <?php if (isset($mensaje_error)) { ?>
                            <p style="color: red;"><?php echo $mensaje_error; ?></p>
                        <?php } ?>
                        <br>
                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                            <input id="usuario" name="usuario" type="number" class="form-control" placeholder="Documento" min="0" step="1" required />
                        </div>
                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                            <input id="contrasena" name="contrasena" type="password" class="form-control" placeholder="Contraseña" required />
                        </div>
                        <?php
                        ?>
                        <!-- <div class="form-group">
                            <label class="checkbox-inline">
                                <input type="checkbox" /> Remember me
                            </label>
                            <span class="pull-right">
                                <a href="index.html">Forget password ? </a>
                            </span>
                        </div> -->
                        <button type="submit" class="btn btn-warning" style="background-color: #f17c01;">Iniciar Sesión</button>
                        <hr />
                        <!-- 
                        Not register ? <a href="index.html">click here </a> or go to <a href="index.html">Home</a> -->
                    </form>
                </div>

            </div>


        </div>
    </div>

</body>

</html>