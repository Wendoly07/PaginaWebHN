<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Conexión a SQL Server
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

// Obtener contenido dinámico
$stmt = $conn->query("SELECT * FROM paginaweb_hn_aplica_loto WHERE id = 1");
$contenido = $stmt->fetch(PDO::FETCH_ASSOC);

$mensajeExito = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    try {
        // Conexión a SQL Server
        $conn = new PDO(
            "sqlsrv:Server=srvdbcacdev.database.windows.net;Database=dblotocacdev",
            "LotoAdmin",
            "LotAdmin1.",
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        // Insert en base de datos
        $sql = "INSERT INTO aplica_con_nostros_hn
        (nombre, genero, edad, identidad, telefono, email, direccion, departamento, estudios, titulo, ingles, posicion, experiencia, transporte, juegos, salario, cv) 
        VALUES 
        (:nombre, :genero, :edad, :identidad, :telefono, :email, :direccion, :departamento, :estudios, :titulo, :ingles, :posicion, :experiencia, :transporte, :juegos, :salario, :cv)";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':nombre' => $_POST['nombre'],
            ':genero' => $_POST['genero'],
            ':edad' => $_POST['edad'],
            ':identidad' => $_POST['identidad'],
            ':telefono' => $_POST['telefono'],
            ':email' => $_POST['email'],
            ':direccion' => $_POST['direccion'],
            ':departamento' => $_POST['departamento'],
            ':estudios' => $_POST['estudios'],
            ':titulo' => $_POST['titulo'],
            ':ingles' => $_POST['ingles'],
            ':posicion' => $_POST['posicion'],
            ':experiencia' => $_POST['experiencia'],
            ':transporte' => $_POST['transporte'],
            ':juegos' => $_POST['juegos'],
            ':salario' => $_POST['salario'],
            ':cv' => 'sin_cv'
        ]);

        // LLAMADA A LOGIC APP CORRECTA
        $logicAppUrl = "https://prod-18.canadacentral.logic.azure.com:443/workflows/2feff373bde24c1cb618b30caa35466d/triggers/When_an_HTTP_request_is_received/paths/invoke?api-version=2016-10-01&sp=%2Ftriggers%2FWhen_an_HTTP_request_is_received%2Frun&sv=1.0&sig=JQitZn1p0T-D76UPZlK19UYQKwjgDtuLwaUJNPXuZWU";

        $data = [
            "nombre" => $_POST['nombre'],
            "genero" => $_POST['genero'],
            "edad" => $_POST['edad'],
            "identidad" => $_POST['identidad'],
            "telefono" => $_POST['telefono'],
            "email" => $_POST['email'],
            "direccion" => $_POST['direccion'],
            "departamento" => $_POST['departamento'],
            "estudios" => $_POST['estudios'],
            "titulo" => $_POST['titulo'],
            "ingles" => $_POST['ingles'],
            "posicion" => $_POST['posicion'],
            "experiencia" => $_POST['experiencia'],
            "transporte" => $_POST['transporte'],
            "juegos" => $_POST['juegos'],
            "salario" => $_POST['salario']
        ];

        $ch = curl_init($logicAppUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_exec($ch);
        curl_close($ch);

        $mensajeExito = "Formulario enviado correctamente ✅";

    } catch (PDOException $e) {
        $mensajeExito = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Formulario LOTO</title>
<style>
  body {
    margin: 0;
    background: #f6eedd;
    font-family: 'HelveticaRounded', sans-serif;  /* Fuente personalizada */
}
  .container {
    width: 860px;
    max-width: 95%;
    background: white;
    margin: 50px auto;
    border-radius: 18px;
    padding: 40px 50px;
    box-sizing: border-box;
  }

  .title-main {
    font-size: 42px;
    font-weight: 800;
    color: #ff6a00;
    line-height: 1.1;
    margin-bottom: 25px;
    text-align: center;
  }

  .banner {
    position: relative; 
    background: #0070d9;
    height: 150px; 
    display: flex;
    align-items: center;
    padding-left: 250px;
    border-radius: 1px;
    box-sizing: border-box;
    color: white;
    width: calc(100% + 100px);
    margin-left: -50px;
  }

  .banner-img {
    position: absolute;
    top: -100px;
    left: 20px;
    width: 200px; 
    z-index: 2;
  }

  .banner-content {
    max-width: 500px;
    margin-left: 50px;
  }

  .banner-text {
    color: white;
    font-size: 18px;
    line-height: 1.4;
    font-weight: 600;
  }

  .banner-description {
    margin-top: 40px;
    font-size: 16px;
    line-height: 1.5;
    color: #005bbb;
    font-weight: 600;
    text-align: center;
  }

  h2.section-title {
    text-align: center;
    color: #ff6a00;
    font-size: 26px;
    margin-top: 35px;
  }

  .beneficios {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: space-between;
    margin-top: 20px;
  }

  .beneficios-list li {
    font-weight: 600;
    color: #005bbb;
    margin-bottom: 12px;
    list-style: none;
    padding-left: 32px;
    position: relative;
    font-size: 17px;
  }

  .beneficios-list li::before {
    content: "✔";
    color: white;
    background: #ff6a00;
    width: 23px;
    height: 23px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    left: 0;
    top: 0;
    font-size: 14px;
  }

  .beneficios img {
    width: 170px;
    margin: auto;
  }

  h3.form-title {
    text-align: center;
    color: #005bbb;
    font-size: 24px;
    margin-top: 35px;
  }

  form {
    padding: 0; 
    max-width: 700px;
    margin: 30px auto;
    box-sizing: border-box;
  }

  form label.required::after {
    content: "*";
    color: red;
    margin-left: 3px;
  }

  form input[type="text"],
  form input[type="email"],
  form input[type="number"],
  form select {
    width: 100%;
    padding: 12px 15px;
    margin-top: 6px;
    border: 1px solid #bbb;
    border-radius: 10px;
    font-size: 16px;
    box-sizing: border-box;
    transition: border-color 0.3s;
  }

  form input:focus,
  form select:focus {
    border-color: #005bbb;
    outline: none;
  }

  .radio-group {
    display: flex;
    flex-direction: column; 
    margin-top: 6px;
    gap: 6px;
  }

  .radio-group label {
    font-weight: normal;
    color: #333;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .btn-submit {
    background: #1a8cff;
    color: white;
    display: block;
    padding: 12px 28px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 25px;
    border: none;
    cursor: pointer;
    transition: background 0.3s, transform 0.2s;
    margin: 25px auto 0 auto;
  }

  .btn-submit:hover {
    background: #006fd6;
    transform: translateY(-2px);
  }

  .alert-success {
  background-color: #d4edda;
  border-left: 6px solid #28a745;
  color: #155724;
  padding: 12px 20px;
  border-radius: 8px;
  margin-bottom: 15px;
  text-align: center;
  font-weight: bold;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  max-width: 500px;
  margin-left: auto;
  margin-right: auto;
  animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

/* ===================== FIX BANNER – SOLO MÓVIL ===================== */
@media (max-width: 768px) {

  /* TÍTULO */
  .title-main {
    font-size: 30px;
    margin-bottom: 20px;
  }

  /* BANNER */
  .banner {
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: auto;
    padding: 80px 20px 30px 20px;
    width: 100%;
    margin: 0;
    border-radius: 12px;
    text-align: center;
  }

  /* IMAGEN */
  .banner-img {
    position: relative;
    top: 0;
    left: 0;
    width: 160px;
    margin-bottom: 15px;
  }

  /* TEXTO DEL BANNER */
  .banner-content {
    margin: 0;
    max-width: 100%;
  }

  .banner-text {
    font-size: 16px;
    line-height: 1.4;
  }

  /* DESCRIPCIÓN */
  .banner-description {
    margin-top: 25px;
    font-size: 15px;
    line-height: 1.5;
    padding: 0 10px;
  }
}

/* ===== BAJAR CONTENIDO POR HEADER FIJO – SOLO MÓVIL ===== */
@media (max-width: 768px) {

  .container {
    margin-top: 120px; /* ajustá este valor si tu header es más alto */
  }
  /* Opcional: reduce tamaño del título y margen inferior */
  .title-main {
    margin-top: 50px; /* aumenta este valor para bajarlo más */
    font-size: 28px;  /* mantiene tamaño compacto en móvil */
    text-align: center;
  }

}


</style>
</head>
<body>

<div class="container">

  <div class="title-main">
    Formá parte de<br>
    la familia LOTO
  </div>

  <div class="banner">
    <img src="<?= htmlspecialchars($contenido['imagen1'] ?? '/ImagesSV/Foto Moni.png') ?>" class="banner-img">
    <div class="banner-content">
      <p class="banner-text">
        <?= nl2br(htmlspecialchars($contenido['titulo'] ?? 'En LOTO somos una empresa dónde valoramos el talento y nos caracterizamos por brindar oportunidades de crecimiento y desarrollo profesional a nuestros colaboradores además de muchos beneficios.')) ?>
      </p>
    </div>
</div>

  <p class="banner-description">
  <?= nl2br(htmlspecialchars($contenido['descripcion'] ?? 'INTERLOTO...')) ?>
</p>

  <h2 class="section-title">CONOCÉ ALGUNOS DE NUESTROS BENEFICIOS</h2>
  <div class="beneficios">
    <ul class="beneficios-list">
      <?php
      $beneficios = explode("\n", $contenido['beneficios'] ?? "EMPRESA SOLIDA CON ESTABILIDAD LABORAL\nSEGURO MEDICO PRIVADO\nSEGURO DE VIDA\nEXCELENTE AMBIENTE LABORAL");
      foreach($beneficios as $b):
      ?>
        <li><?= htmlspecialchars($b) ?></li>
      <?php endforeach; ?>
    </ul>
    <img src="<?= htmlspecialchars($contenido['imagen2'] ?? '/ImagesSV/icono beneficios.png') ?>">
</div>

  <h3 class="form-title">LLENÁ EL SIGUIENTE FORMULARIO</h3>

  <?php if($mensajeExito): ?>
  <div class="alert-success" id="alert-success">
    <?= $mensajeExito ?>
  </div>
<?php endif; ?>

  <form method="POST">
    <!-- Aquí va todo tu formulario tal como lo tenías, con los inputs y radios, sin CV -->
    <label class="required">Nombre completo:</label>
    <input type="text" name="nombre" required>
    <br><br>

    <label class="required main-label">Género:</label>
    <div class="radio-group">
      <label><input type="radio" name="genero" value="Masculino" required> Masculino</label>
      <label><input type="radio" name="genero" value="Femenino"> Femenino</label>
    </div>
    <br>

    <label class="required">Edad:</label>
    <input type="number" name="edad" required>
    <br><br>

    <label class="required">Número de identidad:</label>
    <input type="text" name="identidad" required>
    <br><br>

    <label class="required">Teléfono celular:</label>
    <input type="text" name="telefono" required>
    <br><br>

    <label class="required">Correo electrónico:</label>
    <input type="email" name="email" required>
    <br><br>

    <label class="required">Dirección:</label>
    <input type="text" name="direccion" required>
    <br><br>

    <label class="required">Departamento:</label>
<select name="departamento" required>
  <option value="">Seleccione…</option>
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
<br><br>

    <label class="required main-label">Formación académica:</label>
    <div class="radio-group">
      <label><input type="radio" name="estudios" value="Primaria" required> Primaria</label>
      <label><input type="radio" name="estudios" value="Secundaria"> Secundaria</label>
      <label><input type="radio" name="estudios" value="Pasante universitario"> Pasante universitario</label>
      <label><input type="radio" name="estudios" value="Universidad completa"> Universidad completa</label>
      <label><input type="radio" name="estudios" value="Pasante maestria"> Pasante maestría</label>
      <label><input type="radio" name="estudios" value="Maestria completa"> Maestría completa</label>
    </div>
    <br>

    <label class="required">Título obtenido:</label>
    <input type="text" name="titulo" required>
    <br><br>

    <label class="required main-label">Manejo del idioma inglés:</label>
    <div class="radio-group">
      <label><input type="radio" name="ingles" value="Basico" required> Básico</label>
      <label><input type="radio" name="ingles" value="Intermedio"> Intermedio</label>
      <label><input type="radio" name="ingles" value="Avanzado"> Avanzado</label>
    </div>
    <br>

    <label class="required">Posición a la que aplica:</label>
    <input type="text" name="posicion" required>
    <br><br>

    <label class="required main-label">Años en puestos similares:</label>
    <div class="radio-group">
      <label><input type="radio" name="experiencia" value="Sin experiencia" required> Sin experiencia</label>
      <label><input type="radio" name="experiencia" value="0-6 meses"> 0 - 6 meses</label>
      <label><input type="radio" name="experiencia" value="6 meses - 1 año"> 6 meses - 1 año</label>
      <label><input type="radio" name="experiencia" value="1 - 3 años"> 1 - 3 años</label>
      <label><input type="radio" name="experiencia" value="3 - 5 años"> 3 - 5 años</label>
      <label><input type="radio" name="experiencia" value="Más de 5 años"> Más de 5 años</label>
    </div>
    <br>

    <label class="required main-label">¿Tiene transporte propio?</label>
    <div class="radio-group">
      <label><input type="radio" name="transporte" value="Sí" required> Sí</label>
      <label><input type="radio" name="transporte" value="No"> No</label>
    </div>
    <br>

    <label class="required main-label">¿Ha jugado nuestros juegos?</label>
    <div class="radio-group">
      <label><input type="radio" name="juegos" value="Sí" required> Sí</label>
      <label><input type="radio" name="juegos" value="No"> No</label>
    </div>
    <br>

    <label class="required">¿Cuál es tu pretensión salarial?</label>
    <input type="text" name="salario" required>
    <br><br>

    <button type="submit" class="btn-submit">Enviar información</button>
  </form>

</div>

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


</body>
</html>
