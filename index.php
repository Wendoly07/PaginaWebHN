<?php


// Obtener la página solicitada
$pag = "";
if(isset($_GET['pag'])){
    $pag = $_GET['pag'];
}

// Si no hay página, redirigir a login.php
if ($pag == "") {
    header("Location: /login.php");
    exit(); // Detiene el script
}

// Incluir el header después de la redirección
include 'includes/header.php';

// Mostrar el contenido según la página
switch ($pag) {
    case 'body':
    include 'includes/body.php';
    break;
    case 'diaria':
        include 'diaria.php';
    break;
    case 'quiero_ser_agente':
        include 'quiero_ser_agente.php';
    break;
    case 'noticias':
        include 'noticias.php';
    break;
    case 'apostemos':
        include 'apostemos.php';
    break;
    case 'aplica_con_nosotros':
        include 'aplica_con_nosotros.php';
    break;
    case 'contactanos':
        include 'contactanos.php';
    break;
    case 'sobre_nosotros':
        include 'sobre_nosotros.php';
    break;
    case 'instacash':
        include 'instacash.php';
    break;
    case 'super_premio':
        include 'super_premio.php';
    break;
    case 'suerte':
        include 'suerte.php';
    break;
    case 'dobletea_tu_suerte':
        include 'dobletea_tu_suerte.php';
    break;
    case 'juga3':
        include 'juga_3.php';
    break;
    case 'pega_3':
        include 'pega_3.php';
    break;
    case 'bingo_con_todo':
        include 'bingo_con_todo.php';
    break;
    case 'ganagol':
        include 'ganagol.php';
    break;
    case 'multi_x':
        include 'multi_x.php';
    break;
    case 'premia2':
        include 'premia2.php';
    break;
    case 'noticia_detalle':
        include 'noticia_detalle.php';
    break;
    case 'test':
        include 'test.php';
    break;

    case 'rse':
        include 'rse.php';
    break;

    case 'responsable':
        include 'responsable.php';
    break;

    case 'podcast':
        include 'podcast.php';
    break;
    
    
    default:
        include 'includes/body.php';
    break;

    
}

include 'includes/footer.php';
?>
