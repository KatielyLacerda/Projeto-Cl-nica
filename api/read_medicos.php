<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'database.php';
include_once 'medico.php';

$database = new Database();
$db = $database->getConnection();

$medico = new Medico($db);

$stmt = $medico->read();
$num = $stmt->rowCount();

if($num>0) {
    $medicos_arr=array();
    $medicos_arr["records"]=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $medico_item=array(
            "id" => $id,
            "primeiroNome" => $primeiroNome,
            "ultimoNome" => $ultimoNome,
            "crm" => $crm,
            "especialidade" => $especialidade,
            "telefone" => $telefone
        );
        array_push($medicos_arr["records"], $medico_item);
    }

    http_response_code(200);
    echo json_encode($medicos_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Nenhum médico encontrado."));
}
?>