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
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Traer configuración actual de la página
$stmt = $conn->query("SELECT * FROM paginaweb_hn_quiero_ser_agente WHERE id=1");
$config = $stmt->fetch(PDO::FETCH_ASSOC);

$mensajeExito = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Recibir los datos del formulario
    $Nombre = $_POST['Nombre'] ?? null;
    $Apellidos = $_POST['Apellidos'] ?? null;
    $Identidad = $_POST['Identidad'] ?? null;
    $Telefono = $_POST['Telefono'] ?? null;
    $Correo = $_POST['Correo'] ?? null;

    $Negocio = $_POST['Negocio'] ?? null;
    $Direccion = $_POST['Direccion'] ?? null;
    $TipoNegocio = $_POST['TipoNegocio'] ?? null;
    $Departamento = $_POST['Departamento'] ?? null;
    $Ciudad = $_POST['Ciudad'] ?? null;
    $Municipio = $_POST['Municipio'] ?? null;
    $Barrio = $_POST['Barrio'] ?? null;

    // Si tienes foto como URL o base64
   $FotoNegocio = null;

if (!empty($_FILES['FotoNegocio']['tmp_name'])) {

    $extension = pathinfo($_FILES['FotoNegocio']['name'], PATHINFO_EXTENSION);
    $nombreArchivo = uniqid("negocio_") . "." . $extension;

    // Ruta física donde se guarda el archivo
    $rutaDestino = "ImagesSV/uploads/agentes/" . $nombreArchivo;

    move_uploaded_file($_FILES['FotoNegocio']['tmp_name'], $rutaDestino);

    // Esto es lo que se guarda en la BD y se envía a la Logic App
    $FotoNegocio = $rutaDestino;
}

    try {
        //  Guardar en SQL Server
        $conn = new PDO(
            "sqlsrv:Server=srvdbcacdev.database.windows.net;Database=dblotocacdev",
            "LotoAdmin",
            "LotAdmin1.",
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        $sql = "INSERT INTO quiero_ser_agente_hn 
                (Nombre, Apellidos, Identidad, Telefono, Correo, Negocio, Direccion, TipoNegocio, Departamento, Ciudad, Municipio, Barrio, FotoNegocio)
                VALUES 
                (:Nombre, :Apellidos, :Identidad, :Telefono, :Correo, :Negocio, :Direccion, :TipoNegocio, :Departamento, :Ciudad, :Municipio, :Barrio, :FotoNegocio)";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ':Nombre' => $Nombre,
            ':Apellidos' => $Apellidos,
            ':Identidad' => $Identidad,
            ':Telefono' => $Telefono,
            ':Correo' => $Correo,
            ':Negocio' => $Negocio,
            ':Direccion' => $Direccion,
            ':TipoNegocio' => $TipoNegocio,
            ':Departamento' => $Departamento,
            ':Ciudad' => $Ciudad,
            ':Municipio' => $Municipio,
            ':Barrio' => $Barrio,
            ':FotoNegocio' => $FotoNegocio
        ]);

        //  Enviar los datos a Logic App
        $logicAppUrl = "https://prod-23.canadacentral.logic.azure.com:443/workflows/6abd12eba8db4bbda5df37bf0e48c2d7/triggers/When_an_HTTP_request_is_received/paths/invoke?api-version=2016-10-01&sp=%2Ftriggers%2FWhen_an_HTTP_request_is_received%2Frun&sv=1.0&sig=kr8CE23tQJTzHtFUBIdG2-0SN6evm5TCH7YXlWsBIBc";

        $data = [
            "Nombre" => $Nombre,
            "Apellidos" => $Apellidos,
            "Identidad" => $Identidad,
            "Telefono" => $Telefono,
            "Correo" => $Correo,
            "Negocio" => $Negocio,
            "Direccion" => $Direccion,
            "TipoNegocio" => $TipoNegocio,
            "Departamento" => $Departamento,
            "Ciudad" => $Ciudad,
            "Municipio" => $Municipio,
            "Barrio" => $Barrio,
            "FotoNegocio" => $FotoNegocio
        ];

        $ch = curl_init($logicAppUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            $mensajeExito = "✅ Formulario enviado correctamente y Logic App ejecutada.";
        } else {
            $mensajeExito = "⚠️ Formulario guardado, pero hubo un problema enviando la Logic App. HTTP code: $httpCode";
        }

    } catch (PDOException $e) {
        $mensajeExito = "❌ Error al guardar en SQL: " . $e->getMessage();
    }
}

