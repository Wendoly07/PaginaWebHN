<?php
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
 
// Obtener secciones por ID
$stmt = $conn->query("SELECT * FROM paginaweb_hn_apostemos");
 
$seccionesData = $stmt->fetchAll(PDO::FETCH_ASSOC);
$datos = [];
foreach($seccionesData as $s){
    $datos[$s['seccion']] = $s;
}
?>
<style>
/* ======== ESTILO GENERAL ======== */
body {
  margin: 0;
  padding: 0;
  font-family: "Segoe UI", sans-serif;
  background: #111;
  color: white;
  text-align: center;
}
 
/* ======== HERO PRINCIPAL ======== */
.hero-apostemos img {
  width: 100%;
  max-width: 1400px;
  border-radius: 12px;
  display: block;
  margin: 0px auto 20px; /* Subido */
  box-shadow: 0 6px 20px rgba(0,0,0,0.4);
}
 
/* ======== TÍTULO PRINCIPAL ======== */
.intro h2 {
  color: #FFFFFF;
  margin-top: 10px;
  font-size: 39px;
}
 
.intro p {
  max-width: 900px;
  margin: 0 auto;
  font-size: 18px;
  opacity: 0.9;
}
 
/* ======== SECCIONES DE CATEGORÍAS ======== */
.seccion {
  padding: 50px 20px;
  margin: 40px auto;
  border-radius: 16px;
  max-width: 1400px;
  color: white;
}
 
.seccion h3 {
  font-size: 32px;
  letter-spacing: 6px;
  margin-bottom: 10px;
  text-transform: uppercase;
}
 
.seccion p {
  font-size: 18px;
  opacity: 0.9;
  max-width: 900px;
  margin: auto;
}
 
.seccion img {
  width: 100%;
  max-width: 1200px;
  border-radius: 12px;
  margin-top: 25px;
  box-shadow: 0 6px 20px rgba(0,0,0,0.4);
}
 
/* ======== BOTONES ======== */
.btn-jugar {
  display: inline-block;
  margin-top: 20px;
  background: #ffaf37;
  color: #000;
  padding: 14px 30px;
  border-radius: 40px;
  font-weight: bold;
  text-decoration: none;
  box-shadow: 0 4px 10px rgba(0,0,0,0.3);
  transition: 0.2s;
}
 
.btn-jugar:hover {
  background: #ffc56e;
  transform: translateY(-3px);
}
 
/* ======== MÉTODOS DE RECARGA ======== */
.metodos {
  padding: 40px 20px;
  background: #222;
  margin-top: 60px;
  text-align: center;
}
 
.metodos h3 {
  color: #FFFFFF;
  font-size: 28px;
  margin-bottom: 30px;
}
 
.metodos .logos {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 20px;
}
 
.metodos .logos .boton-imagen {
  width: 220px;           /* ancho del botón */
  height: 100px;          /* alto del botón */
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
  border-radius: 12px;
  overflow: hidden;       /* para que la imagen no se salga del contenedor */
  box-shadow: 0 6px 20px rgba(0,0,0,0.4);
  transition: transform 0.2s, box-shadow 0.2s;
}
.metodos .logos .boton-imagen img {
  width: 100%;           /* la imagen ocupa todo el contenedor */
  height: 100%;          /* y todo el alto */
  object-fit: cover;     /* mantiene proporción y cubre todo */
}
 
.metodos .logos .boton-imagen:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.5);
}
.metodos .logos .fila {
  display: flex;
  justify-content: center;
  gap: 40px; /* separación entre imágenes */
}
 
.metodos .logos img {
  width: 200px;       /* ancho deseado */
  height: auto;       /* mantiene proporción original */
  padding: 15px;      /* más espacio para efecto de botón */
  border-radius: 12px;
  box-shadow: 0 6px 20px rgba(0,0,0,0.4);
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;
}
 
.metodos .logos img:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.5);
}
 
.btn-como-jugar img {
  margin-top: 25px;
  max-width: 250px; /* igual que otros botones */
  cursor: pointer;
}
 
.btn-como-jugar:hover {
  transform: translateY(-3px); /* sensación de “flotar” */
  box-shadow: 0 8px 15px rgba(0,0,0,0.4); /* resalta un poco */
}
 
