<?php
session_start();
if (!isset($_SESSION['nome'])) {
    header("Location: ../index.php");
    exit();
}
if (isset($_POST['Logout'])) {
    session_destroy();
    header("Location: ../index.php");
}
require '../apontamento/consulta.php';
$dbQuery = new DatabaseQuery();
$status = $dbQuery->executeThirdyQuery($_SESSION['cid']);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Madepar</title>
    <link rel="stylesheet" type="text/css" href="menu.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap"
        rel="stylesheet">
    <script src="script.js"></script>

</head>

<body>
    <div id="sidebar">
        <div>
            <img src="logo-bola.png" alt="logo" id="logo">
        </div>
        <!-- OPÇÕES -->
        <div id="options">
            <a href="tela_Inicial/index.php" target="conteudoFrame" id="optionHome">
                <div class="option">
                    Home
                </div>
            </a>
            <?php if ($status == 'A') { ?>
                <a href="../apontamento/apontamento.php" target="conteudoFrame" id="optionAponta">
                    <div class="option">
                        Apontamento
                    </div>
                </a>
            <?php } ?>
            <a href="http://192.168.10.191" target="_blank" id="optionChamados">
                <div class="option">
                    Chamados TI
                </div>
            </a>

            <a href="https://docs.google.com/spreadsheets/d/1PhLK3syyhPXGQ_ZjwqYMr7V1xY5shw49myfyFRpWyEs/edit?usp=sharing"
                target="conteudoFrame" id="optionContatos">
                <div class="option">
                    Contatos
                </div>
            </a>

            <a href="https://email.platonic.cloud/interface/root#/login" target="_blank" id="optionWebmail">
                <div class="option">
                    WebMail
                </div>
            </a>

            <div class="option" onclick="expandeAba('subOptionsSalas', 'optionSala')" id="optionSala">
                Salas de Reunião
            </div>


            <div class="option" onclick="expandeAba('subOptionsVeiculos', 'optionVeiculos')" id="optionVeiculos">
                Veículos
            </div>

            <div class="option" onclick="expandeAba('subOptionsSolicita', 'optionSolicita')" id="optionSolicita">
                Solicitações
            </div>
            <!-- OPÇÕES -->
            <!-- SUBOPÇÕES -->
            <div class="subOptions" id="subOptionsVeiculos">
                <a href="https://forms.gle/pZkmNx7gMhyirKRY9" target="conteudoFrame">
                    <div class="subOption">
                        Agendamento
                    </div>
                </a>
                <a href="https://calendar.google.com/calendar/u/0/embed?src=madeparcontrole@gmail.com&ctz=America/Sao_Paulo"
                    target="conteudoFrame">
                    <div class="subOption">
                        Consulta
                    </div>
                </a>
            </div>
            <div class="subOptions" id="subOptionsSalas">
                <a href="https://forms.gle/dzvWPUp6MV6aEwpA9" target="conteudoFrame">
                    <div class="subOption">
                        Agendamento
                    </div>
                </a>
                <a href="https://abre.ai/aoqV" target="conteudoFrame">
                    <div class="subOption">
                        Consulta
                    </div>
                </a>
            </div>
            <div class="subOptions" id="subOptionsSolicita">
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSdLHk0RNk3b3OXKpL_57FuOxmZkTIf07BMXG71jI1H2M3Kd7w/viewform"
                    target="conteudoFrame">
                    <div class="subOption">
                        Cadastro
                        Fornecedor Cliente
                    </div>
                </a>
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSfq2WXP6jCYmEujpdRJCbus9fwuDGAhzqHMOf7Wtiy0r0CNzA/viewform"
                    target="conteudoFrame">
                    <div class="subOption">
                        Cadastro de Produto
                    </div>
                </a>
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSceMrj4hKP34zHZcJEVAhwPX5nBRyDAgMESm1vO1LRxWRVeEw/formResponse"
                    target="conteudoFrame">
                    <div class="subOption">
                        Cadastro Transportador
                    </div>
                </a>
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSfS8_4zMW0YOhccC4TMsXBy7BwSUjXDMiQXfd8hGn8cg3HAxQ/viewform"
                    target="conteudoFrame">
                    <div class="subOption">
                        NF Devolução
                    </div>
                </a>
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSexHCZoXJaH3J8gyFPLoRJY2aJItywn664m04FmT6I-boV1NQ/viewform"
                    target="conteudoFrame">
                    <div class="subOption">
                        NF Remessa
                    </div>
                </a>
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSdAVJ-hiQFoz7Ud4y6L37F5GjT4RHR-8UcPMZibXsGSEChH5g/viewform?vc=0&c=0&w=1&flr=0"
                    target="conteudoFrame">
                    <div class="subOption">
                        NF Toras
                    </div>
                </a>
                <a href="https://docs.google.com/forms/d/e/1FAIpQLScCzdVjgwUoPsHXZ95gKwc-dn5cdjH_0hZnWnCSt8PvwhFayg/viewform?usp=sf_link"
                    target="conteudoFrame">
                    <div class="subOption">
                        NF Vendas Diversas
                    </div>
                </a>
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSeiP7xDbO40-35slY0qVZk6FiqpsThQlxLmgqXGKioPkkcAJA/viewform"
                    target="conteudoFrame">
                    <div class="subOption">
                        NF Mercado Interno
                    </div>
                </a>
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSe_Ei-nW2t26ozmytW_SAdFOr8QkYLM_3Jidu4jA7H2GimLVA/viewform"
                    target="conteudoFrame">
                    <div class="subOption">
                        Recusa e Cancelamento NFe
                    </div>
                </a>
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSfZiuxXWu_cJuHVBLPbCb1Qn7WJypmKAc2Te09DQcy1lsoZ6A/viewform"
                    target="conteudoFrame">
                    <div class="subOption">
                        Carta Correção NFe
                    </div>
                </a>
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSfIa-YpdLmITaFwu_7lsxz_YvlDhUL9BIpa7Lqtw2Edf1unzg/viewform"
                    target="conteudoFrame">
                    <div class="subOption">
                        Condição de Pagamento
                    </div>
                </a>
                <a href="https://docs.google.com/forms/d/e/1FAIpQLScSkPrDiMcadKLjgfO7RErg0OlCrltR9zAGlQXIH3A7qnRPfQ/viewform"
                    target="conteudoFrame">
                    <div class="subOption">
                        Exceção Janela Fiscal
                    </div>
                </a>
                <a href="https://docs.google.com/forms/d/1jRWyAHb3sFSISEcDVNKkPrJ4oOMDnOj8qteJ10C1BVY/viewform?edit_requested=true"
                    target="conteudoFrame">
                    <div class="subOption">
                        Entrada Portaria
                    </div>
                </a>
            </div>
            <!-- SUBOPÇÕES -->
        </div>

        <div>
            <form id="login" method="POST">
                <div id="data-login"><?php print_r($_SESSION['nome']) ?> - <?php print_r($_SESSION['cid']) ?></div>
                <button type="submit" name="Logout" id="logout"><img src="logout-svgrepo-com.png" alt=""></button>
            </form>
        </div>
    </div>

    <div id="conteudo">
        <iframe id="conteudoFrame" name="conteudoFrame"></iframe>
    </div>

</body>

</html>