<?php
// Define a classe DatabaseQuery para interagir com o banco de dados
class DatabaseQuery
{
    // Propriedades privadas para credenciais e conexão com o banco
    private $servername = "192.168.10.194"; // Endereço do servidor SQL Server
    private $username = "consulta_pcp";     // Nome de usuário do banco
    private $password = "hMhkFwLHBpDIp0&N"; // Senha do banco
    private $dbname = "DADOSADV";           // Nome do banco de dados
    private $conn;                          // Variável para armazenar a conexão

    // Construtor da classe, inicializa a conexão com o banco
    public function __construct()
    {
        // Opções de conexão para o SQL Server
        $connectionOptions = array(
            "Database" => $this->dbname, // Nome do banco
            "Uid" => $this->username,    // Usuário
            "PWD" => $this->password     // Senha
        );
        // Estabelece a conexão usando sqlsrv_connect
        $this->conn = sqlsrv_connect($this->servername, $connectionOptions);

        // Verifica se a conexão falhou e exibe erros se houver
        if ($this->conn === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    // Método para executar a primeira consulta, baseada em um código de barras
    public function executeQuery($var1)
    {
        // Extrai partes específicas do código de barras ($var1)
        $ZCB_LOTE = substr($var1, 1, 6);   // Lote (posições 2-7)
        $ZCB_NUM = substr($var1, 7, 6);    // Número (posições 8-13)
        $ZCB_ITEM = substr($var1, 13, 2);  // Item (posições 14-15)
        $ZCB_SEQUEN = substr($var1, 15, 3); // Sequência (posições 16-18)
        $ZCB_PARTE = substr($var1, 18, 3);  // Parte (posições 19-21)
        $ZHF_OPERAC = substr($var1, 21, 2); // Operação (posições 22-23)

        // Consulta SQL com junções para obter dados relacionados
        $sql = "
        SELECT 
            ZHF_CTRAB, HB_NOME, ZHF_CC, CTT_DESC01, ZCB_UM, ZCB_QUANT
        FROM 
            ZCB020 ZCB
        LEFT JOIN 
            ZHF020 ZHF ON ZHF_FILIAL = ZCB_FILIAL 
            AND ZHF_OP = ZCB_NUM + ZCB_ITEM + ZCB_SEQUEN
            AND ZHF.D_E_L_E_T_ = ''
        LEFT JOIN 
            SHB020 SHB ON HB_FILIAL = ZHF_FILIAL 
            AND HB_COD = ZHF_CTRAB
            AND SHB.D_E_L_E_T_ = ''
        LEFT JOIN 
            CTT020 CTT ON CTT_FILIAL = '' 
            AND CTT_CUSTO = ZHF_CC
            AND CTT.D_E_L_E_T_ = ''
        WHERE 
            ZCB_LOTE = ?
            AND ZCB_NUM = ?
            AND ZCB_ITEM = ?
            AND ZCB_SEQUEN = ?
            AND ZCB_PARTE = ?
            AND ZHF_OPERAC = ?
            AND ZCB.D_E_L_E_T_ = ''
        ";

        // Parâmetros para a consulta preparada
        $params = array($ZCB_LOTE, $ZCB_NUM, $ZCB_ITEM, $ZCB_SEQUEN, $ZCB_PARTE, $ZHF_OPERAC);
        // Executa a consulta
        $stmt = sqlsrv_query($this->conn, $sql, $params);

        // Verifica se houve erro na execução da consulta
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        // Obtém o primeiro resultado como array associativo
        $result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($result) {
            // Atribui os valores do resultado às variáveis
            $ZHF_CTRAB = $result['ZHF_CTRAB'];   // Centro de trabalho
            $HB_NOME = $result['HB_NOME'];       // Nome do centro de trabalho
            $ZHF_CC = $result['ZHF_CC'];         // Centro de custo
            $CTT_DESC01 = $result['CTT_DESC01']; // Descrição do centro de custo
            $ZCB_UM = $result['ZCB_UM'];         // Unidade de medida
            $ZCB_QUANT = $result['ZCB_QUANT'];   // Quantidade
        } else {
            // Se não houver resultado, define tudo como null
            $ZHF_CTRAB = $HB_NOME = $ZHF_CC = $CTT_DESC01 = $ZCB_UM = $ZCB_QUANT = null;
        }

        // Retorna os dados em um array associativo
        return [
            'ZHF_CTRAB' => $ZHF_CTRAB,
            'HB_NOME' => $HB_NOME,
            'ZHF_CC' => $ZHF_CC,
            'CTT_DESC01' => $CTT_DESC01,
            'ZCB_UM' => $ZCB_UM,
            'ZCB_QUANT' => $ZCB_QUANT
        ];
    }

    // Método para obter uma lista de recursos
    public function executeSecondQuery()
    {
        // Consulta SQL para selecionar código e descrição dos recursos
        $sql = "
        SELECT H1_CODIGO, H1_DESCRI 
        FROM SH1020 
        WHERE D_E_L_E_T_ = ''
        ";

        // Executa a consulta
        $stmt = sqlsrv_query($this->conn, $sql);

        // Verifica se houve erro na execução
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        // Armazena os resultados em um array
        $data = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $data[] = [
                'H1_CODIGO' => $row['H1_CODIGO'], // Código do recurso
                'H1_DESCRI' => $row['H1_DESCRI'], // Descrição do recurso
            ];
        }

        // Retorna o array com os recursos
        return $data;
    }

    // Método para verificar centros de trabalho associados a um usuário
    public function executeThirdyQuery($cid)
    {
        // Consulta SQL para obter dados do usuário
        $sql = "
        SELECT ZHI_FILIAL, ZHI_CTRAB, ZHI_USUARI, ZHI_STATUS
        FROM ZHI020 ZHI
        WHERE ZHI_USUARI = ?
        AND ZHI.D_E_L_E_T_ = ''
        ";
        $params = array($cid); // Parâmetro: ID do usuário
        $stmt = sqlsrv_query($this->conn, $sql, $params);

        // Armazena os resultados em um array
        $data = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $data[] = [
                'ZHI_CTRAB' => $row['ZHI_CTRAB'],   // Centro de trabalho
                'ZHI_STATUS' => $row['ZHI_STATUS'], // Status
            ];
        }
        // Retorna os dados ou "B" se não houver resultados
        if ($data) {
            return $data;
        } else {
            return "B";
        }
    }