echo $mensajeExito;

// Arreglo de municipios por departamento
$municipiosHN = [

"Atlántida" => ["La Ceiba","Tela","Jutiapa","La Masica","San Francisco","Arizona","Esparta","El Porvenir"],

"Choluteca" => ["Choluteca","Apacilagua","Concepción de María","Duyure","El Corpus","El Triunfo","Marcovia","Morolica","Namasigüe","Orocuina","Pespire","San Antonio de Flores","San Isidro","San José","San Marcos de Colón","Santa Ana de Yusguare"],

"Colón" => ["Trujillo","Balfate","Iriona","Limón","Sabá","Santa Fe","Santa Rosa de Aguán","Sonaguera","Tocoa","Bonito Oriental"],

"Comayagua" => ["Comayagua","Ajuterique","El Rosario","Esquías","Humuya","La Libertad","Lamaní","La Trinidad","Lejamani","Meámbar","Minas de Oro","Ojos de Agua","San Jerónimo","San José de Comayagua","San José del Potrero","San Luis","San Sebastián","Siguatepeque","Villa de San Antonio"],

"Copán" => ["Santa Rosa de Copán","Cabañas","Concepción","Copán Ruinas","Corquín","Cucuyagua","Dolores","Dulce Nombre","El Paraíso","Florida","La Jigua","La Unión","Nueva Arcadia","San Agustín","San Antonio","San Jerónimo","San José","San Juan de Opoa","San Nicolás","San Pedro","Santa Rita","Trinidad de Copán","Veracruz"],

"Cortés" => ["San Pedro Sula","Choloma","Omoa","Pimienta","Potrerillos","Puerto Cortés","San Antonio de Cortés","San Francisco de Yojoa","San Manuel","Santa Cruz de Yojoa","Villanueva","La Lima"],

"El Paraíso" => ["Yuscarán","Alauca","Danlí","El Paraíso","Güinope","Jacaleapa","Liure","Morocelí","Oropolí","Potrerillos","San Antonio de Flores","San Lucas","San Matías","Soledad","Teupasenti","Texiguat","Vado Ancho","Yauyupe","Trojes"],

"Francisco Morazán" => ["Distrito Central","Alubarén","Cedros","Curarén","El Porvenir","Guaimaca","La Libertad","La Venta","Lepaterique","Maraita","Marale","Nueva Armenia","Ojojona","Orica","Reitoca","Sabanagrande","San Antonio de Oriente","San Buenaventura","San Ignacio","San Juan de Flores","San Miguelito","Santa Ana","Santa Lucía","Talanga","Tatumbla","Valle de Ángeles","Villa de San Francisco","Vallecillo"],

"Gracias a Dios" => ["Puerto Lempira","Brus Laguna","Ahuas","Juan Francisco Bulnes","Ramón Villeda Morales","Wampusirpi"],

"Intibucá" => ["La Esperanza","Camasca","Colomoncagua","Concepción","Dolores","Intibucá","Jesús de Otoro","Magdalena","Masaguara","San Antonio","San Isidro","San Juan","San Marcos de la Sierra","San Miguel Guancapla","Santa Lucía","Yamaranguila","San Francisco de Opalaca"],

"Islas de la Bahía" => ["Roatán","Guanaja","José Santos Guardiola","Utila"],

"La Paz" => ["La Paz","Aguanqueterique","Cabañas","Cane","Chinacla","Guajiquiro","Lauterique","Marcala","Mercedes de Oriente","Opatoro","San Antonio del Norte","San José","San Juan","San Pedro de Tutule","Santa Ana","Santa Elena","Santa María","Santiago de Puringla","Yarula"],

"Lempira" => ["Gracias","Belén","Candelaria","Cololaca","Erandique","Gualcince","Guarita","La Campa","La Iguala","Las Flores","La Unión","La Virtud","Lepaera","Mapulaca","Piraera","San Andrés","San Francisco","San Juan Guarita","San Manuel Colohete","San Rafael","San Sebastián","Santa Cruz","Talgua","Tambla","Tomalá","Valladolid","Virginia","San Marcos de Caiquín"],

"Ocotepeque" => ["Ocotepeque","Belén Gualcho","Concepción","Dolores Merendón","Fraternidad","La Encarnación","La Labor","Lucerna","Mercedes","San Fernando","San Francisco del Valle","San Jorge","San Marcos","Santa Fe","Sensenti","Sinuapa"],

"Olancho" => ["Juticalpa","Campamento","Catacamas","Concordia","Dulce Nombre de Culmí","El Rosario","Esquipulas del Norte","Gualaco","Guarizama","Guata","Guayape","Jano","La Unión","Mangulile","Manto","Salamá","San Esteban","San Francisco de Becerra","San Francisco de la Paz","Santa María del Real","Silca","Yocón","Patuca"],

"Santa Bárbara" => ["Santa Bárbara","Arada","Atima","Azacualpa","Ceguaca","Concepción del Norte","Concepción del Sur","Chinda","El Níspero","Gualala","Ilama","Las Vegas","Macuelizo","Naranjito","Nuevo Celilac","Petoa","Protección","Quimistán","San Francisco de Ojuera","San José de Colinas","San Luis","San Marcos","San Nicolás","San Pedro Zacapa","Santa Rita","San Vicente Centenario","Trinidad"],

"Valle" => ["Nacaome","Alianza","Amapala","Aramecina","Caridad","Goascorán","Langue","San Francisco de Coray","San Lorenzo"],

"Yoro" => ["Yoro","Arenal","El Negrito","El Progreso","Jocón","Morazán","Olanchito","Santa Rita","Sulaco","Victoria","Yorito"]

];
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Formulario Vendedor</title>
<style>
  body {
  font-family: 'HelveticaRounded', sans-serif; /* Aplicar la fuente personalizada */
  margin: 0;
  padding: 0;
  background: white;
  color: #333;
  text-align: center;
}
  /* ===== HERO ===== */
  /* ===== HERO PRO ===== */
