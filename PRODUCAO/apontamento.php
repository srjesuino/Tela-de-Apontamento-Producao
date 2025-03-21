<?php
// Inicia a sessão para gerenciar dados do usuário
session_start();

// Verifica se o usuário está autenticado (se 'cid' não está na sessão)
if (!isset($_SESSION['cid'])) {
    header("Location: ../../index.php"); // Redireciona para a página de login se não autenticado
    exit(); // Encerra a execução do script
}

// Inclui o arquivo 'consulta.php' que provavelmente contém funções ou dados necessários
require 'consulta.php';

// Verifica se o botão de logout foi acionado
if (isset($_POST['Logout'])) {
    session_destroy(); // Destroi a sessão, encerrando o login
    header("Location: ../index.php"); // Redireciona para a página inicial
}
?>
<html lang="pt-br">

<head>
    <!-- Define o título da página -->
    <title>Apontamento de Produção</title>
    <!-- Define a codificação de caracteres como UTF-8 -->
    <meta charset="UTF-8">
    <!-- Inclui o arquivo CSS personalizado -->
    <link rel="stylesheet" type="text/css" href="apontamento.css">
    <!-- Inclui o CSS do Bootstrap para estilização -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Define o viewport para responsividade em dispositivos móveis -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Inclui o arquivo JavaScript personalizado -->
    <script src="script.js"></script>
    <!-- Inclui a biblioteca Instascan para leitura de QR codes -->
    <script src="instascan.min.js"></script>
</head>

