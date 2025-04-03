<?php
session_start();
require_once '../config.php';
require_once '../classes/Database.php';
require_once '../classes/Usuario.php';

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $senha = trim($_POST["senha"]);

    // Validação do nome
    if (empty($nome) || strlen($nome) < 3) {
        $erro = "O nome deve ter pelo menos 3 caracteres.";
    }
    // Validação do email
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Formato de e-mail inválido.";
    }
    // Validação da senha
    elseif (strlen($senha) < 8 || strlen($senha) > 50) {
        $erro = "A senha deve ter entre 8 e 50 caracteres.";
    } else {
        $usuario = new Usuario();

        // Verifica se o e-mail já está em uso
        if ($usuario->emailExiste($email)) {
            $erro = "Erro ao registrar. O e-mail já está em uso.";
        } else {
            // Registra o usuário (REMOVI O HASH AQUI)
            if ($usuario->registrar($nome, $email, $senha)) {
                header("Location: Login.php");
                exit;
            } else {
                $erro = "Erro ao registrar. Tente novamente.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <a href="../index.php">Início</a>
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
    <h2>Registro</h2>

    <?php if (!empty($erro)): ?>
        <p class="erro"><?php echo htmlspecialchars($erro); ?></p>
    <?php endif; ?>

    <form method="POST" class="form">
        <label>Nome:</label>
        <input type="text" name="nome" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Senha:</label>
        <input type="password" name="senha" required>

        <button type="submit" class="btn-registrar">Registrar</button>
    </form>
</div>

</body>
</html>
