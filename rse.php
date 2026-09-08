<?php
// ============================================================
// RESPONSABILIDAD SOCIAL HONDURAS - DATOS DINÁMICOS
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
        FROM dbo.paginaweb_hn_responsabilidad_social
        WHERE activo = 1
        ORDER BY seccion, orden, id
    ");

    $rsFilas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Si falla SQL, la página mantiene el contenido actual como respaldo.
    $rsFilas = [];
}

$rsPorClave = [];
$rsPorSeccion = [];

foreach ($rsFilas as $fila) {
    $rsPorClave[$fila['clave']] = $fila;
    $rsPorSeccion[$fila['seccion']][] = $fila;
}

function rsRegistro(array $datos, string $clave): array {
    return $datos[$clave] ?? [];
}

function rsValor(array $registro, string $campo, string $fallback = ''): string {
    $valor = $registro[$campo] ?? '';

    if ($valor === null || trim((string)$valor) === '') {
        return $fallback;
    }

    return (string)$valor;
}

function rsHtml(string $valor): string {
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}

// -------------------- BANNER --------------------
$rsBanner        = rsRegistro($rsPorClave, 'banner_principal');
$rsCorazon       = rsRegistro($rsPorClave, 'corazon');
$rsEstrella      = rsRegistro($rsPorClave, 'estrella');
$rsPremio        = rsRegistro($rsPorClave, 'premio_esr');

// -------------------- ODS --------------------
$rsTituloOds = rsRegistro($rsPorClave, 'titulo_ods');

$rsOds = $rsPorSeccion['ods'] ?? [];
$rsOds = array_values(array_filter(
    $rsOds,
    function ($fila) {
        return strpos($fila['clave'] ?? '', 'ods_') === 0
            && ($fila['clave'] ?? '') !== 'titulo_ods';
    }
));

usort($rsOds, function ($a, $b) {
    return ((int)($a['orden'] ?? 0)) <=> ((int)($b['orden'] ?? 0));
});

// -------------------- VOLUNTARIADO --------------------
$rsVoluntariado = rsRegistro($rsPorClave, 'informacion');

$rsVoluntariadoImagenes = $rsPorSeccion['voluntariado_imagen'] ?? [];

usort($rsVoluntariadoImagenes, function ($a, $b) {
    return ((int)($a['orden'] ?? 0)) <=> ((int)($b['orden'] ?? 0));
});

// -------------------- CAMBIANDO VIDAS --------------------
$rsCambiando = rsRegistro($rsPorClave, 'principal');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Responsabilidad Social </title>

    <style>
        @font-face {
            font-family: 'HelveticaRounded';
            src: url('fonts/HelveticaRoundedLTStd-Bd.ttf') format('truetype');
            font-weight: bold;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #fffaf1;
            color: #333;
            font-family: 'HelveticaRounded', Arial, sans-serif;
        }

        img {
            max-width: 100%;
        }

        .rs-pagina {
            width: 100%;
            overflow: hidden;
            background: #fffaf1;
        }

        .rs-contenedor {
            width: calc(100% - 40px);
            max-width: 1200px;
            margin: 0 auto;
        }

   /* =========================
   BANNER PRINCIPAL
   ========================= */

.rs-banner {
    padding: 120px 0 55px;
    background: #ffffff;
}

.rs-banner .rs-contenedor {
    width: calc(100% - 40px);
    max-width: 1200px;
    margin: 0 auto;
}

.rs-banner-contenido {
    position: relative;
    width: 100%;
    min-height: 430px;
}

/* FONDO NARANJA */
.rs-banner-fondo {
    position: absolute;
    top: 15px;
    left: 65px;
    right: 0;
    height: 310px;
    background: #ff7900;
    border-radius: 28px 28px 85px 0;
    z-index: 1;
}

/* FOTOGRAFÍA */
.rs-banner-personas {
    position: absolute;
    top: 0;
    left: 115px;
    z-index: 4;
    width: 335px;
    height: 405px;
    object-fit: cover;
    object-position: center;
    border-radius: 170px 170px 20px 20px;
}

/* CORAZÓN */
.rs-banner-corazon {
    position: absolute;
    left: 55px;
    bottom: 28px;
    z-index: 7;
    width: 105px;
    height: auto;
}
/* ESTRELLA DETRÁS DE LA FOTOGRAFÍA */
.rs-banner-estrella {
    position: absolute;
    top: -30px;
    left: 365px;
    z-index: 3;
    width: 105px;
    height: auto;
}

/* TÍTULO MÁS GRANDE */
.rs-banner-titulo {
    position: absolute;
    top: 48px;
    left: 500px;
    right: 20px;
    z-index: 5;
    margin: 0;
    color: #ffffff;
    font-size: 44px;
    line-height: 1.02;
    text-align: left;
    text-transform: uppercase;
}

.rs-banner-titulo .azul {
    color: #0667d9;
}

/* RECTÁNGULO AZUL */
.rs-banner-mensaje {
    position: absolute;
    left: 480px;
    right: 95px;
    bottom: 10px;
    z-index: 2;
    min-height: 158px;
    padding: 28px 215px 22px 28px;
    background: #0867db;
    color: #ffffff;
    border-radius: 18px 80px 18px 18px;
}

.rs-banner-mensaje p {
    max-width: 500px;
    margin: 0;
    font-size: 19px;
    line-height: 1.15;
    text-transform: uppercase;
}