<body>
    <!-- Contêiner principal da interface -->
    <div class="container">
        <!-- Cabeçalho da página -->
        <div class="header">
            <h2>Apontamento de Produção</h2> <!-- Título exibido -->
        </div>
        <!-- Painel principal -->
        <div class="panel">
            <!-- Cabeçalho do painel com campo de entrada -->
            <div class="header-panel">
                <!-- Campo para escanear ou digitar o código de barras -->
                <input type="text" id="codigoBarra" placeholder="Escaneie o código de barras" onchange="fazerConsulta()"
                    maxlength="24">
            </div>
            <!-- Área para mensagens de erro ou sucesso -->
            <div id="erro">
                <!-- Mensagem de erro (oculta por padrão no CSS) -->
                <div class="alert alert-danger" role="alert" id="erro-div"></div>
                <!-- Mensagem de sucesso (oculta por padrão no CSS) -->
                <div class="alert alert-success" role="alert" id="sucess-div">Apontado com Sucesso</div>
            </div>
            <!-- Linhas de informações -->
            <div class="row-panel">
                <!-- Linha 1: Centro de Trabalho e Quantidade Apontada -->
                <div id="line1">
                    <label class="dados">Centro de Trabalho:</label>
                    <label id="ctrab">*****</label> <!-- Placeholder inicial -->
                    <label id="nome"></label> <!-- Nome associado ao centro de trabalho -->
                    <label class="dados" id="qntderesttxt">Quantidade Apontada:</label>
                    <label id="qntdeapnt">*****</label> <!-- Placeholder inicial -->
                </div>
                <!-- Linha 2: Centro de Custo e Quantidade Restante -->
                <div id="line2">
                    <label class="dados">Centro de Custo:</label>
                    <label id="cc">*****</label> <!-- Placeholder inicial -->
                    <label id="desc"></label> <!-- Descrição associada -->
                    <label class="dados" id="rest">Restante:</label>
                    <label id="quant">*****</label> <!-- Placeholder inicial -->
                </div>
                <!-- Linha 3: Unidade de Medida e Recurso -->
                <div id="line3">
                    <label class="dados" id="text-label">UN de Medida:</label>
                    <label id="um">*****</label> <!-- Placeholder inicial -->
                    <label class="dados" id="text-label">Recurso:</label>
                    <!-- Dropdown personalizado para selecionar recursos -->
                    <div id="customDropdown">
                        <!-- Campo de entrada para filtrar opções -->
                        <input type="text" id="filterInput" onkeyup="filterFunction()" onclick="toggleDropdown()"
                            placeholder="Digite para filtrar">
                        <!-- Lista de opções do dropdown -->
                        <div id="dropdownOptions" class="dropdown-options">
                            <?php foreach ($recursos as $index => $recurso) { ?> <!-- Loop pelos recursos do PHP -->
                                <!-- Cada opção do dropdown -->
                                <div class="option" onclick="selectOption(this)"
                                    data-value="<?php echo htmlspecialchars($recurso['H1_CODIGO']); ?>"
                                    data-descri="<?php echo htmlspecialchars($recurso['H1_DESCRI']); ?>">
                                    <?php echo ($index + 1) . '. ' . htmlspecialchars($recurso['H1_CODIGO']) . ' - ' . htmlspecialchars($recurso['H1_DESCRI']); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <!-- Exibe o recurso selecionado -->
                        <div id="nomeRecurso"></div>
                    </div>
                </div>
                <!-- Botões de ação -->
                <div id="botoes" style="width: 100%; display: flex">
                    <!-- Botão para abrir a câmera (oculto por padrão) -->
                    <button id="abrir-camera" class="btn btn-secondary" style="display:none;"
                        onclick="abrirCamera()">Acessar Câmera</button>
                    <div style="margin-left: auto;"> <!-- Alinha botões à direita -->
                        <!-- Botão para listar apontamentos -->
                        <button id="listarapontamento" onclick="listarApontamentos('listarapontamentos')" type="button"
                            class="btn btn-secondary">Listar Apontamentos</button> 
                        <!-- Botão para realizar o apontamento -->
                        <button id="apontar" usuario="<?php echo $_SESSION['cid']; ?>" onclick="apontar()" type="submit"
                            class="btn btn-primary" style="background-color:#3FB09D">Apontar</button>
                    </div>
                </div>
            </div>
            <!-- Contêiner da lista de apontamentos (oculto por padrão) -->
            <div id="container-lista"
                style="display:none; position: fixed; top: 0; left: 0; width: 100vw; height: calc(var(--vh, 1vh) *100); background-color: rgba(0, 0, 0, 0.8); z-index: 1000; align-items: center; justify-content:center">
                <div id="lista-apontamentos">
                    <!-- Área de pesquisa -->
                    <div id="pesquisa">
                        <!-- Botão para voltar -->
                        <button style="margin-right: 20px;" id="voltar" class="btn btn-danger" onclick="sairLista()">Voltar</button>
                        Inicio
                        <input type="date" id="inicio"> <!-- Campo de data inicial -->
                        Fim
                        <input type="date" id="fim"> <!-- Campo de data final -->
                        <!-- Botão para pesquisar -->
                        <button class="btn btn-success" style="margin-left: 20px" onclick="listarApontamentos('pesquisa')">Pesquisar</button>
                    </div>
                    <!-- Tabela de apontamentos -->
                    <div id="tabela">
                        <table>
                            <thead>
                                <!-- Cabeçalho da tabela -->
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
                                <!-- Corpo da tabela, preenchido via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Overlay da câmera (oculto por padrão) -->
            <div id="camera-overlay"
                style="display:none; position: fixed; top: 0; left: 0; width: 100vw; height: calc(var(--vh, 1vh) *100); background-color: rgba(0, 0, 0, 0.8); z-index: 1000; align-items: center; justify-content:center">
                <!-- Botão para fechar a câmera -->
                <button id="fecharCam" type="button" onclick="fecharCamera()" class="btn btn-danger">Sair da camera</button>
                <!-- Elemento de vídeo para leitura de QR code -->
                <video id="qr-reader" style="width: 480px; height: 480px;"></video>
            </div>
        </div>
    </div>
    <!-- Rodapé com direitos autorais -->
    <label id="copy">©Madepar Indústria e Comércio de Madeiras LTDA. 2024.</label>

    <!-- Scripts JavaScript -->
    <script>
        // Função para verificar se o dispositivo é móvel
        function isMobile() {
            return /Mobi|Android/i.test(navigator.userAgent); // Testa o user agent
        }

        // Se for dispositivo móvel, exibe o botão de câmera
        if (isMobile()) {
            document.getElementById('abrir-camera').style.display = 'block';
        }

        // Função para abrir a câmera e escanear QR code
        function abrirCamera() {
            document.getElementById('camera-overlay').style.display = 'flex'; // Exibe o overlay
            document.getElementById('qr-reader').style.display = "block"; // Exibe o vídeo
            let scanner = new Instascan.Scanner({ video: document.getElementById('qr-reader') }); // Inicializa o scanner
            // Listener para quando um QR code é escaneado
            scanner.addListener('scan', function (content) {
                document.getElementById('codigoBarra').value = content; // Preenche o campo com o conteúdo
                fazerConsulta(); // Chama a função de consulta
                fecharCamera(); // Fecha a câmera
                scanner.stop(); // Para o scanner
            });
            // Obtém as câmeras disponíveis
            Instascan.Camera.getCameras().then(function (cameras) {
                if (cameras.length > 0) {
                    // Procura pela câmera traseira
                    let backCamera = cameras.find(camera => camera.name.toLowerCase().includes('back'));
                    if (backCamera) {
                        scanner.start(backCamera); // Usa a câmera traseira
                    } else {
                        scanner.start(cameras[0]); // Usa a primeira câmera disponível
                    }
                } else {
                    console.error('Nenhuma câmera encontrada.'); // Erro se não houver câmeras
                }
            }).catch(function (e) {
                console.error(e); // Trata erros
            });
        }

        // Função para fechar a câmera
        function fecharCamera() {
            document.getElementById('camera-overlay').style.display = 'none'; // Oculta o overlay
            document.getElementById('qr-reader').style.display = 'none'; // Oculta o vídeo
        }
    </script>
</body>

</html>