<?php
require_once __DIR__ . '/../classes/Produto.php';
require_once __DIR__ . '/../classes/Carrinho.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Produto não encontrado.");
}

$idProduto = (int)$_GET['id'];
$produto = Produto::buscarPorId($idProduto);

if (!$produto) {
    die("Produto não encontrado.");
}

// Adicionar ao carrinho caso o formulário seja enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    Carrinho::adicionarProduto($idProduto);
    header("Location: Carrinho.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($produto['nome']); ?></title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php session_start(); ?>

<!-- Navbar -->
<nav class="navbar">
    <a href="../index.php">Início</a>
    <a href="Usuario.php">Usuario</a>
    <a href="Produtos.php">Produtos</a>
    <a href="Carrinho.php">Carrinho</a>

    <?php if (isset($_SESSION['usuario_id'])): ?>
        <a href="Usuario.php">Meu Perfil</a> <!-- Se logado, exibe 'Meu Perfil' -->
        <a href="Logout.php">Sair</a> <!-- Adiciona opção para logout -->
    <?php else: ?>
        <a href="Login.php">Login</a> <!-- Se não logado, exibe 'Login' -->
        <a href="Registro.php">Registro</a>
    <?php endif; ?>
</nav>


<div class="produto-detalhes">
    <h2><?php echo htmlspecialchars($produto['nome']); ?></h2>
    <img src="<?php echo htmlspecialchars($produto['imagem']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>" class="produto-imagem">
    
    <p class="descricao"><?php echo htmlspecialchars($produto['descricao']); ?></p>
    <p class="preco"><strong>Preço:</strong> R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>

    <!-- Formulário para adicionar ao carrinho -->
    <form method="POST">
        <button type="submit" class="btn-adicionar-carrinho">Adicionar ao Carrinho</button>
    </form>

    <a href="Produtos.php" class="btn-voltar">Voltar</a>
</div>

</body>
</html>
