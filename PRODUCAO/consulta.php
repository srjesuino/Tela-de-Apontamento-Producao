<?php
class DatabaseQuery
{
    private $servername = "192.168.10.194";
    private $username = "consulta_pcp";
    private $password = "hMhkFwLHBpDIp0&N";
    private $dbname = "DADOSADV";
    private $conn;
    public function __construct()
    {
        $connectionOptions = array(
            "Database" => $this->dbname,
            "Uid" => $this->username,
            "PWD" => $this->password
        );
        $this->conn = sqlsrv_connect($this->servername, $connectionOptions);

        if ($this->conn === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    public function executeQuery($var1)
    {
        $ZCB_LOTE = substr($var1, 1, 6);
        $ZCB_NUM = substr($var1, 7, 6);
        $ZCB_ITEM = substr($var1, 13, 2);
        $ZCB_SEQUEN = substr($var1, 15, 3);
        $ZCB_PARTE = substr($var1, 18, 3);
        $ZHF_OPERAC = substr($var1, 21, 2);

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

        $params = array($ZCB_LOTE, $ZCB_NUM, $ZCB_ITEM, $ZCB_SEQUEN, $ZCB_PARTE, $ZHF_OPERAC);
        $stmt = sqlsrv_query($this->conn, $sql, $params);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        $result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($result) {
            $ZHF_CTRAB = $result['ZHF_CTRAB'];
            $HB_NOME = $result['HB_NOME'];
            $ZHF_CC = $result['ZHF_CC'];
            $CTT_DESC01 = $result['CTT_DESC01'];
            $ZCB_UM = $result['ZCB_UM'];
            $ZCB_QUANT = $result['ZCB_QUANT'];
        } else {
            $ZHF_CTRAB = $HB_NOME = $ZHF_CC = $CTT_DESC01 = $ZCB_UM = $ZCB_QUANT = null;
        }

        return [
            'ZHF_CTRAB' => $ZHF_CTRAB,
            'HB_NOME' => $HB_NOME,
            'ZHF_CC' => $ZHF_CC,
            'CTT_DESC01' => $CTT_DESC01,
            'ZCB_UM' => $ZCB_UM,
            'ZCB_QUANT' => $ZCB_QUANT
        ];
    }
    public function executeSecondQuery()
    {
        $sql = "
        SELECT H1_CODIGO, H1_DESCRI 
        FROM SH1020 
        WHERE D_E_L_E_T_ = ''
        ";

        // Executa a consulta
        $stmt = sqlsrv_query($this->conn, $sql);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        // Armazena os resultados em um array
        $data = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $data[] = [
                'H1_CODIGO' => $row['H1_CODIGO'],
                'H1_DESCRI' => $row['H1_DESCRI'],
            ];
        }

        return $data;
    }
    public function executeThirdyQuery($cid)
    {
        $sql = "
    SELECT ZHI_FILIAL, ZHI_CTRAB, ZHI_USUARI, ZHI_STATUS
    FROM ZHI020 ZHI
    WHERE ZHI_USUARI = ?
    AND ZHI.D_E_L_E_T_ = ''
    ";
        $params = array($cid);
        $stmt = sqlsrv_query($this->conn, $sql, $params);
        $data = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $data[] = [
                'ZHI_CTRAB' => $row['ZHI_CTRAB'],
                'ZHI_STATUS' => $row['ZHI_STATUS'],
            ];
        }
        if ($data) {
            return $data;
        } else {
            return "B";
        }
    }
    public function executeFourthQuery($var1)
    {
        $ZCB_LOTE = substr($var1, 1, 6);
        $ZCB_NUM = substr($var1, 7, 6);
        $ZCB_ITEM = substr($var1, 13, 2);
        $ZCB_SEQUEN = substr($var1, 15, 3);
        $ZCB_PARTE = substr($var1, 18, 3);
        $ZHF_OPERAC = substr($var1, 21, 2);
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
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        return $result;
    }
    public function executeFifthQuery($usuario, $dataini, $datafim)
    {
        $sql = "SELECT ZHG_DATA, ZHG_HORA, ZHG_NOME, ZHG_NOMECT, ZHG_RECURS, ZHG_LOTE, ZHG_NUM, ZHG_ITEM, ZHG_SEQUEN, ZHG_PARTE 
        FROM ZHG020 WHERE D_E_L_E_T_ = '' AND ZHG_USUARI = ? AND ZHG_DATA BETWEEN ? AND ?";
        $params = array($usuario, $dataini, $datafim);
        $stmt = sqlsrv_query($this->conn, $sql, $params);
        $data = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $data[] = [
                'ZHG_DATA' => $row['ZHG_DATA'],
                'ZHG_HORA' => $row['ZHG_HORA'],
                'ZHG_NOME' => $row['ZHG_NOME'],
                'ZHG_NOMECT' => $row['ZHG_NOMECT'],
                'ZHG_RECURS' => $row['ZHG_RECURS'],
                'ZHG_LOTE' => $row['ZHG_LOTE'],
                'ZHG_NUM' => $row['ZHG_NUM'],
                'ZHG_ITEM' => $row['ZHG_ITEM'],
                'ZHG_SEQUEN' => $row['ZHG_SEQUEN'],
                'ZHG_PARTE' => $row['ZHG_PARTE']
            ];
        }
        return $data;
    }
    public function closeConnection()
    {
        sqlsrv_close($this->conn);
    }
}
$dbQuery = new DatabaseQuery();
$recursos = $dbQuery->executeSecondQuery();
$dbQuery->closeConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();

    if ($_POST['action'] == 'fazerConsulta') {

        $var1 = $_POST['codigoBarra'];

        $dbQuery = new DatabaseQuery();
        $result = $dbQuery->executeQuery($var1);
        $result2 = $dbQuery->executeThirdyQuery($_SESSION['cid']);
        $fourthQ = $dbQuery->executeFourthQuery($var1);
        $result3 = $fourthQ['ZHG_QUANT'];

        $dbQuery->closeConnection();
        if ($result['ZHF_CTRAB'] == null) {
            $erro = "erro";
            echo json_encode($erro);
        } else {
            echo json_encode([
                'OP' => $result,
                'USUARIO' => $result2,
                'ZHG_QUANT' => $result3
            ]);
        }
    }
    if ($_POST['action'] == "listarApontamentos") {
        $usuario = $_SESSION['cid'];
        $dataini = $_POST['dataini'];
        $datafim = $_POST['datafim'];
        $dbQuery = new DatabaseQuery();
        $result = $dbQuery->executeFifthQuery($usuario, $dataini, $datafim);
        echo json_encode($result);
    }
}
?>