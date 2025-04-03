<?php
// Garante que a sessão só será iniciada se ainda não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../classes/Produto.php";
require_once "../classes/Carrinho.php";

// Obtém os itens do carrinho ou inicializa caso não existam
$itens = Carrinho::listarProdutos();
$total = 0;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <link rel="stylesheet" href="../Carrinho.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <a href="../index.php">Início</a>
    <a href="Usuario.php">Usuario</a>
    <a href="Produtos.php">Produtos</a>
    

    <?php if (isset($_SESSION['usuario_id'])): ?>
        <a href="Usuario.php">Meu Perfil</a>
        <a href="Logout.php">Sair</a>
    <?php else: ?>
        <a href="Login.php">Login</a>
        <a href="Registro.php">Registro</a>
    <?php endif; ?>
</nav>

<div class="container">
    <h1>Carrinho de Compras</h1>

    <div id="mensagem" class="mensagem-sucesso" style="display: none;"></div>

    <?php if (empty($itens)): ?>
        <p class="mensagem">Seu carrinho está vazio.</p>
    <?php else: ?>
        <div class="carrinho-container">
            <?php foreach ($itens as $id => $quantidade): 
                $produto = Produto::buscarPorId((int) $id);
                if (!$produto) continue; // Ignora produtos inexistentes no banco

                $subtotal = (float) $produto['preco'] * (int) $quantidade;
                $total += $subtotal;
            ?>
                <div class="carrinho-item" data-id="<?= (int) $id ?>">
                    <img src="<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>" class="produto-imagem">
                    
                    <div class="produto-info">
                        <h2><?= htmlspecialchars($produto['nome']) ?></h2>
                        <p class="preco">Preço: R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
                        <p class="subtotal">Subtotal: R$ <?= number_format($subtotal, 2, ',', '.') ?></p>

                        <div class="quantidade-controle">
                            <button class="btn-menos" data-id="<?= (int) $id ?>">-</button>
                            <span class="quantidade"><?= (int) $quantidade ?></span>
                            <button class="btn-mais" data-id="<?= (int) $id ?>">+</button>
                        </div>

                        <button class="btn-remover" data-id="<?= (int) $id ?>">Remover</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <p class="total-compra"><strong>Total:</strong> R$ <span id="total"><?= number_format($total, 2, ',', '.') ?></span></p>
        <a href="FinalizarCompra.php" class="btn btn-finalizar">Finalizar Compra</a>
    <?php endif; ?>

    <a href="Produtos.php" class="btn btn-voltar">Continuar Comprando</a>
</div>

<script>
    $(document).ready(function() {
        // Atualiza quantidade no carrinho
        $(".btn-mais, .btn-menos").click(function() {
            var idProduto = $(this).data("id");
            var action = $(this).hasClass("btn-mais") ? "aumentar" : "diminuir";

            $.ajax({
                url: "AtualizarCarrinho.php",
                method: "POST",
                data: { id_produto: idProduto, acao: action },
                success: function(response) {
                    location.reload();
                },
                error: function() {
                    alert("Erro ao atualizar o carrinho.");
                }
            });
        });

        // Remove item do carrinho sem recarregar a página
        $(".btn-remover").click(function() {
            var idProduto = $(this).data("id");
            var itemCarrinho = $(this).closest(".carrinho-item");

            $.ajax({
                url: "RemoverItem.php",
                method: "POST",
                data: { id_produto: idProduto },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        itemCarrinho.fadeOut(500, function() {
                            $(this).remove();
                            
                            // Atualiza o total dinamicamente
                            var totalAtual = parseFloat($("#total").text().replace(",", "."));
                            var precoProduto = parseFloat(itemCarrinho.find(".subtotal").text().replace("Subtotal: R$", "").replace(",", "."));
                            var novoTotal = totalAtual - precoProduto;
                            $("#total").text(novoTotal.toFixed(2).replace(".", ","));

                            // Se não houver mais itens, exibe a mensagem de carrinho vazio
                            if ($(".carrinho-item").length === 1) {
                                $(".carrinho-container, .total-compra").fadeOut();
                                $("h1").after('<p class="mensagem">Seu carrinho está vazio.</p>');
                            }
                        });
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert("Erro ao remover o item.");
                }
            });
        });
    });
</script>

</body>
</html>