/* PREMIO ESR */
.rs-premio-esr {
    position: absolute;
    right: 100px;
    bottom: -10px;
    z-index: 6;
    width: 185px;
    height: auto;
}
        /* =========================
           TÍTULOS
           ========================= */

        .rs-titulo {
            margin: 25px 0 55px;
            color: #0864b5;
            font-size: 42px;
            line-height: 1.1;
            text-align: center;
            text-transform: uppercase;
        }

        /* =========================
   SECCIÓN ODS
   ========================= */

.rs-ods {
    padding: 25px 0 65px;
    background: #ffffff;
}

.rs-ods .rs-contenedor {
    width: calc(100% - 40px);
    max-width: 910px;
    margin: 0 auto;
    padding: 30px 0 28px;
    background: #fff9ef;
    border: 2px solid #e5e5e5;
    border-radius: 22px;
}

/* TÍTULO ODS */
.rs-ods .rs-titulo {
    margin: 0 0 88px;
    color: #0867d1;
    font-size: 36px;
    line-height: 1.1;
    text-align: center;
    text-transform: uppercase;
}

/* CUADRÍCULA */
.rs-ods-grid {
    display: grid;
    grid-template-columns: repeat(3, 290px);
    justify-content: center;
    gap: 60px 48px;
}

/* TARJETA */
.rs-ods-card {
    position: relative;
    width: 290px;
    height: 294px;
    padding: 90px 25px 25px;
    background: #ffffff;
    border: 3px solid var(--color);
    border-radius: 22px;
    text-align: center;
}

/* CÍRCULO ODS */
.rs-ods-icono {
    position: absolute;
    top: -55px;
    left: 50%;
    z-index: 2;
    width: 128px;
    height: 128px;
    object-fit: contain;
    transform: translateX(-50%);
    border: none;
    border-radius: 50%;
    background: transparent;
}

/* OCULTAMOS LOS TÍTULOS INTERNOS */
.rs-ods-card h3 {
    display: none;
}

/* TEXTO DE LAS TARJETAS */
.rs-ods-card p {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    margin: 0;
    color: #696969;
    font-family: Arial, sans-serif;
    font-size: 18px;
    font-weight: 700;
    line-height: 1.18;
    text-align: center;
}

       /* =========================
   CARRUSEL DE VOLUNTARIADO
   ========================= */

.rs-voluntariado {
    padding: 35px 0 120px;
    background: #ffffff;
}

.rs-voluntariado .rs-contenedor {
    width: calc(100% - 40px);
    max-width: 1120px;
    margin: 0 auto;
}

/* TÍTULO NARANJA */
.rs-voluntariado .rs-titulo {
    margin: 0 0 20px;
    color: #ff7900;
    font-size: 38px;
    line-height: 1.1;
    text-align: center;
    text-transform: uppercase;
}

/* CONTENEDOR DEL CARRUSEL */
.rs-carousel {
    position: relative;
    width: 100%;
    padding: 0 55px;
}

/* VENTANA DEL CARRUSEL */
.rs-carousel-ventana {
    position: relative;
    width: 100%;
    height: 485px;
    overflow: hidden;
    border-radius: 23px;
}

/* CADA IMAGEN */
.rs-carousel-slide {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    visibility: hidden;
    transition:
        opacity 0.6s ease,
        visibility 0.6s ease;
}

.rs-carousel-slide.activo {
    opacity: 1;
    visibility: visible;
}

.rs-carousel-slide img {
    display: block;
    width: 100%;
    height: 100%;
    border-radius: 23px;
    object-fit: cover;
    object-position: center;
}

/* FLECHAS */
.rs-carousel-boton {
    position: absolute;
    top: 50%;
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 52px;
    height: 52px;
    padding: 0;
    background: #0867d9;
    color: #ffffff;
    border: 0;
    border-radius: 50%;
    cursor: pointer;
    transform: translateY(-50%);
    transition:
        background 0.2s ease,
        transform 0.2s ease;
}

.rs-carousel-boton:hover {
    background: #0052b7;
    transform: translateY(-50%) scale(1.08);
}

.rs-carousel-anterior {
    left: 0;
}

.rs-carousel-siguiente {
    right: 0;
}

.rs-carousel-boton span {
    display: block;
    font-family: Arial, sans-serif;
    font-size: 50px;
    font-weight: 300;
    line-height: 0.7;
}

/* CUADRO NARANJA */
.rs-voluntariado-informacion {
    position: absolute;
    left: 50%;
    bottom: -75px;
    z-index: 12;
    width: 78%;
    min-height: 120px;
    padding: 15px 30px 18px;
    background: #ff7900;
    color: #ffffff;
    border-radius: 20px;
    text-align: center;
    transform: translateX(-50%);
    box-shadow: 0 8px 14px rgba(0, 0, 0, 0.15);
}

.rs-voluntariado-informacion h3 {
    margin: 0 0 8px;
    color: #ffffff;
    font-size: 40px;
    line-height: 1;
    text-transform: uppercase;
}

.rs-voluntariado-informacion p {
    max-width: 780px;
    margin: 0 auto;
    color: #ffffff;
    font-family: 'HelveticaRounded', Arial, sans-serif;
    font-size: 19px;
    font-weight: bold;
    line-height: 1.2;
}

        /* =========================
           CAMBIANDO VIDAS
           ========================= */

        /* =========================
   CAMBIANDO VIDAS
   ========================= */

/* =========================
   CAMBIANDO VIDAS
   ========================= */

