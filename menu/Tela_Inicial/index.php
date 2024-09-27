<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div id="text">
        <div id="msg">
            <h1>Bem vindo <?php print_r($_SESSION['nome'])?> </h1>
            <p>Através do menu à esquerda, você pode explorar todos os recursos disponíveis.</p>
            <img id="logo">
        </div>
    </div>

    <img id="julio" src="Julio_direito.gif" alt="Imagem do Julio">
</body>

</html>