    // Método para obter a soma da quantidade apontada
    public function executeFourthQuery($var1)
    {
        // Extrai partes específicas do código de barras ($var1)
        $ZCB_LOTE = substr($var1, 1, 6);   // Lote
        $ZCB_NUM = substr($var1, 7, 6);    // Número
        $ZCB_ITEM = substr($var1, 13, 2);  // Item
        $ZCB_SEQUEN = substr($var1, 15, 3); // Sequência
        $ZCB_PARTE = substr($var1, 18, 3);  // Parte
        $ZHF_OPERAC = substr($var1, 21, 2); // Operação

        // Consulta SQL para somar a quantidade apontada
        $sql = "SELECT SUM(ZHG_QUANT) ZHG_QUANT
            FROM ZHG020 ZHG 
            WHERE D_E_L_E_T_=''
            AND ZHG_LOTE   = ? 
            AND ZHG_NUM    = ? 
            AND ZHG_ITEM   = ? 
            AND ZHG_SEQUEN = ? 
            AND ZHG_PARTE  = ? 
            AND ZHG_OPERAC = ?";
        $params = array($ZCB_LOTE, $ZCB_NUM, $ZCB_ITEM, $ZCB_SEQUEN, $ZCB_PARTE, $ZHF_OPERAC);
        $stmt = sqlsrv_query($this->conn, $sql, $params);

        // Verifica se houve erro na execução
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        // Retorna o resultado como array associativo
        $result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        return $result;
    }

