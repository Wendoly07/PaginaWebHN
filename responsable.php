<?php

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_GET['accion']) &&
    $_GET['accion'] === 'enviar_autoevaluacion'
) {
    header('Content-Type: application/json; charset=utf-8');

    $datos = json_decode(file_get_contents('php://input'), true);

    $camposRequeridos = [
        'JugoParaDeudas',
        'ImpulsoVolverAJugar',
        'PidioPrestamo',
        'Remordimientos',
        'QuedarseSinDinero',
        'RecuperarPerdidas',
        'TristezaPorJuego'
    ];

    foreach ($camposRequeridos as $campo) {
        if (
            !isset($datos[$campo]) ||
            !in_array($datos[$campo], ['Sí', 'No'], true)
        ) {
            http_response_code(400);

            echo json_encode([
                'ok' => false,
                'mensaje' => 'Todas las preguntas deben ser respondidas.'
            ]);

            exit;
        }
    }

    $logicAppUrl = 'https://prod-21.canadacentral.logic.azure.com:443/workflows/fc7f31683113487faeefcca1dfd172b9/triggers/When_an_HTTP_request_is_received/paths/invoke?api-version=2016-10-01&sp=%2Ftriggers%2FWhen_an_HTTP_request_is_received%2Frun&sv=1.0&sig=HmMVDBLEq5OhojfiemJBKZ6_XcHVgmsQZ-Z95BQvTg8';

    $payload = [
        'JugoParaDeudas'       => $datos['JugoParaDeudas'],
        'ImpulsoVolverAJugar'  => $datos['ImpulsoVolverAJugar'],
        'PidioPrestamo'        => $datos['PidioPrestamo'],
        'Remordimientos'       => $datos['Remordimientos'],
        'QuedarseSinDinero' => $datos['QuedarseSinDinero'],
        'RecuperarPerdidas'    => $datos['RecuperarPerdidas'],
        'TristezaPorJuego'  => $datos['TristezaPorJuego']
    ];

    $ch = curl_init($logicAppUrl);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json'
        ],
        CURLOPT_POSTFIELDS     => json_encode(
            $payload,
            JSON_UNESCAPED_UNICODE
        ),
        CURLOPT_TIMEOUT        => 30
    ]);

    $respuestaLogicApp = curl_exec($ch);
    $codigoHttp = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $errorCurl = curl_error($ch);

    curl_close($ch);

    if ($errorCurl !== '') {
        http_response_code(500);

        echo json_encode([
            'ok' => false,
            'mensaje' => 'No se pudo conectar con el servicio.'
        ]);

        exit;
    }

    if ($codigoHttp < 200 || $codigoHttp >= 300) {
        http_response_code(502);

        echo json_encode([
            'ok' => false,
            'mensaje' => 'La Logic App rechazó la información.',
            'codigo' => $codigoHttp
        ]);

        exit;
    }

    echo json_encode([
        'ok' => true,
        'mensaje' => 'Autoevaluación enviada correctamente.'
    ]);

    exit;
}

?>

<?php
// ============================================================
// JUEGO RESPONSABLE HONDURAS - DATOS DINÁMICOS
// ============================================================