/* ===== GAMING ===== */
.seccion.gaming {
  background: none; /* quita el morado */
  padding: 40px 20px;
}
 
.banner-gaming {
  width: 100%;
  max-width: 1200px;
  margin: 0 auto 20px auto;
  display: block;
  border-radius: 12px;
  box-shadow: 0 6px 20px rgba(0,0,0,0.4);
}
 
.titulo-gaming {
  font-size: 32px;
  letter-spacing: 2px;
  margin: 20px 0 10px 0;
  color: white;
}
 
.texto-gaming {
  font-size: 18px;
  opacity: 0.9;
  max-width: 900px;
  margin: 0 auto 20px auto;
  line-height: 1.4;
}
 
/* Botón de imagen con efecto hover */
.btn-como-jugar {
  display: inline-block;
  margin-top: 20px;
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;
}
 
.btn-como-jugar:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 15px rgba(0,0,0,0.4);
}
 
.imagen-principal {
  width: 100%;
  max-width: 1200px;
  margin: 20px auto 0 auto;
  display: block;
  border-radius: 12px;
  box-shadow: 0 6px 20px rgba(0,0,0,0.4);
}
 
/* Títulos y textos de Quiniela */
.titulo-quiniela {
  font-size: 32px;
  margin-top: 20px;
  color: #ff7f00;
}
 
.texto-quiniela {
  font-size: 18px;
  margin: 10px auto 20px auto;
  max-width: 900px;
  line-height: 1.4;
  color: white;
}
 
/* Botón Cómo Jugar */
.btn-como-jugar img {
  margin-top: 15px;
  max-width: 250px;
}
 
/* ===== PLAYLIST YOUTUBE ===== */
.apostemos-video-wrap {
  padding: 42px 20px 46px;
  background:
    radial-gradient(circle at 16% 88%, rgba(255,175,55,0.22) 0 74px, transparent 76px),
    radial-gradient(circle at 80% 26%, rgba(255,127,0,0.2) 0 86px, transparent 88px),
    radial-gradient(circle at 91% 78%, rgba(255,175,55,0.16) 0 48px, transparent 50px),
    radial-gradient(circle at 9% 28%, rgba(255,127,0,0.14) 0 42px, transparent 44px),
    radial-gradient(circle at 50% 0%, rgba(255,175,55,0.18), transparent 34%),
    #151515;
}
 
.apostemos-video-section {
  position: relative;
  overflow: hidden;
  width: min(920px, 100%);
  margin: 0 auto;
  text-align: center;
}
 
.apostemos-video-section::before {
  content: "";
  position: absolute;
  top: 18px;
  right: 28px;
  width: 115px;
  height: 115px;
  border-radius: 50%;
  background: rgba(255,127,0,0.18);
  pointer-events: none;
}
 
.apostemos-video-section::after {
  content: "";
  position: absolute;
  left: -38px;
  bottom: -42px;
  width: 145px;
  height: 145px;
  border-radius: 50%;
  background: rgba(255,175,55,0.16);
  pointer-events: none;
}
 
.apostemos-video-section > * {
  position: relative;
  z-index: 1;
}
 
.apostemos-video-header {
  margin-bottom: 18px;
}
 
