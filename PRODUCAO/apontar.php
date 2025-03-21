<?php
    function apontar($cbarras, $recurso, $usuario){
        try {
            $conn = new SoapClient("http://192.168.10.194:8180/ws0201/MADWS005.apw?WSDL");
        } catch (Exception $e) {
            die("Erro ao conectar ao serviço: " . $e->getMessage());
        }
        try{
            $stmt = $conn->GRVAPONT(array("TOKEN" => "_SROODRAPEDAM_", "DADOSAPONT" => array("CBARRAS" => $cbarras, "RECURSO" => $recurso, "USUARIO" => $usuario)));
            $result = $stmt;

            return $result;
        } catch (Exception $e){
            die("Erro ao apontar: " . $e->getMessage());
        }
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $codigoBarra = $_POST['codigoBarra'];
        $recurso = $_POST['recurso'];
        $usuario = $_POST['usuario'];
        $result = apontar($codigoBarra, $recurso, $usuario);
        echo json_encode($result);
    }
?>