.rs-cambiando {
    position: relative;
    padding: 55px 0 35px;
    overflow: hidden;
    background: #fff9ef;
}

/* CÍRCULO AZUL SUPERIOR */
.rs-cambiando::before {
    content: "";
    position: absolute;
    top: -28px;
    left: 80px;
    width: 95px;
    height: 95px;
    background: #0865b9;
    border-radius: 50%;
    z-index: 1;
}

/* FIGURA NARANJA DERECHA */
.rs-cambiando::after {
    content: "";
    position: absolute;
    top: 15px;
    right: -45px;
    width: 155px;
    height: 180px;
    background: #ff7900;
    border-radius: 60% 0 0 60%;
    z-index: 1;
}

.rs-cambiando .rs-contenedor {
    position: relative;
    z-index: 3;
    width: calc(100% - 40px);
    max-width: 1000px;
    margin: 0 auto;
}

/* LOGO + VIDEO */
.rs-cambiando-contenido {
    display: grid;
    grid-template-columns: 330px 1fr;
    align-items: center;
    gap: 40px;
}

/* LOGO */
.rs-cambiando-logo-contenedor {
    display: flex;
    align-items: center;
    justify-content: center;
}

.rs-logo-cambiando {
    display: block;
    width: 280px;
    max-width: 100%;
    height: auto;
}

/* CONTENEDOR DEL VIDEO */
.rs-cambiando-video {
    position: relative;
    width: 100%;
    max-width: 650px;
    overflow: hidden;
    background: #000000;
    border: 3px solid #ff7900;
    border-radius: 22px;
    aspect-ratio: 16 / 9;
    box-shadow: 0 5px 12px rgba(0, 0, 0, 0.10);
}

/* VIDEO LOCAL */
.rs-cambiando-video video {
    position: absolute;
    inset: 0;
    display: block;
    width: 100%;
    height: 100%;
    background: #000000;
    border: 0;
    object-fit: cover;
}

/* TEXTO INFERIOR */
.rs-cambiando-texto {
    position: relative;
    z-index: 4;
    max-width: 780px;
    margin: 22px auto 0;
    color: #0864b8;
    font-family: 'HelveticaRounded', Arial, sans-serif;
    font-size: 20px;
    font-weight: bold;
    line-height: 1.18;
    text-align: center;
}

/* FIGURA AZUL INFERIOR */
.rs-cambiando-forma-azul {
    position: absolute;
    left: -45px;
    bottom: 22px;
    z-index: 1;
    width: 155px;
    height: 105px;
    background: #0865b9;
    border-radius: 45% 55% 50% 45%;
    transform: rotate(10deg);
    pointer-events: none;
}
.rs-cambiando {
    position: relative;
    isolation: isolate;
    padding: 55px 0 35px;
    overflow: hidden;
    background: #fff9ef;
}

        /* =========================
           TABLET
           ========================= */

        @media (max-width: 900px) {
            .rs-ods-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .rs-imagen-voluntariado > img {
                height: 460px;
            }

            .rs-cambiando-contenido {
                grid-template-columns: 30% 70%;
            }

            .rs-titulo {
                font-size: 34px;
            }
        }

        /* =========================
           CELULAR
           ========================= */

        @media (max-width: 600px) {
            .rs-contenedor {
                width: calc(100% - 24px);
            }

            .rs-banner {
    padding: 90px 0 25px;
}

.rs-banner {
    padding: 90px 0 35px;
}

.rs-banner .rs-contenedor {
    width: calc(100% - 24px);
}

.rs-banner-contenido {
    min-height: 590px;
}

.rs-banner-fondo {
    top: 0;
    right: 0;
    width: 100%;
    height: 350px;
    border-radius: 20px 20px 55px 20px;
}

.rs-banner-personas {
    top: 0;
    left: 10px;
    width: 185px;
    height: 325px;
    object-fit: cover;
    object-position: center;
    border-radius: 95px 95px 15px 15px;
}

.rs-banner-corazon {
    left: -5px;
    bottom: 245px;
    width: 65px;
}

.rs-banner-estrella {
    top: -12px;
    left: 165px;
    width: 65px;
}

.rs-banner-titulo {
    top: 35px;
    left: 205px;
    right: 10px;
    font-size: 21px;
    line-height: 1.05;
}

.rs-banner-mensaje {
    left: 12px;
    right: 12px;
    bottom: 20px;
    min-height: 190px;
    padding: 25px 125px 25px 20px;
    border-radius: 16px 60px 16px 16px;
}

.rs-banner-mensaje p {
    font-size: 14px;
    line-height: 1.15;
}

.rs-premio-esr {
    right: 12px;
    bottom: 0;
    width: 125px;
}

            .rs-titulo {
                margin-bottom: 55px;
                font-size: 27px;
            }

            .rs-ods {
    padding: 20px 12px 45px;
}

.rs-ods .rs-contenedor {
    width: 100%;
    max-width: 440px;
    padding: 30px 18px 45px;
    border-radius: 18px;
}

.rs-ods .rs-titulo {
    margin-bottom: 85px;
    font-size: 27px;
}

.rs-ods-grid {
    grid-template-columns: 1fr;
    justify-items: center;
    gap: 85px;
}

.rs-ods-card {
    width: 100%;
    max-width: 310px;
    height: 300px;
    padding: 85px 22px 25px;
}

.rs-ods-icono {
    top: -55px;
    width: 125px;
    height: 125px;
}

.rs-ods-card p {
    font-size: 17px;
    line-height: 1.2;
}

            .rs-voluntariado {
    padding: 30px 0 105px;
}

.rs-voluntariado .rs-contenedor {
    width: calc(100% - 24px);
}

.rs-voluntariado .rs-titulo {
    margin-bottom: 20px;
    font-size: 27px;
}

.rs-carousel {
    padding: 0 24px;
}

.rs-carousel-ventana {
    height: 350px;
    border-radius: 18px;
}

.rs-carousel-slide img {
    border-radius: 18px;
}

.rs-carousel-boton {
    width: 42px;
    height: 42px;
}

.rs-carousel-boton span {
    font-size: 42px;
}

.rs-carousel-anterior {
    left: 0;
}

.rs-carousel-siguiente {
    right: 0;
}

.rs-voluntariado-informacion {
    bottom: -78px;
    width: calc(100% - 65px);
    min-height: 135px;
    padding: 17px 15px;
    border-radius: 17px;
}

.rs-voluntariado-informacion h3 {
    font-size: 25px;
    line-height: 1.05;
}

.rs-voluntariado-informacion p {
    font-size: 14px;
    line-height: 1.2;
}

           .rs-cambiando {
    padding: 55px 0 60px;
}

.rs-cambiando::before {
    top: -25px;
    left: 15px;
    width: 75px;
    height: 65px;
}

.rs-cambiando::after {
    top: 25px;
    right: -45px;
    width: 100px;
    height: 125px;
}

.rs-cambiando .rs-contenedor {
    width: calc(100% - 24px);
}

.rs-cambiando-contenido {
    grid-template-columns: 1fr;
    gap: 25px;
}

.rs-logo-cambiando {
    width: 220px;
}

.rs-cambiando-video {
    width: 100%;
    border-width: 2px;
    border-radius: 16px;
}

.rs-cambiando-texto {
    max-width: 340px;
    margin-top: 22px;
    font-size: 15px;
    line-height: 1.2;
}

.rs-cambiando-forma-azul {
    left: -70px;
    bottom: -35px;
    width: 155px;
    height: 100px;
}}