.apostemos-video-header h2 {
  margin: 0;
  color: #ffaf37;
  font-size: 28px;
  line-height: 1.1;
  letter-spacing: 2px;
  text-transform: uppercase;
  text-shadow: 0 0 12px rgba(255,175,55,0.32);
  background: linear-gradient(90deg, #fff 0%, #ffaf37 34%, #ffc56e 52%, #ffaf37 70%, #fff 100%);
  background-size: 220% auto;
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
  animation: apostemosTitleGlow 4s linear infinite;
}
 
@keyframes apostemosTitleGlow {
  0% {
    background-position: 220% center;
  }
 
  100% {
    background-position: -220% center;
  }
}
 
.apostemos-video-frame {
  position: relative;
  width: min(760px, 100%);
  margin: 0 auto;
  aspect-ratio: 16 / 9;
  overflow: hidden;
  border: 2px solid rgba(255,175,55,0.75);
  border-radius: 14px;
  box-shadow: 0 8px 26px rgba(0,0,0,0.45);
  background: #000;
}
 
.apostemos-video-frame iframe {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  border: 0;
}
 
.apostemos-youtube-link {
  display: inline-block;
  margin-top: 18px;
  background: #ff7f00;
  color: #111;
  padding: 12px 26px;
  border-radius: 40px;
  font-weight: 800;
  text-decoration: none;
  transition: transform 0.2s, box-shadow 0.2s;
}
 
.apostemos-youtube-link:hover {
  transform: translateY(-3px);
  background: #ff9b21;
  box-shadow: 0 10px 24px rgba(255,127,0,0.28);
}
 
@media (max-width: 767px) {
  .apostemos-video-section,
  .apostemos-video-header {
    text-align: center;
  }
 
  .apostemos-video-header {
    display: block;
  }
 
  .apostemos-video-header h2 {
    font-size: 24px;
  }
 
  .apostemos-youtube-link {
    width: 100%;
    box-sizing: border-box;
  }
}
 
/* ===== FIX HERO TAPADO SOLO EN MÓVIL ===== */
@media (max-width: 767px) {
  .hero-apostemos {
    margin-top: 300px; /* ajusta si tu header es más alto */
  }
}
 
.banner-apostemos {
  display: block;
  margin: 25px auto 0 auto; /* centrado horizontal */
  max-width: 100%;
}
 
/* Ajuste SOLO en móvil */
@media (max-width: 767px) {
  .banner-apostemos {
    margin-top: 20px;
    width: 100%;
  }
}
 
@media (max-width: 767px) {
  /* ===== BOTÓN LOTO SALDO ===== */
 /* Ajuste del botón Loto Saldo */
  .metodos .logos a img[alt="Loto Saldo"] {
    height: 55px;             /* un poco más pequeño */
    max-width: 80%;           /* limita el ancho máximo */
    margin: 0 auto;           /* centra horizontalmente */
    display: block;           /* importante para que el margin funcione */
    padding-left: 10px;       /* espacio desde el borde izquierdo */
    padding-right: 10px;      /* espacio desde el borde derecho */
  }
 
  /* ===== TEXTO DE INTRO ===== */
  .intro p {
    padding-left: 15px;
    padding-right: 15px;
    box-sizing: border-box; /* para que el padding no rompa el ancho */
    text-align: center;      /* opcional, centra el texto */
  }
}
 
@media (max-width: 767px) {
  /* Contenedor de los botones */
  .metodos .logos .fila {
    display: flex;
    justify-content: center;  /* centra los botones */
    gap: 15px;                /* espacio entre los botones */
    flex-wrap: wrap;           /* si no caben, bajan a otra línea */
  }
 
  /* Todos los botones de la fila */
  .metodos .logos .fila a img {
    height: 60px;             /* mismo alto */
    max-width: 150px;         /* mismo ancho máximo para todos */
    display: block;
  }
}
 
/* POPUP */
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
$imagen = !empty($datos['hero']['imagen_inferior'])
          ? $datos['hero']['imagen_inferior']
          : 'ImagesSV/pop up apostemos.webp';
?>
 
      <img src="<?= $imagen ?>" alt="Popup principal">
 
      <button class="popup-close" id="cerrarPopup">&times;</button>
    </div>
  </div>
</div>
 
 
<!-- ======================== CONTENIDO ======================== -->
<!-- HERO PRINCIPAL -->
<section class="hero-apostemos">
  <img src="<?= $datos['hero']['imagen_header'] ?? 'ImagesSV/header_apostemos.png' ?>">
</section>
 
<!-- INTRO -->
<section class="intro">
  <h2><?= $datos['hero']['titulo'] ?? '¡BIENVENIDO A APOSTEMOS!' ?></h2>
 
  <p>
    <?= nl2br($datos['hero']['texto']?? "TU CASA DE APUESTAS EXPLORA NUESTRAS EXPERIENCIAS\nÚNICAS Y ELIGE CÓMO VIVIR LA EMOCIÓN DEL JUEGO.") ?>
  </p>
</section>
 
<!-- DEPORTE -->
<section class="seccion deporte">
 
  <img src="<?= $datos['deportes']['imagen_header'] ?? 'ImagesSV/Banner_deporte.png' ?>">
 
  <h3 class="titulo-deporte">
    <?= $datos['deportes']['titulo'] ?? 'APUESTA EN TUS DEPORTES FAVORITOS' ?>
  </h3>
 
  <p class="texto-deporte">
    <?= $datos['deportes']['texto'] ?? 'PRONOSTICÁ RESULTADOS EN TIEMPO REAL...' ?>
  </p>
 
  <a href="<?= $datos['deportes']['link'] ?? 'https://juega.loto.hn/fob' ?>" class="btn-como-jugar">
    <img src="ImagesSV/jugá-aquí.png">
  </a>
 
  <img src="<?= $datos['deportes']['imagen_inferior'] ?? 'ImagesSV/apostemos.png' ?>">
 
</section>
 
 
 
<!-- GAMING -->
<section class="seccion gaming">
  <img src="<?= $datos['gaming']['imagen_header'] ?? 'ImagesSV/Banner_gaming.png' ?>">
 
 
  <h3 class="titulo-gaming"><?= $datos['gaming']['titulo'] ?></h3>
 
  <p class="texto-gaming">
    <?= $datos['gaming']['texto'] ?>
  </p>
 
  <a href="<?= $datos[7]['link'] ?? 'https://juega.loto.hn/gaming/' ?>" class="btn-como-jugar">
    <img src="ImagesSV/juga-aqui-nmorado 2.png" alt="Cómo Jugar" />
  </a>
 
  <img src="<?= $datos['gaming']['imagen_inferior'] ?? 'ImagesSV/apostemos.png' ?>">
 
 
</section>
 

 
<!-- PLAYLIST YOUTUBE -->
<!-- PLAYLIST YOUTUBE -->
<section class="apostemos-video-wrap">

    <div class="apostemos-video-section" aria-label="Videos Apostemos">

        <div class="apostemos-video-header">
            <h2>Apostemos paso a paso</h2>
        </div>

        <div class="apostemos-video-frame">

            <iframe
                src="https://www.youtube.com/embed/videoseries?list=PLTia6MlR-G6s"
                title="Podcast Aquí Es - Apostemos"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen>
            </iframe>

        </div>

        <a
            class="apostemos-youtube-link"
            href="https://www.youtube.com/playlist?list=PLTia6MlR-G6s"
            target="_blank"
            rel="noopener"
        >
            Ver playlist en YouTube
        </a>

    </div>

</section>
 
 
<!--
<section class="seccion quiniela">
 
  <img src="ImagesSV/Banner_quinela.png" alt="Banner Quiniela" class="banner-quiniela">
  <h3 class="titulo-quiniela">PRONOSTICA Y GANA</h3>
  <p class="texto-quiniela">
    ELIGE LOS RESULTADOS DE 10 PARTIDOS DE FÚTBOL.<br>
    ¡ENTRE MÁS ACIERTOS, MÁS OPORTUNIDAD DE GANAR EL POZO!
  </p>
 
  <a href="https://juega.loto.sv/lottery/" class="btn-como-jugar">
    <img src="ImagesSV/como-jugar-quinela.png" alt="Cómo Jugar" />
  </a>
  <img src="ImagesSV/quinela.png" alt="Quiniela" class="imagen-principal">
</section>
-->
<!-- MÉTODOS DE RECARGA -->
<section class="metodos">
  <h3>MÉTODOS PARA RECARGAR</h3>
  <div class="logos">
    <div class="fila">
      <a href="https://juega.loto.hn/websales/?action=login">
    <img src="ImagesSV/tarjeta.svg" alt="Tarjeta" style="cursor:pointer;">
  </a>
 
  <a href="https://juega.loto.hn/websales/?action=login">
    <img src="ImagesSV/punto tengo.svg" alt="Punto Tengo" style="cursor:pointer;">
  </a>
    </div>
    <div class="fila">
      <a href="https://juega.loto.hn/websales/?action=login">
    <img src="ImagesSV/tigo money.svg" alt="Tigo Money" style="cursor:pointer;">
  </a>
      <a href="https://juega.loto.hn/websales/?action=login">
  <img src="ImagesSV/Logo LotoSaldo.png"
       alt="Loto Saldo"
       style="cursor:pointer; height:60px;">
</a>
 
    </div>
  </div>
 
  <a href="https://juega.loto.hn/websales/" class="btn-como-jugar">
    <img src="ImagesSV/jugá-aquí.png" alt="Jugar Aquí" />
  </a>
</section>
 
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