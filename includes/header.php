<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>LOTO - HOME</title>

  <link rel="icon" type="image/png" href="/imagesSV/icono.png">
  <link rel="stylesheet" href="/css/style.css">

  <!-- Google Analytics 4 y Campaign Manager -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-E4X7DF4HRN"></script>

  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }

    gtag('js', new Date());

    // Nuevo Google Analytics 4
    gtag('config', 'G-E4X7DF4HRN');

    // Campaign Manager / Floodlight existente
    gtag('config', 'DC-14581472');
  </script>

  <style>
    body {
      font-family: 'Helvetica Rounded Black', Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    /* Dropdown juegos corregido */
    .dropdown {
      position: relative;
      display: inline-block;
    }

    .dropdown > a {
      color: white;
      text-decoration: none;
      font-weight: bold;
      padding: 5px 10px;
      transition: color 0.3s;
    }

    .dropdown:hover > a {
      color: #0070c0;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      top: 100%;
      left: 0;
      background-color: white;
      border-radius: 8px;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
      min-width: 180px;
      z-index: 1000;
    }

    .dropdown-content a {
      display: block;
      padding: 10px 15px;
      color: black !important;
      text-decoration: none;
      font-weight: normal;
    }

    .dropdown-content a:hover {
      background-color: #0070c0;
      color: white !important;
    }

    .dropdown:hover .dropdown-content {
      display: block;
    }

    /* Hover azul para links del nav principal */
    .nav-menu a {
      color: white;
      text-decoration: none;
      font-weight: bold;
      padding: 5px 10px;
      transition: color 0.3s;
    }

    .nav-menu a:hover {
      color: #0070c0;
    }

    /* Responsive solo móvil */
    @media (max-width: 768px) {
      .top-menu {
        display: grid;
        grid-template-columns: repeat(2, auto);
        justify-content: center;
        column-gap: 14px;
        row-gap: 6px;
        padding: 6px 10px;
        text-align: center;
      }

      .top-menu a {
        font-size: 11px;
        padding: 4px 8px;
        white-space: nowrap;
      }

      .main-header {
        flex-direction: column;
        align-items: center;
        gap: 8px;
        padding: 5px 0;
      }

      .logo img {
        max-width: 140px;
        height: auto;
      }

      .nav-menu {
        flex-wrap: wrap;
        justify-content: center;
        gap: 5px;
        text-align: center;
      }

      .nav-menu a {
        font-size: 12px;
        padding: 4px 6px;
      }

      .dropdown-content {
        left: 50%;
        transform: translateX(-50%);
        min-width: 140px;
      }

      .play-button a {
        display: inline-block;
        background: none;
        padding: 0;
      }

      .play-button img {
        display: block;
        max-width: 160px;
        height: auto;
      }
    }

    /* =====================================
   DESPLEGABLE SOBRE NOSOTROS
===================================== */

.top-menu {
    position: relative;
    z-index: 3000;
    overflow: visible;
}

.top-menu .dropdown {
    position: relative;
    display: inline-flex;
    align-items: center;
}

.top-menu .dropdown-toggle {
    display: inline-flex;
    align-items: center;
    color: #ffffff;
    text-decoration: none;
    font-weight: bold;
    white-space: nowrap;
    cursor: pointer;
}

.top-menu .dropdown-content {
    top: calc(100% + 7px);
    left: 50%;
    width: max-content;
    min-width: 210px;
    padding: 7px 0;
    transform: translateX(-50%);
    overflow: hidden;
    border: 1px solid rgba(0, 80, 160, 0.12);
    border-radius: 10px;
    background: #ffffff;
    box-shadow: 0 10px 25px rgba(0, 50, 110, 0.25);
    z-index: 5000;
}

.top-menu .dropdown-content a {
    display: block;
    padding: 12px 18px;
    color: #004f9e !important;
    background: #ffffff;
    font-size: 13px;
    font-weight: 700;
    text-align: left;
    text-decoration: none;
    white-space: nowrap;
    transition:
        background-color 0.2s ease,
        color 0.2s ease,
        padding-left 0.2s ease;
}

.top-menu .dropdown-content a:hover {
    padding-left: 23px;
    color: #ffffff !important;
    background: linear-gradient(90deg, #0067bd, #0083d7);
}

/* Abre cualquiera de los dropdowns */
.dropdown:hover > .dropdown-content,
.dropdown.abierto > .dropdown-content {
    display: block;
}

/* Mantener el botón naranja cuando está abierto */
.top-menu .dropdown.abierto > .dropdown-toggle,
.top-menu .dropdown:hover > .dropdown-toggle {
    color: #ffffff;
    background: #ff8100;
    border-radius: 5px;
}

/* Responsive */
@media (max-width: 768px) {
    .top-menu .dropdown-content {
        top: 100%;
        left: 50%;
        min-width: 190px;
        transform: translateX(-50%);
    }

    .top-menu .dropdown-content a {
        padding: 11px 15px;
        font-size: 12px;
        text-align: center;
    }
}

/* HEADER Y MENÚS SIEMPRE ENCIMA DEL CONTENIDO */
header {
    position: relative !important;
    z-index: 999999 !important;
    overflow: visible !important;
    isolation: isolate;
}

.top-menu {
    position: relative !important;
    z-index: 999999 !important;
    overflow: visible !important;
}

.main-header {
    position: relative !important;
    z-index: 999998 !important;
    overflow: visible !important;
}

.nav-menu,
.dropdown {
    position: relative;
    overflow: visible !important;
}

.dropdown-content {
    z-index: 1000000 !important;
}

/* El contenido de las páginas queda debajo del header */
main {
    position: relative;
    z-index: 1;
}

/* =====================================
   MISMO ESTILO PARA EL MENÚ DE JUEGOS
===================================== */

.nav-menu .dropdown-content {
    top: calc(100% + 7px);
    left: 50%;
    width: max-content;
    min-width: 210px;
    padding: 7px 0;
    transform: translateX(-50%);
    overflow: hidden;
    border: 1px solid rgba(0, 80, 160, 0.12);
    border-radius: 10px;
    background: #ffffff;
    box-shadow: 0 10px 25px rgba(0, 50, 110, 0.25);
    z-index: 1000000 !important;
}

.nav-menu .dropdown-content a {
    display: block;
    padding: 12px 18px;
    color: #004f9e !important;
    background: #ffffff;
    font-size: 13px;
    font-weight: 700;
    line-height: 1.2;
    text-align: left;
    text-decoration: none;
    white-space: nowrap;
    transition:
        background-color 0.2s ease,
        color 0.2s ease,
        padding-left 0.2s ease;
}

.nav-menu .dropdown-content a:hover {
    padding-left: 23px;
    color: #ffffff !important;
    background: linear-gradient(90deg, #0067bd, #0083d7);
}

/* Botón Juegos cuando está abierto */
.nav-menu .dropdown:hover > a,
.nav-menu .dropdown.abierto > a {
    color: #ffffff !important;
    background: #0067bd;
    border-radius: 5px;
}

/* TELÉFONO */
@media (max-width: 768px) {
    .nav-menu .dropdown-content {
        top: 100%;
        left: 50%;
        min-width: 190px;
        transform: translateX(-50%);
    }

    .nav-menu .dropdown-content a {
        padding: 11px 15px;
        font-size: 12px;
        text-align: center;
    }

    .nav-menu .dropdown-content a:hover {
        padding-left: 15px;
    }
}
  </style>
</head>

<body>

  <header>

    <!-- Menú superior azul -->
    <div class="top-menu">
      <div class="dropdown dropdown-nosotros">

    <a href="#" class="dropdown-toggle">
        Sobre nosotros ▾
    </a>

    <div class="dropdown-content">
        <a href="?pag=sobre_nosotros">
            Sobre nosotros
        </a>

        <a href="?pag=rse">
            RSE
        </a>

        <a href="?pag=responsable">
            Juego Responsable
        </a>
    </div>

</div>
      <a href="?pag=quiero_ser_agente">
        Quiero ser vendedor
      </a>

      <a
        href="https://www.google.com/maps/d/viewer?mid=1_7d7-vxgaC0-T3cYocFlitpQJmON4vI&femb=1&ll=14.7901669030724%2C-86.52528013500002&z=7"
        target="_blank"
        rel="noopener noreferrer"
      >
        Puntos de venta
      </a>

      <a href="?pag=aplica_con_nosotros">
        Aplicá con nosotros
      </a>
    </div>

    <!-- Cuadro naranja con logo, navegación y botón -->
    <div class="main-header">

      <div class="logo">
        <a href="login.php">
          <img
            src="/ImagesSV/logo-02-LOTO (1).png"
            alt="Logo"
            style="cursor: pointer;"
          >
        </a>
      </div>

      <nav class="nav-menu">

        <div class="dropdown">
          <a href="#" id="btn-menu-juegos">
            JUEGOS ▾
          </a>

          <div class="dropdown-content">
            <a href="?pag=diaria">La Diaria</a>
            <a href="?pag=super_premio">Loto Super Premio</a>
            <a href="?pag=instacash">InstaCash</a>
            <a href="?pag=apostemos">Apostemos</a>
            <a href="?pag=bingo_con_todo">Bingo con Todo</a>
            <a href="?pag=juga3">Jugá Tres</a>
            <a href="?pag=premia2">Premia2</a>
            <a href="?pag=pega_3">Pega3</a>
            <a href="?pag=multi_x">Multi X</a>
            <a href="?pag=ganagol">Ganagol</a>
          </div>
        </div>

        <a href="?pag=noticias">
          NOTICIAS
        </a>

        <a href="?pag=contactanos">
          CONTÁCTANOS
        </a>

        <a href="?pag=podcast">
          PODCAST
        </a>

      </nav>

      <div class="play-button">
        <a
          href="https://juega.loto.hn/websales/"
          target="_blank"
          rel="noopener noreferrer"
        >
          <img
            src="/ImagesSV/boton-jugar-en-linea.png"
            alt="Jugar en línea"
          >
        </a>
      </div>

    </div>
  </header>


  <script>
document.addEventListener("DOMContentLoaded", function () {

    const dropdowns = document.querySelectorAll(".dropdown");

    dropdowns.forEach(function (dropdown) {

        const boton = dropdown.querySelector(":scope > a");

        if (!boton) {
            return;
        }

        boton.addEventListener("click", function (event) {
            event.preventDefault();
            event.stopPropagation();

            const estabaAbierto =
                dropdown.classList.contains("abierto");

            dropdowns.forEach(function (otroDropdown) {
                otroDropdown.classList.remove("abierto");
            });

            if (!estabaAbierto) {
                dropdown.classList.add("abierto");
            }
        });
    });

    document.addEventListener("click", function () {
        dropdowns.forEach(function (dropdown) {
            dropdown.classList.remove("abierto");
        });
    });

});
</script>

  <!-- Meta Pixel Code -->
  <script>
    !function (f, b, e, v, n, t, s) {
      if (f.fbq) return;

      n = f.fbq = function () {
        n.callMethod
          ? n.callMethod.apply(n, arguments)
          : n.queue.push(arguments);
      };

      if (!f._fbq) f._fbq = n;

      n.push = n;
      n.loaded = true;
      n.version = '2.0';
      n.queue = [];

      t = b.createElement(e);
      t.async = true;
      t.src = v;

      s = b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t, s);

    }(
      window,
      document,
      'script',
      'https://connect.facebook.net/en_US/fbevents.js'
    );

    fbq('init', '1517228626366116');
    fbq('track', 'PageView');
  </script>

  <noscript>
    <img
      height="1"
      width="1"
      style="display: none;"
      src="https://www.facebook.com/tr?id=1517228626366116&ev=PageView&noscript=1"
      alt=""
    >
  </noscript>
  <!-- End Meta Pixel Code -->

  <!-- Evento Floodlight Loto_Traffic -->
  <script>
    gtag('event', 'conversion', {
      'allow_custom_scripts': true,
      'send_to': 'DC-14581472/invmedia/loto_0+standard'
    });
  </script>

  <noscript>
    <img
      src="https://ad.doubleclick.net/ddm/activity/src=14581472;type=invmedia;cat=loto_0;dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;tfua=;npa=;gdpr=${GDPR};gdpr_consent=${GDPR_CONSENT_755};ord=1?"
      width="1"
      height="1"
      alt=""
    >
  </noscript>
  <!-- End Floodlight -->

</body>
</html>