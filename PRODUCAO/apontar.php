<?php
    // Define a função 'apontar' que realiza o apontamento de produção
    function apontar($cbarras, $recurso, $usuario){
        try {
            // Cria uma conexão com o serviço SOAP usando o WSDL fornecido
            $conn = new SoapClient("http://192.168.10.***:****/ws0201/MADWS005.apw?WSDL");
        } catch (Exception $e) {
            // Se houver erro na conexão, exibe a mensagem de erro e encerra o script
            die("Erro ao conectar ao serviço: " . $e->getMessage());
        }
        try {
            // Chama o método 'GRVAPONT' do serviço SOAP, passando os parâmetros
            // TOKEN: chave fixa para autenticação no serviço
            // DADOSAPONT: array com código de barras, recurso e usuário
            $stmt = $conn->GRVAPONT(array(
                "TOKEN" => "_******_", 
                "DADOSAPONT" => array(
                    "CBARRAS" => $cbarras, 
                    "RECURSO" => $recurso, 
                    "USUARIO" => $usuario
                )
            ));
            // Armazena o resultado da chamada
            $result = $stmt;

            // Retorna o resultado da operação
            return $result;
        } catch (Exception $e) {
            // Se houver erro na chamada ao método SOAP, exibe a mensagem de erro e encerra o script
            die("Erro ao apontar: " . $e->getMessage());
        }
    }

    // Verifica se a requisição é do tipo POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtém os dados enviados pelo formulário via POST
        $codigoBarra = $_POST['codigoBarra']; // Código de barras
        $recurso = $_POST['recurso'];         // Recurso selecionado
        $usuario = $_POST['usuario'];         // ID do usuário

        // Chama a função 'apontar' com os dados recebidos
        $result = apontar($codigoBarra, $recurso, $usuario);

        // Converte o resultado em JSON e o retorna para o cliente
        echo json_encode($result);
    }
?>
