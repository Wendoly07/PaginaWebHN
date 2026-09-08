<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

try {
    $conn = new PDO(
        "sqlsrv:Server=srvdbcacdev.database.windows.net;Database=dblotocacdev",
        "LotoAdmin",
        "LotAdmin1.",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $stmt = $conn->query("SELECT * FROM paginaweb_hn_superpremio WHERE id = 1");
    $datos = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<?php
// ================= ÚLTIMO RESULTADO SUPER PREMIO =================
$stmtSP = $conn->prepare("
    SELECT TOP 1
        par1, par2, par3, par4, par5, par6
    FROM numeros_ganadores_sorteos_prod
    WHERE pais = 'Honduras'
      AND UPPER(game_name) = 'SUPER PREMIO'
      AND par1 IS NOT NULL   --  ESTA ES LA CLAVE
    ORDER BY draw_date DESC
");

$stmtSP->execute();
$superPremio = $stmtSP->fetch(PDO::FETCH_ASSOC);

// Valores por defecto
$sp = ['00','00','00','00','00','00'];

if ($superPremio) {
    $sp[0] = str_pad($superPremio['par1'] ?? '0', 2, '0', STR_PAD_LEFT);
    $sp[1] = str_pad($superPremio['par2'] ?? '0', 2, '0', STR_PAD_LEFT);
    $sp[2] = str_pad($superPremio['par3'] ?? '0', 2, '0', STR_PAD_LEFT);
    $sp[3] = str_pad($superPremio['par4'] ?? '0', 2, '0', STR_PAD_LEFT);
    $sp[4] = str_pad($superPremio['par5'] ?? '0', 2, '0', STR_PAD_LEFT);
    $sp[5] = str_pad($superPremio['par6'] ?? '0', 2, '0', STR_PAD_LEFT);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Super Premio</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>

* { margin:0; padding:0; box-sizing:border-box; font-family:'Helvetica Rounded', Arial, sans-serif; }
body { background:#fff; font-weight:600; }
/* HEADER */
.top { background:#e53935; display:flex; justify-content:center; padding:25px 10px; }
.top-content { display:flex; align-items:center; max-width:1300px; width:100%; position:relative; }
.top img { width:400px; height:auto; margin-top:20px; margin-left:80px; }
.ganador-box { text-align:center; color:white; margin-top:10px; margin-left:160px; }
.ganador { font-weight:900; font-size:26px; margin-bottom:15px; }
.nums { margin-bottom:10px; }
.num { width:65px; height:65px; line-height:65px; display:inline-block; background:#b71c1c; border-radius:50%; font-weight:bold; font-size:24px; color:white; margin:0 4px; border:2px solid white; text-align:center; }
.etiqueta-hola { background:yellow; color:#b71c1c; padding:6px 14px; border-radius:20px; font-weight:900; font-size:18px; }
/* MENÚ */
.menu { display:flex; justify-content:center; gap:18px; background:#e53935; padding:16px; flex-wrap:wrap; }
.menu a { background:white; color:#b71c1c; text-decoration:none; padding:10px 22px; border-radius:30px; font-size:14px; font-weight:bold; transition:0.3s; box-shadow:0 4px 10px rgba(0,0,0,.25); }
.menu a:hover { background:#f8f8f8; transform:translateY(-3px); box-shadow:0 6px 14px rgba(0,0,0,.3); }
/* RESULTADOS */
.resultados { border:1px solid #b71c1c; display:flex; max-width:1100px; margin:40px auto; background:white; border-radius:20px; box-shadow:0 5px 15px rgba(0,0,0,0.15); padding:25px; gap:20px; }
.resultados .col { flex:1; text-align:center; }
.izquierda { display:flex; flex-direction:column; justify-content:center; align-items:flex-start; gap:15px; min-height:100%; }
.izquierda h2 { font-size:42px; font-weight:900; color:#b71c1c; margin:0; line-height:1.1; }
.label-fecha { font-size:18px; font-weight:700; background:yellow; color:#b71c1c; padding:8px 16px; border-radius:20px; display:inline-block; margin-left:35px; }
/* FILTROS */
.filtros-calendario { display:flex; justify-content:center; gap:15px; margin-bottom:15px; }
.filtros-calendario select { padding:6px 10px; font-weight:700; border-radius:8px; border:1px solid #b71c1c; }
/* CALENDARIO */
.calendario-real { background:linear-gradient(145deg, #ffffff, #f4f4f4); border-radius:16px; padding:12px 14px; box-shadow:0 8px 18px rgba(0,0,0,0.15); border:1px solid #e5e5e5; max-width:260px; margin:auto; }
.calendario-real table { width:100%; border-collapse:collapse; text-align:center; }
.calendario-real th { font-size:12px; font-weight:800; color:#b71c1c; padding:6px 0; text-transform:uppercase; }
.calendario-real td { padding:7px 0; font-weight:700; font-size:13px; color:#444; cursor:pointer; transition:0.25s; border-radius:50%; }
.calendario-real td:hover { background:rgba(183,28,28,0.15); }
.calendario-real td.activo { background:linear-gradient(135deg, #b71c1c, #e53935); color:white; box-shadow:0 4px 10px rgba(0,0,0,0.25); }
/* SORTEOS */
.derecha { display:flex; flex-direction:column; justify-content:center; align-items:center; }
.derecha .sorteo { margin-bottom:20px; text-align:center; }
.derecha h3 { font-size:20px; font-weight:900; color:#b71c1c; margin-bottom:12px; text-align:center; letter-spacing:0.5px; }
.derecha .nums { display:flex; justify-content:center; gap:4px; flex-wrap:nowrap; }
.derecha .num { width:40px; height:40px; line-height:40px; font-size:16px; margin:0 2px; }
/* ACCORDION */
.accordion { max-width:1100px; margin:30px auto; border-radius:15px; overflow:hidden; background:white; border:2px solid #b71c1c; }
.accordion-header { padding:18px; text-align:center; color:white; font-weight:bold; font-size:24px; cursor:pointer; position:relative; background:#b71c1c; }
.accordion-header .arrow { position:absolute; right:20px; font-size:26px; }
.accordion-content { display:none; padding:20px; background:white; }
.sub-accordion-header { background:#b71c1c; color:white; padding:14px 20px; margin-bottom:10px; border-radius:12px; display:flex; justify-content:space-between; align-items:center; cursor:pointer; font-weight:600; font-size:20px; }
.sub-accordion-header span { display:flex; justify-content:center; align-items:center; width:32px; height:32px; border-radius:50%; background:#f28b82; color:white; font-size:18px; flex-shrink:0; transition:transform 0.3s; }
.sub-accordion-header.active span { transform:rotate(180deg); }
.sub-accordion-content { display:none; padding:15px 20px; background:white; border-radius:0 0 12px 12px; margin-bottom:12px; font-size:16px; font-weight:600; line-height:1.5; color:#333; }
.info-juego { margin-bottom:30px; padding:20px; background:#ffffff; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.08); }
.slogan { text-align:center; font-size:22px; font-weight:700; color:#b71c1c; margin-bottom:12px; line-height:1.4; }
.descripcion { text-align:center; font-size:17px; font-weight:600; color:#333; margin-bottom:20px; line-height:1.5; }
.subtitulo-rojo { text-align:center; font-size:20px; font-weight:700; color:#b71c1c; margin-bottom:15px; }
.linea-juego { display:flex; align-items:flex-start; gap:16px; margin-top:15px; font-size:16px; font-weight:600; color:#333; line-height:1.5; }
.img-super { width:75px; height:auto; flex-shrink:0; border-radius:6px; }
.reglamento { text-align:center; margin:40px 0; }
.reglamento button { background:#b71c1c; color:white; padding:14px 30px; border-radius:30px; border:none; font-weight:bold; font-size:16px; cursor:pointer; transition:0.3s; }
.reglamento button:hover { background:white; color:#b71c1c; border:2px solid #b71c1c; }
/* =========================
   FIX SUPER PREMIO - MÓVIL
   ========================= */
@media (max-width: 768px) {

  /* HEADER */
  .top {
    padding: 20px 10px;
  }

  .top-content {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }

  .top img {
    width: 220px;       /* logo más pequeño */
    margin: 0 0 15px 0; /* centrado */
  }

  .ganador-box {
    margin: 0;
  }

  .ganador {
    font-size: 20px;
  }

  .num {
    width: 48px;
    height: 48px;
    line-height: 48px;
    font-size: 18px;
  }

  .etiqueta-hola {
    font-size: 14px;
    margin-top: 10px;
  }

  /* MENÚ */
  .menu {
    gap: 10px;
    padding: 12px;
  }

  .menu a {
    font-size: 13px;
    padding: 8px 18px;
  }

  /* RESULTADOS */
  .resultados {
    flex-direction: column;
    padding: 20px 15px;
    gap: 25px;
  }

  .izquierda {
    align-items: center;
    text-align: center;
  }

  .izquierda h2 {
    font-size: 28px;
  }

  .label-fecha {
    margin-left: 0;
    font-size: 15px;
  }

  .derecha .nums {
    flex-wrap: wrap;
  }
}

/* FIX LOGO SUPER PREMIO EN MÓVIL */
@media (max-width: 768px) {
  .logo-sp {
    display: block;
    width: 220px;
    max-width: 90%;
    height: auto;
    margin: 0 auto 15px auto; /* CENTRADO */
  }
}

/* =========================
   AJUSTE HEADER MÓVIL
   ========================= */
@media (max-width: 768px) {

  /* Empuja todo el contenido del header hacia abajo para que no se tape */
  .top-content {
      margin-top: 250px; /* Ajusta este valor según lo que necesites */
  }

}

.footer {
    background: #0b5ea8;
    color: white;
    padding: 40px 20px;
}

.footer-column a {
    transition: 0.2s;
}

.footer-column a:hover {
    opacity: 0.7;
    text-decoration: underline;
}


/* =====================================================
   SUPER PREMIO – ACORDEÓN IGUAL A MULTI‑X (CORRECTO)
   ===================================================== */

/* CONTENEDOR */
.accordion {
  border-radius: 16px;
  overflow: hidden;
}

/* HEADER */
.accordion-header {
  background: #b71c1c;
  font-size: 22px;
  font-weight: 700;
  letter-spacing: 0.5px;
}

/* CONTENIDO GENERAL SIN ESPACIOS EXTRA */
.accordion-content {
  padding: 25px 25px 10px;
}

/* TEXTO INTERNO */
.accordion-content,
.accordion-content p,
.sub-accordion-content {
  text-align: center;
  font-weight: 600;
  color: #222;
}

/* QUITAR CAJAS Y SOMBRAS */
.info-juego {
  background: transparent;
  box-shadow: none;
  padding: 0;
  margin: 0;
}

/* ===== BLOQUE PRINCIPAL COMO MULTI‑X ===== */
.linea-juego {
  display: flex;
  flex-direction: column-reverse; /*  LOGO ABAJO */
  align-items: center;
  text-align: center;
  gap: 12px;                       /* espacio corto */
  background: transparent;
  box-shadow: none;
  padding: 0;
  margin: 0;
}

/* LOGO ABAJO DEL TEXTO */
.img-super {
  width: 160px;        /* similar a Multi‑X */
  max-width: 100%;
  height: auto;
  margin-top: 10px;    /* pequeño, NO gigante */
}

/* TEXTO PRINCIPAL */
.linea-juego p {
  max-width: 720px;
  line-height: 1.6;
  margin: 0;
}

/* ===== SUB‑ACORDEONES IGUAL MULTI‑X ===== */
.sub-accordion-header {
  background: #b71c1c;
  font-size: 16px;
  font-weight: 600;
  border-radius: 14px;
  margin-top: 12px;
}

.sub-accordion-header span {
  background: rgba(255,255,255,0.25);
  color: #fff;
}

/* CONTENIDO SUB */
.sub-accordion-content {
  max-width: 720px;
  margin: 0 auto 8px;
  font-size: 15px;
  line-height: 1.6;
}

/* POPUP */
/* POPUP */
.popup-overlay {
  position: fixed;
  inset: 0;
  background: rgba(220, 20, 60, 0.2);
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

.mensaje-info {
    font-size: 14px;
    font-weight: 800;
    color: #b71c1c;
    background: #FFFF00; /* amarillo suave como tu imagen */
    padding: 10px 14px;
    border-radius: 14px;
    margin-bottom: 12px;
    display: inline-block;
    text-align: center;
}


/* ======================
   CARDS ULTRA PREMIUM 
====================== */
.cards-premios {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 25px;
    flex-wrap: wrap;
}

/* CARD */

/* ======================
   CARDS ROJO PRO 
====================== */
.card-premio {
    width: 270px;
    height: 130px;

    border-radius: 20px;

    /* ✅ mismo rojo pero más oscuro que el fondo */
    background: linear-gradient(145deg, #8b0000, #c62828);

    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;

    /* ✅ clave: borde blanco */
    border: 2px solid rgba(255,255,255,0.6);

    /* ✅ profundidad REAL */
    box-shadow: 
        0 8px 20px rgba(0,0,0,0.35),
        inset 0 2px 6px rgba(255,255,255,0.2);

    transition: all 0.25s ease;
}

/* HOVER elegante */
.card-premio:hover {
    transform: translateY(-5px);
    box-shadow: 
        0 14px 30px rgba(0,0,0,0.4),
        inset 0 2px 8px rgba(255,255,255,0.25);
}

/* TEXTO */
.card-premio .titulo {
    font-size: 13px;
    font-weight: 900;
    letter-spacing: 1.5px;
    color: #ffffff;
    opacity: 0.9;
    margin-bottom: 6px;
}

/* MONTO */
.card-premio .monto {
    font-size: 34px;
    font-weight: 900;
    color: #ffffff;

    text-shadow: 
        0 3px 8px rgba(0,0,0,0.5);
}



/* brillo suave constante */
@keyframes brilloTexto {
    from {
        text-shadow:
            0 0 10px rgba(255,255,255,0.3),
            0 0 20px rgba(255,23,68,0.2);
    }
    to {
        text-shadow:
            0 0 20px rgba(255,255,255,0.6),
            0 0 35px rgba(255,23,68,0.4);
    }
}

/* ENTRADA SUAVE */
.card-premio {
    animation: aparecer 0.8s ease both;
}

.card-premio:nth-child(2){
    animation-delay: 0.2s;
}

@keyframes aparecer {
    from {
        opacity: 0;
        transform: translateY(30px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* MOBILE */
@media (max-width: 768px) {
    .cards-premios {
        flex-direction: column;
        align-items: center;
    }

    .card-premio {
        width: 90%;
        max-width: 320px;
    }
}

</style>


<script>
function formatearNumero(num){
    return Number(num).toLocaleString('es-HN');
}

function cargarJackpot(){

    fetch('/api/jackpot_superpremio.php')
    .then(res => res.json())
    .then(data => {

        if(data.error){
            console.error("Error API");
            return;
        }

        document.getElementById("montoSencillo").innerText =
            formatearNumero(data.jugada_sencilla);

        document.getElementById("montoDoble").innerText =
            formatearNumero(data.jugada_doble);
    })
    .catch(err => console.error("Error:", err));
}

// cargar al iniciar
document.addEventListener("DOMContentLoaded", cargarJackpot);
</script>


</head>
<body>

<!-- POPUP PRINCIPAL -->
<!-- POPUP PRINCIPAL -->

<!--
<div id="popupOverlay" class="popup-overlay">
  <div class="popup-content">
    <div class="popup-image-wrapper">

      <?php
      $stmt = $conn->prepare("SELECT imagen_url, link_url FROM paginaweb_hn_sobre_inicio WHERE seccion='popup_principal'");
      $stmt->execute();
      $popup = $stmt->fetch(PDO::FETCH_ASSOC);

      $imagen = (!empty($popup['imagen_url'])) 
                ? $popup['imagen_url'] 
                : 'ImagesSV';
      ?>

      <img src="<?= $imagen ?>" alt="Popup principal">

      <button class="popup-close" id="cerrarPopup">&times;</button>

    </div>
  </div>
</div>
-->

<!-- HEADER -->
<div class="top">
    <div class="top-content">
        <img src="<?= $datos['logo'] ?>" class="logo-sp">

        <div class="ganador-box">
            <div class="ganador">ÚLTIMO NÚMERO GANADOR</div>
            <div class="nums" id="ultimoResultado">
    <span class="num"><?= htmlspecialchars($sp[0]) ?></span>
    <span class="num"><?= htmlspecialchars($sp[1]) ?></span>
    <span class="num"><?= htmlspecialchars($sp[2]) ?></span>
    <span class="num"><?= htmlspecialchars($sp[3]) ?></span>
    <span class="num"><?= htmlspecialchars($sp[4]) ?></span>
    <span class="num"><?= htmlspecialchars($sp[5]) ?></span>
</div>
            <div class="etiqueta-hola">
                PRÓXIMO SORTEO EN VIVO:
                <span id="horaHeader">00</span>:
                <span id="minHeader">00</span>:
                <span id="segHeader">00</span>
            </div>


<div class="cards-premios">
    <div class="card-premio">
        <div class="titulo">JUGADA SENCILLA</div>
        <div class="monto" id="montoSencillo">0</div>
    </div>

    <div class="card-premio">
        <div class="titulo">JUGADA DOBLE</div>
        <div class="monto" id="montoDoble">0</div>
    </div>
</div>

        </div>
    </div>
</div>

<!-- MENÚ -->
<div class="menu">
    <a href="https://juega.loto.hn/websales/" target="_blank">JUGÁ AQUÍ</a>
    
</div>

<!-- RESULTADOS -->
<div class="resultados">
    <div class="col izquierda">
        <h2>RESULTADOS ANTERIORES</h2>
        <div class="label-fecha">SELECCIONÁ LA FECHA</div>
    </div>
    <div class="col calendario">
        <div class="filtros-calendario">
            <select id="mesFiltro">
                <option value="0">Enero</option><option value="1">Febrero</option><option value="2">Marzo</option><option value="3">Abril</option>
                <option value="4">Mayo</option><option value="5">Junio</option><option value="6">Julio</option><option value="7">Agosto</option>
                <option value="8">Septiembre</option><option value="9">Octubre</option><option value="10">Noviembre</option><option value="11">Diciembre</option>
            </select>
            <select id="anioFiltro">
                <option>2026</option>
            </select>
        </div>
        <div class="calendario-real">
            <table>
                <thead><tr><th>DOM</th><th>LUN</th><th>MAR</th><th>MIE</th><th>JUE</th><th>VIE</th><th>SAB</th></tr></thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <div class="col derecha">
        <div class="sorteo">
    <p class="mensaje-info">
        Elegí en el calendario miércoles o sábado para ver los números ganadores
    </p>
    <h3>SORTEO 9:00 P.M.</h3>

            <div class="nums" id="ultimoResultadoInferior">
                <span class="num">00</span><span class="num">00</span><span class="num">00</span><span class="num">00</span><span class="num">00</span><span class="num">00</span>
            </div>
        </div>
    </div>
</div>

<!-- ACCORDION -->
<div class="accordion">
    <div class="sub-accordion-header">
    <?= htmlspecialchars($datos['titulo1']) ?>
    <span>▼</span>
</div>

<div class="sub-accordion-content">
    <div class="linea-juego">
        <img src="/ImagesSV/logo-08-SUPERPREMIO.png" class="img-super">

        <p>
            <?= nl2br(htmlspecialchars($datos['contenido1'])) ?>
        </p>
    </div>
</div>

<div class="sub-accordion-header">
    <?= htmlspecialchars($datos['titulo2']) ?>
    <span>▼</span>
</div>
<div class="sub-accordion-content">
    <?= nl2br(htmlspecialchars($datos['contenido2'])) ?>
</div>

<div class="sub-accordion-header">
    <?= htmlspecialchars($datos['titulo3']) ?>
    <span>▼</span>
</div>
<div class="sub-accordion-content">
    <?= nl2br(htmlspecialchars($datos['contenido3'])) ?>
</div>
</div>

<!-- BOTÓN REGLAMENTO -->
<div class="reglamento">
    <a href="/ImagesSV/documentos/REGLAMENTO Loto SuperPremio.pdf" download>
        <button>DESCARGAR REGLAMENTO</button>
    </a>
</div>

<script>
// Accordion principal
document.querySelectorAll('.accordion-header').forEach(h => h.addEventListener('click', () => {
    const c = h.nextElementSibling;
    document.querySelectorAll('.accordion-content').forEach(cc => { if(cc!==c) cc.style.display='none'; });
    c.style.display = c.style.display==='block'?'none':'block';
}));

// Sub-accordion
document.querySelectorAll('.sub-accordion-header').forEach(h => h.addEventListener('click', () => {
    h.classList.toggle('active');
    const c = h.nextElementSibling;
    c.style.display = c.style.display==='block'?'none':'block';
}));

// Countdown
// =======================
// =======================
// =======================
// CONTADOR REAL CORRECTO
// =======================

function obtenerProximoSorteo() {
    const ahora = new Date();

    const diasSorteo = [3, 6]; // miércoles (3) y sábado (6)
    const horaSorteo = 21; // 9 PM

    let proximo = null;

    for (let i = 0; i < 7; i++) {

        let fecha = new Date(ahora);

        fecha.setDate(ahora.getDate() + i);
        fecha.setHours(horaSorteo, 0, 0, 0);

        let dia = fecha.getDay();

        if (diasSorteo.includes(dia)) {

            // Si es hoy pero ya pasó las 9pm → ignorar
            if (i === 0 && ahora > fecha) continue;

            proximo = fecha;
            break;
        }
    }

    return proximo;
}

function actualizarContador() {

    const ahora = new Date();
    const proximo = obtenerProximoSorteo();

    const diff = proximo - ahora;

    const horas = Math.floor(diff / (1000 * 60 * 60));
    const minutos = Math.floor((diff / (1000 * 60)) % 60);
    const segundos = Math.floor((diff / 1000) % 60);

    function pad(n){
        return n.toString().padStart(2,'0');
    }

    document.getElementById("horaHeader").innerText = pad(horas);
    document.getElementById("minHeader").innerText  = pad(minutos);
    document.getElementById("segHeader").innerText  = pad(segundos);
}

setInterval(actualizarContador, 1000);
actualizarContador();


</script>

<script>
function cargarResultadoSuperPremio(fecha){

  const fechaObj = new Date(fecha + "T00:00:00");
  const diaSemana = fechaObj.getDay(); // 0=Dom, 3=Mié, 6=Sáb

  // ✅ VALIDACIÓN: solo miércoles y sábado
  if(diaSemana !== 3 && diaSemana !== 6){
    document.getElementById('ultimoResultadoInferior').innerHTML = `
      <div style="
        background:#FFFF00;
        color:#b71c1c;
        padding:10px;
        border-radius:10px;
        font-weight:900;
        text-align:center;
      ">
        Día sin sorteo de Loto SuperPremio
      </div>
    `;
    return;
  }

  // ✅ Si es día válido, consulta API
  fetch(`/api/resultados_superpremio_por_fecha.php?fecha=${fecha}`)
    .then(r => r.json())
    .then(d => {

      if(!d || !d.par1){
        document.getElementById('ultimoResultadoInferior').innerHTML = `
          <div style="
            background:#FFFF00;
            color:#b71c1c;
            padding:10px;
            border-radius:10px;
            font-weight:900;
            text-align:center;
          ">
            No hay resultados disponibles
          </div>
        `;
        return;
      }

      document.getElementById('ultimoResultadoInferior').innerHTML = `
        <span class="num">${String(d.par1).padStart(2,'0')}</span>
        <span class="num">${String(d.par2).padStart(2,'0')}</span>
        <span class="num">${String(d.par3).padStart(2,'0')}</span>
        <span class="num">${String(d.par4).padStart(2,'0')}</span>
        <span class="num">${String(d.par5).padStart(2,'0')}</span>
        <span class="num">${String(d.par6).padStart(2,'0')}</span>
      `;
    })
    .catch(e => console.error("❌ error:", e));
}
</script>

<script>
function pad2(n){ return n.toString().padStart(2,'0'); }

document.addEventListener('DOMContentLoaded', () => {
  const mes = document.getElementById('mesFiltro');
  const ano = document.getElementById('anioFiltro');
  const cal = document.querySelector('.calendario-real tbody');

  function render(m,a){
    cal.innerHTML='';

    const f = new Date(a,m,1);
    const u = new Date(a,m+1,0);
    const i = f.getDay();

    let row = document.createElement('tr');

    for(let x=0;x<i;x++) row.appendChild(document.createElement('td'));

    const hoy = new Date();
    let activa = `${a}-${pad2(m+1)}-01`;

    for(let d=1; d<=u.getDate(); d++){
      const td=document.createElement('td');
      td.textContent=d;

      const fecha=`${a}-${pad2(m+1)}-${pad2(d)}`;

      if(d===hoy.getDate() && m===hoy.getMonth() && a===hoy.getFullYear()){
        td.classList.add('activo');
        activa=fecha;
      }

      td.onclick=()=>{
        cal.querySelectorAll('.activo').forEach(e=>e.classList.remove('activo'));
        td.classList.add('activo');

        cargarResultadoSuperPremio(fecha); 
      };

      row.appendChild(td);

      if((d+i)%7===0){
        cal.appendChild(row);
        row=document.createElement('tr');
      }
    }

    cal.appendChild(row);

    cargarResultadoSuperPremio(activa);
  }

  mes.onchange=()=>render(+mes.value,+ano.value);
  ano.onchange=()=>render(+mes.value,+ano.value);

  const h=new Date();
  mes.value=h.getMonth();
  ano.value=h.getFullYear();

  render(h.getMonth(), h.getFullYear());
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const popup = document.getElementById("popupOverlay");
  const cerrar = document.getElementById("cerrarPopup");

  popup.classList.add("active");

  cerrar.addEventListener("click", function() {
    popup.classList.remove("active");
  });

  popup.addEventListener("click", function(e) {
    if (e.target === popup) {
      popup.classList.remove("active");
    }
  });
});
</script>

</body>
</html>

