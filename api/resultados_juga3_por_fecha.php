<?php
header('Content-Type: application/json');

$conn = new PDO(
  "sqlsrv:Server=srvdbcacdev.database.windows.net;Database=dblotocacdev",
  "LotoAdmin",
  "LotAdmin1.",
  [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$fecha = $_GET['fecha'] ?? date('Y-m-d');

$sql = "
SELECT
  CAST(draw_date AS time) AS hora,
  par1
FROM numeros_ganadores_sorteos_prod
WHERE
  pais = 'Honduras'
  AND game_name COLLATE Latin1_General_CI_AI = 'JUGA TRES'
  AND CAST(draw_date AS date) = :fecha
";

$stmt = $conn->prepare($sql);
$stmt->execute(['fecha' => $fecha]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$resultados = ['11'=>null,'15'=>null,'21'=>null];

foreach ($rows as $r) {
  $h = substr($r['hora'],0,2);

  if ($h >= '10' && $h < '12') $resultados['11'] = $r;
  if ($h >= '14' && $h < '16') $resultados['15'] = $r;
  if ($h >= '20' && $h < '22') $resultados['21'] = $r;
}

echo json_encode($resultados);
