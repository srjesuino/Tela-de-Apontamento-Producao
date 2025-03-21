<?php
session_start();

if (!isset($_SESSION['cid'])) {
    header("Location: ../../index.php");
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
                <input type="text" id="codigoBarra" placeholder="Escaneie o código de barras" onchange="fazerConsulta()"
                    maxlength="24">
            </div>
            <div id="erro">
                <div class="alert alert-danger" role="alert" id="erro-div"></div>
                <div class="alert alert-success" role="alert" id="sucess-div">Apontado com Sucesso</div>
            </div>
            <div class="row-panel">
                <div id="line1">
                    <label class="dados">Centro de Trabalho:</label>
                    <label id="ctrab">*****</label>
                    <label id="nome"></label>
                    <label class="dados" id="qntderesttxt">Quantidade Apontada:</label>
                    <label id="qntdeapnt">*****</label>
                </div>
                <div id="line2">
                    <label class="dados">Centro de Custo:</label>
                    <label id="cc">*****</label>
                    <label id="desc"></label>
                    <label class="dados" id="rest">Restante:</label>
                    <label id="quant">*****</label>
                </div>
                <div id="line3">
                    <label class="dados" id="text-label">UN de Medida:</label>
                    <label id="um">*****</label>
                    <label class="dados" id="text-label">Recurso:</label>
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
                <div id="botoes" style="width: 100%; display: flex">
                    <button id="abrir-camera" class="btn btn-secondary" style="display:none;"
                        onclick="abrirCamera()">Acessar Câmera</button>
                    <div style="margin-left: auto;">
                        <button id="listarapontamento" onclick="listarApontamentos('listarapontamentos')" type="button"
                            class="btn btn-secondary">Listar
                            Apontamentos</button> 
                        <button id="apontar" usuario="<?php echo $_SESSION['cid']; ?>" onclick="apontar()" type="submit"
                            class="btn btn-primary" style="background-color:#3FB09D">Apontar</button>
                    </div>
                </div>
            </div>
            <div id="container-lista"
                style="display:none; position: fixed; top: 0; left: 0; width: 100vw; height: calc(var(--vh, 1vh) *100); background-color: rgba(0, 0, 0, 0.8); z-index: 1000; align-items: center; justify-content:center">
                <div id="lista-apontamentos">
                    <div id="pesquisa">
                        <button style="margin-right: 20px;" id="voltar" class="btn btn-danger" onclick="sairLista()">Voltar</button>
                            Inicio
                            <input type="date" id="inicio">
                            Fim
                            <input type="date" id="fim">
                            <button class="btn btn-success" style="margin-left: 20px" onclick="listarApontamentos('pesquisa')">Pesquisar</button>
                    </div>
                    <div id="tabela">
                        <table>
                            <thead>
                                <th>DATA</th>
                                <th>HORA</th>
                                <th>NOME</th>
                                <th>CENTRO DE TRABALHO</th>
                                <th>RECURSO</th>
                                <th>LOTE</th>
                                <th>NUMERO</th>
                                <th>ITEM</th>
                                <th>SEQUENCIA</th>
                                <th>PARTE</th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div id="camera-overlay"
                style="display:none; position: fixed; top: 0; left: 0; width: 100vw; height: calc(var(--vh, 1vh) *100); background-color: rgba(0, 0, 0, 0.8); z-index: 1000; align-items: center; justify-content:center">
                <button id="fecharCam" type="button" onclick="fecharCamera()" class="btn btn-danger">Sair da
                    camera</button>
                <video id="qr-reader" style="width: 480px; height: 480px;"></video>
            </div>
        </div>
    </div>
    <label id="copy">&copy;Madepar Indústria e Comércio de Madeiras LTDA. 2024.</label>

    <script>
        function isMobile() {
            return /Mobi|Android/i.test(navigator.userAgent);
        }

        if (isMobile()) {
            document.getElementById('abrir-camera').style.display = 'block';
        }

        function abrirCamera() {
            document.getElementById('camera-overlay').style.display = 'flex';
            document.getElementById('qr-reader').style.display = "block";
            let scanner = new Instascan.Scanner({ video: document.getElementById('qr-reader') });
            scanner.addListener('scan', function (content) {
                document.getElementById('codigoBarra').value = content;
                fazerConsulta();
                fecharCamera();
                scanner.stop();
            });
            Instascan.Camera.getCameras().then(function (cameras) {
                if (cameras.length > 0) {
                    let backCamera = cameras.find(camera => camera.name.toLowerCase().includes('back'));
                    if (backCamera) {
                        scanner.start(backCamera);
                    } else {
                        scanner.start(cameras[0]);
                    }
                } else {
                    console.error('Nenhuma câmera encontrada.');
                }
            }).catch(function (e) {
                console.error(e);
            });
        }

        function fecharCamera() {
            document.getElementById('camera-overlay').style.display = 'none';
            document.getElementById('qr-reader').style.display = 'none'
        }
    </script>
</body>

</html>