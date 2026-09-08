

<?php
// ==========================
// CONFIGURACIÓN DE BD
// ==========================
// Crear conexión PDO con SQL Server y habilitar excepciones en caso de error.
try {
    $conn = new PDO(
        "sqlsrv:Server=srvdbcacdev.database.windows.net;Database=dblotocacdev",
        "LotoAdmin",
        "LotAdmin1.",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// ==========================
// OBTENER NOTICIA PRINCIPAL
// ==========================
// Cargar la noticia destacada más reciente para mostrar arriba de la lista.
$stmt = $conn->query("
    SELECT TOP 1 *
    FROM paginaweb_hn_noticias
    WHERE es_principal = 1
    ORDER BY id DESC
");
$principal = $stmt->fetch(PDO::FETCH_ASSOC);

// ==========================
// OBTENER NOTICIAS SECUNDARIAS (TOP 8)
// ==========================
// Cargar hasta 8 noticias secundarias ordenadas de la más reciente a la menos reciente.
$stmt = $conn->query("
    SELECT TOP 8 *
    FROM paginaweb_hn_noticias
    WHERE es_principal = 0
    ORDER BY id DESC
");
$noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Noticias</title>

<style>
/* ===================== FUENTE ===================== */
/* Definir y aplicar la fuente personalizada para todo el documento. */
@font-face {
  font-family:'HelveticaRounded';
  src: url('fonts/HelveticaRoundedLTStd-Bd.ttf') format('truetype');
}
*{ font-family:'HelveticaRounded',sans-serif; margin:0; padding:0; box-sizing:border-box; }
body{ font-family:'HelveticaRounded', sans-serif; background:#f4f4f4; }

/* ===================== CONTENEDOR ===================== */
.container-general{ width:90%; max-width:1200px; margin:auto; }

/* ===================== TITULO ===================== */
.titulo-noticias{
  background:#a6d1f8;
  padding:80px 40px 45px;
  border-radius:0 0 14px 14px;
  text-align:center;
  margin:0 -2cm 70px;
}
.titulo-noticias h1{
  color:#fff;
  font-size:45px;
  font-weight:900;
  letter-spacing:2px;
}

/* ===================== NOTICIA PRINCIPAL ===================== */
/* Estilo del bloque principal de noticia con imagen grande y contenido destacado. */
.noticia-principal-container{
  display:flex;
  gap:30px;
  align-items:stretch; /* ya lo tienes ✔ */
  margin-bottom:90px;
  width:100%;
  min-height:350px; /*  asegura altura mínima */
}

/* IMAGEN */
.noticia-principal-container img{
  width:60%;
  border-radius:16px;
  object-fit:cover;
  height:100%; /*  ESTA ES LA CLAVE */
}
/* CONTENEDOR DERECHO */
/* Estilos del panel derecho con fecha, título y descripción de la noticia principal. */
.noticia-principal-contenido{
  width:40%;
  background:rgba(255,255,255,0.95);
  padding:30px;
  border-radius:16px;
  box-shadow:0 8px 24px rgba(0,0,0,0.15);

  display:flex;
  flex-direction:column;
  justify-content:center;
}

.noticia-principal-contenido .fecha{
  font-size:17px;
  font-weight:700;
  color:#444;
}
.noticia-principal-contenido h2{
  color:#ff6600;
  font-size:32px;
  margin:12px 0;
  font-weight:900;
}
.noticia-principal-contenido p{
  font-size:15px;
  line-height:1.6;
  color:#444;
}

/* BOTÓN LEER MÁS */
.btn-leer{
  display:inline-block;
  margin-top:10px;
  padding:6px 14px;
  font-size:13px;
  font-weight:700;
  background:#ff6600;
  color:#fff;
  border-radius:18px;
  text-decoration:none;
  transition:background .2s ease;
}
.btn-leer:hover{ background:#cc5200; }

/* ===================== GRID NOTICIAS SECUNDARIAS ===================== */
.grid-noticias-wrapper{
  background:#fff;
  padding:30px 20px;
  border-radius:16px;
  box-shadow:0 6px 18px rgba(0,0,0,0.12);
}
.grid-noticias{
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:20px;
  transform:translateY(-70px);
}

/* CARD NOTICIAS */
.card-noticia{
  background:#fff;
  border-radius:12px;
  overflow:hidden;
  box-shadow:0 4px 12px rgba(0,0,0,0.1);
  display:flex;
  flex-direction:column;
  transition: transform .25s ease, box-shadow .25s ease;
}
.card-noticia:hover{
  transform: translateY(-6px);
  box-shadow: 0 12px 28px rgba(0,0,0,0.18);
}
.card-noticia img{
  width:100%;
  height:220px;
  object-fit:cover;
  border-radius:12px;
}
.card-noticia .fecha{
  font-size:12px;
  color:#777;
  padding:8px 12px 0;
}
.card-noticia h3{
  font-size:15px;
  color:#ff6600;
  margin:4px 12px;
  font-weight:700;
  line-height:1.4;
}
.card-noticia .contenido{
  font-size:13px;
  color:#444;
  margin:0 12px;
  display:-webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  overflow:hidden;
  line-height:1.5;
  max-height: calc(1.5em * 2);
  transition:max-height .3s ease;
}
.card-noticia .contenido.expandido{
  -webkit-line-clamp:7;
  line-clamp: 7;
  max-height: calc(1.5em * 7);
}
.card-noticia .btn-leer{
  align-self:flex-start;
  margin:0;
  padding:5px 12px;
  font-size:12px;
  border-radius:14px;
  transition:all .2s ease;
}
.card-noticia .btn-leer:hover{
  background:#cc5200;
  transform: translateX(3px);
}

.noticia-principal-contenido .descripcion{
  display:-webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 4; /* número de líneas visibles */
  line-clamp: 4;
  overflow:hidden;
  line-height:1.6;
  max-height: calc(1.6em * 4);
  transition:max-height .3s ease;
}

.noticia-principal-contenido .descripcion.expandido{
  -webkit-line-clamp:7;
  line-clamp: 7;
  max-height: calc(1.6em * 7);
}

.btn-leer-principal{
  display:inline-block;
  margin-top:0;
  padding:7px 16px;
  font-size:13px;
  font-weight:700;
  background:#ff6600;
  color:#fff;
  border-radius:18px;
  text-decoration:none;
  transition:all .2s ease;
  align-self:flex-start;
  width:auto;
}

.btn-leer-principal:hover{
  background:#cc5200;
}

.acciones-noticia{
  display:flex;
  flex-direction:column;
  align-items:flex-start;
  gap:8px;
  margin-top:14px;
}

.card-noticia .acciones-noticia{
  margin:16px 12px 14px;
}

.noticia-principal-contenido .acciones-noticia{
  flex-direction:row;
  justify-content:space-between;
  align-items:center;
  width:100%;
}

.btn-noticia-completa{
  display:inline-block;
  padding:7px 16px;
  font-size:13px;
  font-weight:700;
  background:#1a73e8;
  color:#fff;
  border-radius:18px;
  text-decoration:none;
  transition:all .2s ease;
}

.card-noticia .btn-noticia-completa{
  padding:5px 12px;
  font-size:12px;
  border-radius:14px;
}

.btn-noticia-completa:hover{
  background:#1558b0;
}

/* RESPONSIVE */
@media(max-width:1024px){ .grid-noticias{ grid-template-columns:repeat(2,1fr); } }
@media(max-width:600px){ 
  .grid-noticias{ grid-template-columns:1fr; } 
  .noticia-principal-contenido{ margin:-40px 10px 0; } 
}
@media(max-width:768px){
  .noticia-principal-container img{ width:100%; }
  .noticia-principal-contenido{
    position:static;
    margin-top:-50px;
    max-width:100%;
    min-height:auto;
  }
}
</style>
</head>
<body>

<div class="container-general">

<!-- TITULO -->
<!-- Encabezado principal de la página de noticias. -->
<section class="titulo-noticias">
  <h1>NOTICIAS RECIENTES</h1>
</section>

<!-- NOTICIA PRINCIPAL -->
<!-- Sección que muestra la noticia principal destacada. -->
<section class="noticia-principal-container">
  <img src="<?= $principal['imagen_url'] ?>" alt="">
  <div class="noticia-principal-contenido">
    <p class="fecha"><?= htmlspecialchars($principal['fecha']) ?></p>
    <h2><?= htmlspecialchars($principal['titulo']) ?></h2>
    <p class="descripcion"><?= nl2br(htmlspecialchars($principal['descripcion'])) ?></p>

<div class="acciones-noticia">
  <a href="#" class="btn-leer-principal">Leer más</a>
  <a href="index.php?pag=noticia_detalle&id=<?= urlencode($principal['id']) ?>" class="btn-noticia-completa">Noticia completa</a>
</div>
  </div>
</section>

<!-- GRID DE NOTICIAS SECUNDARIAS -->
<!-- Cuadrícula de noticias adicionales con tarjetas que pueden expandirse. -->
<section class="grid-noticias-wrapper">
  <div class="grid-noticias">
  <?php foreach($noticias as $n): ?>
    <article class="card-noticia">
      <img src="<?= $n['imagen_url'] ?>" alt="">
      <p class="fecha"><?= htmlspecialchars($n['fecha']) ?></p>
      <h3><?= htmlspecialchars($n['titulo']) ?></h3>
      <p class="contenido"><?= htmlspecialchars($n['descripcion']) ?></p>
      <div class="acciones-noticia">
        <a href="#" class="btn-leer">Leer más</a>
        <a href="index.php?pag=noticia_detalle&id=<?= urlencode($n['id']) ?>" class="btn-noticia-completa">Noticia completa</a>
      </div>
    </article>
  <?php endforeach; ?>
  </div>
</section>

</div>
<br>
<br>

<script>
// SCRIPT DE INTERACCIÓN
// Controla los botones de "Leer más" para expandir o contraer el texto de cada noticia.
document.querySelectorAll('.card-noticia .btn-leer').forEach(btn=>{
  btn.addEventListener('click', e=>{
    e.preventDefault();
    const card=btn.closest('.card-noticia');
    const texto=card.querySelector('.contenido');
    texto.classList.toggle('expandido');
    btn.textContent=texto.classList.contains('expandido')?'Leer menos':'Leer más';
  });
});

const btnPrincipal = document.querySelector('.btn-leer-principal');

if(btnPrincipal){
  btnPrincipal.addEventListener('click', e=>{
    e.preventDefault();
    const texto = document.querySelector('.noticia-principal-contenido .descripcion');
    texto.classList.toggle('expandido');

    btnPrincipal.textContent = texto.classList.contains('expandido')
      ? 'Leer menos'
      : 'Leer más';
  });
}
</script>



</body>
</html>