/* RESPONSIVE FINAL DE LA PÁGINA RSE */

html,
body {
    width: 100%;
    max-width: 100%;
    overflow-x: hidden;
}

.rs-pagina {
    width: 100%;
    max-width: 100%;
}

/* TABLET */
@media (max-width: 900px) and (min-width: 601px) {

    .rs-contenedor {
        width: calc(100% - 30px) !important;
    }

    .rs-banner {
        padding-top: 80px !important;
    }

    .rs-banner-personas {
        left: 40px !important;
        width: 300px !important;
    }

    .rs-banner-titulo {
        left: 375px !important;
        font-size: 2rem !important;
    }

    .rs-banner-mensaje {
        left: 350px !important;
        right: 25px !important;
        padding-right: 155px !important;
    }

    .rs-premio-esr {
        right: 35px !important;
        width: 145px !important;
    }

    .rs-ods-grid {
        grid-template-columns: repeat(2, minmax(0, 290px)) !important;
        padding: 0 20px !important;
    }

    .rs-ods-card {
        width: 100% !important;
    }

    .rs-cambiando-contenido {
        grid-template-columns: 280px minmax(0, 1fr) !important;
    }
}

/* TELÉFONO */
@media (max-width: 600px) {

    /* Coloca la página debajo del header */
    .rs-pagina {
        margin-top: 180px !important;
    }

    .rs-contenedor {
        width: calc(100% - 24px) !important;
    }

    /* BANNER */
    .rs-banner {
        padding: 35px 0 30px !important;
    }

    .rs-banner .rs-contenedor {
        width: calc(100% - 24px) !important;
    }

    .rs-banner-contenido {
        min-height: 720px !important;
    }

    .rs-banner-fondo {
        top: 0 !important;
        right: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 570px !important;
        border-radius: 22px 22px 65px 22px !important;
    }

    /* Foto centrada */
    .rs-banner-personas {
        top: 20px !important;
        left: 50% !important;
        width: 220px !important;
        height: 300px !important;
        border-radius: 115px 115px 18px 18px !important;
        transform: translateX(-50%) !important;
    }

    .rs-banner-estrella {
        top: 35px !important;
        right: 12px !important;
        left: auto !important;
        width: 70px !important;
    }

    /* Título debajo de la fotografía */
    .rs-banner-titulo {
        top: 345px !important;
        right: 18px !important;
        left: 18px !important;
        width: auto !important;
        font-size: clamp(1.45rem, 7vw, 1.85rem) !important;
        line-height: 1.04 !important;
        text-align: center !important;
    }

    /* Mensaje azul */
    .rs-banner-mensaje {
        right: 8px !important;
        bottom: 0 !important;
        left: 8px !important;
        min-height: 170px !important;
        padding: 24px 100px 22px 18px !important;
        border-radius: 18px 55px 18px 18px !important;
    }

    .rs-banner-mensaje p {
        max-width: none !important;
        font-size: 0.83rem !important;
        line-height: 1.18 !important;
    }

    .rs-premio-esr {
        right: 12px !important;
        bottom: 15px !important;
        width: 90px !important;
    }

    .rs-banner-corazon {
        bottom: 125px !important;
        left: -2px !important;
        width: 60px !important;
    }

    /* ODS */
    .rs-ods {
        padding: 25px 12px 50px !important;
    }

    .rs-ods .rs-contenedor {
        width: 100% !important;
        padding: 30px 15px 45px !important;
    }

    .rs-ods .rs-titulo {
        margin-bottom: 85px !important;
        font-size: 1.65rem !important;
    }

    .rs-ods-grid {
        grid-template-columns: 1fr !important;
        justify-items: center !important;
        gap: 85px !important;
    }

    .rs-ods-card {
        width: min(100%, 310px) !important;
        height: auto !important;
        min-height: 280px !important;
        padding: 80px 20px 25px !important;
    }

    .rs-ods-card p {
        font-size: 1rem !important;
    }

    /* CARRUSEL */
    .rs-voluntariado {
        padding: 30px 0 115px !important;
    }

    .rs-voluntariado .rs-titulo {
        font-size: 1.65rem !important;
    }

    .rs-carousel {
        padding: 0 20px !important;
    }

    .rs-carousel-ventana {
        height: clamp(260px, 85vw, 350px) !important;
    }

    .rs-carousel-slide img {
        object-fit: cover !important;
    }

    .rs-carousel-boton {
        width: 40px !important;
        height: 40px !important;
    }

    .rs-carousel-boton span {
        font-size: 36px !important;
    }

    .rs-voluntariado-informacion {
        bottom: -90px !important;
        width: calc(100% - 50px) !important;
        min-height: 145px !important;
        padding: 16px 13px !important;
    }

    .rs-voluntariado-informacion h3 {
        font-size: 1.4rem !important;
    }

    .rs-voluntariado-informacion p {
        font-size: 0.85rem !important;
    }

    /* CAMBIANDO VIDAS */
    .rs-cambiando {
        padding: 65px 0 60px !important;
    }

    .rs-cambiando-contenido {
        grid-template-columns: 1fr !important;
        gap: 25px !important;
    }

    .rs-logo-cambiando {
        width: min(220px, 70vw) !important;
        margin: 0 auto !important;
    }

    .rs-cambiando-video {
        width: 100% !important;
        max-width: 100% !important;
        aspect-ratio: 16 / 9 !important;
    }

    .rs-cambiando-texto {
        width: 100% !important;
        max-width: 340px !important;
        font-size: 0.95rem !important;
    }
}

