<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Dobletea tu suerte</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>

* { margin:0; padding:0; box-sizing:border-box; font-family:'Helvetica Rounded', Arial, sans-serif; }
body {
    background: linear-gradient(
        180deg,
        #ffffff  0%,
        #ffffff 40%,
        #ffffff  75%,
        #ffffff 100%
    );
   
    font-weight: 600;
}
/* HEADER */
.top { background: radial-gradient(circle at center, #ffd54f 0%, #ff9800 40%, #f57c00 75%); display:flex; justify-content:center; padding:80px 10px; }
.top-content { display:flex; align-items:center; max-width:1300px; width:100%; position:relative; }
.top img { width:480px; height:auto; margin-top:20px; margin-left:80px; }
.ganador-box { text-align:center; color:white; margin-top:40px; margin-left:160px; }
.ganador { font-weight:900; font-size:26px; margin-bottom:15px; }
.nums { margin-bottom:10px; }
.num {
    width: 65px;
    height: 65px;
    line-height: 65px;
    display: inline-block;
    background: linear-gradient(145deg, #e0e0e0, #9e9e9e);
    border-radius: 50%;
    font-weight: bold;
    font-size: 24px;
    color: #333;
    margin: 0 4px;
    border: 2px solid #ffffff;
    text-align: center;
    box-shadow: 0 6px 12px rgba(0,0,0,0.25);
}
.etiqueta-hola { background:yellow; color:#ef6c00; padding:6px 14px; border-radius:20px; font-weight:900; font-size:18px; }
/* MENÚ */
.menu { 
    display: flex; 
    justify-content: center; 
    gap: 18px; 
    background: radial-gradient(circle at center, #ffd54f 0%, #ff9800 40%, #f57c00 75%); /* Degradado igual al de .top */
    padding: 16px;  
    padding: 16px; 
    flex-wrap: wrap; 
}
.menu a { 
    background: #ef6c00; 
    color: #ffffff; 
    text-decoration: none; 
    padding: 10px 22px; 
    border-radius: 30px; 
    font-size: 14px; 
    font-weight: bold; 
    transition: 0.3s; 
    box-shadow: 0 4px 10px rgba(0,0,0,.25); 
    display: inline-block;  /* Asegura que sea un bloque en línea */
    margin: 0 auto;  /* Centra el botón en el contenedor */
}
.menu a:hover { background:#ef6c00; transform:translateY(-3px); box-shadow:0 6px 14px rgba(0,0,0,.3); }
/* RESULTADOS */
.resultados { border:1px solid #ef6c00; display:flex; max-width:1100px; margin:40px auto; background:white; border-radius:20px; box-shadow:0 5px 15px rgba(0,0,0,0.15); padding:25px; gap:20px; }
.resultados .col { flex:1; text-align:center; }
.izquierda { display:flex; flex-direction:column; justify-content:center; align-items:flex-start; gap:15px; min-height:100%; }
.izquierda h2 { font-size:42px; font-weight:900; color:#ef6c00; margin:0; line-height:1.1; }
.label-fecha { font-size:18px; font-weight:700; background:yellow; color:#ef6c00; padding:8px 16px; border-radius:20px; display:inline-block; margin-left:35px; }
/* FILTROS */
.filtros-calendario { display:flex; justify-content:center; gap:15px; margin-bottom:15px; }
.filtros-calendario select { padding:6px 10px; font-weight:700; border-radius:8px; border:1px solid #ef6c00; }
/* CALENDARIO */
.calendario-real { background:linear-gradient(145deg, #ffffff, #f4f4f4); border-radius:16px; padding:12px 14px; box-shadow:0 8px 18px rgba(0,0,0,0.15); border:1px solid #e5e5e5; max-width:260px; margin:auto; }
.calendario-real table { width:100%; border-collapse:collapse; text-align:center; }
.calendario-real th { font-size:12px; font-weight:800; color:#ef6c00; padding:6px 0; text-transform:uppercase; }
.calendario-real td { padding:7px 0; font-weight:700; font-size:13px; color:#444; cursor:pointer; transition:0.25s; border-radius:50%; }
.calendario-real td:hover { background:rgba(183,28,28,0.15); }
.calendario-real td.activo { background:linear-gradient(135deg, #ef6c00,#ef6c00); color:white; box-shadow:0 4px 10px rgba(0,0,0,0.25); }
/* SORTEOS */
.derecha { display:flex; flex-direction:column; justify-content:center; align-items:center; }
.derecha .sorteo { margin-bottom:20px; text-align:center; }
.derecha h3 { font-size:20px; font-weight:900; color:#ef6c00; margin-bottom:12px; text-align:center; letter-spacing:0.5px; }
.derecha .nums { display:flex; justify-content:center; gap:4px; flex-wrap:nowrap; }
.derecha .num { width:40px; height:40px; line-height:40px; font-size:16px; margin:0 2px; }
/* ACCORDION */
.accordion { max-width:1100px; margin:30px auto; border-radius:15px; overflow:hidden; background:white; border:2px solid #ef6c00; }
.accordion-header { padding:18px; text-align:center; color:white; font-weight:bold; font-size:24px; cursor:pointer; position:relative; background:#ef6c00; }
.accordion-header .arrow { position:absolute; right:20px; font-size:26px; }
.accordion-content { display:none; padding:20px; background:white; }
.sub-accordion-header { background:#ef6c00; color:white; padding:14px 20px; margin-bottom:10px; border-radius:12px; display:flex; justify-content:space-between; align-items:center; cursor:pointer; font-weight:600; font-size:20px; }
.sub-accordion-header span { display:flex; justify-content:center; align-items:center; width:32px; height:32px; border-radius:50%; background:#f28b82; color:white; font-size:18px; flex-shrink:0; transition:transform 0.3s; }
.sub-accordion-header.active span { transform:rotate(180deg); }
.sub-accordion-content { display:none; padding:15px 20px; background:white; border-radius:0 0 12px 12px; margin-bottom:12px; font-size:16px; font-weight:600; line-height:1.5; color:#333; }
.info-juego { margin-bottom:30px; padding:20px; background:#ffffff; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.08); }
.slogan { text-align:center; font-size:22px; font-weight:700; color:#ef6c00; margin-bottom:12px; line-height:1.4; }
.descripcion { text-align:center; font-size:17px; font-weight:600; color:#333; margin-bottom:20px; line-height:1.5; }
.subtitulo-rojo { text-align:center; font-size:20px; font-weight:700; color:#ef6c00; margin-bottom:15px; }
.linea-juego { display:flex; align-items:flex-start; gap:16px; margin-top:15px; font-size:16px; font-weight:600; color:#333; line-height:1.5; }
.img-super { 
    width:150px;  /* antes 75px */
    height:auto; 
    flex-shrink:0; 
    border-radius:6px; 
}

.reglamento { text-align:center; margin:40px 0; }
.reglamento button { background:#ef6c00; color:white; padding:14px 30px; border-radius:30px; border:none; font-weight:bold; font-size:16px; cursor:pointer; transition:0.3s; }
.reglamento button:hover { background:white; color:#ef6c00; border:2px solid #ef6c00; }
/* =========================
   FIX SUPER PREMIO - MÓVIL
   ========================= */
@media (max-width: 768px) {

  /* HEADER */
  .top {
    padding: 50px 10px;
  }

  .top-content {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }

  .top img {
    width: 250px;       /* logo más pequeño */
    margin: 0 0 15px 0; /* centrado */
  }

  .ganador-box {
    margin: 0;
  }

  .ganador {
    font-size: 20px;
  }

  .num {
    width: 48px;
    height: 48px;
    line-height: 48px;
    font-size: 18px;
  }

  .etiqueta-hola {
    font-size: 14px;
    margin-top: 10px;
  }

  /* MENÚ */
  .menu {
    gap: 10px;
    padding: 12px;
  }

  .menu a {
    font-size: 13px;
    padding: 8px 18px;
  }

  /* RESULTADOS */
  .resultados {
    flex-direction: column;
    padding: 20px 15px;
    gap: 25px;
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
    font-size: 15px;
  }

  .derecha .nums {
    flex-wrap: wrap;
  }
}

/* FIX LOGO SUPER PREMIO EN MÓVIL */
@media (max-width: 768px) {
  .logo-sp {
    display: block;
    width: 220px;
    max-width: 90%;
    height: auto;
    margin: 0 auto 15px auto; /* CENTRADO */
  }
}
/* =========================
   AJUSTE HEADER MÓVIL
   ========================= */
@media (max-width: 768px) {
  .top {
      padding: 50px 15px;
  }
}

/* TABLA LADO DERECHO */
.derecha-tabla {
    flex: 1;
    padding-left: 20px;
}

/* FILTROS */
.filtros-tabla {
    display: flex;
    gap: 15px;
    justify-content: center;
    align-items: center;
    margin-bottom: 25px;
    flex-wrap: wrap;
}

.filtros-tabla input,
.filtros-tabla select {
    padding: 10px 15px;
    border-radius: 25px;
    border: 1px solid #ef6c00;
    font-weight: 600;
    outline: none;
}

.filtros-tabla button {
    padding: 10px 20px;
    border-radius: 25px;
    border: none;
    background: #ef6c00;
    color: white;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
}

.filtros-tabla button:hover {
    background: #ef6c00;
}

/* TABLA */
.tabla-resultados {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 15px;
}

.tabla-resultados tr {
    background: #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    border-radius: 15px;
}

.tabla-resultados td {
    padding: 20px;
    text-align: center;
}

/* TEXTOS */
.fecha-sorteo {
    font-weight: bold;
    color: #ef6c00;
    font-size: 16px;
}

.hora-sorteo,
.numero-sorteo {
    font-size: 14px;
    margin-top: 5px;
}

/* ESFERAS */
.ganadores {
    display: flex;
    justify-content: center;  /* CENTRADO */
    align-items: center;
    gap: 12px;
    margin-top: 15px;
    flex-wrap: wrap;
}

.bola {
    width: 45px;
    height: 45px;
    background: linear-gradient(145deg, #e0e0e0, #9e9e9e);
    color: #333;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    box-shadow: 0 6px 12px rgba(0,0,0,0.25);
}
/* RESPONSIVE */
@media (max-width:768px){
    .resultados {
        flex-direction: column;
    }

    .derecha-tabla {
        padding-left: 0;
        margin-top: 20px;
    }
}
</style>
</head>
<body>

<div class="top">
    <div class="top-content">
        <img src="/ImagesSV/Titulo-dobletea-tu-suerte.png" class="logo-sp">

        <div class="ganador-box">
            <div class="ganador">ÚLTIMO NÚMERO GANADOR</div>
            <div class="nums" id="ultimoResultadoInferior">
    <span class="num">1</span>
    <span class="num">7</span>
    <span class="num">8</span>
    <span class="num">5</span>
    <span class="num">9</span>
    <span class="num">0</span>
</div>

            <div class="etiqueta-hola">
    PRÓXIMO SORTEO EN VIVO:
    <span id="fechaHeader">06/04/2025  11:00:00 A.M</span>
</div>
        </div>
    </div>
</div>


<div class="menu">
    <a href="https://juega.loto.sv/websales/" target="_blank">JUGÁ AQUÍ</a>
    
</div>

<!-- RESULTADOS -->
<div class="resultados">
    
    <div class="col izquierda">
        <h2>RESULTADOS ANTERIORES</h2>
        
    </div>

    <div class="col derecha-tabla">

        <!-- FILTROS -->
        <div class="filtros-tabla">
            <input type="date" id="filtroFecha">


            <button onclick="filtrarTabla()">Buscar</button>
        </div>

        <!-- TABLA -->
        <table class="tabla-resultados" id="tablaResultados">
            <tbody>
<tr>
                    <td>
                        <div class="fecha-sorteo" data-fecha="2026-02-19">
                            Lunes 30 de Marzo, 2026
                        </div>
                        <div class="hora-sorteo">11:00 AM</div>
                        <div class="numero-sorteo">Sorteo #5</div>

                        <div class="ganadores">
                            <div class="bola">1</div>
                            <div class="bola">7</div>
                            <div class="bola">8</div>
                            <div class="bola">5</div>
                            <div class="bola">9</div>
                            <div class="bola">0</div>
                        </div>
                    </td>
                </tr>



<tr>
                    <td>
                        <div class="fecha-sorteo" data-fecha="2026-02-19">
                            Lunes 23 de Marzo, 2026
                        </div>
                        <div class="hora-sorteo">11:00 AM</div>
                        <div class="numero-sorteo">Sorteo #5</div>

                        <div class="ganadores">
                            <div class="bola">1</div>
                            <div class="bola">7</div>
                            <div class="bola">4</div>
                            <div class="bola">9</div>
                            <div class="bola">7</div>
                            <div class="bola">8</div>
                        </div>
                    </td>
                </tr>


            <tr>
                    <td>
                        <div class="fecha-sorteo" data-fecha="2026-02-19">
                            Lunes 16 de Marzo, 2026
                        </div>
                        <div class="hora-sorteo">11:00 AM</div>
                        <div class="numero-sorteo">Sorteo #4</div>

                        <div class="ganadores">
                            <div class="bola">2</div>
                            <div class="bola">2</div>
                            <div class="bola">2</div>
                            <div class="bola">0</div>
                            <div class="bola">3</div>
                            <div class="bola">3</div>
                        </div>
                    </td>
                </tr>

            <tr>
                    <td>
                        <div class="fecha-sorteo" data-fecha="2026-02-19">
                            Lunes 9 de Marzo, 2026
                        </div>
                        <div class="hora-sorteo">11:00 AM</div>
                        <div class="numero-sorteo">Sorteo #3</div>

                        <div class="ganadores">
                            <div class="bola">5</div>
                            <div class="bola">9</div>
                            <div class="bola">7</div>
                            <div class="bola">4</div>
                            <div class="bola">3</div>
                            <div class="bola">0</div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="fecha-sorteo" data-fecha="2026-02-19">
                            Lunes 2 de Marzo, 2026
                        </div>
                        <div class="hora-sorteo">11:00 AM</div>
                        <div class="numero-sorteo">Sorteo #2</div>

                        <div class="ganadores">
                            <div class="bola">6</div>
                            <div class="bola">0</div>
                            <div class="bola">9</div>
                            <div class="bola">4</div>
                            <div class="bola">8</div>
                            <div class="bola">0</div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="fecha-sorteo" data-fecha="2026-02-19">
                            Lunes 23 de Febrero, 2026
                        </div>
                        <div class="hora-sorteo">11:00 AM</div>
                        <div class="numero-sorteo">Sorteo #1</div>

                        <div class="ganadores">
                            <div class="bola">1</div>
                            <div class="bola">7</div>
                            <div class="bola">4</div>
                            <div class="bola">5</div>
                            <div class="bola">2</div>
                            <div class="bola">1</div>
                        </div>
                    </td>
                </tr>


                       

            </tbody>
        </table>
    </div>

</div>



<!-- ACCORDION -->
<div class="accordion">
    <div class="accordion-header">Mecánica de la Promoción: Dobleteá Tu Suerte <span class="arrow">▼</span></div>
    <div class="accordion-content">
        <div class="info-juego">
            <p class="descripcion">
                ¡Jugá, guardá tu boleto y doblá tu suerte!<br>
                Ganar con nuestra promo es muy fácil. Seguí estos pasos y participá por un carro Suzuki S-Presso 2025 cada semana:
            </p>
            <p class="subtitulo-rojo">¿Cómo participás?</p>
            <div class="linea-juego">
                <img src="/ImagesSV/BOLETO-dobletea.png" class="img-super">
                <p>
                    1. Comprá tus boletos de tus juegos en los puntos de venta LOTO a nivel nacional o en línea en loto.sv: <br>
                      • Diaria <br>
                      • SuperPremio <br>
                      • Apostemos Deportes <br> <br>

                    2. En la parte inferior de tu boleto, te saldrá un código de 6 dígitos. <br><br>
                    3. Ese código te da la oportunidad de participar en la rifa del carro en esa semana. <br><br>
                    4. Guardá tu boleto, porque es indispensable para reclamar tu premio si resultás ganador.
                </p>
            </div>
        </div>
        <div class="sub-accordion-header">¿Cuándo se hacen los sorteos del carro? <span>▼</span></div>
        <div class="sub-accordion-content">Cada lunes, en el sorteo en vivo de las 11:00 a.m. por: Canal 4 Facebook Loto El Salvador YouTube / lotoelsalvador <br><br>

 Fechas de los sorteos del carro:<br>

· 23 de febrero 2026 <br>

· 2 de marzo 2026 <br>

· 9 de marzo 2026 <br>

· 16 de marzo 2026 <br>

· 23 de marzo 2026 <br>

· 30 de marzo 2026 <br> <br> 

¿Qué podés ganar? <br>

 Cada semana se rifa un Suzuki S-Presso Mecánico 2025 <br> 

¡Son 6 carros en total durante toda la promoción! <br><br> 

¿Cómo saber si ganaste?<br>

· Viendo los sorteos Loto de cada lunes a las 11<br>

· Por medio de nuestras redes sociales.</div>
        <div class="sub-accordion-header">¿Cómo reclamás tu premio? <span>▼</span></div>
        <div class="sub-accordion-content">· Si resultás ganador, debés presentar: Tu boleto original con el código de 6 dígitos legible, firmado en la parte de atrás <br>

· Tu DUI vigente<br> <br>

Podés acercarte a:<br>

· LotoCentro Metrocentro San Salvador<br>

· Kiosco LOTO Metrocentro Santa Ana <br>

· Kiosco LOTO en El Encuentro Aguilares <br>

· Oficina Principal en Antiguo Cuscatlán <br> <br>

Tenés 90 días desde que se emite el premio para reclamarlo. <br> <br>

¿Quiénes pueden participar? <br>

Personas mayores de 18 años <br>

Vigencia de la promoción<br>

Del 15 de febrero al 29 de marzo de 2026, válida únicamente en El Salvador.</div>
    </div>
</div>

<!-- BOTÓN REGLAMENTO -->
<div class="reglamento">
    <a href="/ImagesSV/documentos/Términos y condiciones Dobletea Tu Suerte - Loto El Salvador.pdf" download>
        <button>Términos y condiciones</button>
    </a>
</div>

<script>
// Accordion principal
document.querySelectorAll('.accordion-header').forEach(h => h.addEventListener('click', () => {
    const c = h.nextElementSibling;
    document.querySelectorAll('.accordion-content').forEach(cc => { if(cc!==c) cc.style.display='none'; });
    c.style.display = c.style.display==='block'?'none':'block';
}));

// Sub-accordion
document.querySelectorAll('.sub-accordion-header').forEach(h => h.addEventListener('click', () => {
    h.classList.toggle('active');
    const c = h.nextElementSibling;
    c.style.display = c.style.display==='block'?'none':'block';
}));


function filtrarTabla() {
    const fechaInput = document.getElementById("filtroFecha").value;
    const sorteoInput = document.getElementById("filtroSorteo").value;

    const filas = document.querySelectorAll("#tablaResultados tbody tr");

    filas.forEach(fila => {
        const fechaFila = fila.querySelector(".fecha-sorteo").dataset.fecha;
        const sorteoFila = fila.querySelector(".numero-sorteo").innerText;

        let mostrar = true;

        if (fechaInput && fechaInput !== fechaFila) {
            mostrar = false;
        }

        if (sorteoInput && sorteoInput !== sorteoFila) {
            mostrar = false;
        }

        fila.style.display = mostrar ? "" : "none";
    });
}

</script>

</body>
</html>