.hero {
  background: linear-gradient(135deg, #FF9800, #ff7a00);
  padding: 80px 20px 60px 20px;
  position: relative;
  overflow: hidden;
}

/* glow decorativo */
.hero::before {
  content: "";
  position: absolute;
  width: 500px;
  height: 500px;
  background: rgba(255,255,255,0.08);
  filter: blur(120px);
  top: -150px;
  right: -150px;
  border-radius: 50%;
}

.hero-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  max-width: 1100px;
  margin: 0 auto;
  gap: 40px;
}

/* IMAGEN PERSONA */
.hero-img {
  max-width: 320px;
  width: 100%;
  border-radius: 15px;
  filter: drop-shadow(0 20px 30px rgba(0,0,0,0.2));
}

/* TEXTO */
.hero-text-container {
  flex: 1;
}

.hero-text-img {
  max-width: 400px;
  margin-bottom: 20px;
}

/* TITULO GRANDE */
.hero-subtitle {
  font-size: 36px;
  font-weight: 900;
  color: white;
  line-height: 1.2;
  text-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

/* BOTÓN OPCIONAL (SI QUIERES MÁS PRO) */
.hero-btn {
  margin-top: 25px;
  display: inline-block;
  background: white;
  color: #ff7a00;
  padding: 14px 28px;
  border-radius: 40px;
  font-weight: 700;
  text-decoration: none;
  transition: 0.3s;
}

.hero-btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

/* RESPONSIVE */
@media (max-width: 768px) {

  .hero {
    padding: 60px 20px;
  }

  .hero-content {
    flex-direction: column;
    text-align: center;
  }

  .hero-img {
    max-width: 240px;
  }

  .hero-text-img {
    max-width: 250px;
    margin: 0 auto 10px auto;
  }

  .hero-subtitle {
    font-size: 24px;
  }
}

  /* ===== CONTENEDOR BEIGE PRINCIPAL ===== */
  .info-container {
    background: #FFEFD9; /* beige */
    padding: 40px 20px;
    border-radius: 16px;
    max-width: 1100px;
    margin: -1cm auto 40px auto;
    display: flex;
    flex-direction: column;
    gap: 40px;
    position: relative;   /* habilita z-index */
  z-index: 10;          /* lo trae al frente */
  margin-top: -50px;   /* lo sube encima del hero */
  }

  /* ===== REQUISITOS ===== */
  .requisitos-container {
    display: flex;
    gap: 20px;
    align-items: flex-start; /* alineado arriba con la imagen */
    flex-wrap: wrap; /* para móviles */
}

.requisitos-container .requisitos-texto {
    flex: 1;
}

.requisitos-container img {
    max-width: 300px; /* tamaño hero 2 */
    border-radius: 15px;
}

.requisitos-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.requisitos-list li {
    display: flex;              /* fila con círculo y texto */
    align-items: center;        /* verticalmente centrados */
    gap: 10px;                  /* espacio entre círculo y texto */
    margin-bottom: 10px;
    font-size: 16px;
    font-weight: 600;
    color: #0077CC;
}

.requisitos-list li::before {
    content: '✔';               /* check blanco */
    display: flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 20px;
    background-color: #ff9f43;  /* círculo naranja */
    border-radius: 50%;
    color: white;               /* check blanco */
    flex-shrink: 0;             /* no se encoge */
    font-size: 12px;
}

/* Móvil */
@media (max-width: 768px) {
  .requisitos-container {
      flex-direction: column;
      gap: 15px;
  }
  .requisitos-list li {
      font-size: 15px;
  }
  .requisitos-container img {
      max-width: 90%;
      margin: 0 auto;
  }
}

  /* ===== FORMULARIO ===== */
  .form-container {
    background: #ffffff;
    padding: 40px 35px;
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.08);
    max-width: 1000px;
    width: 95%;
    margin: 0 auto;
    animation: fadeIn .6s ease-out;
}

