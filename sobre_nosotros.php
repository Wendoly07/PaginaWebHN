<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    $conn = new PDO(
        "sqlsrv:Server=srvdbcacdev.database.windows.net;Database=dblotocacdev",
        "LotoAdmin",
        "LotAdmin1.",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Traer datos
    $stmt = $conn->query("SELECT * FROM paginaweb_sobre_nosotros_hn WHERE id=1");
    $datos = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

$mensajeExito = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Validar que todas las variables estén definidas
    $JugoParaDeudas = isset($_POST['JugoParaDeudas']) ? $_POST['JugoParaDeudas'] : null;
    $ImpulsoVolverAJugar = isset($_POST['ImpulsoVolverAJugar']) ? $_POST['ImpulsoVolverAJugar'] : null;
    $PidioPrestamo = isset($_POST['PidioPrestamo']) ? $_POST['PidioPrestamo'] : null;
    $Remordimientos = isset($_POST['Remordimientos']) ? $_POST['Remordimientos'] : null;
    $QuedarseSinDinero = isset($_POST['QuedarseSinDinero']) ? $_POST['QuedarseSinDinero'] : null;
    $RecuperarPerdidas = isset($_POST['RecuperarPerdidas']) ? $_POST['RecuperarPerdidas'] : null;
    $TristezaPorJuego = isset($_POST['TristezaPorJuego']) ? $_POST['TristezaPorJuego'] : null;

    try {
        // Conexión a SQL Server
        $conn = new PDO(
            "sqlsrv:Server=srvdbcacdev.database.windows.net;Database=dblotocacdev",
            "LotoAdmin",
            "LotAdmin1.",
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        // Preparar el insert
        $sql = "INSERT INTO Test_responsable 
                (JugoParaDeudas, ImpulsoVolverAJugar, PidioPrestamo, Remordimientos, QuedarseSinDinero, RecuperarPerdidas, TristezaPorJuego)
                VALUES 
                (:JugoParaDeudas, :ImpulsoVolverAJugar, :PidioPrestamo, :Remordimientos, :QuedarseSinDinero, :RecuperarPerdidas, :TristezaPorJuego)";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ':JugoParaDeudas' => $JugoParaDeudas,
            ':ImpulsoVolverAJugar' => $ImpulsoVolverAJugar,
            ':PidioPrestamo' => $PidioPrestamo,
            ':Remordimientos' => $Remordimientos,
            ':QuedarseSinDinero' => $QuedarseSinDinero,
            ':RecuperarPerdidas' => $RecuperarPerdidas,
            ':TristezaPorJuego' => $TristezaPorJuego
        ]);

        //Aquí llamamos a la Logic App
        $logicAppUrl = "https://prod-21.canadacentral.logic.azure.com:443/workflows/fc7f31683113487faeefcca1dfd172b9/triggers/When_an_HTTP_request_is_received/paths/invoke?api-version=2016-10-01&sp=%2Ftriggers%2FWhen_an_HTTP_request_is_received%2Frun&sv=1.0&sig=HmMVDBLEq5OhojfiemJBKZ6_XcHVgmsQZ-Z95BQvTg8";

        $data = [
            "JugoParaDeudas" => $JugoParaDeudas,
            "ImpulsoVolverAJugar" => $ImpulsoVolverAJugar,
            "PidioPrestamo" => $PidioPrestamo,
            "Remordimientos" => $Remordimientos,
            "QuedarseSinDinero" => $QuedarseSinDinero,
            "RecuperarPerdidas" => $RecuperarPerdidas,
            "TristezaPorJuego" => $TristezaPorJuego
        ];

        $ch = curl_init($logicAppUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_exec($ch);
        curl_close($ch);

        $mensajeExito = "✅ Test enviado correctamente y correo enviado";

    } catch (PDOException $e) {
        $mensajeExito = "❌ Error al guardar: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loto - Cambiando Vidas</title>
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "HelveticaRounded LT Std Bd", Arial, Helvetica, sans-serif;
}

        body {
    font-family: 'HelveticaRounded', Arial, Helvetica, sans-serif;
    background: #ffffff;
}

        /* =============== HEADER BANNER =============== */
       header {
    width: 100%;
    overflow: hidden;
}

header img {
    width: 100%;
    height: auto;
    display: block;
}
/* =============== CONTENEDOR QUIÉNES SOMOS =============== */
    .container-quienes {
    max-width: 1430px;
    margin: 60px auto 40px auto; /* antes 120px / 650px */
    background: #ffffff;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}



        .quienes-text {
            margin-bottom: 40px;
        }

        .quienes-text h2 {
            font-size: 32px;
            font-weight: bold;
            color: #ff7b00;
            margin-bottom: 15px;
        }

        .quienes-text p {
            font-size: 18px;
            color: #005bbb;
            line-height: 1.5;
            font-weight: 600;
        }

        /* FRANJA NARANJA */
        .orange-rect-wrapper {
            position: relative;
            margin-bottom: 30px;
        }

        .orange-rect {
            background: #ff7b00;
            padding: 20px 40px;
            border-radius: 10px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            justify-items: center;
            align-items: center;
        }

        /* MÉTRICAS */
        .metric {
            display: flex;
            align-items: center;
            gap: 15px;
            color: white;
        }

        .metric img {
            width: 70px;
        }

        .metric-text h3,
        .metric-text p {
            margin: 0;
            color: white;
            text-align: center;
        }

        /* SELLO RSE */
        .sello-rse {
            position: absolute;
            top: -40px;
            right: 0;
        }

        .sello-rse img {
            width: 180px;
            display: block;
        }

        /* BOTONES 2 FILAS x 2 COLUMNAS */
        .cuadros-extra {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .card-extra {
            padding: 20px;
            border-radius: 12px;
            color: white;
            text-align: center;
            font-size: 18px;
        }

        .diversion {
            background: #4bb543;
        }

        .confianza {
            background: #3fa1e4;
        }

        .esperanza {
            background: #ff6eb4;
        }

        .solidaridad {
            background: #ffbb00;
        }

        @media(max-width:900px){
            .orange-rect {
                grid-template-columns: 1fr 1fr;
            }
            .cuadros-extra {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media(max-width:600px){
            .orange-rect {
                grid-template-columns: 1fr 1fr;
            }
            .cuadros-extra {
                grid-template-columns: 1fr 1fr;
            }
        }

        /* =============== CAMBIAMOS VIDAS =============== */
        .cambiamos-vidas {
            padding: 70px 20px;
            background: #f6e7d6;
            border-radius: 20px;
        }

        .cv-contenedor {
            display: flex;
            align-items: center;
            gap: 40px;
            max-width: 1200px;
            margin: auto;
        }

        .cv-imagen img {
            width: 100%;
            max-width: 400px;
            border-radius: 15px;
        }

        .cv-texto h2 {
            font-size: 48px;
            font-weight: 900;
            color: #ff7b00;
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .cv-texto p {
            font-size: 22px;
            font-weight: 600;
            color: #333;
        }

        /* TARJETAS */
        .impact-grid {
            margin-top: 60px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
        }

        .impact-card {
            background: #ffffff;
            padding: 35px 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }

        .impact-img {
            width: 80px;
            height: auto;
            margin-bottom: 15px;
        }

        .tarjeta-morada h3, .tarjeta-morada p {
            color: #a950d4;
        }

        .tarjeta-rosada h3, .tarjeta-rosada p {
            color: #ff6eb4;
        }

        .tarjeta-azul h3, .tarjeta-azul p {
            color: #3fa1e4;
        }

        .tarjeta-verde h3, .tarjeta-verde p {
            color: #4bb543;
        }

        .impact-card h3 {
            font-size: 32px;
            font-weight: 900;
            margin-bottom: 8px;
        }

        .impact-card p {
            font-size: 18px;
            font-weight: 600;
        }

        /* =============== MODELO CAMBIA VIDAS =============== */
        .beige-container {
            background-color: #f6e7d6;
            padding: 60px 20px;
            border-radius: 1px;
            max-width: 2300px;
            margin: 50px auto;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .beige-container h2 {
            text-align: center;
            color: #ff7b00;
            font-size: 36px;
            margin-bottom: 20px;
        }

        .beige-container p {
            text-align: center;
            font-size: 18px;
            margin-bottom: 40px;
        }

        .modelo-section {
            max-width: 1200px;
            margin: 50px auto;
            padding: 40px 20px;
        }

        .modelo-section h2 {
            text-align: center;
            font-size: 32px;
            color: #005bbb;
            margin-bottom: 25px;
        }

        .modelo-grid {
            display: grid;
            grid-template-columns: repeat(3,1fr);
            gap: 25px;
        }

        .modelo-card {
            padding: 25px;
            background: #fff;
            border-radius: 15px;
            border: 2px solid #ff9900;
            text-align: center;
        }

        /* =============== RESPONSABILIDAD =============== */
        .responsabilidad-section {
            max-width: 1400px;
            margin: 50px auto;
            padding: 20px;
        }

        .responsabilidad-section h2 {
            text-align: center;
            font-size: 32px;
            color: #ff9900;
            margin-bottom: 40px;
        }

        .responsabilidad-container {
            max-width: 1400px;
            margin: auto;
            display: flex;
            flex-direction: column;
            gap: 40px;
        }

        .resp-item-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
        }

   

.resp-item span {
    text-align: center;
}

.resp-item {
    width: 100%;
    max-width: 1300px;
    border-radius: 18px;
    padding: 25px;
    font-size: 20px;
    font-weight: 600;
    color: white;

    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

/* TEXTO */
.resp-item span {
    flex: 1;
}

/* IMAGEN */
.resp-item img {
    width: 140px;
    height: auto;
}

/* IZQUIERDA (imagen izquierda) */
.resp-item.izquierda {
    flex-direction: row;
    text-align: left;
}

/* DERECHA (imagen derecha) */
.resp-item.derecha {
    flex-direction: row-reverse;
    text-align: right;
}
        

        @media(max-width: 600px){
            .resp-item {
                height: 140px;
                font-size: 16px;
            }
            .resp-item img {
                width: 170px;
            }
        }

        .resp-item.izquierda img { order: 0; }
        .resp-item.izquierda span { order: 1; }
        .resp-item.derecha img { order: 1; }
        .resp-item.derecha span { order: 0; }
        
        .naranja { background: #ff9900; }
        .azul { background: #3fa1e4; }
        .morado { background: #a950d4; }
        .verde { background: #4bb543; }
        .rosado { background: #ff6eb4; }

        @media(max-width: 600px){
            .resp-item { height: 120px; font-size: 16px; }
            .resp-item img { width: 60px; }
        }

        /* Container blanco general */
        .container-blanco {
            max-width: 1400px;
            margin: 2px auto;
            padding: 40px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        /* NUESTRA MISIÓN centrada y bold */
        .mision-text-center h2 {
            text-align: center;
            font-size: 36px;
            font-weight: bold;
            color: #005bbb;
            margin-bottom: 15px;
        }

        .mision-text-center p {
            text-align: center;
            font-size: 18px;
            color: #333;
            margin-bottom: 30px;
        }

        /* QUÉ ES EL JUEGO DE AZAR */
        .azar-container h2 {
            color: #ff9900;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .azar-container p {
            font-size: 16px;
            color: #333;
            line-height: 1.5;
            margin-bottom: 30px;
        }

        /* ACORDEÓN */
        .acordeon-container {
            margin-top: 20px;
        }

        .acordeon-btn {
            background-color: #ff9900;
            color: white;
            cursor: pointer;
            padding: 18px 20px;
            width: 100%;
            text-align: left;
            border: none;
            border-radius: 12px;
            outline: none;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .acordeon-btn .circle {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #fff;
            color: #ff9900;
            border-radius: 50%;
            font-size: 16px;
            transition: transform 0.3s;
        }

        .acordeon-btn.active .circle {
            transform: rotate(180deg);
        }

        .acordeon-content {
            padding: 15px;
            display: none;
            background-color: #fff3e0;
            border-radius: 12px;
            margin-bottom: 10px;
            font-size: 16px;
            color: #333;
        }

        /* BOTÓN AZUL MODERNO */
        .btn-azul {
            display: block;
            margin: 50px auto 0 auto;
            padding: 15px 25px;
            background-color: #005bbb;
            color: white;
            font-size: 18px;
            font-weight: bold;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .modal-test{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.6);
    z-index:9999;
    justify-content:center;
    align-items:center;
}

.modal-contenido{
    background:#fff;
    width:100%;
    max-width:700px;
    padding:30px;
    border-radius:20px;
    max-height:90vh;
    overflow-y:auto;
    position:relative;
}

.modal-contenido h2{
    text-align:center;
    color:#005bbb;
    margin-bottom:20px;
}

.cerrar-modal{
    position:absolute;
    top:15px;
    right:20px;
    font-size:28px;
    cursor:pointer;
}

.pregunta{
    margin-bottom:18px;
}

.pregunta p{
    font-weight:600;
    margin-bottom:6px;
}

/* ===== MEJOR DISEÑO FORMULARIO TEST ===== */

.modal-contenido{
    animation: aparecer .3s ease;
}

@keyframes aparecer{
    from { transform: scale(0.9); opacity: 0; }
    to   { transform: scale(1); opacity: 1; }
}

.modal-contenido h2{
    font-size: 26px;
    font-weight: bold;
    color: #005bbb;
    margin-bottom: 25px;
    text-align: center;
}

/* Caja de cada pregunta */
.pregunta{
    background: #f4f7ff;
    border-radius: 15px;
    padding: 18px;
    margin-bottom: 20px;
}

/* Texto de la pregunta */
.pregunta p{
    font-size: 16px;
    font-weight: bold;
    color: #003f7f;
    margin-bottom: 12px;
}

/* Opciones Si / No */
.opciones{
    display: flex;
    gap: 25px;
}

.opciones label{
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
}

/* Radio más grande */
.opciones input{
    transform: scale(1.3);
    margin-right: 6px;
}

.alert-success {
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #4bb543;
    color: white;
    padding: 15px 25px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    font-weight: bold;
    font-size: 16px;
    z-index: 10000;
    opacity: 0;
    animation: showAlert 0.5s forwards, hideAlert 0.5s 7s forwards;
}

.metric-text p {
    font-size: 20px;
    font-weight: 600;
}

.metric-text p strong {
    font-size: 28px;
    font-weight: 900;
}

@keyframes showAlert {
    to { opacity: 1; }
}

@keyframes hideAlert {
    to { opacity: 0; }
}

        .btn-azul:hover {
            background-color: #003f7f;
        }

        /* RESPONSIVO */
        @media(max-width: 900px){
            .container-blanco { padding: 30px; }
            .mision-text-center h2 { font-size: 28px; }
            .azar-container h2 { font-size: 24px; }
        }

        @media(max-width: 600px){
            .container-blanco { padding: 20px; }
            .mision-text-center h2 { font-size: 24px; }
            .azar-container h2 { font-size: 20px; }
            .acordeon-btn { font-size: 16px; padding: 15px; }
            .acordeon-btn .circle { width: 25px; height: 25px; font-size: 14px; }
        }

       

@media (max-width: 768px) {
    header img {
        margin-bottom: 25px;
    }
}

@media (max-width: 768px) {
    .orange-rect {
        grid-template-columns: 1fr !important;
        padding: 20px;
        text-align: center;
    }

    .metric {
        flex-direction: column;
        gap: 10px;
    }

    .metric img {
        width: 60px !important;
    }
}

@media (max-width: 768px) {
    .resp-item {
        flex-direction: column;
        height: auto !important;
        padding: 20px;
        font-size: 16px;
        text-align: center;
    }

    .resp-item img {
        position: static !important;
        width: 150px !important;
        margin-bottom: 10px;
    }
}
@media (max-width: 768px) {
    body {
        overflow-x: hidden;
    }
}
@media (min-width: 769px) {
    header {
        padding-top: 0 !important;
        height: auto !important;
        min-height: unset !important;
    }
}

@media (max-width: 768px) {
    header {
        height: auto !important;
        min-height: unset !important;
        overflow: visible !important;
    }
}

@media (max-width: 768px) {
    header img {
        width: 100%;
        height: auto;
        display: block;
    }
}

@media (max-width: 768px) {
    .container-quienes {
        margin-top: 40px;
    }
}
@media (max-width: 768px) {
    .container-quienes {
        margin-top: 20px;
    }
}
.titulo-naranja {
    color: #ff9900;
}

.resp-item span {
    flex: 1;
}

.responsabilidad-container {
    max-width: 1200px;
    margin: auto;
}

/* BLOQUE */
.resp-item {
    display: flex;
    align-items: center;
    gap: 25px;

    margin: 20px 0;
    padding: 25px 30px;

    border-radius: 20px;
    color: white;
    font-size: 20px;
    font-weight: 600;

    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
}

/* HOVER */
.resp-item:hover {
    transform: translateY(-5px);
}

/* TEXTO */
/* TEXTO SIEMPRE CENTRADO */
.resp-item p {
    flex: 1;
    margin: 0;
    text-align: center;   /* CLAVE */
}

/* ICONO */
.icon {
    width: 70px;
    height: 70px;
}

/*  ALTERNAR AUTOMÁTICO */
.resp-item:nth-child(even) {
    flex-direction: row-reverse;
}

/*  COLORES (los tuyos mejorados) */
.naranja { background: linear-gradient(135deg, #ff8c00, #ff5e00); }
.azul    { background: linear-gradient(135deg, #4facfe, #00c6ff); }
.morado  { background: linear-gradient(135deg, #a18cd1, #fbc2eb); }
.verde  { background: linear-gradient(135deg, #0f9b0f, #a8e063); }
.rosado {
    background: linear-gradient(135deg, #d63384, #ff9ecf);
}

/* POPUP */
.popup-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.9);
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 20px;
  z-index: 999999;

  opacity: 0;
  visibility: hidden;
  transition: opacity 0.3s ease;
}

.popup-overlay.active {
  opacity: 1;
  visibility: visible;
}

.popup-content{
  position: relative;
  max-width: 600px;
  width: 100%;
  display:flex;
  justify-content:center;
}

.popup-content img {
  width: 100%;
  max-width: 600px;
  height: auto;
  max-height: 85vh;
  object-fit: contain;
  border-radius: 12px;
}

/* BOTÓN CERRAR */
.popup-close {
  position: absolute;
  top: 5px;              /* baja un poco */
  right: 5px;            /* la pega a la esquina de la imagen */
  background: red;
  color: white;
  border: none;
  width: 35px;
  height: 35px;
  border-radius: 50%;
  font-size: 18px;
  cursor: pointer;
  font-weight: bold;
  z-index: 999999;

  transform: none !important; /*  evita que otros estilos la muevan */
}
.popup-image-wrapper {
  position: relative !important;
  display: inline-block !important;
}

.popup-image-wrapper a {
  display: inline-block; /*  importante */
}

.popup-image-wrapper img {
  display: block;
  width: 100%;
}


.popup-close:hover {
  background: red;
}

/* MOBILE */
@media (max-width: 768px){
  .popup-content img{
    max-width: 90vw;
    max-height: 90vh;
  }
}
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const accBtns = document.querySelectorAll('.acordeon-btn');
            accBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    btn.classList.toggle('active');
                    const content = btn.nextElementSibling;
                    if(content.style.display === 'block'){
                        content.style.display = 'none';
                    } else {
                        content.style.display = 'block';
                    }
                });
            });
        });
    </script>

</head>

<script>
document.addEventListener("DOMContentLoaded", function(){

    const abrir = document.getElementById("abrirTest");
    const modal = document.getElementById("modalTest");
    const cerrar = document.getElementById("cerrarTest");

    if(abrir && modal && cerrar){

        abrir.addEventListener("click", function(){
            modal.style.display = "flex";
        });

        cerrar.addEventListener("click", function(){
            modal.style.display = "none";
        });

        modal.addEventListener("click", function(e){
            if(e.target === modal){
                modal.style.display = "none";
            }
        });

    } else {
        console.error("No se encontraron elementos del popup");
    }

});

if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
  }
</script>

<body>

<!-- POPUP PRINCIPAL -->
<div id="popupOverlay" class="popup-overlay">
  <div class="popup-content">
    <div class="popup-image-wrapper">
      <?php
      $stmt = $conn->prepare("SELECT imagen_url, link_url FROM paginaweb_hn_sobre_inicio WHERE seccion='popup_principal'");
      $stmt->execute();
      $popup = $stmt->fetch(PDO::FETCH_ASSOC);

      $imagen = (!empty($popup['imagen_url'])) 
                ? $popup['imagen_url'] 
                : 'ImagesSV/pop up rse.webp';

      $link = (!empty($popup['link_url'])) 
              ? $popup['link_url'] 
              : '#';
      ?>

      <a href="<?= $link ?>" target="_blank">
        <img src="<?= $imagen ?>" alt="Popup principal">
      </a>

      <button class="popup-close" id="cerrarPopup">&times;</button>
    </div>
  </div>
</div>

    <header>
        <img src="<?= $datos['bannerImagen'] ?>">
    </header>

    <!-- =============== QUIÉNES SOMOS =============== -->
    <div class="container-quienes">
        <div class="quienes-text">
            <h2>¿Quiénes somos?</h2>
            
                <br>
<p><?= nl2br(htmlspecialchars($datos['quienesSomosTexto'])) ?></p>

        </div>

        <div class="orange-rect-wrapper">
            <div class="orange-rect">
                <div class="metric">
    <img src="<?= $datos['metric1Imagen'] ?>">
    <div class="metric-text">
        <p><?= htmlspecialchars($datos['metric1Texto']) ?></p>
    </div>
</div>

<div class="metric">
    <img src="<?= $datos['metric2Imagen'] ?>">
    <div class="metric-text">
        <p><?= htmlspecialchars($datos['metric2Texto']) ?></p>
    </div>
</div>
            </div>
        </div> 

        <div class="cuadros-extra">
            <div class="card-extra diversion"><?= htmlspecialchars($datos['cardDiversion']) ?></div>
<div class="card-extra confianza"><?= htmlspecialchars($datos['cardConfianza']) ?></div>
<div class="card-extra esperanza"><?= htmlspecialchars($datos['cardEsperanza']) ?></div>
<div class="card-extra solidaridad"><?= htmlspecialchars($datos['cardSolidaridad']) ?></div>
        </div>
    </div>
</body>
</html>

    <!-- =============== RESPONSABILIDAD CON CONTAINER BLANCO =============== -->
    <section class="responsabilidad-section">
    <h2 class="responsabilidad-title">¿CÓMO JUGAR CON RESPONSABILIDAD?</h2>

    <div class="container-blanco">
        <div class="responsabilidad-container">

            <div class="resp-item naranja">
                <img src="<?= $datos['resp1Imagen'] ?>" class="icon">
                <p><?= htmlspecialchars($datos['resp1Texto']) ?></p>
            </div>

            <div class="resp-item azul">
                <img src="<?= $datos['resp2Imagen'] ?>" class="icon">
                <p><?= htmlspecialchars($datos['resp2Texto']) ?></p>
            </div>

            <div class="resp-item morado">
                <img src="<?= $datos['resp3Imagen'] ?>" class="icon">
                <p><?= htmlspecialchars($datos['resp3Texto']) ?></p>
            </div>

            <div class="resp-item verde">
                <img src="<?= $datos['resp4Imagen'] ?>" class="icon">
                <p><?= htmlspecialchars($datos['resp4Texto']) ?></p>
            </div>

            <div class="resp-item rosado">
                <img src="/ImagesSV/tio loto promos.png" class="icon">
                <p>NO DEJÉS QUE EL JUEGO AFECTE TU RELACIÓN CON TU FAMILIA Y AMIGOS.</p>
            </div>

        </div>
    </div>
</section>


        </div>
    </div>
</section>

<!-- =============== NUESTRA MISIÓN Y QUÉ ES EL JUEGO DE AZAR CON CONTAINER BLANCO =============== -->
<section class="mision-azar-section">
    <div class="container-blanco">

        <!-- NUESTRA MISIÓN -->
        <div class="mision-text-center">
            <h2>NUESTRA MISIÓN ES QUE JUGUÉS CON RESPONSABILIDAD</h2>
            <p><?= nl2br(htmlspecialchars($datos['misionTexto'])) ?></p>
        </div>

        <!-- CONTAINER BLANCO PARA QUÉ ES EL JUEGO DE AZAR -->
        <div class="container-azar-blanco" style="background: #ffffff; border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
            <h2 class="titulo-naranja">
    <?= htmlspecialchars($datos['juegoAzarTitulo']) ?>
</h2>
            <br>
            <p><?= nl2br(htmlspecialchars($datos['juegoAzarTexto'])) ?></p>
        </div>

        <!-- ACORDEONES -->
        <div class="acordeon-container">
            <div class="acordeon">

                <button class="acordeon-btn">
    <?= htmlspecialchars($datos['acordeon1Titulo']) ?>
    <span class="circle">▼</span>
</button>
<div class="acordeon-content">
    <p><?= htmlspecialchars($datos['acordeon1Texto']) ?></p>
</div>

<button class="acordeon-btn">
    <?= htmlspecialchars($datos['acordeon2Titulo']) ?>
    <span class="circle">▼</span>
</button>
<div class="acordeon-content">
    <p><?= htmlspecialchars($datos['acordeon2Texto']) ?></p>
</div>                

              <button class="acordeon-btn">
    <?= htmlspecialchars($datos['acordeon3Titulo']) ?>
    <span class="circle">▼</span>
</button>
<div class="acordeon-content">
    <p><?= htmlspecialchars($datos['acordeon3Texto']) ?></p>
</div>

            </div>

            <!-- BOTÓN AZUL MODERNO -->
            <div class="boton-test">
    <button class="btn-azul" id="abrirTest">
        REALIZA UN TEST PARA AUTOEVALUACION <br>
        SOBRE JUEGO RESPONSABLE
    </button>
</div>

<div class="modal-test" id="modalTest">
    <div class="modal-contenido">
        <span class="cerrar-modal" id="cerrarTest">&times;</span>

        <h2>Test para autoevaluación sobre juego responsable</h2>

        <form method="post" action="">

  <div class="pregunta">
      <p> ¿Has jugado alguna vez para pagar tus deudas o resolver dificultades financieras?</p>
      <div class="opciones">
          <label><input type="radio" name="JugoParaDeudas" value="Sí" required> Sí</label>
          <label><input type="radio" name="JugoParaDeudas" value="No"> No</label>
      </div>
  </div>

  <div class="pregunta">
      <p> ¿Después de ganar, ¿sentís impulso de volver a jugar?
</p>
      <div class="opciones">
          <label><input type="radio" name="ImpulsoVolverAJugar" value="Sí" required> Sí</label>
          <label><input type="radio" name="ImpulsoVolverAJugar" value="No"> No</label>
      </div>
  </div>

  <div class="pregunta">
      <p> ¿Has pedido dinero prestado para jugar?</p>
      <div class="opciones">
          <label><input type="radio" name="PidioPrestamo" value="Sí" required> Sí</label>
          <label><input type="radio" name="PidioPrestamo" value="No"> No</label>
      </div>
  </div>

  <div class="pregunta">
      <p> ¿Has sentido remordimientos después de jugar?</p>
      <div class="opciones">
          <label><input type="radio" name="Remordimientos" value="Sí" required> Sí</label>
          <label><input type="radio" name="Remordimientos" value="No"> No</label>
      </div>
  </div>

  <div class="pregunta">
      <p> ¿Jugás hasta quedarte sin dinero?
</p>
      <div class="opciones">
          <label><input type="radio" name="QuedarseSinDinero" value="Sí" required> Sí</label>
          <label><input type="radio" name="QuedarseSinDinero" value="No"> No</label>
      </div>
  </div>

  <div class="pregunta">
      <p> Después de perder, ¿sentís que debés recuperar lo perdido?</p>
      <div class="opciones">
          <label><input type="radio" name="RecuperarPerdidas" value="Sí" required> Sí</label>
          <label><input type="radio" name="RecuperarPerdidas" value="No"> No</label>
      </div>
  </div>

  <div class="pregunta">
      <p> ¿El juego ha causado tristeza en tu vida?</p>
      <div class="opciones">
          <label><input type="radio" name="TristezaPorJuego" value="Sí" required> Sí</label>
          <label><input type="radio" name="TristezaPorJuego" value="No"> No</label>
      </div>
  </div>

  <button type="submit" class="btn-azul">Enviar Test</button>

</form>

<?php if ($mensajeExito): ?>
  <div class="alert-success" id="alertSuccess">
    <?= $mensajeExito ?>
  </div>

  <script>
    setTimeout(() => {
      const alert = document.getElementById('alertSuccess');
      if(alert) alert.style.display = 'none';
    }, 7000); // 7000 ms = 7 segundos
  </script>
<?php endif; ?>

    </div>
</div>
        </div>
    </div>

    <br>
<br>
</section>

<script>
  const alertBox = document.getElementById('alert-success');

  if (alertBox) {
    setTimeout(() => {
      alertBox.style.transition = 'opacity 0.5s ease';
      alertBox.style.opacity = '0';
      setTimeout(() => alertBox.remove(), 500);
    }, 5000); // desaparece después de 5 segundos
  }

if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
  }
 
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const popup = document.getElementById("popupOverlay");
  const cerrar = document.getElementById("cerrarPopup");

  // ✅ MOSTRAR POPUP
  if (popup) {
    popup.classList.add("active");
  }

  // ✅ BOTÓN CERRAR
  if (cerrar) {
    cerrar.addEventListener("click", function() {
      popup.classList.remove("active");
    });
  }

  // ✅ CLIC AFUERA
  popup.addEventListener("click", function(e) {
    if (e.target === popup) {
      popup.classList.remove("active");
    }
  });
});
</script>
