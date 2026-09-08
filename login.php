<?php
// Conectar a la base de datos
try {
    $conn = new PDO(
        "sqlsrv:Server=srvdbcacdev.database.windows.net;Database=dblotocacdev",
        "LotoAdmin",
        "LotAdmin1.",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Obtener los datos de la base de datos
$stmt = $conn->query("SELECT * FROM paginaweb_sv_seleccion_pais WHERE id = 1");
$contenido = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loto - Selección de País</title>
    <link rel="icon" type="image/png" href="/imagesSV/icono.png">
    <style>
        /* Tu estilo CSS sigue aquí, sin cambios */
        @font-face {
            font-family: 'HelveticaRounded';
            src: url('fonts/HelveticaRoundedLTStd-Bd.ttf') format('truetype');
            font-weight: bold;
        }

        /* RESET */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
    background-color: #fff7eb;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    font-family: 'Roboto', sans-serif;
}

        /* Main content ocupa todo el espacio disponible */
        .main-content {
    flex: 1;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    padding-top: 70px;
    overflow: hidden;
}

        /* HEADER */
      header {
    width: 100%;
    height: 100px;
    background-color: #ff7a00;
    display: flex;
    justify-content: center;
    align-items: flex-end;
    border-bottom-left-radius: 20px;
    border-bottom-right-radius: 20px;
}

header img {
    width: 120px;
    height: auto;
    object-fit: contain;
    position: relative;
    top: 38px;
    z-index: 5;
}

        /* CONTENIDO */
        .main-content {
            min-height: calc(100vh - 100px - 35px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding-top: 60px;
        }

        /* TEXTO */
        .welcome-text {
    text-align: center;
    margin: 10px 0 15px 0;
}

        .welcome-text h1 {
            font-size: 14px;
            color: #0052a5;
            font-weight: bold;
        }

        .welcome-text h2 {
            font-size: 32px;
            color: #ff7a00;
            font-weight: 900;
        }

        /* CONTENEDOR */
      .countries {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    justify-content: center;
    padding: 5px 15px;
    width: 100%;
}

        /* TARJETA */
        .country-card {
            background-color: #fff;
            border-radius: 20px;
            width: 200px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }

        .country-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.25);
        }

        /* NOMBRE */
        .country-card .name {
            text-align: center;
            padding: 10px 0;
            font-size: 18px;
            font-weight: bold;
            color: #0052a5;
        }

        /* IMAGEN */
        .image-wrapper {
            position: relative;
            width: 100%;
            height: 170px;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
        }

        .image-wrapper img.main-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
        }

        /* BANDERA */
        .image-wrapper img.flag {
            position: absolute;
            bottom: -30px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 3px solid #fff;
            background: #fff;
            z-index: 10;
        }

        /* BOTONES */
        .button-wrapper {
    margin-top: 10px;
    text-align: center;
}
        .country-button-img {
    width: 160px;
    height: 38px;
    max-width: 100%;
    cursor: pointer;
    transition: transform 0.2s ease;
    object-fit: fill;
    display: block;
}

        .country-button-img:hover {
            transform: scale(1.03);
        }

        /* Mobile */
        @media (max-width: 480px) {
            .button-wrapper {
                margin-top: 25px;
            }

            .country-button-img {
                width: 140px;
            }
        }

        /* FOOTER */
        footer {
            width: 100%;
            height: 35px;
            background-color: #0052a5;
            margin-top: auto;
        }

        .button-wrapper-top {
    margin-top: 40px;
    margin-bottom: 10px;
    text-align: center;
}

.loto-button {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    width: 160px;
    height: 38px;
    background: #ff7a00;
    color: #fff;
    text-decoration: none;
    border-radius: 25px;
    font-size: 20px;
    font-weight: 900;
    font-family: 'HelveticaRounded', sans-serif;
    transition: .2s;
}

.loto-button:hover{
    background:#e56d00;
    transform:scale(1.03);
}

