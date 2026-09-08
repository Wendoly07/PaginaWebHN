<script>
document.addEventListener('DOMContentLoaded', () => {
  const hoy = new Date().toISOString().slice(0,10);
  cargarResultadosJuga3(hoy);
});
</script>

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
$stmt = $conn->query("SELECT * FROM paginaweb_hn_juga3 WHERE id = 1");
$config = $stmt->fetch(PDO::FETCH_ASSOC);
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

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>La Diaria</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Helvetica+Rounded:wght@400;700;900&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Helvetica Rounded', Arial, sans-serif;
    }

    body {
      background: #fff;
    }
    /* ================= HEADER ================= */

.top {
  background: #f662a7;
  display: flex;
  justify-content: center;
  padding: 25px 10px; /* MENOS ALTO */
}

.top-content {
  display: flex;
  align-items: center; /* Centrado vertical con la imagen */
  max-width: 1300px;
  width: 100%;
  position: relative;
}

.top img {
  width: 330px; /* Imagen más grande */
  height: auto;
  margin-top: 70px;
  margin-left: 80px;
}

.ganador-box {
  text-align: center;
  color: white;
  margin-top: 10px;
  margin-left: 160px; /* Mantener la posición al lado de la imagen */
}

.ganador {
  font-weight: 900;
  font-size: 26px; /* Ligeramente más grande */
  margin-bottom: 15px;
}

.nums {
  margin-bottom: 10px;
}

.num {
  width: 65px; /* Un poco más grande */
  height: 65px;
  line-height: 65px;
  display: inline-block;
  background: #f662a7
;
  border-radius: 50%;
  font-weight: bold;
  font-size: 24px; /* Un poco más grande */
  color: white;
  margin: 0 4px;
  border: 2px solid white;
  text-align: center;
}

.etiqueta-hola {
  background: yellow;
  color: #f662a7;
  padding: 6px 14px;
  border-radius: 20px;
  font-weight: 900;   /* MÁS BOLD */
  font-size: 18px;    /* Ligeramente más grande */
}

    /* ================= MENÚ MODERNO ================= */
    .menu {
      display: flex;
      justify-content: center;
      gap: 18px;
      background: #f662a7;
      padding: 16px;
      flex-wrap: wrap;
    }

    .menu a {
      background: linear-gradient(135deg, #f662a7
, #f8a1c4);
      color: white;
      text-decoration: none;
      padding: 10px 22px;
      border-radius: 30px;
      font-size: 14px;
      font-weight: bold;
      transition: 0.3s;
      box-shadow: 0 4px 10px rgba(0,0,0,.25);
    }

    .menu a:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 14px rgba(0,0,0,.3);
    }

    .menu a:first-child {
  margin-right: 70px; /* mueve un poco "CÓMO JUGAR" hacia la izquierda */
}

.menu a:last-child {
  margin-left: 70px;  /* mueve un poco "RESULTADOS" hacia la derecha */
}

    /* ================= RESULTADOS ================= */
    .resultados {
      border: 1px solid #f662a7
;
      display: flex;
      max-width: 1100px;
      margin: 40px auto;
      background: white;
      border-radius: 20px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.15);
      padding: 25px;
      gap: 20px;
    }

    .resultados .col {
      flex: 1;
      text-align: center;
    }

     /* COLUMNA IZQUIERDA - MÁS IMPONENTE Y CENTRADO */
.izquierda {
  display: flex;
  flex-direction: column;
  justify-content: center; /* Centrado vertical */
  align-items: flex-start; /* Mantener al lado izquierdo */
  gap: 15px;               /* Espacio entre elementos */
  min-height: 100%;         /* Para que tome todo el alto de la fila */
}

.izquierda h2 {
  font-size: 42px;     /* Más grande */
  font-weight: 900;    /* Muy bold */
  color: #f662a7
;
  margin: 0;
  line-height: 1.1;
}

.label-fecha {
  font-size: 18px;     /* Más grande */
  font-weight: 700;
  background: yellow;  /* Mantener color que ya tenías */
  color: #f662a7;
  padding: 8px 16px;
  border-radius: 20px;
  display: inline-block;
  margin-left: 35px;  /* Mueve un poco a la derecha */
}