try {
    $conn = new PDO(
        "sqlsrv:Server=srvdbcacdev.database.windows.net;Database=dblotocacdev",
        "LotoAdmin",
        "LotAdmin1.",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $stmt = $conn->query("
        SELECT *
        FROM dbo.paginaweb_hn_juego_responsable
        WHERE activo = 1
        ORDER BY seccion, orden, id
    ");

    $jrFilas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Si SQL falla, se usan los valores de respaldo del código.
    $jrFilas = [];
}

$jrPorClave = [];
$jrPorSeccion = [];

foreach ($jrFilas as $fila) {
    $jrPorClave[$fila['clave']] = $fila;
    $jrPorSeccion[$fila['seccion']][] = $fila;
}

function jrRegistro(array $datos, string $clave): array {
    return $datos[$clave] ?? [];
}

function jrValor(array $registro, string $campo, string $fallback = ''): string {
    $valor = $registro[$campo] ?? '';

    if ($valor === null || trim((string)$valor) === '') {
        return $fallback;
    }

    return (string)$valor;
}

function jrHtml(string $valor): string {
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}

function jrTextoConDestacado(string $texto, string $destacado): string {
    $textoSeguro = jrHtml($texto);
    $destacado = trim($destacado);

    if ($destacado === '') {
        return $textoSeguro;
    }

    $destacadoSeguro = jrHtml($destacado);
    $pos = mb_stripos($textoSeguro, $destacadoSeguro, 0, 'UTF-8');

    if ($pos === false) {
        return $textoSeguro;
    }

    $antes = mb_substr($textoSeguro, 0, $pos, 'UTF-8');
    $medio = mb_substr($textoSeguro, $pos, mb_strlen($destacadoSeguro, 'UTF-8'), 'UTF-8');
    $despues = mb_substr(
        $textoSeguro,
        $pos + mb_strlen($destacadoSeguro, 'UTF-8'),
        null,
        'UTF-8'
    );

    return $antes . '<span class="naranja">' . $medio . '</span>' . $despues;
}

// ================= BANNER =================
$jrBanner = jrRegistro($jrPorClave, 'banner_principal');
$jrBannerLogo = jrRegistro($jrPorClave, 'banner_logo');

// ================= RECONOCIMIENTO =================
$jrReconocimiento = jrRegistro($jrPorClave, 'reconocimiento_principal');
$jrReconocimientoLogo = jrRegistro($jrPorClave, 'reconocimiento_logo');
$jrReconocimientoSello = jrRegistro($jrPorClave, 'reconocimiento_sello');
$jrBotonWla = jrRegistro($jrPorClave, 'boton_wla');
$jrBotonConocer = jrRegistro($jrPorClave, 'boton_conocer');

// ================= COMPROMISO =================
$jrCompromisoTitulo = jrRegistro($jrPorClave, 'compromiso_titulo');

$jrCompromisos = array_values(array_filter(
    $jrPorSeccion['compromiso'] ?? [],
    function ($fila) {
        return strpos($fila['clave'] ?? '', 'compromiso_') === 0
            && ($fila['clave'] ?? '') !== 'compromiso_titulo';
    }
));

usort($jrCompromisos, function ($a, $b) {
    return ((int)($a['orden'] ?? 0)) <=> ((int)($b['orden'] ?? 0));
});

// ================= CONSEJOS =================
$jrConsejosTitulo = jrRegistro($jrPorClave, 'consejos_titulo');
$jrConsejosCheck = jrRegistro($jrPorClave, 'consejos_check');

$jrConsejos = array_values(array_filter(
    $jrPorSeccion['consejos'] ?? [],
    function ($fila) {
        return strpos($fila['clave'] ?? '', 'consejo_') === 0;
    }
));

usort($jrConsejos, function ($a, $b) {
    return ((int)($a['orden'] ?? 0)) <=> ((int)($b['orden'] ?? 0));
});

// ================= AUTOEVALUACIÓN =================
$jrAutoevaluacion = jrRegistro($jrPorClave, 'autoevaluacion_principal');
$jrMarcoEncuesta = jrRegistro($jrPorClave, 'autoevaluacion_marco');

$jrPreguntas = array_values(array_filter(
    $jrPorSeccion['autoevaluacion'] ?? [],
    function ($fila) {
        return strpos($fila['clave'] ?? '', 'pregunta_') === 0;
    }
));

usort($jrPreguntas, function ($a, $b) {
    return ((int)($a['orden'] ?? 0)) <=> ((int)($b['orden'] ?? 0));
});
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Juego Responsable | Loto</title>

    <style>
        @font-face {
            font-family: 'HelveticaRounded';
            src: url('fonts/HelveticaRoundedLTStd-Bd.ttf') format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        :root {
            --jr-azul: #0865be;
            --jr-azul-oscuro: #004c9e;
            --jr-naranja: #ff7900;
            --jr-crema: #fff9ef;
            --jr-rosado: #ee5795;
            --jr-verde: #74bd19;
            --jr-morado: #7952bf;
            --jr-gris: #656565;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            background: #ffffff;
            color: #333333;
            font-family: 'HelveticaRounded', Arial, sans-serif;
        }

        img {
            display: block;
            max-width: 100%;
        }

        button,
        a {
            font-family: inherit;
        }

        .jr-pagina {
            width: 100%;
            overflow: hidden;
        }

        .jr-contenedor {
            width: calc(100% - 40px);
            max-width: 1180px;
            margin: 0 auto;
        }

        /* =====================================
           BANNER PRINCIPAL
           ===================================== */

        /* =====================================
   BANNER PRINCIPAL
   ===================================== */

.jr-banner {
    position: relative;
    min-height: 390px;
    padding: 115px 0 45px;
    overflow: hidden;
    background: #0057a8;
}
.jr-banner-fondo-imagen {
    position: absolute;
    inset: 0;
    z-index: 0;
    display: block;
    width: 100%;
    height: 100%;
    max-width: none;
    object-fit: cover;
    object-position: center;
    pointer-events: none;
}

/* CONTENIDO DEL BANNER */
.jr-banner-contenido {
    position: relative;
    z-index: 2;
    display: grid;
    grid-template-columns: 39% 61%;
    align-items: center;
    gap: 45px;
}

/* LOGO SOMOS OTRO NIVEL */
.jr-banner-logo {
    display: flex;
    align-items: center;
    justify-content: center;
}

.jr-banner-logo img {
    display: block;
    width: 330px;
    max-height: 235px;
    object-fit: contain;
}

/* INFORMACIÓN DERECHA */
.jr-banner-informacion {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    color: #ffffff;
}

.jr-banner-informacion h1 {
    max-width: 690px;
    margin: 0;
    color: #ffffff;
    font-size: 34px;
    line-height: 1.05;
    text-align: left;
    text-transform: uppercase;
}

.jr-banner-informacion .naranja {
    color: #ff7900;
}

/* FECHA DESTACADA */
.jr-banner-fecha {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    align-self: center;
    min-height: 38px;
    margin-top: 19px;
    padding: 8px 25px;
    background: linear-gradient(
        180deg,
        #ff8a00 0%,
        #ff6f00 100%
    );
    color: #ffffff;
    border: 2px solid rgba(255, 255, 255, 0.22);
    border-radius: 30px;
    box-shadow:
        0 5px 12px rgba(0, 0, 0, 0.18),
        inset 0 1px 0 rgba(255, 255, 255, 0.25);
    font-family: 'HelveticaRounded', Arial, sans-serif;
    font-size: 16px;
    font-weight: bold;
    line-height: 1;
    letter-spacing: 0.2px;
    text-align: center;
    text-transform: uppercase;
}

        /* =====================================
           RECONOCIMIENTO INTERNACIONAL
           ===================================== */

        .jr-reconocimiento {
    padding: 38px 0 60px;
    background: #ffffff;
}

.jr-reconocimiento-tarjeta {
    position: relative;
    max-width: 960px;
    min-height: 295px;
    margin: 0 auto;
    padding: 28px 180px 55px;
    background: var(--jr-crema);
    border: 2px solid #e5e5e5;
    border-radius: 20px;
    text-align: center;
}

        .jr-reconocimiento-cabecera {
            margin: -28px -180px 20px;
            padding: 20px 170px;
            background: linear-gradient(
                90deg,
                var(--jr-azul-oscuro),
                var(--jr-azul)
            );
            border-radius: 18px 18px 0 0;
        }

        .jr-reconocimiento-cabecera h2 {
    margin: 0;
    color: #ffffff;
    font-family: 'HelveticaRounded', Arial, sans-serif;
    font-size: 36px;
    font-weight: 900;
    line-height: 1.05;
    text-transform: uppercase;
}

        .jr-reconocimiento-cabecera .naranja {
            color: var(--jr-naranja);
        }

        .jr-reconocimiento-texto {
    max-width: 650px;
    margin: 5px auto 10px;
    color: #0865be;
    font-family: 'HelveticaRounded', Arial, sans-serif;
    font-size: 18px;
    font-weight: 900;
    line-height: 1.2;
    letter-spacing: 0.15px;
    text-align: center;
    text-transform: uppercase;
}

        .jr-logo-responsable {
            position: absolute;
            top: 52px;
            left: -45px;
            z-index: 3;
            width: 180px;
            filter: drop-shadow(0 5px 5px rgba(0, 0, 0, 0.18));
        }

        .jr-sello {
            position: absolute;
            top: 70px;
            right: -38px;
            z-index: 3;
            width: 165px;
            filter: drop-shadow(0 5px 5px rgba(0, 0, 0, 0.18));
        }

        .jr-reconocimiento-botones {
    position: absolute;
    left: 50%;
    bottom: -23px;
    z-index: 10;
    display: flex;
    justify-content: center;
    width: max-content;
    margin: 0;
    transform: translateX(-50%);
}

        .jr-boton-wla,
        .jr-boton-conocer {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 45px;
            padding: 10px 25px;
            color: #ffffff;
            font-size: 16px;
            text-decoration: none;
            text-transform: uppercase;
        }

        .jr-boton-wla {
            background: var(--jr-naranja);
            border-radius: 25px 0 0 25px;
        }

        .jr-boton-conocer {
            background: var(--jr-azul);
            border-radius: 0 25px 25px 0;
        }

        .jr-boton-wla:hover,
        .jr-boton-conocer:hover {
            filter: brightness(0.92);
        }

        /* =====================================
   NUESTRO COMPROMISO
   ===================================== */

.jr-compromiso {
    padding: 20px 0 45px;
    background: #ffffff;
}

.jr-panel {
    max-width: 1000px;
    margin: 0 auto;
    padding: 22px 25px 20px;
    background: #ffffff;
    border: 2px solid #e6e6e6;
    border-radius: 20px;
}

/* TÍTULO */
.jr-titulo {
    margin: 0;
    color: #0865be;
    font-family: 'HelveticaRounded', Arial, sans-serif;
    font-size: 48px;
    font-weight: 900;
    line-height: 1.05;
    text-align: center;
    text-transform: uppercase;
}

.jr-subtitulo {
    max-width: 850px;
    margin: 10px auto 32px;
    color: #5f5f5f;
    font-family: 'HelveticaRounded', Arial, sans-serif;
    font-size: 20px;
    font-weight: 700;
    line-height: 1.25;
    text-align: center;
}

/* CONTENEDOR DE LAS CINCO TARJETAS */
.jr-compromiso-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 12px;

    /* Hace que sobresalgan un poco del rectángulo */
    margin-right: -38px;
    margin-bottom: -8px;
    margin-left: -38px;
}

/* TARJETAS */
.jr-compromiso-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    min-height: 235px;
    padding: 18px 12px 16px;
    color: #ffffff;
    border-radius: 14px;
    text-align: center;
}

/* COLORES */
.jr-compromiso-card:nth-child(1) {
    background: #ff7900;
}

.jr-compromiso-card:nth-child(2) {
    background: #0867d9;
}

.jr-compromiso-card:nth-child(3) {
    background: #7952bf;
}

.jr-compromiso-card:nth-child(4) {
    background: #74bd19;
}

.jr-compromiso-card:nth-child(5) {
    background: #ee5795;
}

/* ÍCONOS */
.jr-compromiso-card img {
    width: 92px;
    height: 92px;
    margin: 0 auto 12px;
    object-fit: contain;
}

/* LETRAS BLANCAS */
.jr-compromiso-card p {
    display: flex;
    flex: 1;
    align-items: center;
    justify-content: center;
    margin: 0;
    color: #ffffff;
    font-family: 'HelveticaRounded', Arial, sans-serif;
    font-size: 16px;
    font-weight: 900;
    line-height: 1.12;
    text-align: center;
}

        /* =====================================
   CONSEJOS PARA JUGAR RESPONSABLEMENTE
   ===================================== */

.jr-consejos {
    padding: 25px 0 70px;
    overflow: hidden;
    background: #ffffff;
}

.jr-consejos-contenido {
    position: relative;
    max-width: 970px;
    margin: 0 auto;
    padding-bottom: 15px;
}

/* RECTÁNGULO GRIS DE FONDO */
.jr-consejos-contenido::before {
    content: "";
    position: absolute;
    top: 95px;
    right: -80px;
    bottom: 0;
    left: -80px;
    z-index: 0;
    background: #ffffff;
    border: 3px solid #e6e6e6;
    border-radius: 25px;
}

/* TÍTULO */
.jr-consejos h2 {
    position: relative;
    z-index: 2;
    margin: 0 0 27px;
    color: #ff7900;
    font-family: 'HelveticaRounded', Arial, sans-serif;
    font-size: 40px;
    font-weight: 900;
    line-height: 1.05;
    text-align: center;
    text-transform: uppercase;
}

/* LISTA */
.jr-consejos-lista {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin: 0;
    padding: 0;
    list-style: none;
}

/* BARRA NARANJA */
.jr-consejo {
    position: relative;
    display: flex;
    align-items: center;
    min-height: 82px;
    margin-left: 0;
    padding: 15px 25px 15px 62px;
    background: #ff7900;
    color: #ffffff;
    border-radius: 12px;
    font-family: 'HelveticaRounded', Arial, sans-serif;
    font-size: 22px;
    font-weight: 900;
    line-height: 1.15;
}

/* CHECK AZUL */
.jr-consejo img {
    position: absolute;
    top: 50%;
    left: -27px;
    z-index: 3;
    width: 55px;
    height: 55px;
    object-fit: contain;
    transform: translateY(-50%);
}
        /* =====================================
           AUTOEVALUACIÓN
           ===================================== */

        .jr-autoevaluacion {
            position: relative;
            padding: 55px 0 70px;
            overflow: hidden;
            background: var(--jr-crema);
        }

        .jr-autoevaluacion-contenido {
    display: grid;
    grid-template-columns: 48% 52%;
    align-items: center;
    max-width: 1150px;
    margin: 0 auto;
}

       /* CONTENEDOR DE LA IMAGEN Y EL FORMULARIO */
.jr-telefono {
    position: relative;
    width: 470px;
    height: 600px;
    margin: 0 auto;
    padding: 0;
    overflow: visible;
    background: transparent;
}

/* IMAGEN TABLA PARA ENCUESTA */
.jr-tabla-encuesta-imagen {
    position: absolute;
    inset: 0;
    z-index: 1;
    display: block;
    width: 100%;
    height: 100%;
    max-width: none;
    object-fit: fill;
    pointer-events: none;
}

/* FORMULARIO DENTRO DEL ESPACIO BLANCO */
/* FORMULARIO COLOCADO EN EL ESPACIO BLANCO */
.jr-encuesta {
    position: absolute;
    top: 112px;
    right: 55px;
    bottom: 45px;
    left: 55px;
    z-index: 5;
    min-height: 0;
    padding: 18px 15px 20px;
    overflow: visible;
    background: transparent;
    border-radius: 24px;
}

.jr-pregunta {
    display: none;
}

/* FORMULARIO COLOCADO EN EL ESPACIO BLANCO */


        .jr-pregunta {
            display: none;
        }

        .jr-pregunta.activa {
            display: block;
        }

        .jr-pregunta h3 {
    min-height: 92px;
    margin: 0 0 15px;
    color: #0865be;
    font-family: 'HelveticaRounded', Arial, sans-serif;
    font-size: 20px;
    font-weight: 900;
    line-height: 1.18;
}

        .jr-opciones {
    display: grid;
    gap: 15px;
}

.jr-opcion {
    display: flex;
    align-items: center;
    width: 100%;
    min-height: 84px;
    padding: 14px 20px;
    background: #ffffff;
    color: #696969;
    border: 3px solid #e5e5e5;
    border-radius: 14px;
    cursor: pointer;
    font-family: 'HelveticaRounded', Arial, sans-serif;
    font-size: 38px;
    font-weight: 900;
    line-height: 1;
    text-align: left;
}

.jr-opcion:hover,
.jr-opcion.seleccionada {
    color: #0865be;
    background: #f4f9ff;
    border-color: #0865be;
}

/* BOTÓN ENVIAR: OCULTO HASTA LA PREGUNTA 7 */
.jr-enviar {
    position: absolute;
    left: 50%;
    bottom: 0;
    z-index: 60;
    display: none;
    align-items: center;
    justify-content: center;
    min-width: 180px;
    min-height: 48px;
    padding: 10px 30px;
    background: #75bd16;
    color: #ffffff;
    border: 0;
    border-radius: 28px;
    cursor: pointer;
    font-family: 'HelveticaRounded', Arial, sans-serif;
    font-size: 23px;
    font-weight: 900;
    line-height: 1;
    transform: translateX(-50%);
}

.jr-enviar.visible {
    display: flex;
}

.jr-enviar:hover {
    background: #64a912;
}

.jr-enviar:disabled {
    cursor: not-allowed;
    opacity: 0.55;
}

.jr-enviar.enviando {
    background: #999999;
}

       .jr-encuesta-pie {
    position: static;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 18px;
}

.jr-encuesta-anterior,
.jr-encuesta-siguiente {
    position: absolute;
    top: 50%;
    z-index: 50;
    display: flex !important;
    visibility: visible !important;
    align-items: center;
    justify-content: center;
    width: 55px;
    height: 55px;
    padding: 0 0 5px;
    background: #0871db;
    color: #ffffff;
    border: 0;
    border-radius: 50%;
    cursor: pointer;
    font-family: Arial, sans-serif;
    font-size: 48px;
    font-weight: normal;
    line-height: 1;
    box-shadow: 0 3px 7px rgba(0, 0, 0, 0.15);
    transform: translateY(-50%);
}

.jr-encuesta-anterior {
    left: -83px;
}

.jr-encuesta-siguiente {
    right: -83px;
}

.jr-encuesta-anterior:disabled,
.jr-encuesta-siguiente:disabled {
    display: flex !important;
    visibility: visible !important;
    cursor: not-allowed;
    opacity: 0.55;
}

     .jr-progreso {
    position: absolute;
    right: 22px;
    bottom: 12px;
    z-index: 61;
    color: #0865be;
    font-family: 'HelveticaRounded', Arial, sans-serif;
    font-size: 17px;
    font-weight: 900;
}

        .jr-resultado {
            display: none;
            color: var(--jr-azul);
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .jr-resultado.visible {
            display: block;
        }

        .jr-resultado h3 {
            margin: 10px 0;
            font-size: 24px;
        }

        .jr-resultado p {
            font-size: 15px;
            line-height: 1.35;
        }

        .jr-reiniciar {
            padding: 10px 20px;
            background: var(--jr-verde);
            color: #ffffff;
            border: 0;
            border-radius: 20px;
            cursor: pointer;
            font-weight: bold;
        }

        .jr-autoevaluacion-texto {
    padding-left: 55px;
}

.jr-autoevaluacion-texto h2 {
    margin: 0 0 20px;
    color: #0865be;
    font-family: 'HelveticaRounded', Arial, sans-serif;
    font-size: 54px;
    font-weight: 900;
    line-height: 1;
    text-transform: uppercase;
}

.jr-autoevaluacion-texto p {
    max-width: 520px;
    margin: 0;
    color: #656565;
    font-family: 'HelveticaRounded', Arial, sans-serif;
    font-size: 20px;
    font-weight: 700;
    line-height: 1.25;
}

        /* =====================================
           TABLET
           ===================================== */

        @media (max-width: 950px) {
            .jr-banner-contenido {
                grid-template-columns: 35% 65%;
            }

            .jr-banner-informacion h1 {
                font-size: 28px;
            }

            .jr-reconocimiento-tarjeta {
                margin: 0 55px;
                padding-right: 135px;
                padding-left: 135px;
            }

            .jr-reconocimiento-cabecera {
                margin-right: -135px;
                margin-left: -135px;
                padding-right: 130px;
                padding-left: 130px;
            }

            .jr-compromiso-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        /* =====================================
           TELÉFONO
           ===================================== */

        @media (max-width: 700px) {
            .jr-contenedor {
                width: calc(100% - 24px);
            }

            .jr-banner {
    min-height: auto;
    padding: 95px 0 38px;
    background-image: none;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
}

.jr-banner-contenido {
    grid-template-columns: 1fr;
    gap: 22px;
    text-align: center;
}

.jr-banner-logo img {
    width: 260px;
}

.jr-banner-informacion {
    align-items: center;
}

.jr-banner-informacion h1 {
    max-width: 420px;
    margin: 0 auto;
    font-size: 23px;
    line-height: 1.08;
    text-align: center;
}

.jr-banner-fecha {
    min-height: 35px;
    margin-top: 17px;
    padding: 8px 18px;
    font-size: 12px;
    line-height: 1.1;
}

            .jr-reconocimiento {
                padding-top: 25px;
            }

            .jr-reconocimiento-tarjeta {
                min-height: 390px;
                margin: 0 20px;
                padding: 25px 20px;
            }

            .jr-reconocimiento-cabecera {
                margin: -25px -20px 95px;
                padding: 20px 15px;
            }

            .jr-reconocimiento-cabecera h2 {
                font-size: 25px;
            }

            .jr-logo-responsable {
                top: 90px;
                left: 18px;
                width: 115px;
            }

            .jr-sello {
                top: 92px;
                right: 15px;
                width: 110px;
            }

            .jr-reconocimiento-texto {
                font-size: 14px;
            }

            .jr-reconocimiento-botones {
                flex-direction: column;
                align-items: center;
                gap: 8px;
            }

            .jr-boton-wla,
            .jr-boton-conocer {
                width: 230px;
                border-radius: 25px;
            }

            .jr-panel {
                padding: 25px 14px;
            }

            .jr-titulo {
                font-size: 28px;
            }

            .jr-compromiso-grid {
    grid-template-columns: 1fr;
    gap: 12px;
    margin: 0;
}

.jr-compromiso-card {
    width: 100%;
    min-height: 205px;
}

.jr-compromiso-card img {
    width: 95px;
    height: 95px;
}

.jr-compromiso-card p {
    font-size: 17px;
}

            .jr-compromiso-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .jr-compromiso-card {
                min-height: 185px;
            }

            .jr-consejos h2 {
                font-size: 24px;
            }

            .jr-consejo {
                padding-left: 40px;
                font-size: 14px;
            }

            .jr-autoevaluacion {
                padding-top: 75px;
            }

            .jr-autoevaluacion-contenido {
                grid-template-columns: 1fr;
                gap: 35px;
            }

            .jr-telefono {
                width: min(100%, 330px);
            }

            .jr-autoevaluacion-texto {
                padding: 0;
                text-align: center;
            }

            .jr-autoevaluacion-texto h2 {
                font-size: 34px;
            }

            .jr-autoevaluacion-texto p {
                margin: 0 auto;
            }
        }

        .jr-consejos {
    padding: 25px 15px 55px;
}

.jr-consejos-contenido {
    max-width: 100%;
}

.jr-consejos-contenido::before {
    top: 90px;
    right: -10px;
    left: -10px;
    border-width: 2px;
    border-radius: 18px;
}

.jr-consejos h2 {
    margin-bottom: 25px;
    font-size: 28px;
}

.jr-consejos-lista {
    gap: 10px;
}

.jr-consejo {
    min-height: 72px;
    margin-left: 15px;
    padding: 12px 14px 12px 43px;
    border-radius: 10px;
    font-size: 16px;
    line-height: 1.2;
}

.jr-consejo img {
    left: -23px;
    width: 48px;
    height: 48px;
}


/* RESPONSIVE FINAL JUEGO RESPONSABLE */

html,
body {
    width: 100%;
    max-width: 100%;
    overflow-x: hidden;
}

.jr-pagina {
    width: 100%;
    max-width: 100%;
}

/* TABLET */
@media (max-width: 950px) {
    .jr-contenedor {
        width: calc(100% - 30px) !important;
    }

    .jr-reconocimiento-tarjeta {
        width: 100% !important;
        margin-right: auto !important;
        margin-left: auto !important;
    }

    .jr-compromiso-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}

/* TELÉFONO */
@media (max-width: 700px) {

    /* Evita que el header cubra el banner */
    .jr-pagina {
        margin-top: 180px !important;
    }

    .jr-contenedor {
        width: calc(100% - 24px) !important;
    }

    .jr-banner {
        min-height: 600px !important;
        padding: 45px 0 40px !important;
        background-position: center !important;
        background-size: cover !important;
    }

    .jr-banner-contenido {
        display: grid !important;
        grid-template-columns: 1fr !important;
        gap: 20px !important;
        text-align: center !important;
    }

    .jr-banner-logo {
        width: 100% !important;
    }

    .jr-banner-logo img {
        width: min(260px, 75vw) !important;
        margin: 0 auto !important;
    }

    .jr-banner-informacion {
        width: 100% !important;
        align-items: center !important;
    }

    .jr-banner-informacion h1 {
        width: 100% !important;
        max-width: 420px !important;
        margin: 0 auto !important;
        font-size: clamp(1.25rem, 5.7vw, 1.55rem) !important;
        line-height: 1.12 !important;
        text-align: center !important;
    }

    .jr-banner-fecha {
        width: fit-content !important;
        max-width: 95% !important;
        margin: 18px auto 0 !important;
        padding: 9px 16px !important;
        font-size: 0.72rem !important;
        white-space: normal !important;
    }

    /* Reconocimiento */
    .jr-reconocimiento-tarjeta {
        width: 100% !important;
        min-height: auto !important;
        margin: 0 auto !important;
        padding: 25px 18px 30px !important;
    }

    .jr-reconocimiento-cabecera {
        margin: -25px -18px 100px !important;
        padding: 20px 12px !important;
    }

    .jr-reconocimiento-cabecera h2 {
        font-size: 1.45rem !important;
    }

    .jr-reconocimiento-botones {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
    }

    .jr-boton-wla,
    .jr-boton-conocer {
        width: min(100%, 260px) !important;
    }

    /* Tarjetas */
    .jr-panel {
        width: 100% !important;
        padding: 25px 14px !important;
    }

    .jr-titulo {
        font-size: 1.75rem !important;
    }

    .jr-compromiso-grid {
        grid-template-columns: 1fr !important;
        gap: 14px !important;
    }

    .jr-compromiso-card {
        width: 100% !important;
        min-height: 185px !important;
    }

    /* Consejos */
    .jr-consejos {
        padding-right: 12px !important;
        padding-left: 12px !important;
    }

    .jr-consejos h2 {
        font-size: 1.55rem !important;
    }

    .jr-consejo {
        width: calc(100% - 15px) !important;
        margin-left: 15px !important;
        padding: 14px 12px 14px 42px !important;
        font-size: 0.95rem !important;
    }

    /* Autoevaluación */
    .jr-autoevaluacion-contenido {
        grid-template-columns: 1fr !important;
        gap: 35px !important;
    }

    .jr-telefono {
        width: min(100%, 340px) !important;
        margin: 0 auto !important;
    }

    .jr-autoevaluacion-texto {
        width: 100% !important;
        padding: 0 !important;
        text-align: center !important;
    }

    .jr-autoevaluacion-texto h2 {
        font-size: 2rem !important;
    }

    .jr-autoevaluacion-texto p {
        margin: 0 auto !important;
        font-size: 1rem !important;
    }
}

/* TELÉFONOS PEQUEÑOS */
@media (max-width: 380px) {
    .jr-pagina {
        margin-top: 190px !important;
    }

    .jr-banner-logo img {
        width: 225px !important;
    }

    .jr-banner-informacion h1 {
        font-size: 1.2rem !important;
    }
}

/* CORRECCIÓN RECONOCIMIENTO Y AUTOEVALUACIÓN EN TELÉFONO */
@media (max-width: 700px) {

    /* RECONOCIMIENTO */
    .jr-reconocimiento {
        padding: 30px 12px 45px !important;
    }

    .jr-reconocimiento-tarjeta {
        width: 100% !important;
        margin: 0 auto !important;
        padding: 0 18px 28px !important;
        overflow: visible !important;
    }

    .jr-reconocimiento-cabecera {
        margin: 0 -18px 20px !important;
        padding: 16px 12px !important;
        border-radius: 18px 18px 0 0 !important;
    }

    .jr-reconocimiento-cabecera h2 {
        font-size: 1.25rem !important;
        line-height: 1.05 !important;
    }

    /* Logos dejan de estar flotando sobre el texto */
    .jr-logo-responsable,
    .jr-sello {
        position: static !important;
        display: inline-block !important;
        width: 95px !important;
        height: 95px !important;
        margin: 12px 15px !important;
        object-fit: contain !important;
    }

    .jr-reconocimiento-texto {
        clear: both !important;
        width: 100% !important;
        margin: 10px auto 18px !important;
        padding: 0 5px !important;
        font-size: 0.9rem !important;
        line-height: 1.3 !important;
    }

    /* Los botones ya no quedan montados */
    .jr-reconocimiento-botones {
        position: static !important;
        display: flex !important;
        flex-direction: column !important;
        width: 100% !important;
        margin: 18px auto 0 !important;
        transform: none !important;
        gap: 10px !important;
    }

    .jr-boton-wla,
    .jr-boton-conocer {
        width: min(100%, 260px) !important;
        min-height: 46px !important;
        margin: 0 auto !important;
        border-radius: 25px !important;
        font-size: 0.9rem !important;
    }

    /* AUTOEVALUACIÓN */
    .jr-autoevaluacion {
        padding: 45px 0 65px !important;
    }

    .jr-telefono {
        width: 340px !important;
        max-width: calc(100vw - 60px) !important;
        height: 600px !important;
        margin: 0 auto !important;
    }

    /* Baja la pregunta para que no toque el gancho azul */
    .jr-encuesta {
        top: 135px !important;
        right: 48px !important;
        bottom: 48px !important;
        left: 48px !important;
        padding: 10px 8px 18px !important;
    }

    .jr-pregunta h3 {
        min-height: 110px !important;
        margin: 0 0 12px !important;
        font-size: 1.05rem !important;
        line-height: 1.18 !important;
        text-align: center !important;
    }

    .jr-opciones {
        gap: 12px !important;
    }

    .jr-opcion {
        min-height: 66px !important;
        padding: 10px 16px !important;
        font-size: 2rem !important;
        border-width: 2px !important;
    }

    /* Flechas más pequeñas y colocadas sobre los bordes */
    .jr-encuesta-anterior,
    .jr-encuesta-siguiente {
        top: 53% !important;
        width: 46px !important;
        height: 46px !important;
        font-size: 37px !important;
    }

    .jr-encuesta-anterior {
        left: -66px !important;
    }

    .jr-encuesta-siguiente {
        right: -66px !important;
    }

    .jr-progreso {
        right: 12px !important;
        bottom: 5px !important;
        font-size: 0.9rem !important;
    }
}
/* AJUSTE FINAL DEL FORMULARIO RESPONSIVE */
@media (max-width: 700px) {

    .jr-telefono {
        width: min(340px, calc(100vw - 60px)) !important;
        height: auto !important;
        aspect-ratio: 470 / 600 !important;
    }

    .jr-tabla-encuesta-imagen {
        width: 100% !important;
        height: 100% !important;
        object-fit: fill !important;
    }

    .jr-encuesta {
        top: 24% !important;
        right: 14% !important;
        bottom: 10% !important;
        left: 14% !important;
        padding: 0 !important;
    }

    .jr-pregunta h3 {
        min-height: 78px !important;
        margin: 0 0 12px !important;
        font-size: clamp(0.9rem, 4.2vw, 1.05rem) !important;
        line-height: 1.15 !important;
        text-align: center !important;
    }

    .jr-opciones {
        gap: 10px !important;
    }

    .jr-opcion {
        min-height: 54px !important;
        padding: 8px 14px !important;
        font-size: clamp(1.65rem, 8vw, 2rem) !important;
        border-width: 2px !important;
        border-radius: 11px !important;
    }

    .jr-encuesta-anterior,
    .jr-encuesta-siguiente {
        top: 55% !important;
        width: 42px !important;
        height: 42px !important;
        font-size: 34px !important;
    }

    .jr-encuesta-anterior {
        left: -58px !important;
    }

    .jr-encuesta-siguiente {
        right: -58px !important;
    }

    .jr-enviar {
        bottom: -21px !important;
        min-width: 135px !important;
        min-height: 42px !important;
        padding: 8px 20px !important;
        font-size: 1.15rem !important;
    }

    .jr-progreso {
        right: 0 !important;
        bottom: -42px !important;
        font-size: 0.85rem !important;
    }
}

/* TELÉFONOS MUY PEQUEÑOS */
@media (max-width: 380px) {

    .jr-telefono {
        width: calc(100vw - 70px) !important;
    }

    .jr-pregunta h3 {
        min-height: 70px !important;
        font-size: 0.88rem !important;
    }

    .jr-opcion {
        min-height: 50px !important;
        font-size: 1.65rem !important;
    }

    .jr-encuesta-anterior,
    .jr-encuesta-siguiente {
        width: 38px !important;
        height: 38px !important;
        font-size: 30px !important;
    }

    .jr-encuesta-anterior {
        left: -52px !important;
    }

    .jr-encuesta-siguiente {
        right: -52px !important;
    }

    .jr-enviar {
        min-width: 125px !important;
        font-size: 1rem !important;
    }
}

    </style>
</head>

<body>

<main class="jr-pagina">

    <!-- ===================================
         BANNER PRINCIPAL
         =================================== -->
    <section class="jr-banner">

        <img
            class="jr-banner-fondo-imagen"
            src="<?= jrHtml(jrValor(
                $jrBanner,
                'imagen_url',
                'ImagesSV/FONDO (1).jpg'
            )) ?>"
            alt=""
        >

        <div class="jr-contenedor">
            <div class="jr-banner-contenido">

                <div class="jr-banner-logo">
                    <img
                        src="<?= jrHtml(jrValor(
                            $jrBannerLogo,
                            'imagen_url',
                            'ImagesSV/GRAFISMO (2).png'
                        )) ?>"
                        alt="Somos otro nivel, WLA"
                    >
                </div>

                <div class="jr-banner-informacion">

                    <h1>
                        <?= jrTextoConDestacado(
                            jrValor(
                                $jrBanner,
                                'titulo',
                                'Somos la primera lotería de Centroamérica en obtener la certificación nivel 3 de Juego Responsable otorgada por la Asociación Mundial de Loterías'
                            ),
                            jrValor(
                                $jrBanner,
                                'titulo_destacado',
                                'certificación nivel 3 de Juego Responsable'
                            )
                        ) ?>
                    </h1>

                    <span class="jr-banner-fecha">
                        <?= jrHtml(jrValor(
                            $jrBanner,
                            'texto',
                            '17 de febrero, Día del Juego Responsable'
                        )) ?>
                    </span>

                </div>
            </div>
        </div>
    </section>

    <!-- ===================================
         RECONOCIMIENTO INTERNACIONAL
         =================================== -->
    <section class="jr-reconocimiento">
        <div class="jr-contenedor">

            <div class="jr-reconocimiento-tarjeta">

                <img
                    class="jr-logo-responsable"
                    src="<?= jrHtml(jrValor(
                        $jrReconocimientoLogo,
                        'imagen_url',
                        'ImagesSV/JUEGO RESPO (1).png'
                    )) ?>"
                    alt="Juego Responsable"
                >

                <img
                    class="jr-sello"
                    src="<?= jrHtml(jrValor(
                        $jrReconocimientoSello,
                        'imagen_url',
                        'ImagesSV/SELLO (1).png'
                    )) ?>"
                    alt="Certificación WLA RGF"
                >

                <div class="jr-reconocimiento-cabecera">
                    <h2>
                        <?= jrTextoConDestacado(
                            jrValor(
                                $jrReconocimiento,
                                'titulo',
                                'Un esfuerzo reconocido a nivel internacional'
                            ),
                            jrValor(
                                $jrReconocimiento,
                                'titulo_destacado',
                                'internacional'
                            )
                        ) ?>
                    </h2>
                </div>

                <p class="jr-reconocimiento-texto">
                    <?= jrHtml(jrValor(
                        $jrReconocimiento,
                        'texto',
                        'Que respalda nuestras acciones con la promoción de prácticas responsables y la protección de los jugadores.'
                    )) ?>
                </p>

                <div class="jr-reconocimiento-botones">

                    <a
                        class="jr-boton-wla"
                        href="<?= jrHtml(jrValor(
                            $jrBotonWla,
                            'link_url',
                            '#nuestro-compromiso'
                        )) ?>"
                    >
                        <?= jrHtml(jrValor(
                            $jrBotonWla,
                            'titulo',
                            '¿Qué es la WLA?'
                        )) ?>
                    </a>

                    <a
                        class="jr-boton-conocer"
                        href="<?= jrHtml(jrValor(
                            $jrBotonConocer,
                            'link_url',
                            '#autoevaluacion'
                        )) ?>"
                    >
                        <?= jrHtml(jrValor(
                            $jrBotonConocer,
                            'titulo',
                            'Conocé más aquí'
                        )) ?>
                    </a>

                </div>
            </div>
        </div>
    </section>

    <!-- ===================================
         NUESTRO COMPROMISO
         =================================== -->
    <section
        class="jr-compromiso"
        id="nuestro-compromiso"
    >
        <div class="jr-contenedor">
            <div class="jr-panel">

                <h2 class="jr-titulo">
                    <?= jrHtml(jrValor(
                        $jrCompromisoTitulo,
                        'titulo',
                        'Nuestro compromiso'
                    )) ?>
                </h2>

                <p class="jr-subtitulo">
                    <?= jrHtml(jrValor(
                        $jrCompromisoTitulo,
                        'texto',
                        'Trabajamos constantemente para promover una cultura de juego responsable, a través de acciones concretas, educación y comunicación transparente.'
                    )) ?>
                </p>

                <div class="jr-compromiso-grid">

                    <?php if (!empty($jrCompromisos)): ?>

                        <?php foreach ($jrCompromisos as $compromiso): ?>
                            <article
                                class="jr-compromiso-card"
                                style="background: <?= jrHtml(jrValor(
                                    $compromiso,
                                    'color_hex',
                                    '#0867d9'
                                )) ?>;"
                            >
                                <img
                                    src="<?= jrHtml(jrValor($compromiso, 'imagen_url')) ?>"
                                    alt="<?= jrHtml(jrValor(
                                        $compromiso,
                                        'titulo',
                                        'Compromiso'
                                    )) ?>"
                                >

                                <p>
                                    <?= nl2br(jrHtml(jrValor(
                                        $compromiso,
                                        'texto'
                                    ))) ?>
                                </p>
                            </article>
                        <?php endforeach; ?>

                    <?php else: ?>

                        <article class="jr-compromiso-card">
                            <img src="ImagesSV/Icono 21 (2).svg" alt="Mayores de 21 años">
                            <p>No se vende a<br>menores de 21 años</p>
                        </article>

                        <article class="jr-compromiso-card">
                            <img src="ImagesSV/icono publicidad (2).svg" alt="Publicidad responsable">
                            <p>Publicidad<br>responsable</p>
                        </article>

                        <article class="jr-compromiso-card">
                            <img src="ImagesSV/icono porcentajes (2).svg" alt="Información clara">
                            <p>Información clara<br>sobre probabilidades y<br>premios</p>
                        </article>

                        <article class="jr-compromiso-card">
                            <img src="ImagesSV/icono capacitacion (2).svg" alt="Capacitación continua">
                            <p>Capacitación continua<br>de colaboradores y<br>vendedores</p>
                        </article>

                        <article class="jr-compromiso-card">
                            <img src="ImagesSV/icono habitos juego (2).svg" alt="Hábitos saludables">
                            <p>Promoción de hábitos<br>de juego saludables</p>
                        </article>

                    <?php endif; ?>

                </div>
            </div>
        </div>
    </section>

    <!-- ===================================
         CONSEJOS
         =================================== -->
    <section class="jr-consejos">
        <div class="jr-contenedor">

            <div class="jr-consejos-contenido">

                <h2>
                    <?= jrHtml(jrValor(
                        $jrConsejosTitulo,
                        'titulo',
                        'Consejos para jugar responsablemente'
                    )) ?>
                </h2>

                <ul class="jr-consejos-lista">

                    <?php if (!empty($jrConsejos)): ?>

                        <?php foreach ($jrConsejos as $consejo): ?>
                            <li class="jr-consejo">

                                <img
                                    src="<?= jrHtml(jrValor(
                                        $jrConsejosCheck,
                                        'imagen_url',
                                        'ImagesSV/check azul (2).svg'
                                    )) ?>"
                                    alt=""
                                >

                                <?= jrHtml(jrValor(
                                    $consejo,
                                    'texto'
                                )) ?>

                            </li>
                        <?php endforeach; ?>

                    <?php else: ?>

                        <?php
                        $consejosFallback = [
                            'Jugá por diversión y entretenimiento. Establecé un presupuesto y respetálo.',
                            'Administrá el tiempo para jugar de forma equilibrada.',
                            'Conocé las reglas y probabilidades de cada juego.',
                            'Tomá pausas y disfrutá de otras actividades de entretenimiento.',
                            'Buscá orientación si necesitás apoyo para mantener hábitos de juego saludables.'
                        ];
                        ?>

                        <?php foreach ($consejosFallback as $consejo): ?>
                            <li class="jr-consejo">
                                <img
                                    src="ImagesSV/check azul (2).svg"
                                    alt=""
                                >
                                <?= jrHtml($consejo) ?>
                            </li>
                        <?php endforeach; ?>

                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </section>

    <!-- ===================================
         AUTOEVALUACIÓN
         =================================== -->
    <section
        class="jr-autoevaluacion"
        id="autoevaluacion"
    >
        <div class="jr-contenedor">
            <div class="jr-autoevaluacion-contenido">

                <div class="jr-telefono">

                    <img
                        class="jr-tabla-encuesta-imagen"
                        src="<?= jrHtml(jrValor(
                            $jrMarcoEncuesta,
                            'imagen_url',
                            'ImagesSV/tabla para encuesta (1).svg'
                        )) ?>"
                        alt=""
                    >

                    <div
                        class="jr-encuesta"
                        id="jrEncuesta"
                    >

                        <?php
                        if (empty($jrPreguntas)) {
                            $jrPreguntas = [
                                [
                                    'campo_formulario' => 'JugoParaDeudas',
                                    'texto' => '¿Has jugado alguna vez para pagar tus deudas o resolver dificultades financieras?'
                                ],
                                [
                                    'campo_formulario' => 'ImpulsoVolverAJugar',
                                    'texto' => 'Después de ganar, ¿sentís impulso de volver a jugar?'
                                ],
                                [
                                    'campo_formulario' => 'PidioPrestamo',
                                    'texto' => '¿Has pedido dinero prestado para jugar?'
                                ],
                                [
                                    'campo_formulario' => 'Remordimientos',
                                    'texto' => '¿Has sentido remordimientos después de jugar?'
                                ],
                                [
                                    'campo_formulario' => 'QuedarseSinDinero',
                                    'texto' => '¿Jugás hasta quedarte sin dinero?'
                                ],
                                [
                                    'campo_formulario' => 'RecuperarPerdidas',
                                    'texto' => 'Después de perder, ¿sentís que debés recuperar lo perdido?'
                                ],
                                [
                                    'campo_formulario' => 'TristezaPorJuego',
                                    'texto' => '¿El juego ha causado tristeza en tu vida?'
                                ]
                            ];
                        }
                        ?>

                        <?php foreach ($jrPreguntas as $indice => $pregunta): ?>

                            <div
                                class="jr-pregunta <?= $indice === 0 ? 'activa' : '' ?>"
                                data-pregunta="<?= (int)$indice ?>"
                                data-campo="<?= jrHtml(jrValor(
                                    $pregunta,
                                    'campo_formulario'
                                )) ?>"
                            >
                                <h3>
                                    <?= jrHtml(jrValor(
                                        $pregunta,
                                        'texto'
                                    )) ?>
                                </h3>

                                <div class="jr-opciones">

                                    <button
                                        class="jr-opcion"
                                        type="button"
                                        data-valor="Sí"
                                    >
                                        Sí
                                    </button>

                                    <button
                                        class="jr-opcion"
                                        type="button"
                                        data-valor="No"
                                    >
                                        No
                                    </button>

                                </div>
                            </div>

                        <?php endforeach; ?>

                        <div
                            class="jr-resultado"
                            id="jrResultado"
                        >
                            <h3 id="jrResultadoTitulo">
                                Autoevaluación enviada
                            </h3>

                            <p id="jrResultadoTexto">
                                Gracias por responder de forma honesta.
                            </p>

                            <button
                                class="jr-reiniciar"
                                id="jrReiniciar"
                                type="button"
                            >
                                Realizar nuevamente
                            </button>
                        </div>

                        <div
                            class="jr-encuesta-pie"
                            id="jrEncuestaPie"
                        >
                            <button
                                class="jr-encuesta-anterior"
                                id="jrAnterior"
                                type="button"
                                aria-label="Pregunta anterior"
                                disabled
                            >
                                ‹
                            </button>

                            <button
                                class="jr-enviar"
                                id="jrEnviar"
                                type="button"
                                disabled
                            >
                                Enviar
                            </button>

                            <span
                                class="jr-progreso"
                                id="jrProgreso"
                            >
                                1 / <?= count($jrPreguntas) ?>
                            </span>

                            <button
                                class="jr-encuesta-siguiente"
                                id="jrSiguiente"
                                type="button"
                                aria-label="Pregunta siguiente"
                                disabled
                            >
                                ›
                            </button>
                        </div>

                    </div>
                </div>

                <div class="jr-autoevaluacion-texto">

                    <h2>
                        <?= nl2br(jrHtml(jrValor(
                            $jrAutoevaluacion,
                            'titulo',
                            'Realizá una autoevaluación rápida'
                        ))) ?>
                    </h2>

                    <p>
                        <?= jrHtml(jrValor(
                            $jrAutoevaluacion,
                            'texto',
                            'Respondé estas preguntas de forma honesta. Esta autoevaluación puede ayudarte a identificar si necesitas apoyo u orientación.'
                        )) ?>
                    </p>

                </div>

            </div>
        </div>
    </section>