.countries > div {
    width: 200px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.button-wrapper-top,
.button-wrapper {
    width: 160px;
    text-align: center;
}

@media (max-width: 768px) {
    .main-content {
        padding-top: 55px;
        overflow: visible;
    }

    .welcome-text h2 {
        font-size: 26px;
    }

    .countries {
        flex-direction: column;
        align-items: center;
        gap: 25px;
    }

    .countries > div {
        width: 90%;
        max-width: 280px;
    }

    .country-card {
        width: 100%;
    }

    .image-wrapper {
        height: 180px;
    }

    .loto-button,
    .country-button-img,
    .button-wrapper-top,
    .button-wrapper {
        width: 180px;
    }

    footer {
        margin-top: 30px;
    }
}

    </style>
</head>

<body>

<header>
     <!-- Cargar el logo dinámicamente -->
    <img src="<?= $contenido['logo'] ?>" alt="Loto Logo">
</header>

<div class="main-content">

    <div class="welcome-text">
        <h1><?= htmlspecialchars($contenido['bienvenida_h1']) ?></h1>
        <h2><?= htmlspecialchars($contenido['bienvenida_h2']) ?></h2>
    </div>

    <div class="countries">
<!-- EL SALVADOR -->
<div>
    <div class="country-card" 
         onclick="window.location.href='https://loto.sv/?pag=body'" 
         style="cursor:pointer;">
        <div class="name">El Salvador</div>
        <div class="image-wrapper">
            <img class="main-image" src="<?= $contenido['imagen_sv'] ?>" alt="El Salvador">
            <img class="flag" src="<?= $contenido['bandera_sv'] ?>" alt="Bandera El Salvador">
        </div>
    </div>

    <!-- NUEVO BOTON -->
<div class="button-wrapper-top">
    <a href="https://loto.sv/?pag=body" class="loto-button">
        LOTO.SV
    </a>
</div>

    <div class="button-wrapper">
        <img src="<?= $contenido['boton_sv'] ?>" 
             alt="Ingresar El Salvador" 
             class="country-button-img"
             style="cursor:pointer;"
             onclick="window.location.href='https://juega.loto.sv/fob/?utm_source=SV_Apostemos_website_botonpais_Trafico_2026&utm_medium=SV_Apostemos_website_botonpais_Trafico_2026&utm_campaign=SV_Apostemos_website_botonpais_Trafico_2026&utm_id=SV_Apostemos_website_botonpais_Trafico_2026'">
    </div>
</div>

<!-- HONDURAS -->
<div>
    <div class="country-card"
     onclick="window.location.href='https://loto.hn/?pag=body'"
     style="cursor:pointer;">
        <div class="name">Honduras</div>
        <div class="image-wrapper">
            <img class="main-image" src="<?= $contenido['imagen_hn'] ?>" alt="Honduras">
            <img class="flag" src="<?= $contenido['bandera_hn'] ?>" alt="Bandera Honduras">
        </div>
    </div>

    <div class="button-wrapper-top">
    <a href="https://loto.hn/?pag=body" class="loto-button">
        LOTO.HN
    </a>
</div>

    <div class="button-wrapper">
        <img src="<?= $contenido['boton_hn'] ?>" 
             alt="Ingresar Honduras" 
             class="country-button-img"
             style="cursor:pointer;"
             onclick="window.location.href='https://juega.loto.hn/fob/?utm_source=HN_Apostemos_website_botonpais_Trafico_2026&utm_medium=HN_Apostemos_website_botonpais_Trafico_2026&utm_campaign=HN_Apostemos_website_botonpais_Trafico_2026&utm_id=HN_Apostemos_website_botonpais_Trafico_2026'">
    </div>
</div>


<!-- NICARAGUA -->
<div>
    <div class="country-card" 
         onclick="window.location.href='https://loto.com.ni/?pag=body'" 
         style="cursor:pointer;">
        <div class="name">Nicaragua</div>
        <div class="image-wrapper">
            <img class="main-image" src="<?= $contenido['imagen_ni'] ?>" alt="Nicaragua">
            <img class="flag" src="<?= $contenido['bandera_ni'] ?>" alt="Bandera Nicaragua">
        </div>
    </div>
    <div class="button-wrapper-top">
    <a href="https://loto.com.ni/?pag=body" class="loto-button">
        LOTO.NI
    </a>
</div>
</div>
</div>
        
<footer></footer>

</body>
</html>