.form-section-title {
  font-size: 26px;
  font-weight: 800;
  color: #0077CC;
  margin-bottom: 25px;
  text-align: left;
  background: none;     /* elimina cualquier color de fondo */
  padding: 0;           /* elimina el bloque que parecía un rectángulo */
  border-left: 6px solid #FF9800; /* barra moderna a la izquierda */
  padding-left: 12px;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 25px 35px;
}

@media (max-width: 700px) {
    .form-grid { grid-template-columns: 1fr; }
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
  font-weight: 700;
  margin-bottom: 5px;
  color: #333;
  font-size: 15px;
  text-align: left;    /* <- asegura que siempre estén a la izquierda */
}

.form-group input,
.form-group select,
.form-group textarea {
    padding: 14px;
    border-radius: 12px;
    border: 1px solid #dcdcdc;
    font-size: 15px;
    background: #fafafa;
    transition: all .25s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #0077CC;
    background: white;
    box-shadow: 0 0 0 4px rgba(0,119,204,0.15);
    outline: none;
}

.file-upload input[type="file"] {
    margin-top: 10px;
    border: none;
    padding: 10px;
    border-radius: 10px;
    background: linear-gradient(135deg, #0077CC, #005fa3);
    color: white;
    cursor: pointer;
    font-size: 14px;
    transition: .25s;
}

.file-upload input[type="file"]:hover {
    transform: scale(1.03);
}

.btn-submit {
    background: linear-gradient(135deg, #FF9800, #ff7a00);
    color: white;
    border: none;
    padding: 16px 40px;
    font-size: 18px;
    border-radius: 50px;
    cursor: pointer;
    margin: 30px auto 0 auto;
    display: block;
    transition: .35s ease;
    font-weight: 700;
}

.btn-submit:hover {
    background: linear-gradient(135deg, #ff7a00, #FF9800);
    transform: translateY(-4px);
}

/* EFECTO DE APARICIÓN */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}
  /* ===== BENEFICIOS ===== */
  .beneficios-title { text-align: center; color: #0077CC; font-size: 32px; font-weight: 800; margin-bottom: 20px; }
  .beneficios-container { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px 30px; }
  .beneficio { display: flex; align-items: flex-start; gap: 15px; text-align: left; padding: 20px; border-radius: 12px; background: #ffffff; box-shadow: 0 6px 20px rgba(0,0,0,0.05); transition: transform 0.3s, box-shadow 0.3s; }
  .beneficio:hover { transform: translateY(-5px); box-shadow: 0 12px 25px rgba(0,0,0,0.1); }
  .beneficio img { width: 50px; flex-shrink: 0; }
  .beneficio-text h4 { color: #0077CC; font-weight: 700; font-size: 16px; margin: 0 0 5px 0; }
  .beneficio-text p { font-size: 14px; opacity: 0.85; margin: 0; }
  @media (max-width: 700px) { .beneficios-container, .form-grid, .requisitos { grid-template-columns: 1fr; } }
  /* ===============================
   FIX HERO IMAGEN - MÓVIL
   =============================== */
@media (max-width: 768px) {

  .hero-content {
    flex-direction: column;
    justify-content: center;
    text-align: center;
  }

  .hero-img {
    max-width: 220px;
    width: 90%;
    margin: 0 auto 20px auto; /* centrada */
    display: block;
  }

  .hero-text-container {
    align-items: center;
  }

  .hero-text-img {
    max-width: 280px;
    margin: 0 auto;
  }

  .hero-subtitle {
    font-size: 22px;
  }
}
/* ===============================
   FIX FORMULARIO - MÓVIL
   =============================== */
@media (max-width: 768px) {

  .form-container {
    padding: 25px 18px;
    border-radius: 16px;
  }

  .form-section-title {
    font-size: 20px;
  }

  .form-grid {
    grid-template-columns: 1fr; /* una columna */
    gap: 18px;
  }

  .form-group input,
  .form-group select {
    font-size: 16px; /* mejor para teclado móvil */
    padding: 14px;
  }

  .btn-submit {
    width: 100%;
    font-size: 17px;
    padding: 15px;
  }
}
/* =================================
   BAJAR IMAGEN HERO EN MÓVIL
   ================================= */
@media (max-width: 768px) {

  .hero {
    padding-top: 180px; /* empuja todo el hero hacia abajo */
  }
  .hero-img {
    margin-top: 100px; /* baja SOLO la imagen */
  }
}
/* =================================
   REQUISITOS ALINEADOS A LA IZQUIERDA EN MÓVIL
   ================================= */
@media (max-width: 768px) {

  .requisitos {
    text-align: left;
  }

  .requisitos ul {
    padding-left: 0;   /* quita sangría rara */
  }

  .requisitos li {
    text-align: left;
    justify-content: flex-start;
    align-items: flex-start;
    font-size: 15px;
  }

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

  transform: none !important; /* 🔥 evita que otros estilos la muevan */
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
                : 'ImagesSV/Pop up aplica.gif';

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

<body>



<!-- HERO -->
<section class="hero">
  <div class="hero-content">

    <img 
      src="<?= !empty($config['hero_imagen1']) 
          ? htmlspecialchars($config['hero_imagen1']) 
          : 'ImagesSV/Abigail.png' ?>" 
      class="hero-img"
    >

    <div class="hero-text-container">
      
      <img 
        src="<?= !empty($config['hero_imagen2']) 
            ? htmlspecialchars($config['hero_imagen2']) 
            : 'ImagesSV/equipo ganador texto.png' ?>" 
        class="hero-text-img"
      >

      <p class="hero-subtitle">
        <?= nl2br(htmlspecialchars($config['hero_titulo'] ?? '')) ?>
      </p>



    </div>

  </div>
</section>


<!-- CONTENEDOR PRINCIPAL BEIGE -->
<div class="info-container">

  <!-- REQUISITOS -->
  <!-- REQUISITOS -->
<section class="requisitos-section">
  <h2 class="requisitos-title">REQUISITOS</h2>

  <div class="requisitos-container">
    <div class="requisitos-texto">
      
      <ul class="requisitos-list">
      <?php 
      if(!empty($config['requisitos_texto'])) {
          // Separar cada requisito por salto de línea
          $puntos = preg_split("/\r\n|\n|\r/", $config['requisitos_texto']);
          foreach($puntos as $punto) {
              if(trim($punto) !== "") { // Ignorar líneas vacías
                  echo "<li>" . htmlspecialchars($punto) . "</li>";
              }
          }
      }
      ?>
      </ul>
    </div>

    <?php if(!empty($config['requisitos_imagen'])): ?>
      <img src="<?= htmlspecialchars($config['requisitos_imagen']) ?>" alt="Imagen Requisitos">
    <?php endif; ?>
  </div>
</section>

  <!-- FORMULARIO -->
  <section class="form-container">
    <form method="POST" action="" enctype="multipart/form-data">

      <h2 class="form-section-title">Ingresar información del propietario del negocio</h2>
      <div class="form-grid">
        <div class="form-group"><label>Nombre</label><input type="text" name="Nombre" placeholder="Nombre" required></div>
        <div class="form-group"><label>Apellidos</label><input type="text" name="Apellidos" placeholder="Apellidos" required></div>
        <div class="form-group"><label>Número de Identidad</label><input type="text" name="Identidad" placeholder="Número de Identidad" ></div>
        <div class="form-group"><label>Teléfono</label><input type="tel" name="Telefono" placeholder="Teléfono" required></div>
        <div class="form-group"><label>Correo Electrónico</label><input type="email" name="Correo" placeholder="Correo Electrónico" required></div>
      </div>

      <h2 class="form-section-title">Ingresar información del negocio</h2>
      <div class="form-grid">
        <div class="form-group"><label>Nombre del negocio</label><input type="text" name="Negocio" placeholder="Nombre del negocio" required></div>
        <div class="form-group"><label>Dirección actual del negocio</label><input type="text" name="Direccion" placeholder="Dirección del negocio" required></div>
        <div class="form-group"><label>Tipo de negocio</label>
          <select name="TipoNegocio" required>
  <option value="">Seleccione</option>
  <option value="Abarrotería">Abarrotería</option>
  <option value="Bares/Canchas">Bares / Canchas</option>
  <option value="Cafetería/restaurante">Cafetería / Restaurante</option>
  <option value="Carwash/Taller">Carwash / Taller</option>
  <option value="Farmacia">Farmacia</option>
  <option value="Ferretería">Ferretería</option>
  <option value="Internet/Celulares">Internet / Celulares</option>
  <option value="Kiosko/Supermercado">Kiosko / Supermercado</option>
  <option value="Laboratorio">Laboratorio</option>
  <option value="Librería">Librería</option>
  <option value="LNB">LNB</option>
  <option value="Loteria">Lotería</option>
  <option value="Mini Súper">Mini Súper</option>
  <option value="Salon de Belleza">Salón de Belleza</option>
  <option value="Tienda de conveniencia">Tienda de conveniencia</option>
  <option value="Tienda en general">Tienda en general</option>
  <option value="Otros">Otros</option>
</select>

        </div>
        <div class="form-group"><label>Departamento</label>
          <select name="Departamento" required>
  <option value="">Seleccione Departamento</option>
  <option value="Atlántida">Atlántida</option>
  <option value="Choluteca">Choluteca</option>
  <option value="Colón">Colón</option>
  <option value="Comayagua">Comayagua</option>
  <option value="Copán">Copán</option>
  <option value="Cortés">Cortés</option>
  <option value="El Paraíso">El Paraíso</option>
  <option value="Francisco Morazán">Francisco Morazán</option>
  <option value="Gracias a Dios">Gracias a Dios</option>
  <option value="Intibucá">Intibucá</option>
  <option value="Islas de la Bahía">Islas de la Bahía</option>
  <option value="La Paz">La Paz</option>
  <option value="Lempira">Lempira</option>
  <option value="Ocotepeque">Ocotepeque</option>
  <option value="Olancho">Olancho</option>
  <option value="Santa Bárbara">Santa Bárbara</option>
  <option value="Valle">Valle</option>
  <option value="Yoro">Yoro</option>
</select>

        </div>
        <div class="form-group"><label>Ciudad</label><input type="text" name="Ciudad" placeholder="Ciudad" required></div>
        <div class="form-group">
    <label>Municipio</label>
    <select name="Municipio" id="municipio" required>
        <option value="">Seleccione Municipio</option>
    </select>
</div>
        <div class="form-group"><label>Barrio / Colonia</label><input type="text" name="Barrio" placeholder="Barrio / Colonia" required></div>
      </div>

      <button type="submit" class="btn-submit">Enviar mensaje</button>
    </form>
  </section>

  <!-- BENEFICIOS -->
  <h2 class="beneficios-title">BENEFICIOS</h2>
  <section class="beneficios-container">
  <?php for($i=1;$i<=6;$i++): ?>
    <div class="beneficio">
      <img src="<?= htmlspecialchars($config["beneficio{$i}_imagen"]) ?>" alt="Beneficio <?= $i ?>">
      <div class="beneficio-text">
        <h4><?= htmlspecialchars($config["beneficio{$i}_titulo"]) ?></h4>
        <p><?= nl2br(htmlspecialchars($config["beneficio{$i}_texto"])) ?></p>
      </div>
    </div>
  <?php endfor; ?>
</section>

</div>
</body>
</html>

<script>
const municipiosHN = <?php echo json_encode($municipiosHN); ?>;

const departamentoSelect = document.querySelector('select[name="Departamento"]');
const municipioSelect = document.getElementById('municipio');

departamentoSelect.addEventListener('change', function() {
    const depto = this.value;
    municipioSelect.innerHTML = '<option value="">Seleccione Municipio</option>';
    if (municipiosHN[depto]) {
        municipiosHN[depto].forEach(mun => {
            const option = document.createElement('option');
            option.value = mun;
            option.textContent = mun;
            municipioSelect.appendChild(option);
        });
    }
});
if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
  }
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

