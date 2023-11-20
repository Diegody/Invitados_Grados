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


<style>
    /* Estilos del modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        margin: 30%;
        background-color: #fff;
        padding: 20px;
        text-align: center;
        border-radius: 5px;
        max-width: 60%;
        /* Ajusta el ancho del modal según tus necesidades */
    }

    .btn-aceptar {
        padding: 10px;
        width: 100%;
        background-color: #ff7e02;
    }

    .btn-aceptar:hover {
        background-color: gray;
    }
</style>

<!DOCTYPE php>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lector QR</title>

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
    <!-- MORE-->
    <link href="assets/css/qrscanner.css" rel="stylesheet" />
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
                        <a class="active-menu" href="lectorqr.php"><i class="fa fa-qrcode"></i>Lector QR</a>
                    </li>
                    <li>
                        <a href="invitados.php"><i class="fa fa-table"></i>Invitados</a>
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

        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-head-line">LECTOR QR</h1>
                        <h1 class="page-subhead-line">En esta sección, tiene la capacidad de gestionar el acceso de los invitados mediante el escaneo de códigos QR y validar así su autenticidad.
                        </h1>
                    </div>
                </div>
                <!-- /. SCANNER QR  -->
                <div class="panel-body">
                    <h3 class="titleh3">Escanear Código QR</h3>
                    <div class="qr-scanner">
                        <video id="qr-video"></video>
                        <canvas id="qr-canvas"></canvas>
                        <div class="scan-line"></div>
                    </div>

                </div>

            </div>
        </div>
    </div>

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



    <!-- Modal para mostrar la información del QR -->
    <div id="qrModal" class="modal">
        <div class="modal-content">

            <h2>Información del QR</h2>
            <p id="qrInfo"></p>
            <p id="qrValidation"></p>
            <button id="closeModal" class="btn-aceptar">Aceptar</button>
        </div>
    </div>







    <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>

    <!-- Tu script JavaScript -->
    <script>
        var DesactivarEscaner = 0;


        const videoElement = document.getElementById('qr-video');
        const canvasElement = document.createElement('canvas');


        let isReadingQR = true; // Variable de control para habilitar/deshabilitar la lectura
        const cooldownTime = 5000; // Tiempo de espera en milisegundos (5 segundos en este ejemplo)

        // Función para dibujar un cuadro rojo alrededor del código QR
        function drawQRCodeBox(code) {
            const canvasContext = canvasElement.getContext('2d');
            canvasContext.clearRect(0, 0, canvasElement.width, canvasElement.height);

            if (code) {
                // Dibuja un cuadro rojo alrededor del código QR
                canvasContext.strokeStyle = 'red';
                canvasContext.lineWidth = 2;
                canvasContext.beginPath();
                canvasContext.moveTo(code.location.topLeftCorner.x, code.location.topLeftCorner.y);
                canvasContext.lineTo(code.location.topRightCorner.x, code.location.topRightCorner.y);
                canvasContext.lineTo(code.location.bottomRightCorner.x, code.location.bottomRightCorner.y);
                canvasContext.lineTo(code.location.bottomLeftCorner.x, code.location.bottomLeftCorner.y);
                canvasContext.closePath();
                canvasContext.stroke();
            }
        }


        let currentFacingMode = 'environment'; // Inicialmente, utiliza la cámara trasera

        // Detectar si el usuario está en un dispositivo móvil
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);


        const constraints = isMobile ? {
                video: {
                    facingMode: {
                        exact: 'environment'
                    }
                }
            } // Dispositivos móviles (cámara trasera)
            :
            {
                video: true
            }; // Dispositivos de escritorio (cámara predeterminada)


        // Obtener acceso a la cámara
        navigator.mediaDevices
            .getUserMedia(constraints)
            .then(function(stream) {
                videoElement.srcObject = stream;
                videoElement.play();
            })
            .catch(function(error) {
                console.error('Error al acceder a la cámara:', error);
                alert("Hardware NO Compatible");
            });

        // Escuchar el evento 'loadedmetadata' para asegurarse de que el video tenga dimensiones
        videoElement.addEventListener('loadedmetadata', function() {
            const videoWidth = videoElement.videoWidth;
            const videoHeight = videoElement.videoHeight;
            canvasElement.width = videoWidth;
            canvasElement.height = videoHeight;
        });

        // ...

        // Función para leer el código QR
        async function scanQRCode() {
            if (isReadingQR) {
                const canvasContext = canvasElement.getContext('2d');
                canvasContext.drawImage(videoElement, 0, 0, canvasElement.width, canvasElement.height);

                const imageData = canvasContext.getImageData(0, 0, canvasElement.width, canvasElement.height);
                const code = jsQR(imageData.data, imageData.width, imageData.height);

                if (code) {
                    // Cuando se lee el código QR
                    const invitationCode = code.data; // Contenido del código QR

                    drawQRCodeBox(code); // Dibujar cuadro rojo alrededor del código QR



                    // Realizar una solicitud AJAX al servidor para verificar la invitación
                    try {
                        const response = await fetch('verificar_invitacion.php', {
                            method: 'POST',
                            body: JSON.stringify({
                                invitationCode
                            }),
                            headers: {
                                'Content-Type': 'application/json',
                            },
                        });
                        const data = await response.json();

                        if (data.valid) {

                            const audio = new Audio('assets/sounds/success.mp3');
                            audio.play();

                        } else {

                            const audio = new Audio('assets/sounds/error.mp3');
                            audio.play();

                        }

                        showQRModal(invitationCode, data.valid);

                    } catch (error) {
                        // Manejar errores de la solicitud AJAX
                        console.error('No se pudo leer el código QR:', error);

                    }


                    // Configurar el temporizador para habilitar la lectura nuevamente después del tiempo de espera
                    isReadingQR = false;
                    setTimeout(() => {
                        isReadingQR = true;
                    }, cooldownTime);
                }
            }

            if (DesactivarEscaner == 1) {

            } else {

                // Continuar escaneando
                requestAnimationFrame(scanQRCode);

            }









        }


        function showQRModal(invitationCode, isValid) {
            DesactivarEscaner = 1;
            const qrInfoElement = document.getElementById('qrInfo');
            const qrValidationElement = document.getElementById('qrValidation');

            qrInfoElement.textContent = `Contenido del código QR: ${invitationCode}`;

            if (isValid) {
                qrValidationElement.textContent = 'Invitación válida';
                qrValidationElement.style.color = 'green';
            } else {
                qrValidationElement.textContent = 'Invitación no válida';
                qrValidationElement.style.color = 'red';
            }

            const modal = document.getElementById('qrModal');
            modal.style.display = 'block';

            // Asignar el evento click al botón de cierre del modal
            const closeModalButton = document.getElementById('closeModal');
            closeModalButton.onclick = function() {
                modal.style.display = 'none';
                DesactivarEscaner = 0;
                scanQRCode();

            };
        }

        // ...

        // Iniciar el escaneo del código QR
        scanQRCode();
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