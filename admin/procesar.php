<?php
session_start();
ob_start();
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

include("../../bases_datos/adodb/adodb.inc.php");
include("../../bases_datos/usb_defglobales.inc");

$dbi = NewADOConnection("$motor_odb1");
$dbi->Connect($base_db1, $usuario_db1, $contra_db1);

if (!$dbi->Connect($base_db1, $usuario_db1, $contra_db1)) {
    echo "Error en la conexión a la base de datos";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["archivo_csv"]) && $_FILES["archivo_csv"]["error"] == UPLOAD_ERR_OK) {
        $archivo_temporal = $_FILES["archivo_csv"]["tmp_name"];
        $nombre_archivo = $_FILES["archivo_csv"]["name"];

        // Verifica que el archivo sea un archivo CSV
        $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
        if ($extension != "csv") {
            die("El archivo debe ser un archivo CSV.");
        }

        // Leer el contenido del archivo temporal
        $contenido = file_get_contents($archivo_temporal);

        // // Si el archivo contiene punto y coma, reemplazarlo por coma
        if (strpos($contenido, ';') !== false) {
            $contenido = str_replace(';', ',', $contenido);
            file_put_contents($archivo_temporal, $contenido);
        }

        // Procesa el archivo CSV
        $filas = [];
        if (($gestor = fopen($archivo_temporal, "r")) !== false) {
            $header = fgetcsv($gestor, 5000, ","); // Leer y descartar la primera fila (encabezados)
            $secondRow = fgetcsv($gestor, 5000, ","); // Leer y descartar la segunda fila

            while (($datos = fgetcsv($gestor, 5000, ",")) !== false) {
                $filas[] = $datos;
            }
            fclose($gestor);
        } else {
            die("Error al abrir el archivo CSV.");
        }

        $dbi->StartTrans();

        $tabla_insercion = [];
        $tabla_insercion_ceremonias = [];
        $conteo_buenos = 0;
        $conteo_malos = 0;
        $conteo_buenos_c = 0;
        $conteo_malos_c = 0;
        $graduandosInsertados = [];  // Array para llevar registro de los graduandos insertados

        foreach ($filas as $fila) {
            $DOCUMENTO_GRADUANDO = $dbi->qstr($fila[0]);
            $TIPO_IDENTIFICACION = $dbi->qstr($fila[1]);
            $NOMBRES = $dbi->qstr($fila[2]);
            $APELLIDOS = $dbi->qstr($fila[3]);
            $CLAVE = $dbi->qstr($fila[4]);
            $PERFIL = $dbi->qstr($fila[5]);
            $CORREO_PERSONAL = $dbi->qstr($fila[6]);
            $CORREO_INSTITUCIONAL = $dbi->qstr($fila[7]);
            $TELEFONO = $dbi->qstr($fila[8]);
            $ID_CEREMONIA = $dbi->qstr($fila[9]);
            $ID_VPLANES = $dbi->qstr($fila[10]);
            $CICLO = $dbi->qstr($fila[11]);

            if (!in_array($DOCUMENTO_GRADUANDO, $graduandosInsertados)) {
                $consulta = "INSERT INTO academico.GI_GRADUANDOS_TB (DOCUMENTO_GRADUANDO, TIPO_IDENTIFICACION, NOMBRES, APELLIDOS, CLAVE, PERFIL ,CORREO_PERSONAL, CORREO_INSTITUCIONAL, TELEFONO, CICLO) 
                        VALUES ($DOCUMENTO_GRADUANDO, $TIPO_IDENTIFICACION, $NOMBRES, $APELLIDOS, $CLAVE, $PERFIL, $CORREO_PERSONAL, $CORREO_INSTITUCIONAL, $TELEFONO, $CICLO)";

                if ($dbi->Execute($consulta)) {
                    $graduandosInsertados[] = $DOCUMENTO_GRADUANDO;
                    $tabla_insercion[] = [
                        'DOCUMENTO_GRADUANDO' => $DOCUMENTO_GRADUANDO,
                        'TIPO_IDENTIFICACION' => $TIPO_IDENTIFICACION,
                        'NOMBRES' => $NOMBRES,
                        'APELLIDOS' => $APELLIDOS,
                        'CLAVE' => $CLAVE,
                        'PERFIL' => $PERFIL,
                        'CORREO_PERSONAL' => $CORREO_PERSONAL,
                        'CORREO_INSTITUCIONAL' => $CORREO_INSTITUCIONAL,
                        'TELEFONO' => $TELEFONO,
                        'CICLO' => $CICLO,
                        'Mensaje' => 'Inserción realizada de manera exitosa'
                    ];
                    $conteo_buenos++;
                } else {
                    $error_info = $dbi->ErrorMsg();
                    switch ($error_info) {
                        case 'ORA-00001: unique constraint (ACADEMICO.PK_GRADUANDOS) violated':
                            $mensaje = "Ya existe este dato en los registros de graduados";
                            break;
                        case 984:
                            $mensaje = "Ingresa un valor que no concuerda con los parámetros en la tabla";
                            break;
                        default:
                            $mensaje = "Error en la inserción en GI_GRADUADOS_TB: " . $mensaje;
                            break;
                    }
                    $tabla_insercion[] = [
                        'DOCUMENTO_GRADUANDO' => $DOCUMENTO_GRADUANDO,
                        'TIPO_IDENTIFICACION' => $TIPO_IDENTIFICACION,
                        'NOMBRES' => $NOMBRES,
                        'APELLIDOS' => $APELLIDOS,
                        'CLAVE' => $CLAVE,
                        'PERFIL' => $PERFIL,
                        'CORREO_PERSONAL' => $CORREO_PERSONAL,
                        'CORREO_INSTITUCIONAL' => $CORREO_INSTITUCIONAL,
                        'TELEFONO' => $TELEFONO,
                        'CICLO' => $CICLO,
                        'Mensaje' => $mensaje
                    ];
                    $conteo_malos++;
                }
            }

            $consulta_relacion = "INSERT INTO academico.GI_RELACION_TB (DOCUMENTO_GRADUANDO, ID_CEREMONIA, ID_VPLANES, CANT_INV, ESTADO) 
            VALUES ($DOCUMENTO_GRADUANDO, $ID_CEREMONIA, $ID_VPLANES, (SELECT CANT_INV FROM academico.GI_CEREMONIAS_TB WHERE ID_CEREMONIA = $ID_CEREMONIA), 'HABILITADO')";

            if ($dbi->Execute($consulta_relacion)) {
                $tabla_insercion_ceremonias[] = [
                    'DOCUMENTO_GRADUANDO' => $DOCUMENTO_GRADUANDO,
                    'ID_CEREMONIA' => $ID_CEREMONIA,
                    'ID_VPLANES' => $ID_VPLANES,
                    'Mensaje' => 'Inserción exitosa'
                ];
                $conteo_buenos_c++;
            } else {
                $error_info = $dbi->ErrorMsg();
                switch ($error_info) {
                    case 'ORA-00001: unique constraint (ACADEMICO.UNI_RELACION) violated':
                        $mensaje = "Ya existe este dato de graduado con su ceremonia";
                        break;
                    case 984:
                        $mensaje = "Ingresa un valor que no concuerda con los parámetros en la tabla";
                        break;
                    case 'ORA-00984: column not allowed here':
                        $mensaje = "Se utilizó el nombre de columna en una parte de la consulta donde no debería estar";
                        break;
                    default:
                        $mensaje = "Inserción Erronea";
                        break;
                }

                $tabla_insercion_ceremonias[] = [
                    'DOCUMENTO_GRADUANDO' => $DOCUMENTO_GRADUANDO,
                    'ID_CEREMONIA' => $ID_CEREMONIA,
                    'ID_VPLANES' => $ID_VPLANES,
                    'Mensaje' => $mensaje
                ];
                $conteo_malos_c++;
            }
        }

        if ($dbi->HasFailedTrans()) {
            $dbi->FailTrans();  // Esto rechaza la transacción
            echo "<script>window.alert('Error. Operación Fallida: $mensaje')</script>";
        } else {
            $dbi->CompleteTrans();  // Esto completa la transacción y aplica los cambios
            echo "<script>window.alert('Transacción completada con éxito.')</script>";
        }
    }
}
if (isset($_SESSION['id_usuario']) && isset($_SESSION['nombre_usuario']) && isset($_SESSION['apellido_usuario'])) {
    $id_adm = $_SESSION['id_usuario'];
    $nombre_adm = $_SESSION['nombre_usuario'];
    $apellido_adm = $_SESSION['apellido_usuario'];
}
?>