/* TELÉFONOS PEQUEÑOS */
@media (max-width: 380px) {

    .rs-pagina {
        margin-top: 190px !important;
    }

    .rs-banner-contenido {
        min-height: 745px !important;
    }

    .rs-banner-personas {
        width: 200px !important;
        height: 280px !important;
    }

    .rs-banner-titulo {
        top: 325px !important;
        font-size: 1.35rem !important;
    }

    .rs-banner-mensaje {
        padding-right: 85px !important;
    }

    .rs-banner-mensaje p {
        font-size: 0.76rem !important;
    }

    .rs-premio-esr {
        width: 78px !important;
    }
}

/* BANNER RSE MÓVIL CON DISEÑO HORIZONTAL */
@media (max-width: 600px) {

    .rs-banner {
        padding: 25px 0 35px !important;
    }

    .rs-banner .rs-contenedor {
        width: calc(100% - 16px) !important;
    }

    .rs-banner-contenido {
        position: relative !important;
        width: 100% !important;
        min-height: 355px !important;
    }

    /* Fondo naranja */
    .rs-banner-fondo {
        top: 8px !important;
        right: 0 !important;
        left: 2% !important;
        width: auto !important;
        height: 250px !important;
        border-radius: 20px 20px 55px 0 !important;
    }

    /* Fotografía a la izquierda */
    .rs-banner-personas {
        top: 0 !important;
        left: 5% !important;
        width: 34% !important;
        height: 320px !important;
        border-radius: 80px 80px 12px 12px !important;
        object-fit: cover !important;
        transform: none !important;
    }

    /* Estrella */
    .rs-banner-estrella {
        top: -8px !important;
        right: auto !important;
        left: 34% !important;
        width: 14% !important;
    }

    /* Título a la derecha */
    .rs-banner-titulo {
        top: 34px !important;
        right: 3% !important;
        left: 41% !important;
        width: auto !important;
        font-size: clamp(1.05rem, 4.8vw, 1.5rem) !important;
        line-height: 1.02 !important;
        text-align: left !important;
    }

    /* Cuadro azul inferior */
    .rs-banner-mensaje {
        right: 8% !important;
        bottom: 10px !important;
        left: 38% !important;
        min-height: 125px !important;
        padding: 17px 23% 15px 14px !important;
        border-radius: 15px 45px 15px 15px !important;
    }

    .rs-banner-mensaje p {
        margin: 0 !important;
        font-size: clamp(0.58rem, 2.5vw, 0.78rem) !important;
        line-height: 1.12 !important;
    }

    /* Premio ESR */
    .rs-premio-esr {
        right: 7% !important;
        bottom: -2px !important;
        width: 22% !important;
        max-width: 110px !important;
    }

    /* Corazón */
    .rs-banner-corazon {
        bottom: 12px !important;
        left: 1% !important;
        width: 14% !important;
        max-width: 70px !important;
    }
}

/* TELÉFONOS PEQUEÑOS */
@media (max-width: 380px) {

    .rs-banner-contenido {
        min-height: 325px !important;
    }

    .rs-banner-fondo {
        height: 225px !important;
    }

    .rs-banner-personas {
        height: 290px !important;
    }

    .rs-banner-titulo {
        top: 30px !important;
        font-size: 1rem !important;
    }

    .rs-banner-mensaje {
        min-height: 115px !important;
        padding-top: 14px !important;
        padding-left: 11px !important;
    }

    .rs-banner-mensaje p {
        font-size: 0.55rem !important;
    }
}

