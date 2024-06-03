<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'database.php';
include_once 'medico.php';

$database = new Database();
$db = $database->getConnection();

$medico = new Medico($db);


$data = json_decode(file_get_contents("php://input"));

//ver se tem todos os compos
if(
    !empty($data->primeiroNome) &&
    !empty($data->ultimoNome) &&
    !empty($data->crm) &&
    !empty($data->especialidade) &&
    !empty($data->telefone)
) {
    $medico->primeiroNome = $data->primeiroNome;
    $medico->ultimoNome = $data->ultimoNome;
    $medico->crm = $data->crm;
    $medico->especialidade = $data->especialidade;
    $medico->telefone = $data->telefone;
    
    //criando o medico no banco
    if($medico->create()) {
        //se deu certo
        http_response_code(201);
        echo json_encode(array("message" => "Médico foi cadastrado."));
    } else {
        // se não (tá dando erro pq tá retornado um html e n json)
        http_response_code(503);
        echo json_encode(array("message" => "Não foi possível cadastrar o médico."));
    }
} else {
    // aqui no caso
    http_response_code(400);
    echo json_encode(array("message" => "Dados incompletos."));
}


exit;
?>