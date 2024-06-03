<?php
// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once 'database.php';

    $tipoUsuario = $_POST['tipoUsuario'];

    if ($tipoUsuario == 'paciente') {
        include_once 'paciente.php';
        $object = new Paciente($db);
    } elseif ($tipoUsuario == 'medico') {
        include_once 'medico.php';
        $object = new Medico($db);
    } else {
       
        header("Location: pesquisar.php?alteracao=erro");
        exit();
    }

    $object->id = $_POST['id'];
    $object->primeiroNome = $_POST['primeiroNome'];
    $object->ultimoNome = $_POST['ultimoNome'];

    if ($tipoUsuario == 'paciente') {
        $object->cpf = $_POST['cpf'];
        $object->cep = $_POST['cep'];
        $object->endereco = $_POST['endereco'];
        $object->numero = $_POST['numero'];
        $object->cidade = $_POST['cidade'];
        $object->estado = $_POST['estado'];
        $object->telefone = $_POST['telefone'];
    } elseif ($tipoUsuario == 'medico') {
        $object->crm = $_POST['crm'];
        $object->especialidade = $_POST['especialidade'];
        $object->telefone = $_POST['telefone'];
    }

    //tenta atualizaro banco
    if ($object->update()) {
        // Redirecionar de volta para a página de pesquisa com uma mensagem de sucesso
        header("Location: pesquisar.php?alteracao=sucesso");
        exit();
    } else {
        // Redirecionar de volta para a página de pesquisa com uma mensagem de erro
        header("Location: pesquisar.php?alteracao=erro");
        exit();
    }
} else {
    // Redirecionar de volta para a página de pesquisa se os dados estiverem incompletos
    header("Location: pesquisar.php?alteracao=erro");
    exit();
}
?>
