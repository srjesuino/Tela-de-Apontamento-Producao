<?php
session_start();

if (!isset($_SESSION['nome'])) {
    header("Location: ../index.php");
    exit();
}
require 'consulta.php';
if (isset($_POST['Logout'])) {
    session_destroy();
    header("Location: ../index.php");
}
?>
<html lang="pt-br">

<head>
    <title>Apontamento de Produção</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="apontamento.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="script.js"></script>
    <script src="instascan.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Apontamento de Produção</h2>
        </div>
        <div class="panel">
            <div class="header-panel">
                <input type="text" id="codigoBarra" placeholder="Escaneie o código de barras"
                    onchange="fazerConsulta()">
            </div>
            <div id="erro">
                <div class="alert alert-danger" role="alert" id="erro-div">OP Digitada é invalida.</div>
            </div>
            <div class="row-panel">
                <div id="line1">
                    <h1>Centro de Trabalho:</h1>
                    <label id="ctrab">*****</label>
                    <label id="nome"></label>
                </div>
                <div id="line2">
                    <h1>Centro de Custo:</h1>
                    <label id="cc">*****</label>
                    <label id="desc"></label>
                </div>
                <div id="line3">
                    <h1>UN de Medida:</h1>
                    <label id="um">*****</label>
                    <h1>Quantidade</h1>
                    <label id="quant">*****</label>
                    <h1>Recurso:</h1>
                    <div id="customDropdown">
                        <input type="text" id="filterInput" onkeyup="filterFunction()" onclick="toggleDropdown()"
                            placeholder="Digite para filtrar">
                        <div id="dropdownOptions" class="dropdown-options">
                            <?php foreach ($recursos as $index => $recurso) { ?>
                                <div class="option" onclick="selectOption(this)"
                                    data-value="<?php echo htmlspecialchars($recurso['H1_CODIGO']); ?>"
                                    data-descri="<?php echo htmlspecialchars($recurso['H1_DESCRI']); ?>">
                                    <?php echo ($index + 1) . '. ' . htmlspecialchars($recurso['H1_CODIGO']) . ' - ' . htmlspecialchars($recurso['H1_DESCRI']); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div id="nomeRecurso"></div>
                    </div>
                </div>
            </div>
            <video id="qr-reader"></video>
        </div>
    </div>
    <label id="copy">&copy;Madepar Indústria e Comércio de Madeiras LTDA. 2024.</label>
</body>

</html>


<script>
    function isMobile() {
        return /Mobi|Android/i.test(navigator.userAgent);
    }

    // Mostrar o elemento da câmera se for um dispositivo móvel
    if (isMobile()) {
        document.getElementById('qr-reader').style.display = 'block';

        let scanner = new Instascan.Scanner({ video: document.getElementById('qr-reader') });
        scanner.addListener('scan', function (content) {
            document.getElementById('codigoBarra').value = content;
            fazerConsulta()
        });

        Instascan.Camera.getCameras().then(function (cameras) {
            if (cameras.length > 0) {

                let backCamera = cameras.find(camera => camera.name.toLowerCase().includes('back'));
                if (backCamera) {
                    scanner.start(backCamera);
                } else {
                    scanner.start(cameras[0]); // Usa a primeira câmera se a traseira não for encontrada
                }
            } else {
                console.error('Nenhuma câmera encontrada.');
            }
        }).catch(function (e) {
            console.error(e);
        });
    }
</script>