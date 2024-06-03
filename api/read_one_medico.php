<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'database.php';
include_once 'medico.php';

$database = new Database();
$db = $database->getConnection();

$medico = new Medico($db);

$medico->id = isset($_GET['id']) ? $_GET['id'] : die();

$medico->readOne();

if($medico->primeiroNome != null) {
    $medico_arr = array(
        "id" => $medico->id,
        "primeiroNome" => $medico->primeiroNome,
        "ultimoNome" => $medico->ultimoNome,
        "crm" => $medico->crm,
        "especialidade" => $medico->especialidade,
        "telefone" => $medico->telefone
    );

    http_response_code(200);
    echo json_encode($medico_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Médico não encontrado."));
}
?>