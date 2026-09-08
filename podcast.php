<style>
* {
    box-sizing: border-box;
}

.podcast-page {
    width: 100%;
    overflow: hidden;
    background: #ffffff;
    font-family: Arial, Helvetica, sans-serif;
}

/* =====================================
   ENCABEZADO
===================================== */

.podcast-hero {
    position: relative;
    width: 100%;
    min-height: 330px;
    overflow: hidden;

    background-image:
        linear-gradient(
            90deg,
            rgba(0, 0, 0, 0.73) 0%,
            rgba(0, 0, 0, 0.35) 40%,
            rgba(0, 0, 0, 0.04) 100%
        ),
        url("ImagesSV/podcast.png");

    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

.podcast-hero-contenido {
    position: relative;
    z-index: 3;
    width: min(1180px, 90%);
    min-height: 330px;
    margin: auto;
    display: flex;
    align-items: center;
}

.podcast-hero-texto {
    width: 52%;
    color: #ffffff;
}

.podcast-hero-texto h1 {
    margin: 0 0 14px;
    color: #ffffff;
    font-size: clamp(3rem, 4.5vw, 4.7rem);
    font-weight: 900;
    line-height: 1;
    text-shadow: 0 4px 8px rgba(0, 0, 0, 0.45);
}

.podcast-hero-texto p {
    max-width: 500px;
    margin: 0 0 22px;
    color: #ffffff;
    font-size: 1.2rem;
    font-weight: 600;
    line-height: 1.45;
}

.podcast-nuevos {
    display: flex;
    align-items: center;
    gap: 11px;
    color: #ffdd00;
    font-size: 1rem;
    font-weight: 800;
}

.podcast-play {
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid #ff7900;
    border-radius: 50%;
    color: #ff7900;
    padding-left: 3px;
}

/* =====================================
   MICRÓFONO
===================================== */

.podcast-microfono-contenedor {
    position: absolute;
    z-index: 4;
    top: 0;
    right: 15%;
    width: 290px;
    height: 330px;
    overflow: hidden;
    pointer-events: none;
}

.podcast-microfono {
    position: absolute;
    width: 820px;
    max-width: none;
    height: auto;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    filter: drop-shadow(0 15px 12px rgba(0, 0, 0, 0.45));
}

/* =====================================
   PODCAST / VIDEO
===================================== */

.podcast-contenido {
    min-height: 500px;
    padding: 30px 20px 70px;
    background: #ffffff;
}

.podcast-introduccion {
    width: min(800px, 100%);
    margin: 0 auto 22px;
    text-align: center;
}

.podcast-introduccion h2 {
    margin: 0;
    color: #004b96;
    font-size: clamp(1.55rem, 2.4vw, 2.15rem);
    font-weight: 900;
    line-height: 1.25;
}

/* Contenedor del video */

.podcast-video-contenedor {
    width: min(720px, 100%);
    margin: 0 auto;
    text-align: center;
}

.podcast-video {
    position: relative;
    width: 100%;
    padding-top: 56.25%;
    overflow: hidden;
    border-radius: 17px;
    background: #000000;
    box-shadow: 0 12px 28px rgba(0, 54, 115, 0.25);
}

.podcast-video iframe {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    border: 0;
}

/* Botón playlist */

.podcast-boton {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    min-width: 255px;
    min-height: 48px;
    margin-top: 20px;
    padding: 11px 25px;
    border-radius: 999px;
    color: #ffffff;
    background: linear-gradient(135deg, #ff9100, #ff5500);
    box-shadow: 0 7px 15px rgba(255, 94, 0, 0.32);
    font-size: 0.95rem;
    font-weight: 800;
    text-decoration: none;
    transition: 0.2s ease;
}

.podcast-boton:hover {
    color: #ffffff;
    transform: translateY(-3px);
    box-shadow: 0 10px 19px rgba(255, 94, 0, 0.42);
}

.podcast-youtube-icono {
    position: relative;
    width: 26px;
    height: 18px;
    border-radius: 5px;
    background: #ffffff;
}

.podcast-youtube-icono::after {
    content: "";
    position: absolute;
    top: 50%;
    left: 52%;
    transform: translate(-50%, -50%);
    border-top: 4px solid transparent;
    border-bottom: 4px solid transparent;
    border-left: 7px solid #ff5700;
}

/* =====================================
   TABLETA
===================================== */

@media (max-width: 900px) {
    .podcast-hero-texto {
        width: 62%;
    }

    .podcast-microfono-contenedor {
        right: 2%;
        width: 250px;
    }

    .podcast-microfono {
        width: 750px;
    }
}

/* =====================================
   TELÉFONO
===================================== */

@media (max-width: 650px) {
    .podcast-hero {
        min-height: 500px;
        background-position: center;
    }

    .podcast-hero-contenido {
        min-height: 500px;
        align-items: flex-start;
        justify-content: center;
        padding-top: 45px;
        text-align: center;
    }

    .podcast-hero-texto {
        width: 100%;
    }

    .podcast-hero-texto h1 {
        font-size: 3.1rem;
    }

    .podcast-hero-texto p {
        margin-left: auto;
        margin-right: auto;
        font-size: 1.05rem;
    }

    .podcast-nuevos {
        justify-content: center;
    }

    .podcast-microfono-contenedor {
        top: auto;
        right: 50%;
        bottom: 0;
        width: 260px;
        height: 220px;
        transform: translateX(50%);
    }

    .podcast-microfono {
        width: 700px;
    }

    .podcast-contenido {
        min-height: 420px;
        padding: 28px 15px 55px;
    }

    .podcast-introduccion h2 {
        font-size: 1.45rem;
    }

    .podcast-boton {
        width: 100%;
        max-width: 290px;
    }
}




/* =====================================
   MEJORAS VISUALES FINALES
===================================== */

/* Más altura y separación del header */
.podcast-hero {
    min-height: 375px;
    border-bottom: 7px solid #ff7200;
}

.podcast-hero-contenido {
    min-height: 375px;
    padding-top: 32px;
}

/* Baja un poco las letras */
.podcast-hero-texto {
    padding-top: 18px;
}

.podcast-hero-texto h1 {
    font-size: clamp(3.2rem, 4.5vw, 4.8rem);
    letter-spacing: 1px;
}

.podcast-hero-texto p {
    font-size: 1.2rem;
    line-height: 1.5;
}

/* Baja el micrófono para que no pegue con el header */
.podcast-microfono-contenedor {
    top: 30px;
    height: 330px;
}

/* Fondo más atractivo para la sección */
.podcast-contenido {
    position: relative;
    min-height: 650px;
    padding: 55px 20px 130px;
    background:
        radial-gradient(
            circle at 50% 10%,
            rgba(0, 90, 180, 0.09),
            transparent 42%
        ),
        linear-gradient(
            180deg,
            #ffffff 0%,
            #f6faff 58%,
            #ffffff 100%
        );
}

/* Adornos laterales */
.podcast-contenido::before,
.podcast-contenido::after {
    content: "";
    position: absolute;
    width: 150px;
    height: 150px;
    border-radius: 50%;
    opacity: 0.08;
    pointer-events: none;
}

.podcast-contenido::before {
    top: 70px;
    left: -70px;
    background: #ff7300;
}

.podcast-contenido::after {
    right: -75px;
    bottom: 80px;
    background: #0065c7;
}

.podcast-introduccion {
    margin-bottom: 30px;
}

.podcast-introduccion h2 {
    color: #004b96;
    font-size: clamp(1.7rem, 2.5vw, 2.3rem);
    line-height: 1.2;
}

/* Tarjeta que contiene el video */
.podcast-video-contenedor {
    position: relative;
    width: min(760px, 100%);
    margin: 42px auto 0;
    padding: 14px;
    border: 1px solid rgba(0, 73, 151, 0.13);
    border-radius: 24px;
    background: #ffffff;
    box-shadow:
        0 20px 45px rgba(0, 54, 115, 0.17),
        0 5px 14px rgba(0, 0, 0, 0.07);
}


.podcast-video {
    border: 4px solid #ffffff;
    border-radius: 17px;
    box-shadow: none;
}

/* Botón más bonito y separado */
.podcast-boton {
    min-width: 275px;
    min-height: 52px;
    margin-top: 23px;
    margin-bottom: 5px;
    font-size: 1rem;
    border: 2px solid rgba(255, 255, 255, 0.55);
    background: linear-gradient(135deg, #ff9400 0%, #ff5900 100%);
    box-shadow:
        0 9px 20px rgba(255, 91, 0, 0.3),
        inset 0 1px rgba(255, 255, 255, 0.35);
}

.podcast-boton:hover {
    transform: translateY(-4px) scale(1.02);
}

/* Teléfonos */
@media (max-width: 650px) {
    .podcast-hero {
        min-height: 540px;
    }

    .podcast-hero-contenido {
        min-height: 540px;
        padding-top: 65px;
    }

    .podcast-hero-texto {
        padding-top: 0;
    }

    .podcast-microfono-contenedor {
        top: auto;
        bottom: 10px;
    }

    .podcast-contenido {
        min-height: 520px;
        padding: 48px 15px 100px;
    }

    .podcast-introduccion h2 {
        font-size: 1.55rem;
    }

    .podcast-video-contenedor {
        margin-top: 40px;
        padding: 8px;
        border-radius: 18px;
    }



    .podcast-video {
        border-width: 2px;
        border-radius: 13px;
    }

    .podcast-microfono {
    animation: movimientoMicrofono 3.5s ease-in-out infinite;
    transform-origin: center bottom;
}

/* =====================================
   RESPONSIVE DEFINITIVO
===================================== */

/* Evita que la página corte el logo del header */
.podcast-page {
    width: 100% !important;
    max-width: 100% !important;
    margin-top: 70px !important;
    overflow-x: hidden !important;
}

/* TABLET */
@media (max-width: 900px) {

    .podcast-page {
        margin-top: 60px !important;
    }

    .podcast-hero,
    .podcast-hero-contenido {
        min-height: 450px !important;
    }

    .podcast-hero-contenido {
        width: 92% !important;
        padding: 40px 0 !important;
    }

    .podcast-hero-texto {
        width: 58% !important;
    }

    .podcast-hero-texto h1 {
        font-size: 3.8rem !important;
    }

    .podcast-hero-texto p {
        font-size: 1.05rem !important;
    }

    .podcast-microfono-contenedor {
        top: 35px !important;
        right: 0 !important;
        width: 290px !important;
        height: 390px !important;
    }

    .podcast-microfono {
        width: 820px !important;
        animation: none !important;
    }

    .podcast-video-contenedor {
        width: min(720px, 94%) !important;
    }
}

/* TELÉFONOS */
@media (max-width: 650px) {

    .podcast-page {
        margin-top: 45px !important;
    }

    .podcast-hero {
        min-height: 610px !important;
        background-position: center !important;
    }

    .podcast-hero-contenido {
        width: 90% !important;
        min-height: 610px !important;
        display: flex !important;
        align-items: flex-start !important;
        justify-content: center !important;
        padding: 55px 0 280px !important;
        text-align: center !important;
    }

    .podcast-hero-texto {
        width: 100% !important;
        padding: 0 !important;
    }

    .podcast-hero-texto h1 {
        margin-bottom: 16px !important;
        font-size: clamp(2.9rem, 15vw, 3.7rem) !important;
        line-height: 1 !important;
    }

    .podcast-hero-texto p {
        width: 100% !important;
        max-width: 420px !important;
        margin: 0 auto 24px !important;
        font-size: 1rem !important;
        line-height: 1.45 !important;
    }

    .podcast-nuevos {
        justify-content: center !important;
        font-size: 0.92rem !important;
    }

    .podcast-microfono-contenedor {
        top: auto !important;
        right: 50% !important;
        bottom: 5px !important;
        width: min(300px, 90vw) !important;
        height: 270px !important;
        transform: translateX(50%) !important;
    }

    .podcast-microfono {
        width: 790px !important;
        animation: none !important;
    }

    .podcast-contenido {
        min-height: auto !important;
        padding: 42px 12px 90px !important;
    }

    .podcast-introduccion {
        width: 100% !important;
        margin-bottom: 25px !important;
    }

    .podcast-introduccion h2 {
        font-size: clamp(1.35rem, 7vw, 1.7rem) !important;
        line-height: 1.22 !important;
    }

    .podcast-video-contenedor {
        width: 100% !important;
        margin: 0 auto !important;
        padding: 7px 7px 15px !important;
        border-radius: 17px !important;
    }

    .podcast-video {
        width: 100% !important;
        height: auto !important;
        padding-top: 0 !important;
        aspect-ratio: 16 / 9 !important;
        border-width: 0 !important;
        border-radius: 12px !important;
    }

    .podcast-video iframe {
        width: 100% !important;
        height: 100% !important;
    }

    .podcast-boton {
        width: min(100%, 310px) !important;
        min-width: 0 !important;
        min-height: 48px !important;
        padding: 10px 15px !important;
        font-size: 0.9rem !important;
    }
}

/* TELÉFONOS PEQUEÑOS */
@media (max-width: 380px) {

    .podcast-page {
        margin-top: 35px !important;
    }

    .podcast-hero,
    .podcast-hero-contenido {
        min-height: 580px !important;
    }

    .podcast-hero-contenido {
        padding-top: 45px !important;
    }

    .podcast-hero-texto h1 {
        font-size: 2.8rem !important;
    }

    .podcast-nuevos {
        font-size: 0.85rem !important;
    }

    .podcast-play {
        width: 35px !important;
        height: 35px !important;
        flex: 0 0 35px !important;
    }
}

@keyframes movimientoMicrofono {
    0%,
    100% {
        transform:
            translate(-50%, -50%)
            translateY(0)
            rotate(-1deg);
    }

    50% {
        transform:
            translate(-50%, -50%)
            translateY(-13px)
            rotate(1deg);
    }
}

@media (prefers-reduced-motion: reduce) {
    .podcast-microfono {
        animation: none;
    }
}
}

/* Quitar la franja naranja */
.podcast-hero {
    border-bottom: none !important;
}

/* Movimiento más visible del micrófono */
.podcast-microfono {
    animation: microfonoFlotando 2.8s ease-in-out infinite;
    transform-origin: center bottom;
}

@keyframes microfonoFlotando {
    0%,
    100% {
        transform:
            translate(-50%, -50%)
            translate(0, 10px)
            rotate(-3deg);
    }

    25% {
        transform:
            translate(-50%, -50%)
            translate(-12px, -18px)
            rotate(2deg);
    }

    50% {
        transform:
            translate(-50%, -50%)
            translate(8px, -35px)
            rotate(4deg);
    }

    75% {
        transform:
            translate(-50%, -50%)
            translate(14px, -12px)
            rotate(-2deg);
    }
}

/* Título amplio para que solo tenga dos renglones */
.podcast-introduccion {
    width: min(1150px, 94%);
    margin: 0 auto 30px;
}

.podcast-introduccion h2 {
    font-size: clamp(1.65rem, 2.1vw, 2.15rem);
    line-height: 1.22;
    white-space: normal;
}

/* En teléfono permitimos más líneas para evitar que se corte */
@media (max-width: 650px) {
    .podcast-introduccion {
        width: 95%;
    }

    .podcast-introduccion h2 {
        font-size: 1.45rem;
    }
}

@media (prefers-reduced-motion: reduce) {
    .podcast-microfono {
        animation: none;
    }
}

/* =====================================
   BANNER PRINCIPAL MÁS ALTO
===================================== */

.podcast-hero {
    min-height: 470px !important;
    background-position: center center;
}

.podcast-hero-contenido {
    min-height: 470px !important;
    padding-top: 45px;
}

/* Texto con mayor presencia */
.podcast-hero-texto h1 {
    font-size: clamp(3.8rem, 5vw, 5.5rem);
    margin-bottom: 20px;
}

.podcast-hero-texto p {
    max-width: 550px;
    font-size: 1.3rem;
    line-height: 1.5;
    margin-bottom: 28px;
}

.podcast-nuevos {
    font-size: 1.08rem;
}

/* Más espacio para el micrófono */
.podcast-microfono-contenedor {
    top: 45px;
    width: 340px;
    height: 410px;
}

.podcast-microfono {
    width: 920px;
}

/* TABLETA */
@media (max-width: 900px) {
    .podcast-hero,
    .podcast-hero-contenido {
        min-height: 450px !important;
    }

    .podcast-microfono-contenedor {
        width: 300px;
        height: 390px;
        right: 2%;
    }

    .podcast-microfono {
        width: 840px;
    }
}

/* TELÉFONO */
@media (max-width: 650px) {
    .podcast-hero,
    .podcast-hero-contenido {
        min-height: 620px !important;
    }

    .podcast-hero-contenido {
        padding-top: 70px;
    }

    .podcast-hero-texto h1 {
        font-size: 3.5rem;
    }

    .podcast-hero-texto p {
        font-size: 1.1rem;
    }

    .podcast-microfono-contenedor {
        top: auto;
        bottom: 15px;
        width: 300px;
        height: 270px;
    }

    .podcast-microfono {
        width: 800px;
    }
}

/* Desactivar movimiento del micrófono */
.podcast-microfono {
    animation: none !important;
}
/* CORRECCIÓN FINAL PARA TELÉFONOS */
@media (max-width: 650px) {

    /* Coloca la página debajo del header */
    .podcast-page {
    margin-top: 180px !important;
}

    .podcast-hero,
    .podcast-hero-contenido {
        min-height: 620px !important;
    }

    .podcast-hero-contenido {
        width: 92% !important;
        padding: 45px 0 285px !important;
        align-items: flex-start !important;
    }

    .podcast-hero-texto {
        width: 100% !important;
        text-align: center !important;
    }

    .podcast-hero-texto h1 {
        margin: 0 0 14px !important;
        font-size: clamp(2.7rem, 13vw, 3.5rem) !important;
    }

    .podcast-hero-texto p {
        max-width: 390px !important;
        margin: 0 auto 22px !important;
        font-size: 1rem !important;
    }

    .podcast-nuevos {
        justify-content: center !important;
        flex-wrap: wrap !important;
    }

    .podcast-microfono-contenedor {
        top: auto !important;
        right: 50% !important;
        bottom: 10px !important;
        width: 300px !important;
        height: 270px !important;
        transform: translateX(50%) !important;
    }

    .podcast-microfono {
        width: 790px !important;
        animation: none !important;
    }
}

@media (max-width: 380px) {
    .podcast-page {
    margin-top: 190px !important;
}

    .podcast-hero-texto h1 {
        font-size: 2.7rem !important;
    }
}

body:has(.podcast-page) footer,
body:has(.podcast-page) .footer {
    position: relative !important;
    z-index: 50 !important;
}



</style>

<main class="podcast-page">

    <!-- ENCABEZADO -->
    <section class="podcast-hero">

        <div class="podcast-hero-contenido">

            <div class="podcast-hero-texto">

                <h1>PODCAST</h1>

                <p>
                    Disfrutá todos los episodios de 
                    Apostemos y nuestras marcas aliadas.
                </p>

                <div class="podcast-nuevos">
                    <span class="podcast-play">▶</span>
                    <span>Nuevos episodios cada semana</span>
                </div>

            </div>

        </div>

        <div class="podcast-microfono-contenedor">
            <img
                class="podcast-microfono"
                src="ImagesSV/microfono.png"
                alt="Micrófono de podcast"
            >
        </div>

    </section>

    <!-- VIDEO DEL PODCAST -->
    <section class="podcast-contenido">

        <div class="podcast-introduccion">
            <h2>
                Elegí tu marca favorita y mirá todos sus
                episodios en YouTube, Spotify y Apple Podcast
            </h2>
        </div>

        <div class="podcast-video-contenedor">

            <div class="podcast-video">
                <iframe
    src="https://www.youtube.com/embed/videoseries?list=PLTia6MlR-G6s"
    title="Último episodio de Aquí Es Apostemos"
    loading="lazy"
    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
    allowfullscreen
></iframe>
            </div>

            <a
                class="podcast-boton"
                href="https://www.youtube.com/playlist?list=PLTia6MlR-G6s"
                target="_blank"
                rel="noopener noreferrer"
            >
                <span class="podcast-youtube-icono"></span>
                Ver playlist en YouTube
            </a>

        </div>

    </section>

</main>