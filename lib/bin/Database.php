<?php 

class Database{
    private $isConnect;
    private $host;
    private $dbname;
    private $username;
    private $pass;
    protected $con;


    public function __construct(){
        $this->host = 'localhost';
        $this->dbname = 'bulkemail';
        $this->username = 'root';
        $this->pass = '';
        $this->isConnect = TRUE;
        try{
            $this->con = new PDO("mysql:host=$this->host;dbname=$this->dbname",$this->username,$this->pass);
            $this->con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $this->con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            throw new Exception( $e->getMessage() );
        }
    }   

    public function disconnect(){
        $this->isConnect = FALSE;
        $this->con = NULL;
    }

    public function getRow($query,$params = []){
        try{

            $stmt = $this->con->prepare($query);
            $stmt->execute($params);
            return $stmt->fetch();

        }catch(PDOException $e){
            throw new Exception( $e->getMessage() );
        }
    }

    public function getRows($query,$params = []){
        try{

            $stmt = $this->con->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();

        }catch(PDOException $e){
            throw new Exception( $e->getMessage() );
        }
    }

    public function insertRow($query,$params = []){
        try{

            $stmt = $this->con->prepare($query);
            $stmt->execute($params);
            return TRUE;

        }catch(PDOException $e){
            throw new Exception( $e->getMessage() );
        }
    }

}