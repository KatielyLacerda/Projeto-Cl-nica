<?php
class Paciente {
    // Propriedades
    private $conn;
    private $table_name = "pacientes";

    public $id;
    public $primeiroNome;
    public $ultimoNome;
    public $cpf;
    public $cep;
    public $endereco;
    public $numero;
    public $cidade;
    public $estado;
    public $telefone;


    public function __construct($db) {
        $this->conn = $db;
    }

    //função de criar 
    function create() {
        //inserir
        $query = "INSERT INTO " . $this->table_name . "
                SET primeiroNome=:primeiroNome, ultimoNome=:ultimoNome, cpf=:cpf, cep=:cep, endereco=:endereco, numero=:numero, cidade=:cidade, estado=:estado, telefone=:telefone";

        //consulta
        $stmt = $this->conn->prepare($query);

    //limpar os dados
        $this->primeiroNome = htmlspecialchars(strip_tags($this->primeiroNome));
        $this->ultimoNome = htmlspecialchars(strip_tags($this->ultimoNome));
        $this->cpf = htmlspecialchars(strip_tags($this->cpf));
        $this->cep = htmlspecialchars(strip_tags($this->cep));
        $this->endereco = htmlspecialchars(strip_tags($this->endereco));
        $this->numero = htmlspecialchars(strip_tags($this->numero));
        $this->cidade = htmlspecialchars(strip_tags($this->cidade));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->telefone = htmlspecialchars(strip_tags($this->telefone));

        //bing dos valores
        $stmt->bindParam(":primeiroNome", $this->primeiroNome);
        $stmt->bindParam(":ultimoNome", $this->ultimoNome);
        $stmt->bindParam(":cpf", $this->cpf);
        $stmt->bindParam(":cep", $this->cep);
        $stmt->bindParam(":endereco", $this->endereco);
        $stmt->bindParam(":numero", $this->numero);
        $stmt->bindParam(":cidade", $this->cidade);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":telefone", $this->telefone);

        //consultando
        if($stmt->execute()) {
            return true;
        }

        return false;
    }
    function pesquisarPorNome($nome) {
        //pesquisa por nome 
        $query = "SELECT * FROM " . $this->table_name . " WHERE primeiroNome LIKE ? OR ultimoNome LIKE ?";
        $stmt = $this->conn->prepare($query);

    
        $nome = '%' . htmlspecialchars(strip_tags($nome)) . '%';
        $stmt->bindParam(1, $nome);
        $stmt->bindParam(2, $nome);
        $stmt->execute();
        return $stmt;
    }
    function readOne($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $this->primeiroNome = $row['primeiroNome'];
            $this->ultimoNome = $row['ultimoNome'];
            $this->cpf = $row['cpf'];
            $this->cep = $row['cep'];
            $this->endereco = $row['endereco'];
            $this->numero = $row['numero'];
            $this->cidade = $row['cidade'];
            $this->estado = $row['estado'];
            $this->telefone = $row['telefone'];
            return $row; //return
        }
        return null; 
    }
}
?>