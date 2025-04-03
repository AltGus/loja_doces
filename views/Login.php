<?php 
session_start();

// Se o usuário já está logado, redireciona para Usuario.php
if (isset($_SESSION['usuario_id'])) {
    header("Location: Usuario.php");
    exit();
}

require_once '../config.php';
require_once '../classes/Database.php';
require_once '../classes/Usuario.php';

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];

    // Validação da senha
    if (strlen($senha) < 8 || strlen($senha) > 50) {
        $erro = "A senha deve ter entre 8 e 50 caracteres.";
    } else {
        $usuario = new Usuario();
        if ($usuario->login($email, $senha)) {
            // Redireciona para Usuario.php após login bem-sucedido
            header("Location: Usuario.php");
            exit;
        } else {
            $erro = "E-mail ou senha incorretos.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <a href="../index.php">Início</a>
    <a href="Usuario.php">Usuario</a>
    <a href="Produtos.php">Produtos</a>
    <a href="Carrinho.php">Carrinho</a>

    <?php if (isset($_SESSION['usuario_id'])): ?>
        <a href="Usuario.php">Meu Perfil</a>
        <a href="Logout.php">Sair</a>
    <?php else: ?>
        <a href="Login.php">Login</a>
        <a href="Registro.php">Registro</a>
    <?php endif; ?>
</nav>

<div class="form-container">
    <h2>Login</h2>

    <?php if (!empty($erro)): ?>
        <p class="erro"><?php echo $erro; ?></p>
    <?php endif; ?>

    <form method="POST" class="form">
        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Senha:</label>
        <input type="password" name="senha" required>

        <button type="submit" class="btn-login">Entrar</button>
    </form>
</div>

</body>
</html>