/* ================= CALENDARIO ULTRA MODERNO COMPACTO ================= */
.calendario-real {
  background: linear-gradient(145deg, #ffffff, #f4f4f4);
  border-radius: 16px;
  padding: 12px 14px;       /* MÁS PEQUEÑO */
  box-shadow: 0 8px 18px rgba(0,0,0,0.15);
  border: 1px solid #e5e5e5;
  max-width: 260px;        /* TAMAÑO CONTROLADO */
  margin: auto;
}

.calendario-real table {
  width: 100%;
  border-collapse: collapse;
  text-align: center;
}

.calendario-real th {
  font-size: 12px;         /* MÁS PEQUEÑO */
  font-weight: 800;
  color: #f662a7;
  padding: 6px 0;
  text-transform: uppercase;
}

.calendario-real td {
  padding: 7px 0;         /* MÁS PEQUEÑO */
  font-weight: 700;
  font-size: 13px;
  color: #444;
  cursor: pointer;
  transition: 0.25s;
  border-radius: 50%;
}

.calendario-real td:hover {
 background: rgba(17,161,220,0.15);;
}

.calendario-real td.activo {
  background: linear-gradient(135deg, #f662a7, #4fc3f7);
  color: white;
  box-shadow: 0 4px 10px rgba(0,0,0,0.25);
}

/* ================= FILTROS PREMIUM COMPACTOS ================= */

.filtros {
  gap: 6px;
  margin-bottom: 10px;
}

.filtros select {
  padding: 6px 12px;      /* MÁS PEQUEÑO */
  font-size: 13px;
  border-radius: 10px;
  border: none;
  background: #f3f3f3;
  box-shadow: inset 0 2px 5px rgba(0,0,0,0.15);
  cursor: pointer;
  font-weight: 700;
  color: #f662a7;
  transition: 0.3s;
}

.filtros select:hover {
  background: #e9e9e9;
}
    /* ================= SORTEOS MEJORADOS ================= */
.derecha {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.derecha .sorteo {
  margin-bottom: 20px;
  text-align: center;
}

.derecha h3 {
  font-size: 20px;        /* Más grande */
  font-weight: 900;      /* ULTRA BOLD */
  color: #f662a7;
  margin-bottom: 12px;
  text-align: center;    /* Bien centrado */
  letter-spacing: 0.5px;
}

/* NÚMEROS DEL SORTEO */
.derecha .num {
  width: 52px;
  height: 52px;
  line-height: 52px;
  display: inline-block;
  background: #f662a7;
  border-radius: 50%;
  font-weight: 900;
  font-size: 20px;
  color: white;
  margin: 0 6px;
  border: 2px solid white;
  text-align: center;
}

    /* ================= ACCORDION ================= */
    .accordion {
      max-width: 1100px;
      margin: 30px auto;
      border-radius: 15px;
      overflow: hidden;
      background: white; /*  FONDO BLANCO */
      border: 2px solid #f662a7; /* BORDE VISIBLE */
    }

    .accordion-header {
      padding: 18px;
      text-align: center;
      color: white;
      font-weight: bold;
      font-size: 24px;
      cursor: pointer;
      position: relative;
      background: #f662a7;
    }

    .accordion-header .arrow {
      position: absolute;
      right: 20px;
      font-size: 26px;
    }

    .accordion-content {
      display: none;
      padding: 20px;
      background: white;
    }

    /* SUB-ACORDEONES */
/* ================= SUB-ACORDEONES MEJORADOS ================= */
.sub-accordion-header {
  background: #f662a7; /* Verde más intenso */
  color: white;
  padding: 14px 20px;
  margin-bottom: 10px;
  border-radius: 12px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  cursor: pointer;
  font-weight: 600;
  font-size: 20px; /* Letra más grande y legible */
  box-shadow: 0 3px 6px rgba(0,0,0,0.1); /* Sombra suave */
  transition: background 0.3s;
}

/* Hover opcional */
.sub-accordion-header:hover {
  background: #4fc3f7; /* Verde más claro al pasar mouse */
}

/* CÍRCULO DE LA FLECHA */
.sub-accordion-header span {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: #b4e5b0; /* Verde claro */
  color: #ffffff;      /* Flecha blanca */
  font-size: 18px;
  flex-shrink: 0;
  transition: transform 0.3s;
}

/* Girar flecha al abrir */
.sub-accordion-header.active span {
  transform: rotate(180deg);
}

/* CONTENIDO DE SUB-ACORDEONES */
.sub-accordion-content {
  display: none;
  padding: 15px 20px;
  background: white; /* Fondo blanco */
  border-radius: 0 0 12px 12px;
  margin-bottom: 12px;
  font-size: 17px; /* Texto más legible */
  font-weight: 600;
  line-height: 1.5; /* Espaciado de línea para lectura */
}

 /*  TODO EN HELVETICA ROUNDED SEMIBOLD */
body {
  font-family: 'Helvetica Rounded', Arial, sans-serif;
  font-weight: 600;
}

/* BLOQUE SUPERIOR DEL ACCORDION */
/* ================= INFO-JUEGO MEJORADA ================= */
.info-juego {
  margin-bottom: 30px;
  padding: 20px;
  background: #ffffff; /* Fondo blanco */
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.08); /* Sombra sutil */
}

/* SLOGAN PRINCIPAL */
.slogan {
  text-align: center; /* Centrado para resaltar */
  font-size: 22px;
  font-weight: 700;
  color: #f662a7; /* Verde destacado */
  margin-bottom: 12px;
  line-height: 1.4;
}

/* DESCRIPCIÓN */
.descripcion {
  text-align: center;
  font-size: 17px;
  font-weight: 600;
  color: #333; /* Gris oscuro para mejor lectura */
  margin-bottom: 20px;
  line-height: 1.5;
}

/* SUBTÍTULO */
.subtitulo-verde {
  text-align: center;
  font-size: 20px;
  font-weight: 700;
  color: #f662a7;
  margin-bottom: 15px;
}

/* TEXTO + IMAGEN AL LADO */
.linea-juego {
  display: flex;
  align-items: flex-start; /* Ajuste arriba para que texto se alinee con imagen */
  gap: 16px;
  margin-top: 15px;
  font-size: 16px;
  font-weight: 600;
  color: #333;
  line-height: 1.5;
}

/* IMAGEN */
.img-diaria {
  width: 75px;
  height: auto;
  flex-shrink: 0;
  border-radius: 6px; /* Bordes suaves */
}

/* ================= SUB-ACORDEONES ================= */
.sub-accordion-header {
  background: #f662a7; /* Verde principal */
  color: white;
  padding: 14px 20px;
  margin-bottom: 10px;
  border-radius: 12px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  cursor: pointer;
  font-weight: 600;
  font-size: 20px;
  transition: background 0.3s;
}

/* Hover opcional */
.sub-accordion-header:hover {
  background: #4fc3f7;
}

/* CÍRCULO DE LA FLECHA */
.sub-accordion-header span {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: #d9f3ff; /* Verde más suave y elegante */
  color: #f662a7;      /* Flecha verde oscuro */
  font-size: 18px;
  flex-shrink: 0;
  transition: transform 0.3s;
}

/* Girar flecha al abrir */
.sub-accordion-header.active span {
  transform: rotate(180deg);
}

/* CONTENIDO DE SUB-ACORDEONES */
.sub-accordion-content {
  display: none;
  padding: 15px 20px;
  background: white; /* Fondo blanco */
  border-radius: 0 0 12px 12px;
  margin-bottom: 12px;
  font-size: 16px;
  font-weight: 600;
  line-height: 1.5;
  color: #333;
}
    /* ================= BOTÓN ================= */
    .reglamento {
      text-align: center;
      margin: 40px 0;
    }

    .reglamento button {
      background: #ff6f00;
      color: white;
      padding: 14px 30px;
      border-radius: 30px;
      border: none;
      font-weight: bold;
      font-size: 16px;
      cursor: pointer;
    }

    .calendario-real td.activo {
    background: linear-gradient(135deg, #f662a7, #f8a1c4); /* Fondo verde */
    color: white;
    border-radius: 50%; /* Hace el fondo circular */
    font-weight: bold;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25); /* Sombra sutil para el efecto de resaltar */
}

/* ================= FIX HEADER LA DIARIA – SOLO MÓVIL ================= */
@media (max-width: 768px) {

  /* Header contenedor */
  .top {
    padding: 20px 10px;
  }

  .top-content {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }

  /* LOGO */
  .top img {
    width: 220px;
    max-width: 90%;
    margin: 0 auto 15px auto;
  }

  /* Caja de resultados */
  .ganador-box {
    margin: 0;
    text-align: center;
  }

  .ganador {
    font-size: 20px;
    margin-bottom: 10px;
  }

  /* NÚMEROS */
  .num {
    width: 48px;
    height: 48px;
    line-height: 48px;
    font-size: 18px;
    margin: 0 3px;
  }

  /* CONTADOR */
  .etiqueta-hola {
    font-size: 15px;
    padding: 6px 12px;
    margin-top: 10px;
  }

  /* MENÚ */
  .menu {
    gap: 10px;
    padding: 12px;
  }

  .menu a {
    width: 100%;
    text-align: center;
    margin: 0;
  }

  .menu a:first-child,
  .menu a:last-child {
    margin: 0;
  }

  /* RESULTADOS */
  .resultados {
    flex-direction: column;
    padding: 20px 15px;
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
  }

}

@media (max-width: 768px) {

  /* Bajamos todo el contenido del header */
  .top-content {
      margin-top: 280px; /* Ajusta este valor según cuánto quieras bajarlo */
  }

}

.linea-juego {
  display: flex;
  flex-wrap: wrap;        /* Para que sea responsive */
  align-items: center;    /* Centrado vertical */
  gap: 20px;              /* Espacio entre imagen y texto */
  margin-top: 20px;
  background: #f8f9fa;    /* Fondo suave para resaltar */
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08); /* Sombra sutil */
}

.img-container {
  flex: 0 0 120px;        /* Ancho fijo de la imagen */
  display: flex;
  justify-content: center;
  align-items: center;
}

.img-diaria {
  width: 100%;
  height: auto;
  border-radius: 10px;
  object-fit: cover;
  transition: transform 0.3s;
}

.img-diaria:hover {
  transform: scale(1.05); /* Animación ligera al pasar el mouse */
}

.texto-principal {
  flex: 1;
  font-size: 16px;
  font-weight: 600;
  color: #333;
  line-height: 1.6;
}
/* FILA DE ESFERAS */
.fila-resultados {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
}

/* IMAGEN MÁS 1 DEL MISMO TAMAÑO QUE LAS ESFERAS */
/* ESFERA QUE CONTIENE LA IMAGEN "MÁS 1" */
/* ESFERA "MÁS 1" */
.num.mas1 {
  display: flex;
  align-items: center;
  justify-content: center;

  background: transparent;      /* NO verde */
  padding: 0;
  line-height: normal;          /* CLAVE */
}

/* IMAGEN DENTRO DE LA ESFERA */
.num.mas1 img {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  object-fit: contain;          /* USAR todo el círculo */
  display: block;
}

/* ===== ARREGLO DEFINITIVO MÁS 1 (HEADER) ===== */


/* === ESFERA MÁS 1 (HEADER) === */
.num.mas1 {
  position: relative;     /* base para centrar */
  background: transparent;
  overflow: hidden;       /* nada se sale */
}

/* === IMAGEN CENTRADA A LA FUERZA === */
.num.mas1 img {
  position: absolute;
  top: 50%;
  left: 50%;

  width: 100%;
  height: 100%;

  transform: translate(-50%, -50%);

  object-fit: cover;
  border-radius: 50%;
  display: block;
}
/* ESFERA MÁS 1 = IMAGEN */
/* === MÁS 1: SOLO IMAGEN, SIN ESFERA === */
.num.mas1 {
  background-image: url("/ImagesSV/logo-13-MÁS 1.png");
  background-repeat: no-repeat;
  background-position: center;
  background-size: 120%;   /* ajusta si quieres más/menos grande */

  background-color: transparent; /* quita fondo */
  border: none;                  /* quita borde blanco */
  box-shadow: none;              /* por si acaso */
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
   COLOR ESFERAS – JUGÁ TRES
   ===================================================== */

/* Header */
.fila-resultados .num {
  background: #1EA8E0 !important;
  border-color: #ffffff;
  color: #ffffff;
}

/* Sorteos */
.sorteo .fila-resultados .num {
  background: #1EA8E0 !important;
  border-color: #ffffff;
  color: #ffffff;
}

/* =====================================================
   AFINADO ACORDEÓN – ESTILO MULTI‑X (JUGÁ TRES)
   ===================================================== */

.accordion-content {
  padding: 40px 30px;
}

/* Texto interno centrado y SEMIBOLD */
.accordion-content,
.accordion-content p,
.accordion-content .descripcion,
.accordion-content .texto-principal,
.sub-accordion-content {
  font-weight: 600;
  text-align: center;
  color: #222;
}

/* Texto principal */
.info-juego .descripcion {
  max-width: 720px;
  margin: 0 auto 25px auto;
  line-height: 1.6;
}

/* Layout como Multi‑X */
.linea-juego {
  background: transparent;
  box-shadow: none;
  padding: 0;
  margin-top: 10px;

  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}

/* Imagen Jugá Tres más grande */
.accordion .img-diaria {
  width: 190px;     /* tamaño ideal */
  max-width: 100%;
  height: auto;
  margin-bottom: 20px;
}

/* Texto debajo del logo */
.texto-principal {
  max-width: 720px;
  margin: 0 auto;
  line-height: 1.6;
}

/* Sub‑acordeones simples tipo Multi‑X */
.sub-accordion-header {
  font-size: 16px;
  font-weight: 600;
}

.sub-accordion-content {
  font-size: 15px;
  line-height: 1.6;
}

/* POPUP */
.popup-overlay {
  position: fixed;
  inset: 0;
  background: rgba(255, 193, 7, 0.25);
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
</head>

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
                : 'ImagesSV/pop up juga3.webp';

      $link = (!empty($popup['link_url'])) 
              ? $popup['link_url'] 
              : '#';
      ?>

      
        <img src="<?= $imagen ?>" alt="Popup principal">
     

      <button class="popup-close" id="cerrarPopup">&times;</button>
    </div>
  </div>
</div>

  <!-- HEADER -->
  <div class="top">
    <div class="top-content">
      <img src="<?= !empty($config['logo']) ? $config['logo'] : '/ImagesSV/logo-30-JUGA TRES.png' ?>" alt="Logo Diaria">

      <div class="ganador-box">
        <div class="ganador">ÚLTIMO NÚMERO GANADOR</div>

        <div class="nums fila-resultados">
  <span class="num"><?= htmlspecialchars($jt1) ?></span>
  <span class="num"><?= htmlspecialchars($jt2) ?></span>
  <span class="num extra"><?= htmlspecialchars($jt3) ?></span>
</div>

        <div class="etiqueta-hola">
  PRÓXIMO SORTEO EN VIVO:
  <span id="horaHeader">00</span>:
  <span id="minHeader">00</span>:
  <span id="segHeader">00</span>
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
      
  <!-- FILTROS MODERNOS -->
  <div class="filtros">
    <select id="filtro-mes">
      <option value="01">Enero</option>
      <option value="02">Febrero</option>
      <option value="03">Marzo</option>
      <option value="04">Abril</option>
      <option value="05">Mayo</option>
      <option value="06">Junio</option>
      <option value="07">Julio</option>
      <option value="08">Agosto</option>
      <option value="09">Septiembre</option>
      <option value="10">Octubre</option>
      <option value="11">Noviembre</option>
      <option value="12">Diciembre</option>
    </select>

    <select id="filtro-ano">
      <option value="2026">2026</option>
    </select>
  </div>

  <!-- CALENDARIO MODERNO -->
  <div class="calendario-real">
    <table>
      <thead>
        <tr>
          <th>DOM</th><th>LUN</th><th>MAR</th><th>MIE</th><th>JUE</th><th>VIE</th><th>SAB</th>
        </tr>
      </thead>
      <tbody>
        <tr><td></td><td></td><td>1</td><td>2</td><td>3</td><td>4</td><td>5</td></tr>
        <tr><td>6</td><td>7</td><td>8</td><td>9</td><td class="activo">10</td><td>11</td><td>12</td></tr>
        <tr><td>13</td><td>14</td><td>15</td><td>16</td><td>17</td><td>18</td><td>19</td></tr>
        <tr><td>20</td><td>21</td><td>22</td><td>23</td><td>24</td><td>25</td><td>26</td></tr>
        <tr><td>27</td><td>28</td><td>29</td><td>30</td><td>31</td><td></td><td></td></tr>
      </tbody>
    </table>
  </div>

</div>

    <div class="col derecha">

  <div class="sorteo">
    <h3>SORTEO 11:00 A.M.</h3>
    <div class="fila-resultados">
      
<!-- 11 AM -->
<span class="num" id="jt11_1">0</span>
<span class="num" id="jt11_2">0</span>
<span class="num" id="jt11_3">0</span>

    </div>
  </div>

  <div class="sorteo">
    <h3>SORTEO 3:00 P.M.</h3>
    <div class="fila-resultados">
      
<!-- 3 PM -->
<span class="num" id="jt15_1">0</span>
<span class="num" id="jt15_2">0</span>
<span class="num" id="jt15_3">0</span>

    </div>
  </div>

  <div class="sorteo">
    <h3>SORTEO 9:00 P.M.</h3>
    <div class="fila-resultados">
      
<!-- 9 PM -->
<span class="num" id="jt21_1">0</span>
<span class="num" id="jt21_2">0</span>
<span class="num" id="jt21_3">0</span>

    </div>
  </div>

</div>
</div>

  

  <!-- ACCORDION -->
<div class="accordion">

  <div class="accordion-header" onclick="toggle(this)">
    <!-- SUBTÍTULO EDITABLE -->
    <?= htmlspecialchars($config['titulo1']) ?>
    <span class="arrow">▼</span>
  </div>

  <div class="accordion-content">

  <div class="info-juego">
    <!-- TEXTO PRINCIPAL EDITABLE -->
    <p class="descripcion">
      <?= nl2br(htmlspecialchars($config['contenido1'])) ?>
    </p>

    <!-- IMAGEN FIJA QUE NUNCA CAMBIA -->
    <div class="linea-juego">
    <div class="img-container">
        <img src="/ImagesSV/logo-30-JUGA TRES.png" class="img-diaria" alt="Imagen Diaria">
    </div>
    <div class="texto-principal">
        <?= nl2br(htmlspecialchars($config['contenido_principal'])) ?>
    </div>
</div>
  </div>

  
  <!-- SUB-ACORDEONES DINÁMICOS -->
  <div class="sub-accordion-header" onclick="toggle(this)">
    <?= htmlspecialchars($config['titulo2']) ?>
    <span>▼</span>
  </div>
  <div class="sub-accordion-content">
    <?= nl2br(htmlspecialchars($config['contenido2'])) ?>
  </div>

  <div class="sub-accordion-header" onclick="toggle(this)">
    <?= htmlspecialchars($config['titulo3']) ?>
    <span>▼</span>
  </div>
  <div class="sub-accordion-content">
    <?= nl2br(htmlspecialchars($config['contenido3'])) ?>
  </div>

</div>
</div>

  <!-- BOTÓN -->
  <!-- BOTÓN REGLAMENTO -->
<div class="reglamento">
  <a href="/ImagesSV/documentos/REGLAMENTO JUGÁ TRES.pdf" target="_blank">
    <button class="btn-reglamento">
       LEER EL REGLAMENTO
    </button>
  </a>
</div>

  <script>
    function toggle(h) {
      let c = h.nextElementSibling;
      c.style.display = (c.style.display === "block") ? "none" : "block";
    }
  </script>

<script>
const second = 1000,
      minute = second * 60,
      hour   = minute * 60,
      day    = hour * 24;

/* Calcula el próximo sorteo */
function proximoSorteo() {
  const ahora = new Date();

  const hoy = new Date(
    ahora.getFullYear(),
    ahora.getMonth(),
    ahora.getDate()
  );

  const sorteos = [
    new Date(hoy.getTime() + 11 * hour), // 11:00 AM
    new Date(hoy.getTime() + 15 * hour), // 3:00 PM
    new Date(hoy.getTime() + 21 * hour)  // 9:00 PM
  ];

  // Buscar el próximo sorteo que no haya pasado
  for (let s of sorteos) {
    if (ahora < s) return s;
  }

  // Si ya pasó el de las 9 PM → mañana 11 AM
  return new Date(hoy.getTime() + day + 11 * hour);
}

// Fecha objetivo
let countDown = proximoSorteo().getTime();

// Actualizar cada segundo
let timer = setInterval(function () {
  let now = new Date().getTime();
  let distance = countDown - now;

  if (distance <= 0) {
    document.querySelector(".etiqueta-hola").innerText = "¡SORTEO EN VIVO!";
    clearInterval(timer);
    return;
  }

  let h = Math.floor((distance % day) / hour).toString().padStart(2, '0');
  let m = Math.floor((distance % hour) / minute).toString().padStart(2, '0');
  let s = Math.floor((distance % minute) / second).toString().padStart(2, '0');

  document.getElementById("horaHeader").innerText = h;
  document.getElementById("minHeader").innerText  = m;
  document.getElementById("segHeader").innerText  = s;

}, second);
</script>


<script>
function pintarJuga3(pref, data){
  if(!data || !data.par1){
    ['_1','_2','_3'].forEach(s=>{
      document.getElementById(pref+s).innerText = '0';
    });
    return;
  }

  const num = String(data.par1).padStart(3,'0');

  document.getElementById(pref+'_1').innerText = num[0];
  document.getElementById(pref+'_2').innerText = num[1];
  document.getElementById(pref+'_3').innerText = num[2];
}

function cargarResultadosJuga3(fecha){
  console.log("🔥 llamando API con fecha:", fecha);

  fetch(`/api/resultados_juga3_por_fecha.php?fecha=${fecha}`)
    .then(r => r.json())
    .then(d => {
      console.log("✅ respuesta API:", d);

      pintarJuga3('jt11', d['11']);
      pintarJuga3('jt15', d['15']);
      pintarJuga3('jt21', d['21']);
    })
    .catch(err => console.error("❌ ERROR:", err));
}
</script>

<script>
function pad2(n) {
  return n.toString().padStart(2, '0');
}

document.addEventListener('DOMContentLoaded', () => {
  const mesSelect = document.getElementById('filtro-mes');
  const anoSelect = document.getElementById('filtro-ano');
  const calendario = document.querySelector('.calendario-real tbody');

  function renderizarCalendario(mes, ano) {
    calendario.innerHTML = '';

    const primerDia = new Date(ano, mes - 1, 1);
    const ultimoDia = new Date(ano, mes, 0);
    const inicio = primerDia.getDay();

    let fila = document.createElement('tr');

    for (let i = 0; i < inicio; i++) {
      fila.appendChild(document.createElement('td'));
    }

    const hoy = new Date();
    let fechaActiva = `${ano}-${pad2(mes)}-01`;

    for (let d = 1; d <= ultimoDia.getDate(); d++) {
      const celda = document.createElement('td');
      celda.textContent = d;

      const fecha = `${ano}-${pad2(mes)}-${pad2(d)}`;

      // Marcar hoy
      if (
        d === hoy.getDate() &&
        mes === hoy.getMonth() + 1 &&
        ano === hoy.getFullYear()
      ) {
        celda.classList.add('activo');
        fechaActiva = fecha;
      }

      // ✅ CLICK QUE CAMBIA FECHA
      celda.addEventListener('click', () => {
        calendario.querySelectorAll('.activo')
          .forEach(td => td.classList.remove('activo'));

        celda.classList.add('activo');
        cargarResultadosJuga3(fecha); // ✅ AQUÍ ESTABA EL PROBLEMA
      });

      fila.appendChild(celda);

      if ((d + inicio) % 7 === 0) {
        calendario.appendChild(fila);
        fila = document.createElement('tr');
      }
    }

    calendario.appendChild(fila);

    // ✅ Cargar automáticamente el día activo
    cargarResultadosJuga3(fechaActiva);
  }

  mesSelect.addEventListener('change', () => {
    renderizarCalendario(parseInt(mesSelect.value), parseInt(anoSelect.value));
  });

  anoSelect.addEventListener('change', () => {
    renderizarCalendario(parseInt(mesSelect.value), parseInt(anoSelect.value));
  });

  const hoy = new Date();
  mesSelect.value = pad2(hoy.getMonth() + 1);
  anoSelect.value = hoy.getFullYear();

  renderizarCalendario(hoy.getMonth() + 1, hoy.getFullYear());
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
