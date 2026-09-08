<?php
header('Content-Type: application/json');

try {

    $conn = new PDO(
        "sqlsrv:Server=srvdbcacdev.database.windows.net;Database=dblotocacdev",
        "LotoAdmin",
        "LotAdmin1.",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $stmt = $conn->prepare("
        SELECT TOP 1 next_jackpot
        FROM numeros_ganadores_sorteos_prod
        WHERE pais = 'Honduras'
          AND game_name = 'Super Premio'
          AND next_jackpot IS NOT NULL
        ORDER BY draw_date DESC
    ");

    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    $nextJackpot = $data ? (float)$data['next_jackpot'] : 0;

    echo json_encode([
        "jugada_sencilla" => $nextJackpot,
        "jugada_doble"   => $nextJackpot * 2
    ]);

} catch (PDOException $e) {

    echo json_encode([
        "error" => true,
        "message" => "Error al obtener next_jackpot"
    ]);
}