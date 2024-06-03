<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'database.php';
include_once 'medico.php';

$database = new Database();
$db = $database->getConnection();

$medico = new Medico($db);

$data = json_decode(file_get_contents("php://input"));

$medico->id = $data->id;

if($medico->delete()) {
    http_response_code(200);
    echo json_encode(array("message" => "Médico deletado."));
} else {
    http_response_code(503);
    echo json_encode(array("message" => "Não foi possível deletar o médico."));
}
?>