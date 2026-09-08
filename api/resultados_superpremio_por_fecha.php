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
SELECT TOP 1
  par1, par2, par3, par4, par5, par6
FROM numeros_ganadores_sorteos_prod
WHERE
  pais = 'Honduras'
  AND UPPER(game_name) = 'SUPER PREMIO'
  AND CAST(draw_date AS date) = :fecha
ORDER BY draw_date DESC
";

$stmt = $conn->prepare($sql);
$stmt->execute(['fecha'=>$fecha]);

$data = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($data ?: []);