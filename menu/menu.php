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

</head>

<body>
    <div id="sidebar">
        <div>
            <img src="logo-bola.png" alt="logo" id="logo">
        </div>
         
        <div id="options">
            <a href="https://docs.google.com/forms/d/e/1FAIpQLSeHHUL-UUYX0WsixinnD3R3-aH14GrjggefIz2R2-WoKK8wUA/viewform"
                target="conteudoFrame" class="option">
                <div>
                    Home
                </div>
            </a>
            <?php if ($status == 'A') {?>
            <a href="../apontamento/apontamento.php" target="conteudoFrame" class="option">
                <div>
                    Apontamento
                </div>
            </a> 
            <?php } ?>
            <a href="http://192.168.10.191" class="option">
                <div>
                    Chamados TI
                </div>
            </a>
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
