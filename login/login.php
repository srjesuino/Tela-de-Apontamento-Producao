<?php 
class Login{
    private $conn;
    public function __construct() {
        try{
            $this->conn = new SoapClient("http://192.168.10.195:8180/ws0201/MADWS001.apw?WSDL");
        } catch (Exception $e){
            die("Erro ao conectar ao serviço: " . $e->getMessage());
        }
    }
    public function validaUsuario($username, $password) {
        try{

        
        $result = $this->conn->VALIDAUSUARIO(array("CUSUARIO" => $username, "CSENHA" => $password));
        $cid = $result->VALIDAUSUARIORESULT->AUSUARIO->OWUSUARIO->CID;
        $nome = $result->VALIDAUSUARIORESULT->AUSUARIO->OWUSUARIO->CNOME;
        return [
            'nome'=>$nome,
            'cid'=>$cid
        ];
        } catch(Exception $e) {
            return false;
        }
    }
}
?>