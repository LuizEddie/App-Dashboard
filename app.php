<?php

    class Dashboard{

        public $data_inicio;
        public $data_fim;
        public $numeroVendas;
        public $totalVendas;
        public $clientesAtivos;
        public $clientesInativos;
        public $totalDespesas;
        public $criticas;
        public $sugestoes;
        public $elogios;

        public function __get($atributo){
            return  $this->$atributo;
        }

        public function __set($atributo, $valor){
            $this->$atributo = $valor;
            return $this;
        }

    }

    class Conexao{

        private $host = 'localhost';
        private $dbname = 'dashboard';
        private $user = 'root';
        private $senha = '';

        public function conectar(){

            try{

                $conexao = new PDO("mysql:host=$this->host;dbname=$this->dbname",$this->user, $this->senha);
                $conexao->exec('set charset set utf8');
                return $conexao;
            }catch(PDOException $e){
                echo "<p>".$e->getMessage()."</p>";
            }

        }
    }

    class Bd{
        private $conexao;
        private $dashboard;

        public function __construct(Conexao $conexao, Dashboard $dashboard){
            $this->conexao = $conexao->conectar();
            $this->dashboard = $dashboard;
        }

        public function getNumeroVendas(){
            $query = "SELECT count(*) as numero_vendas FROM tb_vendas WHERE data_venda BETWEEN :data1 AND :data2";
            $select = $this->conexao->prepare($query);
            $select->bindValue(':data1', $this->dashboard->__get("data_inicio"));
            $select->bindValue(':data2', $this->dashboard->__get("data_fim"));
            $select->execute();

            return $select->fetch(PDO::FETCH_OBJ)->numero_vendas;
        }

        public function getTotalDespesas(){
            $query = "SELECT sum(total) as total_despesas FROM tb_despesas WHERE data_despesa BETWEEN :data1 AND :data2";
            $select = $this->conexao->prepare($query);
            $select->bindValue(':data1', $this->dashboard->__get("data_inicio"));
            $select->bindValue(':data2', $this->dashboard->__get("data_fim"));
            $select->execute();

            return $select->fetch(PDO::FETCH_OBJ)->total_despesas;
        }

        public function getClientesByAtivo(){
            $query = "SELECT count(*) as total, cliente_ativo FROM tb_clientes GROUP BY cliente_ativo ORDER BY cliente_ativo ASC";
            $select = $this->conexao->prepare($query);
            $select->execute();

            return $select->fetchAll(PDO::FETCH_OBJ);
        }

        public function getContatosByTipoContato(){
            $query = "SELECT count(*) as total, tipo_contato FROM tb_contatos GROUP BY tipo_contato ORDER BY tipo_contato ASC";
            $select = $this->conexao->prepare($query);
            $select->execute();

            return $select->fetchAll(PDO::FETCH_OBJ);
        }

        public function getTotalVendas(){
            $query = "SELECT sum(total) as total_vendas FROM tb_vendas WHERE data_venda BETWEEN :data1 AND :data2";
            $select = $this->conexao->prepare($query);
            $select->bindValue(':data1', $this->dashboard->__get("data_inicio"));
            $select->bindValue(':data2', $this->dashboard->__get("data_fim"));
            $select->execute();

            return $select->fetch(PDO::FETCH_OBJ)->total_vendas;
        }

    }

    $dashboard = new Dashboard;
    $conexao = new Conexao;

    $competencia = explode("-",$_GET['competencia']);

    $dias_do_mes = cal_days_in_month(CAL_GREGORIAN, $competencia[1], $competencia[0]);

    $dashboard->__set('data_inicio', $competencia[0]."-".$competencia[1].'-01');
    $dashboard->__set('data_fim', $competencia[0]."-".$competencia[1]."-".$dias_do_mes);
    
    $bd = new Bd($conexao, $dashboard);

    $dashboard->__set('numeroVendas', $bd->getNumeroVendas());
    $dashboard->__set('totalVendas', $bd->getTotalVendas());
    $dashboard->__set("totalDespesas", $bd->getTotalDespesas());

    $clientes = $bd->getClientesByAtivo();
    $dashboard->__set("clientesAtivos", $clientes[1]->total);
    $dashboard->__set("clientesInativos", $clientes[0]->total);

    $contatos = $bd->getContatosByTipoContato();
    $dashboard->__set("criticas", $contatos[0]->total);
    $dashboard->__set("sugestoes", $contatos[1]->total);
    $dashboard->__set("elogios", $contatos[2]->total);
    
    echo json_encode($dashboard);
    

?>