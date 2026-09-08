<?php
header('Content-Type: application/json');

$conn = new PDO(
  "sqlsrv:Server=srvdbcacdev.database.windows.net;Database=dblotocacdev",
  "LotoAdmin",
  "LotAdmin1.",
  [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$fecha = $_GET['fecha'] ?? date('Y-m-d');

// DIARIA
$sql1 = "
SELECT
  CAST(draw_date AS time) AS hora,
  par1
FROM numeros_ganadores_sorteos_prod
WHERE pais='Honduras'
AND UPPER(game_name)='LA DIARIA'
AND CAST(draw_date AS date)=:fecha
";

$stmt1 = $conn->prepare($sql1);
$stmt1->execute(['fecha'=>$fecha]);
$rows1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

// DIARIA +1
$sql2 = "
SELECT
  CAST(draw_date AS time) AS hora,
  par1
FROM numeros_ganadores_sorteos_prod
WHERE pais='Honduras'
AND UPPER(game_name)='DIARIA +1'
AND CAST(draw_date AS date)=:fecha
";

$stmt2 = $conn->prepare($sql2);
$stmt2->execute(['fecha'=>$fecha]);
$rows2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// estructura
$resultados = ['11'=>null,'15'=>null,'21'=>null];

foreach($rows1 as $r){
  $h = substr($r['hora'],0,2);

  if($h>='10' && $h<'12') $resultados['11']['diaria']=$r;
  if($h>='14' && $h<'16') $resultados['15']['diaria']=$r;
  if($h>='20' && $h<'22') $resultados['21']['diaria']=$r;
}

foreach($rows2 as $r){
  $h = substr($r['hora'],0,2);

  if($h>='10' && $h<'12') $resultados['11']['mas1']=$r;
  if($h>='14' && $h<'16') $resultados['15']['mas1']=$r;
  if($h>='20' && $h<'22') $resultados['21']['mas1']=$r;
}

echo json_encode($resultados);