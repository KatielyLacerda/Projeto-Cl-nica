<?php
class Medico {
    private $conn;
    private $table_name = "medicos";

    public $id;
    public $primeiroNome;
    public $ultimoNome;
    public $crm;
    public $especialidade;
    public $telefone;

    public function __construct($db) {
        $this->conn = $db;
    }

    function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function create() {
        $query = "INSERT INTO " . $this->table_name . " SET primeiroNome=:primeiroNome, ultimoNome=:ultimoNome, crm=:crm, especialidade=:especialidade, telefone=:telefone";
        $stmt = $this->conn->prepare($query);

        $this->primeiroNome=htmlspecialchars(strip_tags($this->primeiroNome));
        $this->ultimoNome=htmlspecialchars(strip_tags($this->ultimoNome));
        $this->crm=htmlspecialchars(strip_tags($this->crm));
        $this->especialidade=htmlspecialchars(strip_tags($this->especialidade));
        $this->telefone=htmlspecialchars(strip_tags($this->telefone));

        $stmt->bindParam(":primeiroNome", $this->primeiroNome);
        $stmt->bindParam(":ultimoNome", $this->ultimoNome);
        $stmt->bindParam(":crm", $this->crm);
        $stmt->bindParam(":especialidade", $this->especialidade);
        $stmt->bindParam(":telefone", $this->telefone);

        if($stmt->execute()) {
            return true;
        }
        return false;
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
            $this->crm = $row['crm'];
            $this->especialidade = $row['especialidade'];
            $this->telefone = $row['telefone'];
            return $row; // Retorna os dados encontrados
        }
        return null; 
    }

    function update() {
        $query = "UPDATE " . $this->table_name . " SET primeiroNome=:primeiroNome, ultimoNome=:ultimoNome, crm=:crm, especialidade=:especialidade, telefone=:telefone WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->primeiroNome=htmlspecialchars(strip_tags($this->primeiroNome));
        $this->ultimoNome=htmlspecialchars(strip_tags($this->ultimoNome));
        $this->crm=htmlspecialchars(strip_tags($this->crm));
        $this->especialidade=htmlspecialchars(strip_tags($this->especialidade));
        $this->telefone=htmlspecialchars(strip_tags($this->telefone));
        $this->id=htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":primeiroNome", $this->primeiroNome);
        $stmt->bindParam(":ultimoNome", $this->ultimoNome);
        $stmt->bindParam(":crm", $this->crm);
        $stmt->bindParam(":especialidade", $this->especialidade);
        $stmt->bindParam(":telefone", $this->telefone);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        $this->id=htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
     //pesquisar medico por nome, e cmr abaxio (lembrar de fazer n ser obrigatorio o nome se tiver cmr)
    function pesquisarPorNome($nome) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE primeiroNome LIKE ? OR ultimoNome LIKE ?";
        $stmt = $this->conn->prepare($query);


        $nome = '%' . htmlspecialchars(strip_tags($nome)) . '%';
        $stmt->bindParam(1, $nome);
        $stmt->bindParam(2, $nome);
        $stmt->execute();
        return $stmt;
    }
    function pesquisarPorCRM($crm) {
        //pesqusiar por cmr
        $query = "SELECT * FROM " . $this->table_name . " WHERE crm = ?";
        $stmt = $this->conn->prepare($query);
    
        //bing
        $stmt->bindParam(1, $crm);
    
        //consulta
        $stmt->execute();
        return $stmt;
    }
}
?>