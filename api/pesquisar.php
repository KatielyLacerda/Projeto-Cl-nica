<?php
include_once 'database.php';


// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $primeiroNome = $_POST['primeiroNome'];
    $ultimoNome = $_POST['ultimoNome'];
    $crm = $_POST['crm'];
    $tipoUsuario = $_POST['tipoUsuario'];

    if ($tipoUsuario == 'paciente') {
        include_once 'paciente.php';
        $database = new Database();
        $db = $database->getConnection();
        $paciente = new Paciente($db);
        $stmt = $paciente->pesquisarPorNome($primeiroNome);
    } elseif ($tipoUsuario == 'medico') {
        include_once 'medico.php';
        $database = new Database();
        $db = $database->getConnection();
        $medico = new Medico($db);
        $stmt = $medico->pesquisarPorNome($primeiroNome);
    }

    // Exibir os resultados
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Aqui você pode exibir os dados encontrados, como em um formulário para edição
            echo '<form id="editar-form" action="update.php" method="post">';
            echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
            echo '<input type="text" name="primeiroNome" value="' . $row['primeiroNome'] . '">';
            echo '<input type="text" name="ultimoNome" value="' . $row['ultimoNome'] . '">';
            
            if ($tipoUsuario == 'paciente') {
                echo '<input type="text" name="cpf" value="' . $row['cpf'] . '">';
                echo '<input type="text" name="cep" value="' . $row['cep'] . '">';
                echo '<input type="text" name="endereco" value="' . $row['endereco'] . '">';
                echo '<input type="text" name="numero" value="' . $row['numero'] . '">';
                echo '<input type="text" name="cidade" value="' . $row['cidade'] . '">';
                echo '<input type="text" name="estado" value="' . $row['estado'] . '">';
                echo '<input type="text" name="telefone" value="' . $row['telefone'] . '">';
            } elseif ($tipoUsuario == 'medico') {
                echo '<input type="text" name="crm" value="' . $row['crm'] . '">';
                echo '<input type="text" name="especialidade" value="' . $row['especialidade'] . '">';
                echo '<input type="text" name="telefone" value="' . $row['telefone'] . '">';
            }
    
            echo '<button type="submit">Alterar</button>';
            echo '</form>';
        }
    } else {
        echo 'Nenhum resultado encontrado.';
    }
}
?>