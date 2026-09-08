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

    $stmt = $conn->query("SELECT * FROM paginaweb_hn_bingocontodo WHERE id = 1");
    $datos = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<?php
// ================= ÚLTIMO RESULTADO BINGO CON TODO =================
$stmtBingo = $conn->prepare("
    SELECT TOP 1
        par1, par2, par3, par4, par5, par6, par7
    FROM numeros_ganadores_sorteos_prod
    WHERE pais = 'Honduras'
      AND game_name = 'Bingo Con Todo'
    ORDER BY draw_date DESC
");
$stmtBingo->execute();
$bingo = $stmtBingo->fetch(PDO::FETCH_ASSOC);

// Valores por defecto
$bingoNums = ['00','00','00','00','00','00','00'];

if ($bingo) {
    $bingoNums[0] = str_pad($bingo['par1'], 2, '0', STR_PAD_LEFT);
    $bingoNums[1] = str_pad($bingo['par2'], 2, '0', STR_PAD_LEFT);
    $bingoNums[2] = str_pad($bingo['par3'], 2, '0', STR_PAD_LEFT);
    $bingoNums[3] = str_pad($bingo['par4'], 2, '0', STR_PAD_LEFT);
    $bingoNums[4] = str_pad($bingo['par5'], 2, '0', STR_PAD_LEFT);
    $bingoNums[5] = str_pad($bingo['par6'], 2, '0', STR_PAD_LEFT);
    $bingoNums[6] = str_pad($bingo['par7'], 2, '0', STR_PAD_LEFT);
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
/* ===================================================
   HEADER BINGO CON TODO – DEGRADADO PREMIUM
   =================================================== */

/* ===================================================
   HEADER BINGO CON TODO – DEGRADADO LIMPIO Y CORRECTO
   =================================================== */
.top {
  background: linear-gradient(
    to bottom,
    #f29a12 0%,
    #f5a623 55%,
    #ffffff 70%,
    #ffffff 100%
  );

  padding-top: 90px;
  padding-bottom: 80px;

  display: flex;
  align-items: center;
  justify-content: center;
}


.top::after {
  content: "";
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 80px;
  background: linear-gradient(to bottom, transparent, rgba(0,0,0,0.15));
}

.top-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  max-width: 1200px;
  width: 100%;
  padding: 40px 20px;
}

.logo-sp {
  width: 420px;
  max-width: 100%;
}
.top-content > img {
  margin-right: 20px;
}

.ganador-box {
  width: 100%;
  max-width: 600px;
  text-align: center;
  color: white;
}
.top-content > img {
  margin-right: 40px;
}
.ganador {
  font-size: 32px;
  font-weight: 900;
  margin-bottom: 20px;
  text-align: center;
}

.nums {
  display: flex;
  justify-content: center;
  gap: 12px;
  margin-bottom: 20px;
}

.nums { margin-bottom:10px; }
.num {
  width:65px;
  height:65px;
  line-height:65px;
  display:inline-flex;
  justify-content:center;
  align-items:center;
  background: radial-gradient(circle at 30% 30%, #ffffff, #d9d9d9);
  color:#1d1d1d;
  border-radius:50%;
  font-weight:900;
  font-size:22px;
  margin:0 4px;
  border:2px solid #cfcfcf;
  box-shadow:
    inset -4px -6px 10px rgba(255,255,255,.6),
    inset 4px 6px 10px rgba(0,0,0,.15),
    0 6px 12px rgba(0,0,0,.25);
}
.etiqueta-hola {
  background: #fff200;
  color: #d87b00;
  padding: 10px 25px;
  border-radius: 40px;
  font-weight: 900;
  font-size: 18px;
  display: inline-block
}

/* MENÚ */
.menu {
  display: flex;
  justify-content: center;
  background: transparent;

  margin-top: -90px; /*  SUBE el botón al naranja */
}

.menu a {
  background: #e6e6e6;
  color: #e6e6e6;

  padding: 10px 28px;   /* 👈 más pequeño */
  border-radius: 30px;

  font-weight: 900;
  font-size: 14px;

  text-decoration: none; /* ✅ quita la raya */

  box-shadow: 0 6px 15px rgba(0,0,0,.25);
}



.menu a:hover { background:#ffd773; transform:translateY(-3px); box-shadow:0 6px 14px rgba(0,0,0,.3); }
/* RESULTADOS */
.resultados { border:1px solid #eea212; display:flex; max-width:1100px; margin:40px auto; background:white; border-radius:20px; box-shadow:0 5px 15px rgba(0,0,0,0.15); padding:25px; gap:20px; }
.resultados .col { flex:1; text-align:center; }
.izquierda { display:flex; flex-direction:column; justify-content:center; align-items:flex-start; gap:15px; min-height:100%; }
.izquierda h2 { font-size:42px; font-weight:900; color:#eea212; margin:0; line-height:1.1; }
.label-fecha { font-size:18px; font-weight:700; background:yellow; color:#eea212; padding:8px 16px; border-radius:20px; display:inline-block; margin-left:35px; }
/* FILTROS */
.filtros-calendario { display:flex; justify-content:center; gap:15px; margin-bottom:15px; }
.filtros-calendario select { padding:6px 10px; font-weight:700; border-radius:8px; border:1px solid #eea212; }
/* CALENDARIO */
.calendario-real { background:linear-gradient(145deg, #ffffff, #f4f4f4); border-radius:16px; padding:12px 14px; box-shadow:0 8px 18px rgba(0,0,0,0.15); border:1px solid #e5e5e5; max-width:260px; margin:auto; }
.calendario-real table { width:100%; border-collapse:collapse; text-align:center; }
.calendario-real th { font-size:12px; font-weight:800; color:#eea212; padding:6px 0; text-transform:uppercase; }
.calendario-real td { padding:7px 0; font-weight:700; font-size:13px; color:#444; cursor:pointer; transition:0.25s; border-radius:50%; }
.calendario-real td:hover { background:rgba(238,162,18,0.18); }
.calendario-real td.activo { background:linear-gradient(135deg, #eea212, #eea212); color:white; box-shadow:0 4px 10px rgba(0,0,0,0.25); }
/* SORTEOS */
.derecha { display:flex; flex-direction:column; justify-content:center; align-items:center; }
.derecha .sorteo { margin-bottom:20px; text-align:center; }
.derecha h3 { font-size:20px; font-weight:900; color:#eea212; margin-bottom:12px; text-align:center; letter-spacing:0.5px; }
.derecha .nums { display:flex; justify-content:center; gap:4px; flex-wrap:nowrap; }
.derecha .num {
  width:42px;
  height:42px;
  line-height:42px;
  font-size:16px;
}
/* ACCORDION */
.accordion { max-width:1100px; margin:30px auto; border-radius:15px; overflow:hidden; background:white; border:2px solid #eea212; }
.accordion-header { padding:18px; text-align:center; color:white; font-weight:bold; font-size:24px; cursor:pointer; position:relative; background:#eea212; }
.accordion-header .arrow { position:absolute; right:20px; font-size:26px; }
.accordion-content { display:none; padding:20px; background:white; }
.sub-accordion-header { background:#eea212; color:white; padding:14px 20px; margin-bottom:10px; border-radius:12px; display:flex; justify-content:space-between; align-items:center; cursor:pointer; font-weight:600; font-size:20px; }
.sub-accordion-header span {
  display:flex;
  justify-content:center;
  align-items:center;
  width:32px;
  height:32px;
  border-radius:50%;
  background:#2f3a40; /* color nuevo */
  color:white;
  font-size:18px;
  flex-shrink:0;
  transition:transform 0.3s;
}
.sub-accordion-header.active span { transform:rotate(180deg); }
.sub-accordion-content { display:none; padding:15px 20px; background:white; border-radius:0 0 12px 12px; margin-bottom:12px; font-size:16px; font-weight:600; line-height:1.5; color:#333; }
.info-juego { margin-bottom:30px; padding:20px; background:#ffffff; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.08); }
.slogan { text-align:center; font-size:22px; font-weight:700; color:#eea212; margin-bottom:12px; line-height:1.4; }
.descripcion { text-align:center; font-size:17px; font-weight:600; color:#333; margin-bottom:20px; line-height:1.5; }
.subtitulo-rojo { text-align:center; font-size:20px; font-weight:700; color:#eea212; margin-bottom:15px; }
.linea-juego { display:flex; align-items:flex-start; gap:16px; margin-top:15px; font-size:16px; font-weight:600; color:#333; line-height:1.5; }
.img-super { width:75px; height:auto; flex-shrink:0; border-radius:6px; }
.reglamento { text-align:center; margin:40px 0; }
.reglamento button { background:#eea212; color:white; padding:14px 30px; border-radius:30px; border:none; font-weight:bold; font-size:16px; cursor:pointer; transition:0.3s; }
.reglamento button:hover { background:white; color:#eea212; border:2px solid #eea212; }
/* =========================
   FIX SUPER PREMIO - MÓVIL
   ========================= */
@media (max-width: 768px) {

  /* HEADER */
  .top {
    padding: 20px 10px;
  }

 .top-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  max-width: 1200px;
  width: 100%;
  padding: 20px 40px;
}

  .top img {
    width: 260px;       /* logo más pequeño */
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
  background:#2f3a40;
  color:white;
  padding:6px 14px;
  border-radius:20px;
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
  background:#2f3a40;
  color:white;
  padding:8px 16px;
  border-radius:20px;
}
  .derecha .nums {
    flex-wrap: wrap;
  }
}

/* FIX LOGO SUPER PREMIO EN MÓVIL */
@media (max-width: 768px) {
  .logo-sp {
    display: block;
    width: 120px;
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

/* TOQUE PREMIUM */
.top {
  background: linear-gradient(
    to bottom,
    #f29a12 0%,
    #f5a623 70%,
    #ffffff 100%
  );

  padding-top: 80px;
  padding-bottom: 80px;

  display: flex;
  align-items: center;
  justify-content: center;
}



.accordion-header,
.sub-accordion-header {
  box-shadow: inset 0 -3px 0 rgba(0,0,0,0.15);
}

.ganador {
  text-shadow: 0 2px 6px rgba(0,0,0,0.35);
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
   BINGO CON TODO – ACORDEÓN IGUAL A MULTI‑X
   ===================================================== */

/* CONTENEDOR */
.accordion {
  border-radius: 16px;
  overflow: hidden;
}

/* HEADER PRINCIPAL */
.accordion-header {
  background: #eea212;
  font-size: 22px;
  font-weight: 700;
  letter-spacing: 0.5px;
}

/* CONTENIDO SIN ESPACIOS DE MÁS */
.accordion-content {
  padding: 22px 22px 10px;
}

/* TEXTO INTERNO */
.accordion-content,
.accordion-content p,
.sub-accordion-content {
  text-align: center;
  font-weight: 600;
  color: #222;
}

/* ELIMINAMOS CAJAS Y SOMBRAS */
.info-juego {
  background: transparent;
  box-shadow: none;
  padding: 0;
  margin: 0;
}

/* ===== BLOQUE PRINCIPAL COMO MULTI‑X ===== */
.linea-juego {
  display: flex;
  flex-direction: column-reverse;   /* ✅ LOGO ABAJO */
  align-items: center;
  text-align: center;
  gap: 12px;                        /* espacio corto */
  background: transparent;
  box-shadow: none;
  padding: 0;
  margin: 0;
}

/* LOGO BINGO ABAJO DEL TEXTO */
.img-super {
  width: 230px;     /* mismo tamaño que Multi‑X */
  max-width: 100%;
  height: auto;
  margin-top: 10px;
}

/* TEXTO PRINCIPAL */
.linea-juego p {
  max-width: 720px;
  line-height: 1.6;
  margin: 0;
}

/* ===== SUB‑ACORDEONES IGUAL MULTI‑X ===== */
.sub-accordion-header {
  background: #eea212;
  font-size: 16px;
  font-weight: 600;
  border-radius: 14px;
  margin-top: 12px;
}

/* FLECHA */
.sub-accordion-header span {
  background: rgba(0,0,0,0.25);
  color: #ffffff;
}

/* CONTENIDO SUB */
.sub-accordion-content {
  max-width: 720px;
  margin: 0 auto 8px;
  font-size: 15px;
  line-height: 1.6;
}

/* ===================================================
   ACORDEÓN BINGO – CLON VISUAL DE MULTI‑X
   =================================================== */

.accordion {
  max-width: 1100px;
  margin: 40px auto;
  border-radius: 18px;
  background: white;
  border: 2px solid #eea212;
}

/* HEADER PRINCIPAL */
.accordion-header {
  padding: 22px;
  font-size: 26px;             /* ⬆️ MÁS GRANDE */
  font-weight: 900;
  text-align: center;
  color: white;
  background: #eea212;
}

/* CONTENIDO */
.accordion-content {
  padding: 40px 35px;           /* ⬆️ MÁS AIRE */
}

/* TEXTO PRINCIPAL */
.descripcion,
.linea-juego p {
  font-size: 17px;
  line-height: 1.6;
}

/* SUB‑ACORDEONES */
.sub-accordion-header {
  font-size: 20px;              /* IGUAL MULTI‑X */
  padding: 16px 22px;
  font-weight: 700;
  margin-bottom: 14px;
}

/* Flecha */
.sub-accordion-header span {
  width: 34px;
  height: 34px;
  font-size: 18px;
}

/* Contenido sub */
.sub-accordion-content {
  font-size: 16px;
  padding: 18px 24px;
}
/* Imagen del acordeón – balance final */
.img-super {
  width: 240px;        /* similar a Multi‑X */
  margin: 30px auto 20px;
}

/* ===================================================
   AJUSTE HEADER BINGO CON TODO – NIVEL MULTI‑X
   =================================================== */



/* Caja de ganador un poco más abajo */


/* POPUP */
.popup-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.3);
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
                : 'ImagesSV/pop up bingo con todo.webp';

      $link = (!empty($popup['link_url'])) 
              ? $popup['link_url'] 
              : '#';
      ?>

      
        <img src="<?= $imagen ?>" alt="Popup principal">
     

      <button class="popup-close" id="cerrarPopup">&times;</button>
    </div>
  </div>
</div>
</head>
<body>

<!-- HEADER -->
<div class="top">
    <div class="top-content">
        
        <img src="<?= !empty($datos['logo']) ? $datos['logo'] : '/ImagesSV/Bingo PNG (1).png' ?>" class="logo-sp">
 

        <div class="ganador-box">
            <div class="ganador">ÚLTIMO NÚMERO GANADOR</div>
            <div class="nums" id="ultimoResultado">
    <span class="num"><?= htmlspecialchars($bingoNums[0]) ?></span>
    <span class="num"><?= htmlspecialchars($bingoNums[1]) ?></span>
    <span class="num"><?= htmlspecialchars($bingoNums[2]) ?></span>
    <span class="num"><?= htmlspecialchars($bingoNums[3]) ?></span>
    <span class="num"><?= htmlspecialchars($bingoNums[4]) ?></span>
    <span class="num"><?= htmlspecialchars($bingoNums[5]) ?></span>
    <span class="num"><?= htmlspecialchars($bingoNums[6]) ?></span>
</div>

            <br>
            <div class="etiqueta-hola">
                PRÓXIMO SORTEO EN VIVO:
                <span id="horaHeader">00</span>:
                <span id="minHeader">00</span>:
                <span id="segHeader">00</span>


            </div>
            <br>
            <br>
            <div class="texto-sorteo">
        Sorteo de Lunes a Domingo a las 4:00 PM
    </div>
        </div>
    </div>
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
            <h3>SORTEO 4:00 P.M.</h3>
            <div class="nums" id="ultimoResultadoInferior">
                <span class="num">00</span><span class="num">00</span><span class="num">00</span><span class="num">00</span><span class="num">00</span><span class="num">00</span><span class="num">00</span>
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
        <img src="/ImagesSV/Bingo PNG (1).png" class="img-super">

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
    <a href="/ImagesSV/documentos/Reglamento-SuperPremio Loto-SV 1.pdf" download>
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
const second=1000, minute=second*60, hour=minute*60, day=hour*24;
let now = new Date(),
    dia = now.getDate(),
    hora = now.getHours(),
    HoraSorteo = 16; // 4:00 PM hora Honduras

// Si ya pasó las 4 PM, cuenta para mañana
if (hora >= HoraSorteo) {
    dia += 1;
}
let countDown=new Date(now.getFullYear(), now.getMonth(), dia, HoraSorteo).getTime();
Number.prototype.padStart=function(n,str){return Array(n-String(this).length+1).join(str||'0')+this;}
setInterval(()=>{let now2=new Date().getTime(), dist=countDown-now2,h=Math.floor((dist%day)/hour).padStart(2,"0"), m=Math.floor((dist%hour)/minute).padStart(2,"0"), s=Math.floor((dist%minute)/second).padStart(2,"0"); document.getElementById("horaHeader").innerText=h; document.getElementById("minHeader").innerText=m; document.getElementById("segHeader").innerText=s; if(dist<=0){document.querySelector(".etiqueta-hola").innerText="¡SORTEO EN VIVO!";}}, second);


</script>

<script>
function cargarResultadoBingo(fecha){
  console.log(" BINGO fecha:", fecha);

  fetch(`/api/resultados_bingo_por_fecha.php?fecha=${fecha}`)
    .then(r => r.json())
    .then(d => {
      console.log("✅ respuesta:", d);

      if(!d){
        document.getElementById('ultimoResultadoInferior').innerHTML =
          '<span class="num">00</span>'.repeat(7);
        return;
      }

      document.getElementById('ultimoResultadoInferior').innerHTML = `
        <span class="num">${String(d.par1 || '0').padStart(2,'0')}</span>
        <span class="num">${String(d.par2 || '0').padStart(2,'0')}</span>
        <span class="num">${String(d.par3 || '0').padStart(2,'0')}</span>
        <span class="num">${String(d.par4 || '0').padStart(2,'0')}</span>
        <span class="num">${String(d.par5 || '0').padStart(2,'0')}</span>
        <span class="num">${String(d.par6 || '0').padStart(2,'0')}</span>
        <span class="num">${String(d.par7 || '0').padStart(2,'0')}</span>
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
    const inicio = f.getDay();

    let row = document.createElement('tr');

    for(let i=0;i<inicio;i++){
      row.appendChild(document.createElement('td'));
    }

    const hoy = new Date();
    let activa = `${a}-${pad2(m+1)}-01`;

    for(let d=1; d<=u.getDate(); d++){
      const td = document.createElement('td');
      td.textContent = d;

      const fecha = `${a}-${pad2(m+1)}-${pad2(d)}`;

      // marcar hoy
      if(
        d === hoy.getDate() &&
        m === hoy.getMonth() &&
        a === hoy.getFullYear()
      ){
        td.classList.add('activo');
        activa = fecha;
      }

      td.onclick = () => {
        cal.querySelectorAll('.activo')
          .forEach(x=>x.classList.remove('activo'));

        td.classList.add('activo');
        cargarResultadoBingo(fecha); // ✅ AQUI SE CONECTA TODO
      };

      row.appendChild(td);

      if((d + inicio) % 7 === 0){
        cal.appendChild(row);
        row = document.createElement('tr');
      }
    }

    cal.appendChild(row);

    // ✅ carga automática del día actual
    cargarResultadoBingo(activa);
  }

  mes.onchange = () => render(+mes.value, +ano.value);
  ano.onchange = () => render(+mes.value, +ano.value);

  const h = new Date();
  mes.value = h.getMonth();
  ano.value = h.getFullYear();

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