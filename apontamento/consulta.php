<?php
class DatabaseQuery
{
    private $servername = "192.168.10.195";
    private $username = "sa";
    private $password = "Telstar#18";
    private $dbname = "DADOSADV_R2310";
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
        LEFT JOIN 
            SHB020 SHB ON HB_FILIAL = ZHF_FILIAL 
            AND HB_COD = ZHF_CTRAB
        LEFT JOIN 
            CTT020 CTT ON CTT_FILIAL = '' 
            AND CTT_CUSTO = ZHF_CC 
        WHERE 
            ZCB_LOTE = ?
            AND ZCB_NUM = ?
            AND ZCB_ITEM = ?
            AND ZCB_SEQUEN = ?
            AND ZCB_PARTE = ?
            AND ZHF_OPERAC = ?
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
        $data = null;
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($row != null) {
            $data = $row['ZHI_STATUS'];
            return $data;
        }
        else {
            return 'B';
        }
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

    $var1 = $_POST['codigoBarra'];

    $dbQuery = new DatabaseQuery();
    $result = $dbQuery->executeQuery($var1);
    $dbQuery->closeConnection();
    if ($result['ZHF_CTRAB'] == null) {
        $erro = "erro";
        echo json_encode($erro);
    } else {
        // Retorna os resultados como JSON
        echo json_encode($result);
    }
}
?>