    // Método para listar apontamentos de um usuário em um intervalo de datas
    public function executeFifthQuery($usuario, $dataini, $datafim)
    {
        // Consulta SQL para obter detalhes dos apontamentos
        $sql = "SELECT ZHG_DATA, ZHG_HORA, ZHG_NOME, ZHG_NOMECT, ZHG_RECURS, ZHG_LOTE, ZHG_NUM, ZHG_ITEM, ZHG_SEQUEN, ZHG_PARTE 
        FROM ZHG020 WHERE D_E_L_E_T_ = '' AND ZHG_USUARI = ? AND ZHG_DATA BETWEEN ? AND ?";
        $params = array($usuario, $dataini, $datafim); // Parâmetros: usuário, data inicial e final
        $stmt = sqlsrv_query($this->conn, $sql, $params);

        // Armazena os resultados em um array
        $data = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $data[] = [
                'ZHG_DATA' => $row['ZHG_DATA'],     // Data
                'ZHG_HORA' => $row['ZHG_HORA'],     // Hora
                'ZHG_NOME' => $row['ZHG_NOME'],     // Nome
                'ZHG_NOMECT' => $row['ZHG_NOMECT'], // Nome do centro de trabalho
                'ZHG_RECURS' => $row['ZHG_RECURS'], // Recurso
                'ZHG_LOTE' => $row['ZHG_LOTE'],     // Lote
                'ZHG_NUM' => $row['ZHG_NUM'],       // Número
                'ZHG_ITEM' => $row['ZHG_ITEM'],     // Item
                'ZHG_SEQUEN' => $row['ZHG_SEQUEN'], // Sequência
                'ZHG_PARTE' => $row['ZHG_PARTE']    // Parte
            ];
        }
        // Retorna o array com os apontamentos
        return $data;
    }

    // Método para fechar a conexão com o banco
    public function closeConnection()
    {
        sqlsrv_close($this->conn); // Encerra a conexão
    }
}

// Instancia a classe e obtém a lista de recursos
$dbQuery = new DatabaseQuery();
$recursos = $dbQuery->executeSecondQuery(); // Executa a segunda consulta
$dbQuery->closeConnection(); // Fecha a conexão

// Verifica se a requisição é POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start(); // Inicia a sessão

    // Ação para consultar dados com base no código de barras
    if ($_POST['action'] == 'fazerConsulta') {
        $var1 = $_POST['codigoBarra']; // Código de barras recebido via POST

        // Nova instância da classe para executar consultas
        $dbQuery = new DatabaseQuery();
        $result = $dbQuery->executeQuery($var1); // Primeira consulta
        $result2 = $dbQuery->executeThirdyQuery($_SESSION['cid']); // Terceira consulta (usuário)
        $fourthQ = $dbQuery->executeFourthQuery($var1); // Quarta consulta (quantidade apontada)
        $result3 = $fourthQ['ZHG_QUANT']; // Extrai a quantidade apontada

        $dbQuery->closeConnection(); // Fecha a conexão

        // Verifica se o centro de trabalho foi encontrado
        if ($result['ZHF_CTRAB'] == null) {
            $erro = "erro"; // Define mensagem de erro
            echo json_encode($erro); // Retorna o erro em JSON
        } else {
            // Retorna os resultados combinados em JSON
            echo json_encode([
                'OP' => $result,        // Dados da operação
                'USUARIO' => $result2,  // Dados do usuário
                'ZHG_QUANT' => $result3 // Quantidade apontada
            ]);
        }
    }

    // Ação para listar apontamentos
    if ($_POST['action'] == "listarApontamentos") {
        $usuario = $_SESSION['cid']; // ID do usuário da sessão
        $dataini = $_POST['dataini']; // Data inicial
        $datafim = $_POST['datafim']; // Data final

        // Nova instância da classe para listar apontamentos
        $dbQuery = new DatabaseQuery();
        $result = $dbQuery->executeFifthQuery($usuario, $dataini, $datafim); // Quinta consulta
        echo json_encode($result); // Retorna os apontamentos em JSON
    }
}
?>