/* BANNER RSE BONITO PARA MÓVIL */
@media (max-width: 600px) {

    .rs-banner {
        padding: 30px 12px 45px !important;
        background: #ffffff !important;
    }

    .rs-banner .rs-contenedor {
        width: 100% !important;
        max-width: 460px !important;
        margin: 0 auto !important;
    }

    .rs-banner-contenido {
        position: relative !important;
        width: 100% !important;
        min-height: 780px !important;
        padding: 0 !important;
    }

    /* Tarjeta naranja completa */
    .rs-banner-fondo {
        top: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 750px !important;
        border-radius: 28px !important;
        background: #ff7900 !important;
    }

    /* Fotografía centrada */
    .rs-banner-personas {
        top: 28px !important;
        left: 50% !important;
        width: min(270px, 72vw) !important;
        height: 305px !important;
        border: 5px solid #ffffff !important;
        border-radius: 140px 140px 20px 20px !important;
        object-fit: cover !important;
        object-position: center !important;
        transform: translateX(-50%) !important;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2) !important;
    }

    /* Estrella */
    .rs-banner-estrella {
        top: 10px !important;
        right: 25px !important;
        left: auto !important;
        width: 72px !important;
    }

    /* Corazón */
    .rs-banner-corazon {
        top: 275px !important;
        bottom: auto !important;
        left: 25px !important;
        width: 70px !important;
    }

    /* Título debajo de la fotografía */
    .rs-banner-titulo {
        top: 355px !important;
        right: 22px !important;
        left: 22px !important;
        width: auto !important;
        margin: 0 !important;
        color: #ffffff !important;
        font-size: clamp(1.5rem, 7vw, 1.9rem) !important;
        line-height: 1.03 !important;
        text-align: center !important;
    }

    .rs-banner-titulo .azul {
        color: #075ebc !important;
    }

    /* Mensaje azul inferior */
    .rs-banner-mensaje {
        top: 520px !important;
        right: 20px !important;
        bottom: auto !important;
        left: 20px !important;
        width: auto !important;
        min-height: 185px !important;
        padding: 25px 115px 25px 22px !important;
        border-radius: 20px !important;
        background: #0867db !important;
        display: flex !important;
        align-items: center !important;
    }

    .rs-banner-mensaje p {
        width: 100% !important;
        max-width: none !important;
        margin: 0 !important;
        color: #ffffff !important;
        font-size: 0.85rem !important;
        line-height: 1.2 !important;
        text-align: left !important;
    }

    /* Premio ESR dentro del cuadro azul */
    .rs-premio-esr {
        top: 555px !important;
        right: 30px !important;
        bottom: auto !important;
        width: 100px !important;
        height: auto !important;
    }
}

/* TELÉFONOS PEQUEÑOS */
@media (max-width: 380px) {

    .rs-banner-contenido {
        min-height: 750px !important;
    }

    .rs-banner-fondo {
        height: 725px !important;
    }

    .rs-banner-personas {
        width: 225px !important;
        height: 280px !important;
    }

    .rs-banner-corazon {
        top: 255px !important;
        left: 15px !important;
        width: 60px !important;
    }

    .rs-banner-estrella {
        right: 15px !important;
        width: 62px !important;
    }

    .rs-banner-titulo {
        top: 330px !important;
        font-size: 1.45rem !important;
    }

    .rs-banner-mensaje {
        top: 495px !important;
        right: 15px !important;
        left: 15px !important;
        min-height: 185px !important;
        padding: 20px 95px 20px 17px !important;
    }

    .rs-banner-mensaje p {
        font-size: 0.75rem !important;
    }

    .rs-premio-esr {
        top: 535px !important;
        right: 22px !important;
        width: 82px !important;
    }
}
    </style>
</head>

<body>

