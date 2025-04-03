<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja de Doces</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <a href="index.php">Início</a>
    <a href="views/Produtos.php">Ver Produtos</a>

    <?php if (isset($_SESSION['usuario'])): ?>
        <a href="views/Usuario.php">Minha Conta</a>
        <a href="views/Logout.php">Sair</a>
    <?php else: ?>
        <a href="views/Login.php">Login</a>
        <a href="views/Registro.php">Registrar</a>
    <?php endif; ?>
</div>

<h1>Bem-vindo à Loja de Doces!</h1>

</body>
</html>
