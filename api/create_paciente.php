<?php
include_once 'database.php';

$data = json_decode(file_get_contents("php://input"));

$database = new Database();
$db = $database->getConnection();

$query = "INSERT INTO pacientes (primeiroNome, ultimoNome, cpf, cep, endereco, numero, cidade, estado, telefone) VALUES (:primeiroNome, :ultimoNome, :cpf, :cep, :endereco, :numero, :cidade, :estado, :telefone)";
$stmt = $db->prepare($query);

$stmt->bindParam(":primeiroNome", $data->primeiroNome);
$stmt->bindParam(":ultimoNome", $data->ultimoNome);
$stmt->bindParam(":cpf", $data->cpf);
$stmt->bindParam(":cep", $data->cep);
$stmt->bindParam(":endereco", $data->endereco);
$stmt->bindParam(":numero", $data->numero);
$stmt->bindParam(":cidade", $data->cidade);
$stmt->bindParam(":estado", $data->estado);
$stmt->bindParam(":telefone", $data->telefone);

if ($stmt->execute()) {
  $paciente_id = $db->lastInsertId();

  // Inserir exames
  $exames = $data->exames;
  foreach ($exames as $exame) {
    $query = "INSERT INTO exames (paciente_id, nomeExame, codigoExame, dataExame) VALUES (:paciente_id, :nomeExame, :codigoExame, :dataExame)";
    $stmt = $db->prepare($query);

    $stmt->bindParam(":paciente_id", $paciente_id);
    $stmt->bindParam(":nomeExame", $exame->nomeExame);
    $stmt->bindParam(":codigoExame", $exame->codigoExame);
    $stmt->bindParam(":dataExame", $exame->dataExame);

    $stmt->execute();
  }

  http_response_code(201);
  echo json_encode(array("message" => "Paciente cadastrado com sucesso."));
} else {
  http_response_code(503);
  echo json_encode(array("message" => "Erro ao cadastrar paciente."));
}
?>