<main class="rs-pagina">

    <!-- =============================
         BANNER PRINCIPAL
         ============================= -->
    <section class="rs-banner">
        <div class="rs-contenedor">

            <div class="rs-banner-contenido">

                <div class="rs-banner-fondo"></div>

                <img
                    class="rs-banner-personas"
                    src="<?= rsHtml(rsValor(
                        $rsBanner,
                        'imagen_url',
                        'ImagesSV/Banner Responsabilidad Social.png'
                    )) ?>"
                    alt="Programa de Responsabilidad Social Loto"
                >

                <img
                    class="rs-banner-corazon"
                    src="<?= rsHtml(rsValor(
                        $rsCorazon,
                        'imagen_url',
                        'ImagesSV/icono corazon.svg'
                    )) ?>"
                    alt=""
                >

                <img
                    class="rs-banner-estrella"
                    src="<?= rsHtml(rsValor(
                        $rsEstrella,
                        'imagen_url',
                        'ImagesSV/icono estrella.svg'
                    )) ?>"
                    alt=""
                >

                <h1 class="rs-banner-titulo">
                    <span class="azul">
                        <?= rsHtml(rsValor($rsBanner, 'titulo_destacado', '16 años')) ?>
                    </span>
                    <?= nl2br(rsHtml(rsValor(
                        $rsBanner,
                        'titulo',
                        'reconocidos por nuestras acciones de responsabilidad social'
                    ))) ?>
                </h1>

                <div class="rs-banner-mensaje">
                    <p>
                        <?= rsHtml(rsValor(
                            $rsBanner,
                            'texto',
                            'Trabajamos para contribuir al bienestar de las personas, el desarrollo de las comunidades y la construcción de un futuro más sostenible.'
                        )) ?>
                    </p>
                </div>

                <img
                    class="rs-premio-esr"
                    src="<?= rsHtml(rsValor(
                        $rsPremio,
                        'imagen_url',
                        'ImagesSV/Premio RSE.png'
                    )) ?>"
                    alt="Empresa Socialmente Responsable"
                >

            </div>

        </div>
    </section>

    <!-- =============================
         OBJETIVOS ODS
         ============================= -->
    <section class="rs-ods">
        <div class="rs-contenedor">

            <h2 class="rs-titulo">
                <?= rsHtml(rsValor(
                    $rsTituloOds,
                    'titulo',
                    'Trabajamos alineados a los ODS'
                )) ?>
            </h2>

            <div class="rs-ods-grid">

                <?php if (!empty($rsOds)): ?>

                    <?php foreach ($rsOds as $ods): ?>
                        <article
                            class="rs-ods-card"
                            style="--color: <?= rsHtml(rsValor($ods, 'color_hex', '#0867d1')) ?>;"
                        >
                            <img
                                class="rs-ods-icono"
                                src="<?= rsHtml(rsValor($ods, 'imagen_url')) ?>"
                                alt="<?= rsHtml(rsValor($ods, 'titulo', 'ODS')) ?>"
                            >

                            <p>
                                <?= rsHtml(rsValor($ods, 'texto')) ?>
                            </p>
                        </article>
                    <?php endforeach; ?>

                <?php else: ?>

                    <article class="rs-ods-card" style="--color: #ee0026;">
                        <img class="rs-ods-icono" src="ImagesSV/Fin de la Pobrez (1).png" alt="ODS 1 Fin de la pobreza">
                        <p>Promovemos iniciativas que contribuyan a mejorar las condiciones de vida de las personas y el acceso a los recursos básicos.</p>
                    </article>

                    <article class="rs-ods-card" style="--color: #c7162b;">
                        <img class="rs-ods-icono" src="ImagesSV/Educación de Calidad.png" alt="ODS 4 Educación de calidad">
                        <p>Impulsamos el acceso a la educación mediante la entrega de útiles escolares y el apoyo a centros educativos.</p>
                    </article>

                    <article class="rs-ods-card" style="--color: #249b3b;">
                        <img class="rs-ods-icono" src="ImagesSV/Salud y Bienestar.png" alt="ODS 3 Salud y bienestar">
                        <p>Apoyamos iniciativas sociales y de salud que facilitan el acceso a atención especializada.</p>
                    </article>

                    <article class="rs-ods-card" style="--color: #981b35;">
                        <img class="rs-ods-icono" src="ImagesSV/Trabajo decente.png" alt="ODS 8 Trabajo decente y crecimiento económico">
                        <p>Generamos oportunidades de empleo e ingresos mediante una red de más de 3,500 puntos de venta a nivel nacional.</p>
                    </article>

                    <article class="rs-ods-card" style="--color: #ef3123;">
                        <img class="rs-ods-icono" src="ImagesSV/Igualdad de Gnero.png" alt="ODS 5 Igualdad de género">
                        <p>Impulsamos la equidad de género con una representación equilibrada de mujeres y hombres en todos los niveles de la organización.</p>
                    </article>

                    <article class="rs-ods-card" style="--color: #f39a00;">
                        <img class="rs-ods-icono" src="ImagesSV/Comunidades Sostenibles.png" alt="ODS 11 Ciudades y comunidades sostenibles">
                        <p>Impulsamos el desarrollo comunitario a través del apoyo a iniciativas como los Parques CONVIVE, que fortalecen la convivencia y el bienestar social.</p>
                    </article>

                <?php endif; ?>

            </div>

        </div>
    </section>

    <!-- =============================
         VOLUNTARIADO
         ============================= -->
    <section class="rs-voluntariado">
        <div class="rs-contenedor">

            <h2 class="rs-titulo">
                <?= rsHtml(rsValor(
                    $rsVoluntariado,
                    'titulo',
                    'El voluntariado es parte de nuestro ADN'
                )) ?>
            </h2>

            <div class="rs-carousel" id="carouselVoluntariado">

                <button
                    class="rs-carousel-boton rs-carousel-anterior"
                    type="button"
                    aria-label="Fotografía anterior"
                >
                    <span>‹</span>
                </button>

                <div class="rs-carousel-ventana">

                    <?php if (!empty($rsVoluntariadoImagenes)): ?>

                        <?php foreach ($rsVoluntariadoImagenes as $indice => $imagen): ?>
                            <div class="rs-carousel-slide <?= $indice === 0 ? 'activo' : '' ?>">
                                <img
                                    src="<?= rsHtml(rsValor($imagen, 'imagen_url')) ?>"
                                    alt="Actividad de voluntariado Loto"
                                >
                            </div>
                        <?php endforeach; ?>

                    <?php else: ?>

                        <div class="rs-carousel-slide activo">
                            <img src="ImagesSV/IMG_3667.JPG" alt="Actividad de voluntariado Loto">
                        </div>

                        <div class="rs-carousel-slide">
                            <img src="ImagesSV/IMG_0899 (1).jpeg" alt="Colaboradores participando en voluntariado">
                        </div>

                        <div class="rs-carousel-slide">
                            <img src="ImagesSV/IMG_0844 (1).jpeg" alt="Programa de voluntariado Loto">
                        </div>

                        <div class="rs-carousel-slide">
                            <img src="ImagesSV/IMG_20251009_133343_234.jpg" alt="Programa de voluntariado Loto">
                        </div>

                        <div class="rs-carousel-slide">
                            <img src="ImagesSV/IMG_20251217_100903_569.jpg" alt="Programa de voluntariado Loto">
                        </div>

                        <div class="rs-carousel-slide">
                            <img src="ImagesSV/IMG-20260225-WA0051 (1).jpg" alt="Programa de voluntariado Loto">
                        </div>

                    <?php endif; ?>

                </div>

                <button
                    class="rs-carousel-boton rs-carousel-siguiente"
                    type="button"
                    aria-label="Fotografía siguiente"
                >
                    <span>›</span>
                </button>

                <div class="rs-voluntariado-informacion">

                    <h3>
                        <?= rsHtml(rsValor(
                            $rsVoluntariado,
                            'titulo_destacado',
                            '+600 horas de voluntariado'
                        )) ?>
                    </h3>

                    <p>
                        <?= rsHtml(rsValor(
                            $rsVoluntariado,
                            'texto',
                            'Nuestros colaboradores dedican su tiempo y talento a iniciativas que generan un impacto positivo en las comunidades donde operamos.'
                        )) ?>
                    </p>

                </div>

            </div>

        </div>
    </section>

    <!-- =============================
         CAMBIANDO VIDAS
         ============================= -->
    <section class="rs-cambiando">

        <div class="rs-cambiando-forma-azul"></div>

        <div class="rs-contenedor">

            <div class="rs-cambiando-contenido">

                <div class="rs-cambiando-logo-contenedor">

                    <img
                        class="rs-logo-cambiando"
                        src="<?= rsHtml(rsValor(
                            $rsCambiando,
                            'imagen_url',
                            'ImagesSV/Logo Cambiando vidas.svg'
                        )) ?>"
                        alt="Loto Cambiando Vidas"
                    >

                </div>

                <div class="rs-cambiando-video">

                    <video
                        id="videoCambiandoVidas"
                        autoplay
                        muted
                        loop
                        playsinline
                        controls
                        preload="auto"
                    >
                        <source
                            src="<?= rsHtml(rsValor(
                                $rsCambiando,
                                'video_url',
                                'Video Recap Mochilas marzo 2026.mp4'
                            )) ?>"
                            type="video/mp4"
                        >

                        Tu navegador no puede reproducir este video.
                    </video>

                </div>

            </div>

            <p class="rs-cambiando-texto">
                <?= rsHtml(rsValor(
                    $rsCambiando,
                    'texto',
                    'Conocé las experiencias de las personas, organizaciones y comunidades que han sido beneficiadas por nuestros programas y el impacto positivo que juntos hemos logrado construir.'
                )) ?>
            </p>

        </div>

    </section>