</main>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const preguntas = Array.from(
        document.querySelectorAll(".jr-pregunta")
    );

    if (preguntas.length === 0) {
        return;
    }

    const respuestas = {};

    const botonAnterior = document.getElementById("jrAnterior");
    const botonSiguiente = document.getElementById("jrSiguiente");
    const botonEnviar = document.getElementById("jrEnviar");
    const progreso = document.getElementById("jrProgreso");
    const resultado = document.getElementById("jrResultado");
    const resultadoTitulo = document.getElementById("jrResultadoTitulo");
    const resultadoTexto = document.getElementById("jrResultadoTexto");
    const encuestaPie = document.getElementById("jrEncuestaPie");
    const botonReiniciar = document.getElementById("jrReiniciar");

    let posicionActual = 0;

    function campoActual() {
        return preguntas[posicionActual].dataset.campo;
    }

    function mostrarPregunta(posicion) {
        posicionActual = posicion;

        preguntas.forEach(function (pregunta, indice) {
            pregunta.classList.toggle(
                "activa",
                indice === posicionActual
            );
        });

        const esPrimera = posicionActual === 0;
        const esUltima = posicionActual === preguntas.length - 1;
        const respondida = respuestas[campoActual()] !== undefined;

        progreso.textContent =
            (posicionActual + 1) + " / " + preguntas.length;

        botonAnterior.disabled = esPrimera;
        botonSiguiente.disabled = !respondida;
        botonEnviar.disabled = !respondida;

        botonSiguiente.style.display =
            esUltima ? "none" : "flex";

        botonEnviar.classList.toggle(
            "visible",
            esUltima
        );
    }

    preguntas.forEach(function (pregunta) {
        const campo = pregunta.dataset.campo;
        const opciones = pregunta.querySelectorAll(".jr-opcion");

        opciones.forEach(function (opcion) {
            opcion.addEventListener("click", function () {
                opciones.forEach(function (otraOpcion) {
                    otraOpcion.classList.remove("seleccionada");
                });

                opcion.classList.add("seleccionada");
                respuestas[campo] = opcion.dataset.valor;

                botonSiguiente.disabled = false;
                botonEnviar.disabled = false;
            });
        });
    });

    botonAnterior.addEventListener("click", function () {
        if (posicionActual > 0) {
            mostrarPregunta(posicionActual - 1);
        }
    });

    botonSiguiente.addEventListener("click", function () {
        if (respuestas[campoActual()] === undefined) {
            return;
        }

        if (posicionActual < preguntas.length - 1) {
            mostrarPregunta(posicionActual + 1);
        }
    });

    botonEnviar.addEventListener("click", async function () {
        if (respuestas[campoActual()] === undefined) {
            return;
        }

        botonEnviar.disabled = true;
        botonEnviar.classList.add("enviando");
        botonEnviar.textContent = "Enviando...";

        try {
            const respuesta = await fetch(
                "responsable.php?accion=enviar_autoevaluacion",
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(respuestas)
                }
            );

            const resultadoServidor = await respuesta.json();

            if (!respuesta.ok || !resultadoServidor.ok) {
                throw new Error(
                    resultadoServidor.mensaje ||
                    "No fue posible enviar la autoevaluación."
                );
            }

            preguntas.forEach(function (pregunta) {
                pregunta.classList.remove("activa");
            });

            encuestaPie.style.display = "none";
            resultado.classList.add("visible");

            resultadoTitulo.textContent =
                "Autoevaluación enviada";

            resultadoTexto.textContent =
                "Gracias por responder de forma honesta.";
        } catch (error) {
            alert(error.message);

            botonEnviar.disabled = false;
            botonEnviar.classList.remove("enviando");
            botonEnviar.textContent = "Enviar";
        }
    });

    botonReiniciar.addEventListener("click", function () {
        Object.keys(respuestas).forEach(function (campo) {
            delete respuestas[campo];
        });

        preguntas.forEach(function (pregunta) {
            pregunta
                .querySelectorAll(".jr-opcion")
                .forEach(function (opcion) {
                    opcion.classList.remove("seleccionada");
                });
        });

        resultado.classList.remove("visible");
        encuestaPie.style.display = "block";

        botonEnviar.classList.remove("enviando");
        botonEnviar.textContent = "Enviar";

        mostrarPregunta(0);
    });

    mostrarPregunta(0);
});
</script>

</body>
</html>