<!DOCTYPE html>
<php xmlns="http://www.w3.org/1999/xphp">

    <html>

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Log de inserción</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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


        <style>
            table {
                display: block;
                overflow-x: scroll;
                text-align: center;
                width: 100%;
            }

            /* Estilos para el div de superposición */
            .loader-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(255, 255, 255, 0.7);
                /* Fondo semitransparente */
                display: none;
                /* Oculto inicialmente */
                justify-content: center;
                align-items: center;
                z-index: 9999;
            }

            /* Estilos para la animación de carga */
            .loader {
                border: 5px solid #f3f3f3;
                border-top: 5px solid #3498db;
                border-radius: 50%;
                width: 50px;
                height: 50px;
                animation: spin 2s linear infinite;
            }

            @keyframes spin {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }


            /* Estilos del botón */
            .custom-button {
                margin-top: 20px;
                display: inline-block;
                padding: 10px 20px;
                background-color: #5cb85c;
                /* Color de fondo */
                color: #fff;
                /* Color del texto */
                text-decoration: none;
                /* Eliminar subrayado por defecto */
                border: none;
                /* Eliminar borde */
                border-radius: 5px;
                /* Borde redondeado */
                cursor: pointer;
                /* Cambiar el cursor a mano al pasar por encima */
                font-size: 16px;
                transition: background-color 0.3s;
                /* Transición de color al pasar el mouse */
            }

            /* Estilos del botón al pasar el mouse por encima */
            .custom-button:hover {
                background-color: #2980b9;
                /* Color de fondo al pasar el mouse */
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
                            <a class="active-menu" href="index.php"><i class="fa fa-dashboard "></i>Inicio</a>
                        </li>
                        <li>
                            <a href="panel-up.php"><i class="fa fa-plus "></i>Subir Graduandos</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-code"></i>Parámetros <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="panel-fec.php"><i class="fa fa-edit "></i>Ceremonias</a>
                                </li>
                                <li>
                                    <a href="panel-cant.php"><i class="fa fa-toggle-on"></i>Cantidad Invitados</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-yelp"></i>Registrar<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">

                                <li>
                                    <a href="formG.php"><i class="fa fa-key "></i>Graduandos</a>
                                </li>
                                <li>
                                    <a href="formU.php"><i class="fa fa-desktop "></i>Usuarios</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-table"></i>Tablas<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="panel-select.php"><i class="fa fa-sitemap"></i>Graduandos</span></a>
                                </li>
                                <li>
                                    <a href="panel-select-inv.php"><i class="fa fa-circle-o "></i>Invitados</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-cloud"></i>Instructivo (Próximamente)</a>
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
                            <h1 class="page-head-line">Log de inserción por csv</h1>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="jumbotron">
                                <div id="loader-overlay" class="loader-overlay">
                                    <div class="loader"></div>
                                </div>
                                <h4 class="page-subhead-lineButton">Resultados de la inserción - Graduandos</h4>
                                <h4 class="page-subhead-lineButton" style="color:green;"><?php echo "Registros descritos correctamente: $conteo_buenos" ?></h4>
                                <h4 class="page-subhead-lineButton" style="color:red;"><?php echo  "Registros erróneos: $conteo_malos" ?></h4>

                                <table border="1">
                                    <tr>
                                        <th>Documento Graduando</th>
                                        <th>Tipo de Identificación</th>
                                        <th>Nombres</th>
                                        <th>Apellidos</th>
                                        <th>Clave</th>
                                        <th>Perfil</th>
                                        <th>Correo Personal</th>
                                        <th>Correo Institucional</th>
                                        <th>Teléfono</th>
                                        <th>Mensaje</th>
                                    </tr>
                                    <?php foreach ($tabla_insercion as $fila) : ?>
                                        <tr>
                                            <td><?php echo $fila['DOCUMENTO_GRADUANDO'] ?> </td>
                                            <td><?php echo $fila['TIPO_IDENTIFICACION'] ?> </td>
                                            <td><?php echo $fila['NOMBRES'] ?> </td>
                                            <td><?php echo $fila['APELLIDOS'] ?> </td>
                                            <td><?php echo $fila['CLAVE'] ?> </td>
                                            <td><?php echo $fila['PERFIL'] ?> </td>
                                            <td><?php echo $fila['CORREO_PERSONAL'] ?> </td>
                                            <td><?php echo $fila['CORREO_INSTITUCIONAL'] ?> </td>
                                            <td><?php echo $fila['TELEFONO'] ?> </td>
                                            <td><?php echo $fila['Mensaje'] ?> </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table><br>

                                <h4 class="page-subhead-lineButton">Resultados de la inserción - Graduandos con su Relación</h4>
                                <h4 class="page-subhead-lineButton" style="color:green;"><?php echo "Registros descritos correctamente: $conteo_buenos_c" ?></h4>
                                <h4 class="page-subhead-lineButton" style="color:red;"><?php echo  "Registros erróneos: $conteo_malos_c" ?></h4>

                                <table border="1">
                                    <tr>
                                        <th>Documento Graduando</th>
                                        <th>ID Ceremonia</th>
                                        <th>Programa</th>
                                        <th>Mensaje</th>
                                    </tr>
                                    <?php foreach ($tabla_insercion_ceremonias as $fila) : ?>
                                        <tr>
                                            <td><?php echo $fila['DOCUMENTO_GRADUANDO'] ?> </td>
                                            <td><?php echo $fila['ID_CEREMONIA'] ?> </td>
                                            <td><?php echo $fila['ID_VPLANES'] ?> </td>
                                            <td><?php echo $fila['Mensaje'] ?> </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table><br>
                                <h4 class="page-subhead-lineButton"><b>Nota: </b>Si al momento de haber realizado la carga de los graduandos se arroja algún error, ninguno de los registros será subido. </h4>
                                <a href="panel-up.php" class="btn btn-danger"><i class="fas fa-times"></i> Cerrar Log</a>

                                <script>
                                    // Función para mostrar la animación de carga
                                    function mostrarCarga() {
                                        document.getElementById("loader-overlay").style.display = "flex";
                                    }

                                    // Función para ocultar la animación de carga
                                    function ocultarCarga() {
                                        document.getElementById("loader-overlay").style.display = "none";
                                    }

                                    // Ejecuta la animación de carga al cargar la página
                                    mostrarCarga();

                                    window.onload = function() {
                                        ocultarCarga();
                                    };
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /. WRAPPER  -->
            <div id="footer-sec">

            </div>
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

        <!-- este es mi parte---------------- -->
    </body>

    </html>
</php>