</main>

</body>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const carousel = document.getElementById("carouselVoluntariado");

    if (!carousel) {
        return;
    }

    const slides = carousel.querySelectorAll(".rs-carousel-slide");
    const botonAnterior = carousel.querySelector(".rs-carousel-anterior");
    const botonSiguiente = carousel.querySelector(".rs-carousel-siguiente");

    if (!slides.length || !botonAnterior || !botonSiguiente) {
        return;
    }

    let posicionActual = 0;
    let intervaloAutomatico;

    function mostrarSlide(posicion) {
        slides.forEach(function (slide) {
            slide.classList.remove("activo");
        });

        posicionActual = posicion;

        if (posicionActual < 0) {
            posicionActual = slides.length - 1;
        }

        if (posicionActual >= slides.length) {
            posicionActual = 0;
        }

        slides[posicionActual].classList.add("activo");
    }

    function mostrarSiguiente() {
        mostrarSlide(posicionActual + 1);
    }

    function mostrarAnterior() {
        mostrarSlide(posicionActual - 1);
    }

    function iniciarAutomatico() {
        detenerAutomatico();

        intervaloAutomatico = setInterval(function () {
            mostrarSiguiente();
        }, 5000);
    }

    function detenerAutomatico() {
        if (intervaloAutomatico) {
            clearInterval(intervaloAutomatico);
        }
    }

    botonSiguiente.addEventListener("click", function () {
        mostrarSiguiente();
        iniciarAutomatico();
    });

    botonAnterior.addEventListener("click", function () {
        mostrarAnterior();
        iniciarAutomatico();
    });

    carousel.addEventListener("mouseenter", detenerAutomatico);
    carousel.addEventListener("mouseleave", iniciarAutomatico);

    mostrarSlide(0);
    iniciarAutomatico();
});


</script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const video = document.getElementById("videoCambiandoVidas");

    if (!video) {
        return;
    }

    video.muted = true;

    video.play().catch(function () {
        console.log("El navegador espera interacción para reproducir.");
    });

    function activarSonido() {
        video.muted = false;
        video.volume = 1;

        video.play().catch(function () {
            console.log("No fue posible activar el video.");
        });

        document.removeEventListener("click", activarSonido);
        document.removeEventListener("touchstart", activarSonido);
    }

    document.addEventListener("click", activarSonido);
    document.addEventListener("touchstart", activarSonido);
});
</script>
</html>