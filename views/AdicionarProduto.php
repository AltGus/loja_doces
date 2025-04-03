<?php
require_once '../config.php';
require_once '../classes/Database.php';
require_once '../classes/Produto.php';

$erro = "";
$sucesso = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $preco = str_replace(',', '.', trim($_POST['preco'])); // Substitui vírgula por ponto
    $estoque = trim($_POST['estoque']);
    $imagem = trim($_POST['imagem']);

    if (empty($nome) || empty($descricao) || empty($preco) || empty($estoque) || empty($imagem)) {
        $erro = "Todos os campos são obrigatórios.";
    } elseif (!is_numeric($preco) || floatval($preco) <= 0) {
        $erro = "O preço deve ser um número positivo.";
    } elseif (!is_numeric($estoque) || intval($estoque) < 0) {
        $erro = "O estoque deve ser um número inteiro positivo.";
    } else {
        try {
            Produto::adicionar($nome, $descricao, $preco, $estoque, $imagem);
            header("Location: ../views/Produtos.php"); 
            exit;
        } catch (Exception $e) {
            $erro = "Erro ao adicionar o produto: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Produto</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../adicionar-produto.css"> <!-- Novo estilo exclusivo -->
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <a href="../index.php">Início</a>
        <a href="../views/Produtos.php">Produtos</a>
        <a href="../views/Carrinho.php">Carrinho</a>
        <a href="../views/Login.php">Login</a>
        <a href="../views/Registro.php">Registro</a>
    </nav>

    <div class="adicionar-produto-container">
        <h2>Adicionar Produto</h2>

        <!-- Mensagem de erro ou sucesso -->
        <?php if (!empty($erro)): ?>
            <p class="erro"><?php echo $erro; ?></p>
        <?php elseif (!empty($sucesso)): ?>
            <p class="sucesso"><?php echo $sucesso; ?></p>
        <?php endif; ?>

        <form method="POST" class="adicionar-produto-form">
            <div class="adicionar-produto-input-group">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>
            </div>

            <div class="adicionar-produto-input-group">
                <label for="descricao">Descrição:</label>
                <textarea id="descricao" name="descricao" required></textarea>
            </div>

            <div class="adicionar-produto-input-group">
                <label for="preco">Preço:</label>
                <input type="text" id="preco" name="preco" required pattern="^\d+([,\.]\d{1,2})?$" title="Digite um número válido (ex: 10,50 ou 10.50)">
            </div>

            <div class="adicionar-produto-input-group">
                <label for="estoque">Estoque:</label>
                <input type="number" id="estoque" name="estoque" required min="0">
            </div>

            <div class="adicionar-produto-input-group">
                <label for="imagem">URL da Imagem:</label>
                <input type="text" id="imagem" name="imagem" required>
            </div>

            <button type="submit" class="btn-adicionar-produto">Adicionar Produto</button>
        </form>

        <!-- Botão para voltar à lista de produtos -->
        <a href="../views/Produtos.php" class="btn-voltar-adicionar-produto">Ver lista de produtos</a>
    </div>

</body>
</html>
