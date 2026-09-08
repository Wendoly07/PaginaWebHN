<?php
try {
    $conn = new PDO(
        "sqlsrv:Server=srvdbcacdev.database.windows.net;Database=dblotocacdev",
        "LotoAdmin",
        "LotAdmin1.",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch(PDOException $e){
    die("Error: " . $e->getMessage());
}
?>
<?php
// ================= RESULTADO ÚLTIMO LA DIARIA =================
$stmtDiaria = $conn->prepare("
    SELECT TOP 1 par1
    FROM numeros_ganadores_sorteos_prod
    WHERE pais = 'Honduras'
      AND game_name = 'La Diaria'
    ORDER BY draw_date DESC
");
$stmtDiaria->execute();
$diaria = $stmtDiaria->fetch(PDO::FETCH_ASSOC);

$digito1 = '0';
$digito2 = '0';

if ($diaria && $diaria['par1'] !== null) {
    $numero = str_pad($diaria['par1'], 2, '0', STR_PAD_LEFT);
    $digito1 = $numero[0];
    $digito2 = $numero[1];
}

// ================= RESULTADO ÚLTIMO DIARIA +1 =================
$stmtMas1 = $conn->prepare("
    SELECT TOP 1 par1
    FROM numeros_ganadores_sorteos_prod
    WHERE pais = 'Honduras'
      AND game_name = 'Diaria +1'
    ORDER BY draw_date DESC
");
$stmtMas1->execute();
$mas1 = $stmtMas1->fetch(PDO::FETCH_ASSOC);

$extra = $mas1 && $mas1['par1'] !== null ? $mas1['par1'] : '0';
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

<?php
// ================= ÚLTIMO RESULTADO JUGÁ TRES =================
$stmtJT = $conn->prepare("
    SELECT TOP 1 par1
    FROM numeros_ganadores_sorteos_prod
    WHERE pais = 'Honduras'
      AND game_name = 'Jugá Tres'
    ORDER BY draw_date DESC
");
$stmtJT->execute();
$juga3 = $stmtJT->fetch(PDO::FETCH_ASSOC);

// Valores por defecto
$jt1 = '0';
$jt2 = '0';
$jt3 = '0';

if ($juga3 && $juga3['par1'] !== null) {
    // Asegura 3 dígitos (ej: 606, 045, 003)
    $num = str_pad($juga3['par1'], 3, '0', STR_PAD_LEFT);

    $jt1 = $num[0];
    $jt2 = $num[1];
    $jt3 = $num[2];
}
?>

<?php
// ================= ÚLTIMO RESULTADO PREMIA2 =================
$stmtP2 = $conn->prepare("
    SELECT TOP 1
        par1, par2
    FROM numeros_ganadores_sorteos_prod
    WHERE pais = 'Honduras'
      AND game_name = 'Premia2'
    ORDER BY draw_date DESC
");
$stmtP2->execute();
$premia2 = $stmtP2->fetch(PDO::FETCH_ASSOC);

// Valores por defecto
$p2a = '0';
$p2b = '0';
$p2c = '0';
$p2d = '0';

if ($premia2) {
    // Asegurar 2 dígitos por par (20, 05, etc.)
    $par1 = isset($premia2['par1']) ? str_pad($premia2['par1'], 2, '0', STR_PAD_LEFT) : '00';
    $par2 = isset($premia2['par2']) ? str_pad($premia2['par2'], 2, '0', STR_PAD_LEFT) : '00';

    // Separar dígitos
    $p2a = $par1[0]; // 2
    $p2b = $par1[1]; // 0
    $p2c = $par2[0]; // 2
    $p2d = $par2[1]; // 9
}
?>

<?php
// ================= ÚLTIMO RESULTADO PEGA 3 =================
$stmtPega3 = $conn->prepare("
    SELECT TOP 1
        par1, par2, par3
    FROM numeros_ganadores_sorteos_prod
    WHERE pais = 'Honduras'
      AND game_name = 'Pega3'
    ORDER BY draw_date DESC
");
$stmtPega3->execute();
$pega3 = $stmtPega3->fetch(PDO::FETCH_ASSOC);

// Valores por defecto
$p3_1 = '00';
$p3_2 = '00';
$p3_3 = '00';

if ($pega3) {
    $p3_1 = str_pad($pega3['par1'], 2, '0', STR_PAD_LEFT);
    $p3_2 = str_pad($pega3['par2'], 2, '0', STR_PAD_LEFT);
    $p3_3 = str_pad($pega3['par3'], 2, '0', STR_PAD_LEFT);
}
?>

<?php
// ================= ÚLTIMO RESULTADO MULTI-X =================
$stmtMX = $conn->prepare("
    SELECT TOP 1
        par1, par2
    FROM numeros_ganadores_sorteos_prod
    WHERE pais = 'Honduras'
      AND game_name = 'Multi-X-LD'
    ORDER BY draw_date DESC
");
$stmtMX->execute();
$multix = $stmtMX->fetch(PDO::FETCH_ASSOC);

// Valores por defecto
$mx1 = '0';
$mx2 = '0';
$mxExtra = '0';

if ($multix) {
    // par1 → dos dígitos (87 → 8 y 7)
    if (!empty($multix['par1'])) {
        $par1 = str_pad($multix['par1'], 2, '0', STR_PAD_LEFT);
        $mx1 = $par1[0];
        $mx2 = $par1[1];
    }

    // par2 → texto tal cual (2x)
    if (!empty($multix['par2'])) {
        $mxExtra = $multix['par2']; // "2x"
    }
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




<!-- POPUP PRINCIPAL-->
<!-- POPUP PRINCIPAL-->
<div id="popupOverlay" class="popup-overlay">
  <div class="popup-content">
    <div class="popup-image-wrapper">
      <?php
      $stmt = $conn->prepare("SELECT imagen_url, link_url FROM paginaweb_hn_sobre_inicio WHERE seccion='popup_principal'");
      $stmt->execute();
      $popup = $stmt->fetch(PDO::FETCH_ASSOC);

      // Imagen por defecto
      $imagen = (!empty($popup['imagen_url'])) 
                ? $popup['imagen_url'] 
                : 'ImagesSV/Turn.png';

      // Link por defecto (opcional)
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

<script>
document.addEventListener("DOMContentLoaded", function() {
  const popup = document.getElementById("popupOverlay");
  const cerrar = document.getElementById("cerrarPopup");

  // Activar popup al cargar
  popup.classList.add("active");

  // Cerrar con botón
  cerrar.addEventListener("click", function() {
    popup.classList.remove("active");
  });

  // Cerrar si hacen click fuera de la imagen
  popup.addEventListener("click", function(e) {
    if (e.target === popup) {
      popup.classList.remove("active");
    }
  });
});
</script>

<body> 

<script type="text/javascript">     (function(c,l,a,r,i,t,y){         c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};         t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;         y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);     })(window, document, "clarity", "script", "xmdqws96me"); </script>


  <style>
@font-face {
  font-family: 'HelveticaRounded';
  src: url('/fonts/HelveticaRoundedLTStd-Bd.ttf') format('truetype');
  font-weight: bold;
  font-style: normal;
  font-display: swap;
}
    html, body {
  font-family: 'HelveticaRounded', Arial, sans-serif !important;
  overflow-x: hidden;
  width: 100%;
}
* {
  font-family: 'HelveticaRounded', Arial, sans-serif !important;
}

    /* FUENTE PARA TODA LA PÁGINA */
    body, h1, h2, h3, h4, h5, h6, p, button, a, span, div {
      font-family: "Helvetica Rounded", "Helvetica Rounded Black", Arial, sans-serif !important;
    }

    /* ANIMACIÓN SUAVE PARA EL SCROLL */
    .resultados-box {
      transition: margin-top 0.3s ease !important;
    }

    .horarios {
    font-size: 22px;
    font-weight: bold;
    color: #0070c0; /* COLOR QUE PEDISTE */
    line-height: 1.5;
  }

  .boton {
    display: inline-block;
    background: white;
    border: 2px solid #0070c0;   /* CONTORNO ELEGANTE */
    color: #0070c0;
    padding: 12px 28px;
    margin-top: 15px;
    font-size: 18px;
    font-weight: bold;
    border-radius: 12px;         /* MODERNO */
    text-decoration: none;
    transition: 0.3s ease;
    box-shadow: 0px 4px 10px rgba(0, 112, 192, 0.25); /* SOMBRA PREMIUM */
  }

  .boton:hover {
    background: #0070c0;         /* AZUL AL PASAR */
    color: white;                /* TEXTO BLANCO */
    transform: translateY(-3px); /* EFECTO DE ELEVAR */
    box-shadow: 0px 8px 18px rgba(0, 112, 192, 0.40);
  }

  .resultados-header h2 {
    font-size: 34px;        /* Más grande */
    font-weight: 900;       /* Bold máximo */
    text-align: center;
    font-stretch: expanded;
    margin: 0;
  }

  .titulo-naranja {
    color: orange;
  }

  .titulo-azul {
    color: #0070c0;
  }
/* Contenedor de resultados */
.resultados-box {
  padding: 40px 20px;
  background-color: #f9f9f9;
  border-radius: 16px;
  box-shadow: 0px 8px 20px rgba(0,0,0,0.1);
  max-width: 1200px;
  margin: 0 auto 50px auto;
  transition: margin-top 0.3s ease;
}

/* Título */
.resultados-header h2 {
  font-size: 40px;
  font-weight: 900;
  text-align: center;
  font-stretch: expanded;
  margin-bottom: 30px;
}

.titulo-naranja {
  color: orange;
}

.titulo-azul {
  color: #0070c0;
}

/* Tarjetas */

.res-card:hover {
  transform: translateY(-5px);
  box-shadow: 0px 12px 25px rgba(0,0,0,0.25);
}



/* Números / esferas */
.numeros {
  display: flex;
  gap: 8px;
  margin-bottom: 15px;
  justify-content: center;
}

.bola-verde, .bola-amarilla {
  display: inline-block;
  width: 40px;
  height: 40px;
  line-height: 40px;
  text-align: center;
  border-radius: 50%;
  font-weight: bold;
  color: white;
  font-size: 18px;
}

.bola-verde {
  background-color: #28a745;
}

.bola-amarilla {
  background-color: #ffc107;
  color: #000;
}

/* Mantener botones tal como los tenías antes */
.btn-container {
  margin-top: auto; /*  empuja los botones abajo */
  display: flex;
  justify-content: center;
  gap: 10px;
}

.btn-container button {
  width: 140px;
}

/* Scroll horizontal */
.resultados-carousel::-webkit-scrollbar {
  height: 8px;
}

.resultados-carousel::-webkit-scrollbar-thumb {
  background: rgba(0,0,0,0.2);
  border-radius: 4px;
}

.resultados-carousel::-webkit-scrollbar-track {
  background: transparent;
}

/* Texto PRÓXIMO SORTEO */
.proximo {
  font-size: 26px;
  font-weight: 900;
  text-align: center;
  color: #003399;
  margin-top: 25px;
}

.youtube-video {
  flex: 1 1 500px;
  max-width: 700px;
  margin-left: -30px; /* Mueve solo el video 3 cm a la izquierda */
  margin-top: -80px;  /* Subir el video 2 cm más hacia arriba */
  border-radius: 15px; /* Redondea las esquinas del video */
}
.youtube-video iframe {
  width: 100%;
  height: 315px;
  border-radius: 15px; /* Redondea las esquinas del iframe */
}

.youtube-right {
  flex: 0 0 auto;
  min-width: 300px;
  max-width: 500px;
  
  display: flex;
  flex-direction: column;
  align-items: center; /* Centra todo el contenido dentro de este bloque */
}

.boton-container {
  display: flex;
  justify-content: center; /* centra horizontalmente el botón */
  width: 100%;             /* ocupa todo el ancho del contenedor */
  margin-top: 20px;        /* separación del texto */
}

.youtube-boton {
  padding: 12px 30px;
  border-radius: 30px;
  font-size: 18px;
  font-weight: bold;
  color: white; /* Texto blanco */
  background: orange; /* Fondo naranja */
  border: none;
  cursor: pointer;
  transition: transform 0.2s ease, background 0.3s ease;
}

.youtube-boton:hover {
  transform: scale(1.05);
  background: #e68928; /* Fondo naranja más oscuro cuando el botón es hover */
}

/* Texto semi-bold */
.youtube-info p {
  font-weight: 600;      /* semi-bold */
}

.hero,
.hero-carousel {
  overflow: hidden;
  max-width: 100vw;
}

.hero-slide {
  display: none;
  width: 100%;
}

.hero-slide.active {
  display: block;
}

.hero-banner {
  width: 100%;
  height: auto;
  display: block;
}

/* Responsive */
@media (max-width: 1024px) {
  .youtube-content {
    flex-direction: column;
    align-items: center;
  }
  .youtube-video, .youtube-right {
    margin-left: 0;
    transform: none;
  }
  .youtube-text h2 {
    font-size: 24px;
  }
  .youtube-info p {
    font-size: 15px;
  }
}
@media (max-width: 768px) {
  .banner-container, .banner-superpremio, .banner-principal {
    width: 100%;
    max-width: 100%;
    margin: 0 auto;
  }
}
/* ===== FIX RSE SOLO MOBILE ===== */
@media (max-width: 768px) {

  /* Apilamos todo vertical */
  .rse-content {
    display: block !important; /* cambiamos a block para móvil */
    text-align: center;        /* centra todo horizontalmente */
  }

  .rse-text {
    display: block !important;
    width: 100% !important;
    text-align: center !important;
  }

  .rse-image {
    display: block !important;
    width: 100% !important;
    text-align: center !important;
    margin: 0 auto !important;
    padding: 0 !important;
  }

  .rse-image img {
    display: inline-block !important;
    margin: 0 auto !important;
    max-width: 90% !important;
    height: auto !important;
  }

  .rse .boton-container {
    display: flex !important;
    justify-content: center !important;
  }

  .rse-text p {
    margin-left: 0 !important;
    text-align: center !important;
  }
}
@media (max-width: 768px) {
  .rse-image img {
    position: relative;
    left: -10px;  /* mueve un poco a la izquierda */
    top: 10px;    /* baja un poquito */
  }
}

/* ===== HERO RESPONSIVE MOVIL COMO PC ===== */
@media (max-width: 768px) {

  /* Baja todo el carousel para que el header no lo tape */
  .hero-carousel {
    margin-top: 200px !important;
  }

  .hero {
    display: flex !important;
    flex-direction: row !important; /* texto izquierda, modelo derecha */
    justify-content: space-between;
    align-items: center;
    position: relative !important;
    width: 95%;
    max-width: 100%;
    margin: 0 auto;
    
  }

  /* Texto a la izquierda */
  .texto-hero {
    flex: 1 1 40%;
    text-align: left !important;
    margin-left: 10px;
  }

  .texto-hero h1 {
    font-size: 1.2rem !important;
    line-height: 1.3 !important;
  }

  .texto-hero .horarios {
    font-size: 1rem !important;
    margin: 5px 0 10px 0 !important;
  }

  .texto-hero .boton {
    font-size: 0.9rem !important;
    padding: 8px 18px !important;
  }

  /* Modelo a la derecha */
  .hero img:not(.esfera) {
    flex: 1 1 55%;
    max-width: 100%;
    height: auto;
    display: block !important;
    margin: 0 auto;
    position: relative;
  }

  /* Esferas: redimensionar y reposicionar proporcionalmente */
  .esfera {
    width: 35px !important;
    height: 35px !important;
    line-height: 35px !important;
    font-size: 14px !important;
    position: absolute !important;
  }

  /* Ajustar posiciones de cada esfera */
  .esfera:nth-of-type(1) { top: 10% !important; left: 60% !important; }
  .esfera:nth-of-type(2) { top: 65% !important; left: 55% !important; }
  .esfera:nth-of-type(3) { top: 45% !important; left: 88% !important; }
}

@media (max-width: 768px) {

  /* Evitar que la imagen se estire */
  .hero img:not(.esfera) {
    flex: 1 1 55%;
    max-width: 100%;
    height: auto !important; /* asegura proporciones correctas */
    object-fit: contain; /* mantiene proporción */
    display: block !important;
    margin: 0 auto;
    position: relative;
  }

  /* Bola roja al lado izquierdo de la modelo */
  /* Ajusta según tu HTML: si la bola roja es la primera .esfera, se coloca aquí */
  .esfera:nth-of-type(1) { top: 40% !important; left: 42% !important; } /* bola roja */
  .esfera:nth-of-type(2) { top: 10% !important; left: 60% !important; }
  .esfera:nth-of-type(3) { top: 65% !important; left: 55% !important; }
  .esfera:nth-of-type(4) { top: 45% !important; left: 88% !important; }
}

/* ===== RESPONSIVE MÓVIL SOLO RESULTADOS-BOX ===== */
@media (max-width: 761px) {

  /* Ajuste del contenedor principal */
  .resultados-box {
    padding: 20px 10px;
    max-width: 95%;
  }

  /* Título centrado y más pequeño */
  .resultados-header h2 {
    font-size: 24px !important;
    line-height: 1.2;
    text-align: center;
  }

  #fecha-api {
    font-size: 20px !important;
  }

  /* Tarjetas más anchas y centradas */
  .res-card {
    width: 90% !important;
    max-width: 90% !important;
    margin: 0 auto !important;
  }

  /* Números centrados y más pequeños */
  .numeros {
    justify-content: center !important;
    gap: 5px !important;
  }

  .bola-verde, .bola-amarilla {
    width: 35px !important;
    height: 35px !important;
    line-height: 35px !important;
    font-size: 16px !important;
  }

  /* Botones apilados y centrados */
  .btn-container {
    flex-direction: column !important;
    gap: 8px !important;
  }

  .btn-jugar, .btn-info {
    font-size: 14px !important;
    padding: 8px 12px !important;
  }

  /* Próximo sorteo más pequeño */
  .proximo {
    font-size: 20px !important;
  }

  #diaSorteo {
    font-size: 14px !important;
  }
}

@media (max-width: 768px) {
  /* Asegurar que las tarjetas se expandan según su contenido */
  .res-card {
    display: flex;
    flex-direction: column;
    justify-content: space-between; /* fuerza que el contenido y los botones queden dentro */
    min-height: auto; /* elimina la altura fija si existía */
    padding-bottom: 20px; /* espacio extra para los botones */
  }

  /* Mantener los botones centrados y del mismo tamaño */
  .res-card .btn-container {
    display: flex;
    justify-content: center;
    gap: 10px;
    flex-shrink: 0; /* evitar que se compriman */
  }

  .res-card .btn-container button {
    width: 120px; /* mismo ancho para ambos botones */
    padding: 10px 0; /* altura uniforme */
    font-size: 16px;
  }
}

@media (max-width: 768px) {


  /* FORZAR BOTONES DENTRO DE LA TARJETA */
  .btn-container {
    margin-top: auto; /* ESTO ES LA CLAVE */
  }

  /* BOTONES MISMO TAMAÑO */
  .btn-jugar,
  .btn-info {
    width: 100%;
    max-width: 180px;
  }
}

@media (max-width: 768px) {

  /* BOTONES A LA PAR */
  .res-card .btn-container {
    flex-direction: row !important;
    justify-content: center !important;
    align-items: center;
    gap: 10px;
  }

  /* MISMO TAMAÑO */
  .res-card .btn-jugar,
  .res-card .btn-info {
    width: 140px;
    padding: 10px 0;
  }
}

@media (max-width: 768px) {
  #jackpot-num-banner {
    font-size: 32px !important; /* tamaño móvil */
    left: 55% !important;       /* opcional: lo centra mejor */
  }
}

@media (max-width: 768px) {
  #jackpot-num-banner {
    font-size: 24px !important;   /* MUCHO más pequeño */
    left: 75% !important;         /* lo centra horizontalmente */
    transform: translate(-50%, -50%) !important; /* centra perfecto */
    top: 50% !important;
    white-space: nowrap;          /* evita que se parta */
  }
}

/* ===== NOTICIAS RESPONSIVE MOVIL ===== */
@media (max-width: 768px) {

  /* Contenedor general */
  .noticias-box {
    flex-direction: column;
    padding: 20px 10px;
  }

  /* Columna izquierda arriba */
  .noticias-left {
    width: 100%;
    text-align: center;
    margin-bottom: 20px;
  }

  .noticias-left h3 {
    font-size: 22px;
  }

  .noticias-boton {
    margin-top: 10px;
  }

  /* Carrusel ocupa todo el ancho */
  .noticias-right {
    width: 100%;
    position: relative;
    overflow: hidden;
  }

  /* Carrusel horizontal con scroll */
  .carousel {
    display: flex;
    gap: 20px;
    overflow-x: auto;
    scroll-behavior: smooth;
    padding-bottom: 10px;
  }

  .carousel::-webkit-scrollbar {
    display: none; /* limpio en móvil */
  }

  /* Cards más grandes para dedo */
  .card {
    min-width: 85%;
    flex: 0 0 auto;
  }

  /* Flechas visibles y usables */
  .prev,
  .next {
    position: absolute;
    top: 45%;
    transform: translateY(-50%);
    background: rgba(0,0,0,0.6);
    color: #fff;
    border: none;
    font-size: 28px;
    padding: 10px 14px;
    border-radius: 50%;
    z-index: 10;
    cursor: pointer;
  }

  .prev {
    left: 5px;
  }

  .next {
    right: 5px;
  }
}

@media (max-width: 768px) {

  /* Contenedor principal */
  .noticias-box {
    flex-direction: column;
  }

  /* Columna izquierda */
  .noticias-left {
    width: 100%;
    text-align: center;
    margin-bottom: 20px;
  }

  /* Parte derecha */
  .noticias-right {
    width: 100%;
    position: relative;
  }

  /* Carrusel se vuelve columna */
  .carousel {
    display: flex;
    flex-direction: column;
    gap: 20px;
    transform: none !important;
  }

  /* Cada noticia ocupa todo el ancho */
  .carousel .card {
    min-width: 100%;
    max-width: 100%;
  }

  /* OCULTAMOS FLECHAS EN MÓVIL */
  .prev,
  .next {
    display: none !important;
  }
}

@media (max-width: 768px) {
  img[src="/ImagesSV/IMG_3933_00013.png"] {
    position: relative !important;
    left: 50% !important;
    transform: translateX(-50%) !important;
    margin: 0 !important;
    display: block;
    max-width: 100%;
    height: auto;
  }
}

@media (max-width: 768px) {

  /* SOLO mover la imagen de la modelo un poco a la izquierda */
  .hero img[src="/ImagesSV/modelo.png"] {
    position: relative;
    left: -25px;   /*  ajustá: -15px, -20px, -30px según necesites */
  }

}

/* SOLO MÓVIL */
@media (max-width: 768px) {
  .rse-content {
    display: flex;
    flex-direction: column; /* apila verticalmente */
    align-items: center;    /* centra el texto horizontalmente */
  }

  .rse-text {
    order: 1;               
    text-align: center;     
    margin-bottom: 15px;    
  }

  .rse-image {
    order: 2;               
    align-self: flex-start; /* la alinea a la izquierda */
    margin-left: 0;         
    transform: translateX(-2cm); /* Mueve la imagen 1cm a la izquierda */
  }

  .rse-image img {
    width: auto;
    max-width: 80%;         
    height: auto;
    display: block;
  }
}

/* ===== AJUSTE BANNERS SOLO MÓVIL ===== */
@media (max-width: 768px) {

  /* Contenedor general de banners (si existe) */
  .banner-container,
  .banner-superpremio,
  .banner-apostemos {
    margin-top: 10px !important;
    margin-bottom: 10px !important;
    padding-top: 0 !important;
    padding-bottom: 0 !important;
  }

  /* Imágenes de banners */
  .banner-container img,
  .banner-superpremio img,
  .banner-apostemos img {
    display: block;
    margin: 0 auto !important;
  }

}
/* ===== AJUSTE ESPACIOS BANNERS SOLO MÓVIL ===== */
@media (max-width: 768px) {

  /* Reduce los espacios en blanco forzados */
  div[style*="height: 50px"] {
    height: 15px !important; /* antes 50px */
  }

  /* Banner Superpremio */
  .banner-superpremio {
    margin-top: 18px !important;
    margin-bottom: 10px !important;
  }

  /* Banner Apostemos (el que tiene margin 80px) */
  a[href*="juega.loto.sv/fob"] > div {
    margin: 20px auto 10px auto !important; /* antes 80px */
  }

  /* Imágenes sin espacios raros */
  .banner-superpremio img,
  a[href*="juega.loto.sv/fob"] img {
    display: block;
    margin: 0 auto !important;
  }
}

/* Ajuste solo móvil para la sección YouTube */
@media (max-width: 768px) {
  .youtube-content {
    flex-direction: column !important; /* apilar video y texto */
    align-items: center !important;    /* centrar horizontalmente */
    gap: 15px !important;              /* espacio uniforme */
  }

  .youtube-video {
    width: 95% !important;             /* deja un pequeño margen a los lados */
    margin: 0 auto !important;         /* centrar */
  }

  .youtube-right {
    width: 95% !important;             /* mismo ancho que el video */
    margin: 0 auto !important;         /* centrar */
    text-align: center !important;     /* centrar texto y botón */
  }

  .youtube-text h2 {
    font-size: 20px !important;        /* reducir tamaño si es necesario */
    line-height: 1.3 !important;
  }

  .youtube-info p {
    font-size: 14px !important;        /* ajustar párrafos */
  }

  .boton-container {
    justify-content: center !important; /* centrar botón */
    margin-top: 10px !important;       /* separar un poco del texto */
  }
}

@media (max-width: 768px) {
  .youtube-video {
    margin-bottom: 4px !important; /* reduce espacio debajo del video */
  }

  .youtube-right {
    margin-top: 0 !important; /* elimina el espacio arriba del container naranja */
  }

  /* ajuste lateral fino */
  .youtube-video,
  .youtube-right {
    margin-left: 1px !important;
    margin-right: 15px;
  }

  /* espacio después de TODA la sección (para que no se pegue a banners) */
  .youtube {
    margin-bottom: 20px !important;
  }

  .youtube-inner {
    padding-bottom: 0;
  }
}
@media (max-width: 768px) {

  .video-subtext {
    margin-bottom: 4px !important;
  }

  .youtube-video,
  .youtube-right {
    margin-left: 2px !important;
    margin-right: 15px;
  }

  .youtube {
    margin-bottom: 20px !important;
  }

}

.hero-carousel {
  position: relative;
  overflow: visible;
}

.hero-slide {
  display: none;
}

.hero-slide.active {
  display: block;
}

/* Flechas */
/* Flechas modernas */
.carousel-btn {
  position: absolute;
  top: 40%;
  transform: translateY(-50%);
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background: rgba(0, 0, 0, 0.65);
  color: #fff;
  border: none;
  font-size: 22px;
  cursor: pointer;

  display: flex;
  align-items: center;
  justify-content: center;

  z-index: 9999;
}

/* Posición */
.carousel-btn.prev {
  left: 20px;
}

.carousel-btn.next {
  right: 20px;
}

/* Hover elegante */
.carousel-btn:hover {
  background: rgba(0, 0, 0, 0.75);
  transform: translateY(-50%) scale(1.1);
}

/* Click */
.carousel-btn:active {
  transform: translateY(-50%) scale(0.95);
}

/* Evita que los banners tapen las flechas */
.hero-slide a,
.hero-slide img {
  z-index: 1;
  position: relative;
}

/* Flechas siempre encima */
.carousel-btn {
  z-index: 10000;
  pointer-events: auto;
}

/* Mobile */
@media (max-width: 768px) {
  .carousel-btn {
    top: 42%;
    width: 44px;
    height: 44px;
    font-size: 20px;
  }

  .carousel-btn.prev {
    left: 8px;
  }

  .carousel-btn.next {
    right: 8px;
  }
}

@media (max-width: 768px) {
  .hero-carousel .carousel-btn {
    display: flex !important;
  }
}


/* ===== POPUP NEGRO ===== */
/* ===== POPUP NEGRO MEJORADO ===== */
.popup-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
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
  display: block;
}

.popup-close {
  position: absolute;
  top: 8px;
  right: 8px;
  background: rgba(0,0,0,0.7);
  color: white;
  border: none;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  font-size: 18px;
  cursor: pointer;
  font-weight: bold;
}

.popup-close:hover {
  background: red;
  transform: scale(1.1);
}
.popup-image-wrapper {
  position: relative;
  display: inline-block;
}

/* Tarjeta Dobletea tu Suerte */
.res-card.naranja {
  background-color: #EF6C00;
  border-radius: 20px;
  padding: 20px;
  text-align: center;
  color: white;
}

/* Contenedor de números */
.numeros {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-top: 15px;
}

/* Bolas grises */
.bola-gris {
  width: 45px;
  height: 45px;
  background: linear-gradient(145deg, #f2f2f2, #cfcfcf);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 18px;
  color: #333;
  box-shadow: 
    inset -3px -3px 6px rgba(0,0,0,0.15),
    inset 3px 3px 6px rgba(255,255,255,0.6),
    2px 2px 5px rgba(0,0,0,0.2);
}

.res-card.naranja .btn-jugar {
  background-color: white;
  color: #EF6C00;
  font-weight: bold;
}

.res-card.naranja .btn-info {
  background-color: rgba(255,255,255,0.2);
  color: white;
  border: 1px solid white;
}

@media (max-width: 768px){

  .popup-overlay{
    padding:10px;
  }

  .popup-content{
    display:flex;
    justify-content:center;
    align-items:center;
  }

  .popup-image-wrapper{
    display:flex;
    justify-content:center;
  }

  .popup-content img{
    max-width:90vw;
    max-height:90vh;
  }

}

.noticias-right {
  overflow: hidden;
  width: 100%;
}

.carousel {
  display: flex;
  gap: 20px;
  transition: transform 0.4s ease;
  will-change: transform;
}

.card {
  min-width: 300px;
  flex: 0 0 auto;
}

/*  DESCRIPCIÓN */
.card-content {
  padding: 10px;
}

.card-content p {
  display: block !important;
  font-size: 14px;
  color: #333;
  margin-top: 5px;

  /* opcional: limitar a 3 líneas */
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.card-content p {
  color: #fff;
}

.resultados-carousel-wrapper {
  position: relative;
  overflow: hidden;
}

/* Flechas */
.res-btn {
  position: absolute;
  top: 45%;
  transform: translateY(-50%);
  width: 45px;
  height: 45px;
  border-radius: 50%;
  background: rgba(0,0,0,0.7);
  color: #fff;
  border: none;
  font-size: 22px;
  cursor: pointer;
  z-index: 10;
}

.res-btn.prev { left: 10px; }
.res-btn.next { right: 10px; }

.res-btn:hover {
  background: rgba(0,0,0,0.9);
}

/* Colores nuevas tarjetas */
.res-card.morada { background: #9647a5; color: white; }
.res-card.azul { background: #11a1dc; color: white; }
.res-card.amarilla { background: #eea212; color: black; }

.res-card.rosada {
  background: #f662a7;
  color: white;
}

.bola-morada {
  background: #9647a5; /* índigo oscuro */
  color: white;
}

.bola-azul {
  background: #003399;
  color: white;
}

.bola-blanca {
  background: white;
  color: black;
  border: 2px solid #ccc;
}

.bola-naranja {
  background: orange;
  color: white;
}
.res-card.verde {
  background: #aeca36;
  color: white;
}

.res-card.roja {
  background: #e31f26;
  color: white;
}

.btn-banner {
  padding: 10px 18px;
  border-radius: 20px;
  font-size: 14px;
  font-weight: bold;
  border: none;
  cursor: pointer;
  transition: 0.3s;
}

/* Estilo oscuro (Apostemos) */
.btn-banner.negro {
  background: #000;
  color: #fff;
   border: 2px solid #fff;
}

/* Estilo amarillo (Loto Gaming) */
.btn-banner.amarillo {
  background: #ffc107;
  color: #000;
}

/* Botón claro */
.btn-banner.claro {
  background: #fff;
  color: #000;
}

/* Hover */
.btn-banner:hover {
  transform: scale(1.05);
}


.res-cards {
  display: flex;
  gap: 15px;
  transition: transform 0.4s ease;
  transform: translateX(0);
}

/* Pausa al pasar el mouse */
.res-cards:hover {
  animation-play-state: paused;
}


* CONTENEDOR */
.resultados-carousel-wrapper {
  position: relative;
  overflow: hidden;
  width: 100%;
}

/* TRACK */
.res-cards {
  display: flex;
  gap: 20px;
  transition: transform 0.4s ease;
}

/* IMAGEN */
.res-card img {
  max-height: 150px;
  object-fit: contain;
  margin: 0 auto;
}

/* NÚMEROS */
.numeros {
  display: flex;
  justify-content: center;
  gap: 8px;
}

/* BOTONES */
.btn-container {
  margin-top: auto;
  display: flex;
  justify-content: center;
  gap: 10px;
}

.btn-container button {
  width: 120px;
}

.res-prev, .res-next {
  position: absolute;
  top: 40%;
  transform: translateY(-50%);
  background: rgba(0,0,0,0.6);
  color: #fff;
  border: none;
  width: 45px;
  height: 45px;
  border-radius: 50%;
  cursor: pointer;
  z-index: 10;
}

.res-prev { left: 10px; }
.res-next { right: 10px; }

.res-prev:hover, .res-next:hover {
  background: rgba(0,0,0,0.8);
}

.res-cards {
  align-items: stretch;
}

.res-prev, .res-next {
  z-index: 9999;
  pointer-events: auto;
}

.resultados-carousel-wrapper {
  max-width: 100%;
}
.res-card:hover {
  transform: translateY(-8px) scale(1.02);
}


/* viewport del carrusel */
.resultados-carousel-wrapper {
  position: relative;
  overflow: hidden;
  max-width: 1200px;
  margin: 0 auto;
}

/* pista */
.res-cards {
  display: flex;
  gap: 20px;
  transition: transform 0.4s ease;
}

/* tarjeta */
.res-card {
  flex: 0 0 260px;
  height: 430px;
  border-radius: 20px;
  padding: 20px;

  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

/* imagen del juego */
.res-card img {
  max-height: 160px;
  object-fit: contain;
  display: block;
  margin: 0 auto;
}

/* bolas */
.numeros {
  display: flex;
  justify-content: center;
  gap: 8px;
}

/* botones */
.btn-container {
  margin-top: auto;
  display: flex;
  justify-content: center;
  gap: 10px;
}

.btn-container button {
  width: 120px;
}


/* ===== FIX DEFINITIVO CARRUSEL ===== */

/* ===== FIX FINAL - FORZAR VISIBILIDAD DE LA PRIMERA TARJETA ===== */
.res-cards {
  display: flex;
  gap: 20px;
  transition: transform 0.4s ease;
}

.resultados-carousel-wrapper {
  overflow: hidden;
  max-width: 1040px; /*  320 * 3 + gaps */
  margin: 0 auto;
}


.res-card {
  flex: 0 0 280px;   /*  tamaño equilibrado */
  max-width: 280px;
}


.res-cards {
  display: flex;
  gap: 20px;
  transition: transform 0.4s ease;
  padding-left: 5px; /*  arregla recorte izquierdo */
}


.res-cards {
  display: flex;
  gap: 20px;
  transform: translateX(0) !important; /*  evita que arranque corrido */
}
.resultados-carousel-wrapper {
  overflow: hidden;
  max-width: 900px; /*  EXACTO para 3 tarjetas */
  margin: 0 auto;
}


.slider-container {
  position: relative;
  overflow: hidden;
   padding: 20px 10px;
}

.slider-track {
  display: flex;
  gap: 20px;
  transition: transform 0.4s ease;
}

/* CARD */

.game-card {
  min-width: 300px;   /* antes 260 */
  border-radius: 25px;
  padding: 25px;      /* más espacio */
  text-align: center;
  color: white;
}


.game-card img {
  width: 250px; 
  margin-bottom: 15px;
}

.ball {
  width: 45px;
  height: 45px;
  line-height: 45px;
  font-size: 21px;
}

/* TEMAS */
.green-theme { background: #aeca36; }
.red-theme { background: #e51b23; }
.pink-theme { background: #f662a7; }
.purple-theme { background: #9647a5; }
.blue-theme { background: #11a1dc; }
.yellow-theme { background: #f4c300; }

/* BOLAS */

/* BOLAS */
.ball {
  display: inline-block;
  width: 42px;
  height: 42px;
  margin: 2px;
  border-radius: 50%;
  line-height: 42px;
  font-weight: bold;
}

.ball {
  box-shadow: inset 0 -2px 4px rgba(0,0,0,0.2);
}

.green { background: #2e7d32; }
.yellow { background: #ffd54f; color: black; }
.purple { background: #6a1b9a; }
.blue { background: #0288d1; }
.white { background: white; color: black; }
.orange { background: orange; }

/* BOTONES */
.actions {
  margin-top: 12px;
}


.actions button {
  margin: 6px;
  padding: 10px 16px;
  border-radius: 25px;
  cursor: pointer;
  font-weight: bold;
  transition: all 0.3s ease;

  border: 2px solid white; /* ✅ AHORA LOS DOS TIENEN BORDE */
}

.info-btn {
  border: 2px solid white;
}
.play-btn {
  border: none;
}


 .green-theme .play-btn { background: #2e7d32; color: white; }
.red-theme .play-btn { background: #c62828; color: white; }
.pink-theme .play-btn { background: #d81b60; color: white; }
.purple-theme .play-btn { background: #6a1b9a; color: white; }
.blue-theme .play-btn { background: #1565c0; color: white; }
.yellow-theme .play-btn { background: #f9a825; color: black; }

.play-btn:hover {
  background: white;   /*  se vuelve blanco */
  color: black;
}

.info-btn {
  background: transparent;
  color: white;
  border: 2px solid white;
}

.info-btn:hover {
  background: white;
  color: black;
}

.actions button:hover {
  transform: scale(1.05);
}


.play-btn {
  background: white;
  color: black;
}

.info-btn {
  background: transparent;
  border: 2px solid white;
  color: white;
}

/* FLECHAS */
.slider-arrow {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: orange;
  border: none;
  border-radius: 50%;
  width: 45px;
  height: 45px;
  color: white;
  font-size: 22px;
  cursor: pointer;
}

.left { left: 10px; }
.right { right: 10px; }

.slider-arrow {
  z-index: 100; /*  SIEMPRE visibles */
  box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}
.slider-container {
  max-width: 1200px;
  margin: auto;
}

.balls {
  display: flex;
  align-items: center;   /*  misma línea */
  justify-content: center;
  gap: 8px;
}


.ball-img {
  width: 45px;
  height: 45px;

  object-fit: cover;   /*  CAMBIA ESTO */
  border-radius: 50%;  /*  forma de esfera */

  display: inline-block;

  flex-shrink: 0;
}


.ball.special {
  padding: 0;
  overflow: hidden;
  background: transparent; /* quita color de bola */
}
.ball.special img {
  width: 100%;
  height: 100%;
  object-fit: contain; /*  mantiene proporción */
}
.ball.special img {
  transform: scale(0.9); /* lo encoge ligeramente elegante */
}

.mas1 {
  background-image: url('/ImagesSV/logo-13-MÁS 1.png');
  background-repeat: no-repeat;
  background-position: center;

  background-size: 135%; /*  AQUÍ decides qué tan grande se ve */
}

.ball.mas1 {
  position: relative;
  background: #aeca36; /* color base de la esfera */
}

.ball.mas1 img {
  position: absolute;

  top: 50%;
  left: 50%;

  transform: translate(-50%, -50%) scale(1.4); /*  centrado + tamaño */

  width: 100%;
  height: 100%;

  object-fit: contain;
}

/* ===== BANNERS SUPERPREMIO Y APOSTEMOS ===== */
.banners-extra-home {
    width: 100%;
    max-width: 1200px;
    margin: 20px auto 40px;
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.banner-extra-home-link {
    display: block;
    width: 100%;
    text-decoration: none;
}

.banner-extra-home-img {
    display: block;
    width: 100%;
    height: auto;
    border-radius: 16px;
    object-fit: contain;
}

@media (max-width: 768px) {
    .banners-extra-home {
        width: 95%;
        margin: 15px auto 25px;
        gap: 15px;
    }

    .banner-extra-home-img {
        border-radius: 10px;
    }
}

  </style>

  <div></div>

<?php
$stmt = $conn->prepare("
    SELECT imagen_url, link_url, titulo
    FROM paginaweb_hn_sobre_inicio 
    WHERE seccion = 'banner_principal'
      AND imagen_url IS NOT NULL
      AND imagen_url <> ''
    ORDER BY orden ASC
");
$stmt->execute();
$banners = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="hero-carousel">

  <button type="button" class="carousel-btn prev">&#10094;</button>
  <button type="button" class="carousel-btn next">&#10095;</button>

  <div class="hero-slide active">
    <div class="hero" style="position: relative;">
      <div class="texto-hero">
        <h1>SINTONIZÁ EL PRÓXIMO SORTEO EN VIVO A LAS</h1>
        <div class="horarios">11:00 AM, 3:00 PM <br> Y 9:00 PM</div>

        <a href="https://www.youtube.com/results?search_query=loto+honduras" class="boton">
          MÍRALO AQUÍ >
        </a>
      </div>

      <img src="/ImagesSV/modelo.png" alt="Conductora">
      <img src="/ImagesSV/Esfera 3.png" class="esfera" style="position:absolute; width:5vw; top:20%; left:65%;">
      <img src="/ImagesSV/Esfera 9.png" class="esfera" style="position:absolute; width:5vw; top:70%; left:59%;">
      <img src="/ImagesSV/Esfera 11.png" class="esfera" style="position:absolute; width:5vw; top:50%; left:94%;">
    </div>
  </div>

  <?php foreach($banners as $b): ?>
    <div class="hero-slide">
      <a href="<?= htmlspecialchars(!empty($b['link_url']) ? $b['link_url'] : '#') ?>" target="_blank">
        <img 
          src="<?= htmlspecialchars($b['imagen_url']) ?>?v=<?= time() ?>" 
          class="hero-banner"
          alt="<?= htmlspecialchars($b['titulo'] ?? 'Banner principal') ?>"
        >
      </a>
    </div>
  <?php endforeach; ?>

</div>

 <?php
// ================= FECHA DEL ÚLTIMO SORTEO =================
$stmtFecha = $conn->prepare("
    SELECT TOP 1 draw_date
    FROM numeros_ganadores_sorteos_prod
    WHERE pais = 'Honduras'
      AND draw_date IS NOT NULL
      AND par1 IS NOT NULL
    ORDER BY draw_date DESC
");

$stmtFecha->execute();
$ultimoSorteo = $stmtFecha->fetch(PDO::FETCH_ASSOC);

$videoDate = 'Fecha no disponible';

if ($ultimoSorteo && !empty($ultimoSorteo['draw_date'])) {

    $timestamp = strtotime($ultimoSorteo['draw_date']);

    $formatter = new IntlDateFormatter(
        'es_HN',
        IntlDateFormatter::LONG,
        IntlDateFormatter::NONE,
        'America/Tegucigalpa'
    );

    $videoDate = $formatter->format($timestamp);
}
?>
  <div class="resultados-box">
    <div class="resultados-header">
        <h2>
            <span class="titulo-naranja">
                ÚLTIMOS RESULTADOS,
            </span>

            <span
                class="titulo-azul"
                id="fecha-api"
                style="font-size:45px; font-family:Nunito; font-weight:795;"
            >
                <?= htmlspecialchars(mb_strtoupper($videoDate, 'UTF-8')) ?>
            </span>
        </h2>
    </div>
    <br>
    <?php
// =================== Conexión ===================
try {
    $conn = new PDO(
        "sqlsrv:Server=srvdbcacdev.database.windows.net;Database=dblotocacdev",
        "LotoAdmin",
        "LotAdmin1.",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch(PDOException $e){
    die("Error de conexión: " . $e->getMessage());
}

// =================== Traer juegos ===================
$stmt = $conn->prepare("
    SELECT * 
    FROM paginaweb_sv_sobre_inicio
    WHERE seccion='juegos_home'
    ORDER BY orden ASC
");
$stmt->execute();
$juegos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<div class="slider-container">

  <!-- FLECHAS -->
  <button class="slider-arrow left">&#10094;</button>
  <button class="slider-arrow right">&#10095;</button>

  <!-- TRACK -->
  <div class="slider-track">

    <!-- 🟢 DIARIA -->
    <div class="game-card green-theme">
      <img src="/ImagesSV/logo-12- LA DIARIA.png">

      <div class="balls">
  <span class="ball green"><?= htmlspecialchars($digito1) ?></span>
  <span class="ball green"><?= htmlspecialchars($digito2) ?></span>

  
<span class="ball mas1">
  <img src="/ImagesSV/logo-13-MÁS 1.png">
</span>



  <span class="ball green"><?= htmlspecialchars($extra) ?></span>
</div>

      <div class="actions">
        
<a href="https://juega.loto.hn/websales/" target="_blank">
  <button class="play-btn">JUGAR</button>
</a>

       <a href="index.php?pag=diaria">
  <button class="info-btn">CONOCE MÁS</button>
</a>
      </div>
    </div>

    <!-- 🔴 SUPER PREMIO -->
    <div class="game-card red-theme">
      <img src="/ImagesSV/logo-08-SUPERPREMIO.png">

      <div class="balls">
        <span class="ball yellow"><?= htmlspecialchars($sp[0]) ?></span>
        <span class="ball yellow"><?= htmlspecialchars($sp[1]) ?></span>
        <span class="ball yellow"><?= htmlspecialchars($sp[2]) ?></span>
        <span class="ball yellow"><?= htmlspecialchars($sp[3]) ?></span>
        <span class="ball yellow"><?= htmlspecialchars($sp[4]) ?></span>
        <span class="ball yellow"><?= htmlspecialchars($sp[5]) ?></span>
      </div>

      <div class="actions">
        
<a href="https://juega.loto.hn/websales/" target="_blank">
  <button class="play-btn">JUGAR</button>
</a>

        <a href="index.php?pag=super_premio">
  <button class="info-btn">CONOCE MÁS</button>
</a>


      </div>
    </div>

    <!-- 🌸 JUGA 3 -->
    <div class="game-card pink-theme">
      <img src="/ImagesSV/logo-30-JUGA TRES.png">

      <div class="balls">
        <span class="ball white"><?= htmlspecialchars($jt1) ?></span>
        <span class="ball white"><?= htmlspecialchars($jt2) ?></span>
        <span class="ball white"><?= htmlspecialchars($jt3) ?></span>
      </div>

      <div class="actions">
        
<a href="https://juega.loto.hn/websales/" target="_blank">
  <button class="play-btn">JUGAR</button>
</a>

        <a href="index.php?pag=juga3">
  <button class="info-btn">CONOCE MÁS</button>
</a>
      </div>
    </div>

    <!-- 🟣 PREMIA2 -->
    <div class="game-card purple-theme">
      <img src="/ImagesSV/logo-20-PREMIA2.png">

      <div class="balls">
        <span class="ball yellow"><?= htmlspecialchars($p2a) ?></span>
        <span class="ball yellow"><?= htmlspecialchars($p2b) ?></span>
        <span class="ball purple"><?= htmlspecialchars($p2c) ?></span>
        <span class="ball purple"><?= htmlspecialchars($p2d) ?></span>
      </div>

      <div class="actions">
        
<a href="https://juega.loto.hn/websales/" target="_blank">
  <button class="play-btn">JUGAR</button>
</a>

         <a href="index.php?pag=premia2">
  <button class="info-btn">CONOCE MÁS</button>
</a>
      </div>
    </div>

    <!-- 🔵 PEGA 3 -->
    <div class="game-card blue-theme">
      <img src="/ImagesSV/logo-16-PEGA3.png">

      <div class="balls">
        <span class="ball blue"><?= htmlspecialchars($p3_1) ?></span>
        <span class="ball blue"><?= htmlspecialchars($p3_2) ?></span>
        <span class="ball blue"><?= htmlspecialchars($p3_3) ?></span>
      </div>

      <div class="actions">
        
<a href="https://juega.loto.hn/websales/" target="_blank">
  <button class="play-btn">JUGAR</button>
</a>

        <a href="index.php?pag=pega_3">
  <button class="info-btn">CONOCE MÁS</button>
</a>
      </div>
    </div>

    <!-- 🟠 MULTI X -->
    <div class="game-card red-theme">
      <br>
      <img src="/ImagesSV/Multi-X.svg">
<br>
<br>
<br>
      <div class="balls">
        <span class="ball orange"><?= htmlspecialchars($mxExtra) ?></span>
      </div>

      <div class="actions">
        
<a href="https://juega.loto.hn/websales/" target="_blank">
  <button class="play-btn">JUGAR</button>
</a>

        <a href="index.php?pag=multi_x">
  <button class="info-btn">CONOCE MÁS</button>
</a>
      </div>
    </div>

    <!-- 🟡 BINGO -->
    <div class="game-card yellow-theme">
      
      <img src="/ImagesSV/Bingo PNG (1).png">
    
<br>
<br>
      <div class="balls">
        <span class="ball white"><?= htmlspecialchars($bingoNums[0]) ?></span>
        <span class="ball white"><?= htmlspecialchars($bingoNums[1]) ?></span>
        <span class="ball white"><?= htmlspecialchars($bingoNums[2]) ?></span>
        <span class="ball white"><?= htmlspecialchars($bingoNums[3]) ?></span>
        <span class="ball white"><?= htmlspecialchars($bingoNums[4]) ?></span>
        <span class="ball white"><?= htmlspecialchars($bingoNums[5]) ?></span>
        <span class="ball white"><?= htmlspecialchars($bingoNums[6]) ?></span>
      </div>

      <div class="actions">
        
<a href="https://juega.loto.hn/websales/" target="_blank">
  <button class="play-btn">JUGAR</button>
</a>

        <a href="index.php?pag=bingo_con_todo">
  <button class="info-btn">CONOCE MÁS</button>
</a>
      </div>
    </div>

  </div>
</div>


<br>
<br>
 <p class="proximo" style="font-size: 28px; font-weight: 900; text-align: center; font-stretch: expanded;">
  <span style="color: #003399;">PRÓXIMO SORTEO EN VIVO:</span> 
  <span style="color: white;">
    <span id="hours">0</span>H :
    <span id="minutes">0</span>M :
    <span id="seconds">0</span>S
  </span>
</p>

<!-- Opcional: mostrar fecha -->
<div id="diaSorteo" style="color: white; font-size: 16px; text-align: center; margin-top: 10px;"></div>

</div>
  </div>
  <br>

<?php
// ====================== CONSULTA JACKPOT ======================
$stmt = $conn->prepare("
    SELECT TOP 1 * 
    FROM paginaweb_sv_sobre_inicio
    WHERE seccion = 'popup_home'
    ORDER BY orden ASC
");
$stmt->execute();
$jackpot = $stmt->fetch(PDO::FETCH_ASSOC);
?>
 
  <?php
$CHANNEL_ID = "UCnheFEkvbmp6Scx-eyep_KA"; // ID de tu canal
$rss_url = "https://www.youtube.com/feeds/videos.xml?channel_id=$CHANNEL_ID";

// Cargar el RSS
$rss = simplexml_load_file($rss_url);

// Datos por defecto
$videoId = "UCnheFEkvbmp6Scx-eyep_KA";
$videoTitle = "Sorteo LOTO 11:00 a.m 25 de Julio del 2025";
$videoDate = date('j \d\e F \d\e Y');

if ($rss && isset($rss->entry[0])) {
    $videoId = (string)$rss->entry[0]->children('yt', true)->videoId;
    $videoTitle = (string)$rss->entry[0]->title;
    $videoDate = date('j \d\e F \d\e Y', strtotime($rss->entry[0]->published));
}
?>

<br>
<br>
<br>
<br>

<!-- SECCIÓN YOUTUBE -->
<?php
$CHANNEL_ID = "UCnheFEkvbmp6Scx-eyep_KA"; // ID de tu canal
$rss_url = "https://www.youtube.com/feeds/videos.xml?channel_id=$CHANNEL_ID";

// Cargar el RSS
$rss = simplexml_load_file($rss_url);

// Datos por defecto
$videoId = "UCnheFEkvbmp6Scx-eyep_KA";
$videoTitle = "Sorteo LOTO 11:00 a.m 25 de Julio del 2025";

if ($rss && isset($rss->entry[0])) {
    $videoId = (string)$rss->entry[0]->children('yt', true)->videoId;
    $videoTitle = (string)$rss->entry[0]->title;
}
?>

<!-- SECCIÓN YOUTUBE -->
<div class="youtube">
  <div class="youtube-inner">
    <div class="youtube-content">

      <!-- Video -->
      <div class="youtube-video">
        <iframe width="100%" height="315"
          src="https://www.youtube.com/embed/<?php echo $videoId; ?>"
          title="YouTube video player"
          frameborder="0"
          allowfullscreen>
        </iframe>

        <!-- SOLO EL TÍTULO -->
        <p class="video-subtext"><?php echo $videoTitle; ?></p>
      </div>

      <?php
      // =================== Traer contenido dinámico ===================
      $stmt = $conn->prepare("
          SELECT TOP 1 * 
          FROM paginaweb_hn_sobre_inicio
          WHERE seccion='youtube_home'
          ORDER BY id ASC
      ");
      $stmt->execute();
      $youtube = $stmt->fetch(PDO::FETCH_ASSOC);
      ?>

      <!-- Texto a la derecha -->
      <div class="youtube-right">
        <div class="youtube-text-wrapper">

          <div class="youtube-text">
            <h2>
              <?= nl2br($youtube['titulo'] ?? "VISUALIZÁ NUESTROS\nSORTEOS EN YOUTUBE\nLOS 365 DÍAS DEL AÑO") ?>
            </h2>
          </div>

          <div class="youtube-info">
            <p><?= $youtube['texto'] ?? "Sintonizá en vivo los sorteos..." ?></p>
          </div>

          <div class="boton-container">
            <a href="<?= $youtube['link_url'] ?? '#' ?>" target="_blank">
              <button class="youtube-boton">Ver más sorteos</button>
            </a>
          </div>

        </div>
      </div>

    </div> <!-- youtube-content -->
  </div> <!-- youtube-inner -->
</div> <!--  ESTE ES EL QUE TE FALTABA -->

  <!-- Espacio en blanco -->
  <div style="height: 50px;"></div>


<?php
function normalizarImagen($url) {
    $url = trim($url ?? '');

    if ($url === '') {
        return '';
    }

    if (preg_match('/^https?:\/\//', $url)) {
        return $url;
    }

    if (substr($url, 0, 1) !== '/') {
        return '/' . $url;
    }

    return $url;
}
?>

<?php
// ================= BANNERS SUPERPREMIO Y APOSTEMOS =================
$stmtBannersExtra = $conn->prepare("
    SELECT
        id,
        LTRIM(RTRIM(seccion)) AS seccion,
        imagen_url,
        link_url
    FROM paginaweb_hn_sobre_inicio
    WHERE LTRIM(RTRIM(seccion)) IN (
        'banner_superpremio',
        'banner_apostemos'
    )
      AND activo = 1
      AND imagen_url IS NOT NULL
      AND LTRIM(RTRIM(imagen_url)) <> ''
    ORDER BY
        CASE
            WHEN LTRIM(RTRIM(seccion)) = 'banner_superpremio' THEN 1
            WHEN LTRIM(RTRIM(seccion)) = 'banner_apostemos' THEN 2
            ELSE 3
        END,
        id DESC
");

$stmtBannersExtra->execute();

$registrosBanners = $stmtBannersExtra->fetchAll(PDO::FETCH_ASSOC);

// Evitar mostrar registros duplicados.
$bannersExtra = [];

foreach ($registrosBanners as $banner) {
    $seccion = trim($banner['seccion']);

    if (!isset($bannersExtra[$seccion])) {
        $bannersExtra[$seccion] = $banner;
    }
}
?>

<div class="banners-extra-home">

<?php foreach ($bannersExtra as $banner): ?>

    <?php
    $imagenBanner = html_entity_decode(
        trim($banner['imagen_url']),
        ENT_QUOTES | ENT_HTML5,
        'UTF-8'
    );

    $linkBanner = trim($banner['link_url'] ?? '');

    if ($linkBanner === '') {
        $linkBanner = '#';
    }

    $sasLectura = "?sp=racw&st=2026-05-19T06:00:00Z&se=2030-01-01T06:00:00Z&spr=https&sv=2025-11-05&sr=c&sig=acdnTK5zyuX0b9XJYN1VmKcYyWySzsHzN4c8yVj6Zms%3D";

    // Si la URL no tiene SAS, se lo agrega
    if (strpos($imagenBanner, '?') === false) {
        $imagenBanner .= $sasLectura;
    }

    // PHP descarga la imagen desde Azure
    $chBanner = curl_init($imagenBanner);

    curl_setopt_array($chBanner, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_TIMEOUT => 20
    ]);

    $contenidoBanner = curl_exec($chBanner);
    $tipoBanner = curl_getinfo($chBanner, CURLINFO_CONTENT_TYPE);
    $estadoBanner = curl_getinfo($chBanner, CURLINFO_HTTP_CODE);

    curl_close($chBanner);
    ?>

    <?php if (
        $estadoBanner === 200 &&
        !empty($contenidoBanner) &&
        strpos($tipoBanner, 'image/') === 0
    ): ?>

        <a
            href="<?= htmlspecialchars($linkBanner, ENT_QUOTES, 'UTF-8') ?>"
            target="_blank"
            rel="noopener noreferrer"
            class="banner-extra-home-link"
        >
            <img
                src="data:<?= htmlspecialchars($tipoBanner, ENT_QUOTES, 'UTF-8') ?>;base64,<?= base64_encode($contenidoBanner) ?>"
                alt="<?= htmlspecialchars($banner['seccion'], ENT_QUOTES, 'UTF-8') ?>"
                class="banner-extra-home-img"
            >
        </a>

    <?php endif; ?>

<?php endforeach; ?>

</div>

<br>

  
<?php
$stmt = $conn->prepare("
    SELECT TOP 4
        id,
        titulo,
        descripcion,
        imagen_url,
        fecha
    FROM paginaweb_hn_noticias
    ORDER BY id DESC
");
$stmt->execute();

$noticiasHome = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="noticias-box">

  <div class="noticias-left">
    <h3>Noticias relevantes</h3>

    <button
      class="noticias-boton"
      onclick="window.location.href='index.php?pag=noticias';"
    >
      Ver más noticias
    </button>
  </div>

  <div class="noticias-right">

    <div class="carousel">

      <?php foreach ($noticiasHome as $n): ?>

        <?php
        $imagenNoticia = !empty($n['imagen_url'])
          ? $n['imagen_url']
          : 'ImagesSV/Noticias1.webp';
        ?>

        <a
          href="index.php?pag=noticia_detalle&id=<?= urlencode($n['id']) ?>"
          class="card"
          style="text-decoration:none;"
        >

          <img
            src="<?= htmlspecialchars($imagenNoticia) ?>"
            alt="<?= htmlspecialchars($n['titulo']) ?>"
          >

          <div class="card-content">

            <?php if (!empty($n['fecha'])): ?>
              <small class="fecha-noticia-home">
                <?= htmlspecialchars($n['fecha']) ?>
              </small>
            <?php endif; ?>

            <h4>
              <?= htmlspecialchars($n['titulo']) ?>
            </h4>

            

          </div>

        </a>

      <?php endforeach; ?>

    </div>

    <button type="button" class="prev">&#10094;</button>
    <button type="button" class="next">&#10095;</button>

  </div>

</div>


<script>
document.addEventListener("DOMContentLoaded", function () {

  const noticiasBox = document.querySelector('.noticias-box');
  const carousel = noticiasBox.querySelector('.carousel');
  const next = noticiasBox.querySelector('.next');
  const prev = noticiasBox.querySelector('.prev');

  let currentTranslate = 0;

  function getStep() {
    const containerWidth = noticiasBox.querySelector('.noticias-right').offsetWidth;
    return containerWidth; //  se mueve por pantalla completa (Netflix)
  }

  function getMaxTranslate() {
    return carousel.scrollWidth - carousel.parentElement.offsetWidth;
  }

  function moveCarousel() {
    const max = getMaxTranslate();

    if (currentTranslate < 0) currentTranslate = 0;
    if (currentTranslate > max) currentTranslate = max;

    carousel.style.transform = `translateX(-${currentTranslate}px)`;
  }

  next.addEventListener('click', () => {
    currentTranslate += getStep();
    moveCarousel();
  });

  prev.addEventListener('click', () => {
    currentTranslate -= getStep();
    moveCarousel();
  });

  window.addEventListener('resize', () => {
    currentTranslate = 0;
    moveCarousel();
  });

});
</script>


  <!-- Espacio en blanco -->
  <div style="height: 50px;"></div>

  <?php
// =================== RSE HOME ===================
$stmt = $conn->prepare("
    SELECT TOP 1 * 
    FROM paginaweb_hn_sobre_inicio
    WHERE seccion='rse_home'
    ORDER BY id ASC
");
$stmt->execute();
$rse = $stmt->fetch(PDO::FETCH_ASSOC);
?>

  <div class="rse"> 
  <div class="rse-content">

    <!-- Texto y número -->
    <div class="rse-text">
      <h2 class="numero" id="contador">0</h2>

      <p style="font-size:30px; font-weight:600; margin-left:25px;">
        <?= $rse['texto'] ?? 'DESDE 2023 HASTA 2025' ?>
      </p>
    </div>

    <!-- Imagen -->
    <div class="rse-image">
      <!--
      <img 
  src="<?= (!isset($rse['imagen_url']) || empty($rse['imagen_url'])) 
            ? 'ImagesSV/esr.webp' 
            : $rse['imagen_url'] ?>" 
  alt="Imagen RSE">
  -->
  <img src="ImagesSV/esr.webp" alt="Imagen RSE">
    </div>

  </div>

  <!-- Botón -->
  <div class="boton-container">
    <a href="index.php?pag=sobre_nosotros" class="rse-boton" style="text-decoration: none;">
      Conocé más
    </a>
  </div>
</div>

  <script>
function animarContadorSuave(idElemento, valorFinal, duracion) {
  const elemento = document.getElementById(idElemento);

  let startTime = null;

  function animar(timestamp) {
    if (!startTime) startTime = timestamp;
    const progreso = timestamp - startTime;

    const porcentaje = Math.min(progreso / duracion, 1);
    const valor = Math.floor(porcentaje * valorFinal);

    elemento.textContent = valor
      .toLocaleString("es-ES")
      .replace(/\./g, ",");

    if (porcentaje < 1) {
      requestAnimationFrame(animar);
    } else {
      //  reinicia suave
      setTimeout(() => {
        startTime = null;
        requestAnimationFrame(animar);
      }, 1500);
    }
  }

  requestAnimationFrame(animar);
}

animarContadorSuave("contador", <?= $rse['titulo'] ?? 1962862 ?>, 2000);
</script>

  <!-- SCRIPT SCROLL SUAVE (REEMPLAZA TRANSFORM POR MARGIN-TOP) -->
  <script>
    window.addEventListener('scroll', function() {
      const box = document.querySelector('.resultados-box');

      if (window.scrollY > 0) {
        box.style.marginTop = "-40px";
      } else {
        box.style.marginTop = "0";
      }
    });
  </script>

<script>
var diasSemana = ["Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado"];
var mesesEnletras = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];

const second = 1000,
      minute = second * 60,
      hour = minute * 60,
      day = hour * 24;

var hoy = new Date();
var dia = hoy.getDate();
var hora = hoy.getHours();
var HoraSorteo = "";

// NUEVA lógica con 3 sorteos
if (hora < 11) {
    HoraSorteo = 11;
} else if (hora >= 11 && hora < 15) {
    HoraSorteo = 15; // 3 PM
} else if (hora >= 15 && hora < 21) {
    HoraSorteo = 21; // 9 PM
} else {
    HoraSorteo = 11;
    dia += 1; // siguiente día
}

Number.prototype.padStart = function (n,str){
    return Array(n-String(this).length+1).join(str||'0')+this;
}

var fechaCompleta = diasSemana[hoy.getDay()] + " " + hoy.getDate() + " de " + mesesEnletras[hoy.getMonth()];

let countDown = new Date(hoy.getFullYear(), hoy.getMonth(), dia, HoraSorteo).getTime();

let x = setInterval(function() {
    let now = new Date().getTime();
    let distance = countDown - now;

    document.getElementById('hours').innerText = Math.floor((distance % day) / hour).padStart(2, "0");
    document.getElementById('minutes').innerText = Math.floor((distance % hour) / minute).padStart(2, "0");
    document.getElementById('seconds').innerText = Math.floor((distance % minute) / second).padStart(2, "0");

    document.getElementById('diaSorteo').innerText = fechaCompleta;

    if(distance <= 0){
        clearInterval(x);
        document.getElementById('countdown-container').innerText = "¡Sorteo en vivo!";
    }
}, second);
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const slides = document.querySelectorAll('.hero-carousel .hero-slide');
  const prevBtn = document.querySelector('.hero-carousel .carousel-btn.prev');
  const nextBtn = document.querySelector('.hero-carousel .carousel-btn.next');

  let currentSlide = 0;

  function showSlide(index) {
    if (!slides.length) return;

    slides.forEach(slide => slide.classList.remove('active'));
    slides[index].classList.add('active');
  }

  nextBtn.addEventListener('click', function () {
    currentSlide = (currentSlide + 1) % slides.length;
    showSlide(currentSlide);
  });

  prevBtn.addEventListener('click', function () {
    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
    showSlide(currentSlide);
  });
});
</script>


<script>
const track = document.querySelector('.slider-track');
const cards = document.querySelectorAll('.game-card');

const next = document.querySelector('.right');
const prev = document.querySelector('.left');

let index = 0;
const visibles = 3;
let size = cards[0].offsetWidth + 20;

window.addEventListener('resize', () => {
  size = cards[0].offsetWidth + 20;
});

next.addEventListener('click', () => {
  if (index < cards.length - visibles) {
    index++;
  }
  update();
});

prev.addEventListener('click', () => {
  if (index > 0) {
    index--;
  }
  update();
});


function update() {
  track.style.transform = `translateX(-${index * size}px)`;

  // controlar visibilidad de flechas
  prev.style.opacity = index === 0 ? "0.3" : "1";
  next.style.opacity = index >= cards.length - visibles ? "0.3" : "1";
}
</script>
</body>


