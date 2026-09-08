<?php
// ==========================
// CONFIGURACION DE BD
// ==========================
try {
    $conn = new PDO(
        "sqlsrv:Server=srvdbcacdev.database.windows.net;Database=dblotocacdev",
        "LotoAdmin",
        "LotAdmin1.",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Error de conexion: " . $e->getMessage());
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location: index.php?pag=noticias");
    exit();
}

$stmt = $conn->prepare("
    SELECT *
    FROM paginaweb_hn_noticias
    WHERE id = :id
");
$stmt->execute([':id' => $id]);
$noticia = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$noticia) {
    header("Location: index.php?pag=noticias");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($noticia['titulo']) ?></title>

<style>
@font-face {
  font-family:'HelveticaRounded';
  src: url('fonts/HelveticaRoundedLTStd-Bd.ttf') format('truetype');
}

*{
  font-family:'HelveticaRounded', sans-serif;
  margin:0;
  padding:0;
  box-sizing:border-box;
}

body{
  background:#f4f4f4;
  color:#333;
}

.container-general{
  width:90%;
  max-width:1200px;
  margin:auto;
}

.titulo-noticias{
  background:#a6d1f8;
  padding:80px 40px 45px;
  border-radius:0 0 14px 14px;
  text-align:center;
  margin:0 -2cm 50px;
}

.titulo-noticias h1{
  color:#fff;
  font-size:45px;
  font-weight:900;
  letter-spacing:2px;
}

.detalle-container{
  width:90%;
  max-width:1000px;
  margin:0 auto 60px;
  background:#fff;
  border-radius:16px;
  box-shadow:0 6px 18px rgba(0,0,0,0.12);
  overflow:hidden;
}

.detalle-imagen{
  width:100%;
  max-height:480px;
  object-fit:cover;
  display:block;
}

.detalle-contenido{
  padding:35px 45px 45px;
}

.detalle-fecha{
  color:#666;
  font-size:15px;
  margin-bottom:12px;
}

.detalle-contenido h1{
  color:#ef7d00;
  font-size:36px;
  line-height:1.2;
  margin-bottom:22px;
}

.detalle-texto{
  color:#444;
  font-size:17px;
  line-height:1.8;
  white-space:normal;
}

.btn-volver{
  display:inline-block;
  margin-top:28px;
  padding:9px 18px;
  background:#ef7d00;
  color:#fff;
  border-radius:18px;
  text-decoration:none;
  font-size:14px;
  font-weight:700;
}

.btn-volver:hover{
  background:#1558b0;
}

@media(max-width:600px){
  .titulo-noticias{
    padding:65px 20px 35px;
    margin:0 -12px 35px;
  }

  .titulo-noticias h1{
    font-size:30px;
  }

  .detalle-container{
    width:94%;
  }

  .detalle-contenido{
    padding:24px 20px 32px;
  }

  .detalle-contenido h1{
    font-size:26px;
  }

  .detalle-texto{
    font-size:15px;
  }
}
</style>
</head>
<body>

<div class="container-general">
  <section class="titulo-noticias">
    <h1>NOTICIAS RECIENTES</h1>
  </section>
</div>

<main class="detalle-container">
  <?php if (!empty($noticia['imagen_url'])): ?>
    <img class="detalle-imagen" src="<?= htmlspecialchars($noticia['imagen_url']) ?>" alt="<?= htmlspecialchars($noticia['titulo']) ?>">
  <?php endif; ?>

  <section class="detalle-contenido">
    <p class="detalle-fecha"><?= htmlspecialchars($noticia['fecha']) ?></p>
    <h1><?= htmlspecialchars($noticia['titulo']) ?></h1>
    <div class="detalle-texto">
      <?= nl2br(htmlspecialchars($noticia['descripcion'])) ?>
    </div>
    <a class="btn-volver" href="index.php?pag=noticias">Volver a noticias</a>
  </section>
</main>



</body>
</html>
