<?php
session_start();
require 'login/login.php';
// Inicializa a variável $erro para evitar o aviso
$erro = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica se os campos de username e password foram preenchidos
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (!empty($username) && !empty($password)) {
        $login = new Login();
        $resultado = $login->validaUsuario($username, $password);
        if ($resultado) {
            $_SESSION['nome'] = $resultado['nome'];
            $_SESSION['cid'] = $resultado['cid'];
            header("Location: menu/menu.php"); // Redireciona para a tela após o login
            exit();
        } else {
            $erro = "Usuário ou senha inválidos.";
        }
    } else {
        $erro = "Por favor, preencha todos os campos.";
    }
}
?>

<html>

<head lang="pt-br">
    <title>Login</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="login/login.css">
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
</head>

<body>
    <form method="POST" action="">
        <div id="welcome">
            <img src="login/LOGO MADEPAR NOVA.png">
            <div id="welcome-container">
                <h5>SERVIÇOS MADEPAR</h5>
            </div>
            <div>
                <label style="color: #555;">&copy;Madepar Indústria e Comércio de Madeiras LTDA. 2024.</label>
            </div>
        </div>
        <div id="form-container">
            <input type="text" name="username" placeholder="Usuário" required>
            <div id="senha"><input id="senha-input" type="password" name="password" placeholder="Senha" required><span class="lnr lnr-eye"></span></div>
            <input class="button-3" role="button" type="submit" value="Entrar">
            <?php if ($erro): ?>
                <div style="margin-bottom: 0; margin-top:20px;" class="alert alert-danger"><?php echo $erro; ?></div>
            <?php endif; ?>
        </div>
    </form>
</body>
</html>
<script>
    let btn = document.querySelector('.lnr-eye');

    btn.addEventListener('click', function() {

    let input = document.querySelector('#senha-input');

    if(input.getAttribute('type') == 'password') {
        input.setAttribute('type', 'text');
    } else {
        input.setAttribute('type', 'password');
    }

});
</script>