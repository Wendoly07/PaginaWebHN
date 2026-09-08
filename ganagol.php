<?php
// ================= CONEXIÓN SQL SERVER =================
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

// ================= OBTENER DATOS =================
$stmt = $conn->query("SELECT * FROM paginaweb_hn_ganagol WHERE id = 1");
$config = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ganagol</title>

<style>
@import url('https://fonts.googleapis.com/css2?family=Helvetica+Rounded:wght@400;700;900&display=swap');

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Helvetica Rounded', Arial, sans-serif;
}

body{
    background:#fff;
    font-weight:600;
}

/* ================= HEADER ================= */

.top{
    background:#0D9150;
    display:flex;
    justify-content:center;
    padding:25px 10px;
}

.top-content{
    display:flex;
    align-items:center;
    justify-content:center;
    max-width:1300px;
    width:100%;
}

.top img{
    width:850px;
    max-width:90%;
    height:auto;
    margin-top:30px;   /* AJUSTA: 20px / 30px / 40px */
}


/* ================= MENÚ ================= */

.menu{
    display:flex;
    justify-content:center;
    gap:18px;
    background:#0D9150;
    padding:16px;
    flex-wrap:wrap;
}

.menu a{
    background:linear-gradient(135deg,#0D9150,#3fbf87);
    color:white;
    text-decoration:none;
    padding:10px 22px;
    border-radius:30px;
    font-size:14px;
    font-weight:bold;
    transition:.3s;
    box-shadow:0 4px 10px rgba(0,0,0,.25);
}

.menu a:hover{
    transform:translateY(-3px);
}

/* ===== ACCORDION LIMPIO COPIA DISEÑO OFICIAL ===== */

.accordion{
    max-width:1200px;
    margin:30px auto;
    border:2px solid #0D9150;
    border-radius:20px;
    background:#fff;
}

/* header igual como estaba */
.accordion-header{
    background:#0D9150;
    color:#fff;
    padding:22px;
    font-size:22px;
    font-weight:700;
    text-align:center;
    cursor:pointer;
    position:relative;
    border-radius:18px 18px 0 0;
}


.accordion-header .arrow{
    position:absolute;
    right:25px;
    top:50%;
    transform:translateY(-50%);
}

/* contenido */
.accordion-content{
    display:none;
    padding:40px;
}

/* layout texto + imagen */
.linea-juego{
    display:flex;
    gap:50px;
    align-items:flex-start;
}

/* texto izquierdo */
.texto-principal{
    flex:1;
    font-size:16px;
    line-height:1.6;
    color:#222;
}

/* imagen derecha */
.img-container{
    flex:0 0 260px;
    display:flex;
    justify-content:flex-end;
}

.img-diaria{
    width:260px;
    height:auto;
    border-radius:8px;
}

/* descripción */
.descripcion{
    font-size:18px;
    margin-bottom:25px;
    color:#222;
}

/* mobile */
@media(max-width:768px){
    .linea-juego{
        flex-direction:column;
    }

    .img-container{
        justify-content:center;
    }
}

.img-diaria{
    width:320px;      /* tamaño correcto como la imagen referencia */
    max-width:100%;
    height:auto;
    border-radius:10px;
}
/* ===== BOTÓN REGLAMENTO (ESTILO ORIGINAL) ===== */

.reglamento{
    text-align:center;
    margin:40px 0;
}

.reglamento a{
    text-decoration:none;
}

.reglamento button{
    background:#ff6f00;
    color:#fff;
    padding:14px 32px;
    border:none;
    border-radius:30px;
    font-size:16px;
    font-weight:700;
    cursor:pointer;
    box-shadow:0 8px 20px rgba(0,0,0,.3);
    transition:transform .2s ease;
}

.reglamento button:hover{
    transform:scale(1.05);
}


/* texto equilibrado */
.texto-principal{
    font-size:16px;
    line-height:1.6;
    color:#333;
}

/* mobile bonito */
@media(max-width:768px){
    .linea-juego{
        flex-direction:column;
        text-align:center;
    }

    .img-container{
        justify-content:center;
    }
}
.footer-column h3 {
    font-weight: 900 !important;
    color: #ffffff;
}

.footer-column p,
.footer-column a {
    font-weight: 500 !important;
}

/* si quieres TODO el footer más marcado */
.footer {
    font-weight: 500;
}

/* opcional: mejorar legibilidad visual */
.footer-column h3 {
    letter-spacing: 0.5px;
}

/* ===== ESFERAS HEADER ===== */

/* 1ra y 2da esfera VERDE */
.fila-resultados .num:nth-child(1),
.fila-resultados .num:nth-child(2) {
  background: #029247;
  border-color: #ffffff;
}

/* 3ra esfera (EXTRA) NARANJA */
.fila-resultados .num.extra {
  background: #F79848;
  border-color: #ffffff;
}


</style>
</head>

<body>

<!-- HEADER -->
<div class="top">
    <div class="top-content">
        <img src="<?= !empty($config['logo']) ? $config['logo'] : '/ImagesSV/Ganagol.png' ?>" alt="Logo Diaria">
    </div>
</div>

<!-- MENÚ -->
<div class="menu">
    <a href="https://juega.loto.hn/websales/" target="_blank">
        JUGÁ AQUÍ
    </a>
</div>

<!-- ACCORDION -->
<div class="accordion">

    <div class="accordion-header" onclick="toggle(this)">
        <?= htmlspecialchars($config['titulo1']) ?>
        <span class="arrow">▼</span>
    </div>

    <div class="accordion-content">

        <div class="info-juego">

            <p class="descripcion">
                <?= nl2br(htmlspecialchars($config['contenido1'])) ?>
            </p>

            <div class="linea-juego">

                <div class="img-container">
                    <img src="/ImagesSV/ganagol-generico-1.png" class="img-diaria" alt="Imagen Diaria">
                </div>

                <div class="texto-principal">
                    <?= nl2br(htmlspecialchars($config['contenido_principal'])) ?>
                </div>

            </div>

        </div>

    </div>
</div>

<!-- BOTÓN -->
<div class="reglamento">
    <a href="/ImagesSV/documentos/REGLAMENTO Ganagol.pdf" target="_blank">
        <button>
            LEER EL REGLAMENTO
        </button>
    </a>
</div>

<script>
function toggle(elemento) {
    const contenido = elemento.nextElementSibling;

    contenido.style.display =
        contenido.style.display === "block"
            ? "none"
            : "block";
}
</script>


</body>
</html>