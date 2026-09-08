


<style>
/* Mantener el footer y su logo encima del contenido */
.footer {
  position: relative !important;
  z-index: 9999 !important;
  overflow: visible !important;
  isolation: isolate;
}

.footer-left {
  position: relative !important;
  z-index: 10000 !important;
  overflow: visible !important;
}

.footer-logo {
  position: relative !important;
  z-index: 10001 !important;
}

/* =========================
   SOLO FIX PARA TELÉFONOS
   ========================= */
@media (max-width: 768px) {

  .footer {
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  /* LOGO PRINCIPAL */
  .footer-left {
    width: 100%;
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
  }

  .footer-left img {
    transform: scale(2) !important; /* mantiene tamaño grande pero controlado */
    margin-top: 25px !important;
  }

  /* COLUMNAS */
  .footer-right {
    width: 100%;
  }

  .footer-columns {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center; /* centra Juegos, Nosotros, Secciones */
    gap: 20px;
  }

  .footer-column {
    width: 100%;
  }

  .footer-column h3 {
    text-align: center;
  }

  .footer-column p {
    text-align: center;
  }
  /* LOGO +18 */
  .footer-logos {
    width: 100%;
    display: flex;
    justify-content: center;
    margin-top: 20px;
  }

  /* REDES SOCIALES */
  .social-icons {
    display: flex;
    justify-content: center;
    width: 100%;
    margin-top: 20px;
  }
}
@media (max-width: 768px) {

  .footer-extra-logo {
    max-width: 70px;     
    width: 100%;
    height: auto;
  }
  
  .footer-logos {
    display: flex;
    justify-content: center;
    align-items: center;
  }
}
</style>

<div class="footer">
  <div class="footer-left">
   <img src="/ImagesSV/LOGO LOTO JDL WHITE.svg" alt="Logo" class="footer-logo"
     style="transform: scale(2.5) translateX(40px); margin-top: 25px;">
  </div>

  <div class="footer-right">
    <div class="footer-columns">
      <div class="footer-column">
        <h3>Juegos</h3>
        <p>
          <a href="?pag=diaria" style="color: inherit; text-decoration: none;">
       La Diaria
      </a>
    </p>
        
        <p>
      <a href="?pag=super_premio" style="color: inherit; text-decoration: none;">
       Loto Super Premio
      </a>
    </p>
        <p>
      <a href="?pag=instacash" style="color: inherit; text-decoration: none;">
        InstaCash
      </a>
    </p>
         <p>
      <a href="?pag=apostemos" style="color: inherit; text-decoration: none;">
        Apostemos
      </a>
    </p>

    <p>
      <a href="?pag=bingo_con_todo" style="color: inherit; text-decoration: none;">
        Bingo con Todo
      </a>
    </p>

    <p>
      <a href="?pag=juga3" style="color: inherit; text-decoration: none;">
        Jugá Tres
      </a>
    </p>

    <p>
      <a href="?pag=premia2" style="color: inherit; text-decoration: none;">
        Premia2
      </a>
    </p>

    <p>
      <a href="?pag=pega_3" style="color: inherit; text-decoration: none;">
        Pega3
      </a>
    </p>
  
    <p>
      <a href="?pag=multi_x" style="color: inherit; text-decoration: none;">
        Multi X
      </a>
    </p>

    <p>
      <a href="?pag=ganagol" style="color: inherit; text-decoration: none;">
        Ganagol
      </a>
    </p>
    
    
        
      </div>

      <div class="footer-column">
        <h3>Nosotros</h3>
        <p>
        <a href="?pag=aplica_con_nosotros" style="color: inherit; text-decoration: none;">
        Aplicá con nosotros
      </a>
      </p>
        <p>
        <a href="?pag=quiero_ser_agente" style="color: inherit; text-decoration: none;">
        Quiero ser vendedor
      </a>
        </p>
       <p>
  <a href="https://www.google.com/maps/d/viewer?mid=1_7d7-vxgaC0-T3cYocFlitpQJmON4vI&femb=1&ll=14.790166903072388%2C-86.525280135&z=7" target="_blank" style="color: inherit; text-decoration: none;">
  
    Puntos de venta
  </a>
</p>


<p>
    <a href="/ImagesSV/documentos/POLÍTICA DE PREVENCIÓN DE LAVADO DE ACTIVOS Y FINANCIACIÓN DEL TERRORISMO.pdf"
       target="_blank"
       rel="noopener noreferrer"
       style="color: inherit; text-decoration: none;">
        Política Prevención LA/FT
    </a>
</p>

      </div>

      <div class="footer-column">
    <h3>Secciones</h3>
    <p>
        <a href="?pag=noticias" style="color: inherit; text-decoration: none;">
            Noticias
        </a>
    </p>
    <p>
        <a href="?pag=contactanos" style="color: inherit; text-decoration: none;">
            Contáctanos
        </a>
    </p>
    
</div>


    </div>
  </div>

  <!-- Logos totalmente a la derecha -->
  <div class="footer-logos">
      <img src="/ImagesSV/Logo ESR.png" alt="Logo ESR" class="footer-extra-logo"> 
    <img src="/ImagesSV/LOTO_21.png" alt="Logo Loto 21" class="footer-extra-logo">
  </div>

  <div class="social-icons">
  <a href="https://www.tiktok.com/@loto_hn" target="_blank">
    <img src="/ImagesSV/tik-tok.svg" alt="TikTok">
  </a>
  <a href="https://www.instagram.com/LotoHonduras/" target="_blank">
    <img src="/ImagesSV/instagram.svg" alt="Instagram">
  </a>
  <a href="https://www.facebook.com/lotohonduras/" target="_blank">
    <img src="/ImagesSV/facebook.svg" alt="Facebook">
  </a>
  <a href="https://x.com/lotohonduras" target="_blank">
    <img src="/ImagesSV/Twitter.svg" alt="Twitter">
  </a>   
  <a href="https://www.youtube.com/user/LOTELHSA" target="_blank">
    <img src="/ImagesSV/Youtube.svg" alt="Youtube">
  </a>
  <a href="https://hn.linkedin.com/company/somosloto" target="_blank">
    <img src="/ImagesSV/linkedin.svg" alt="LinkedIn">
  </a>
</div>

</div>
