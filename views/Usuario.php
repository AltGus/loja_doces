<?php 
session_start();

// Verifica se o usuário está logado, caso contrário, redireciona para a página de login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: Login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Conta - Loja de Doces</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <a href="../index.php">Início</a>
    <a href="Produtos.php">Produtos</a>
    <a href="Carrinho.php">Carrinho</a>
    <?php if (isset($_SESSION['usuario_id'])): ?>
        <a href="User.php">Meu Perfil</a>
        <a href="Logout.php">Sair</a>
    <?php else: ?>
        <a href="Login.php">Login</a>
        <a href="Registro.php">Registro</a>
    <?php endif; ?>
</nav>

<!-- Saudação ao usuário logado -->
<h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</h1>
<p>Aqui você pode gerenciar sua conta, ver seus pedidos e atualizar suas informações.</p>

</body>
</html>
