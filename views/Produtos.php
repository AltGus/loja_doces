<?php 
require_once '../config.php';
require_once '../classes/Database.php';
require_once '../classes/Produto.php';

$erro = "";
$produtos = [];

try {
    $produtos = Produto::listarTodos();
} catch (Exception $e) {
    $erro = "Erro ao carregar os produtos.";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Produtos</title>
    <link rel="stylesheet" href="../style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Importando jQuery -->
    <style>
        /* Estilo do Popup */
        .popup {
            display: none;
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #007bff; /* Azul */
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            font-size: 16px;
            animation: fadeIn 0.5s;
        }
        .popup button {
            background: none;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
            position: absolute;
            top: 5px;
            right: 10px;
        }

        /* Animação de entrada */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<?php session_start(); ?>

<!-- Navbar -->
<nav class="navbar">
    <a href="../index.php">Início</a>
    <a href="Usuario.php">Usuario</a>
    <a href="Carrinho.php">Carrinho</a>

    <?php if (isset($_SESSION['usuario_id'])): ?>
        <a href="Usuario.php">Meu Perfil</a> <!-- Se logado, exibe 'Meu Perfil' -->
        <a href="Logout.php">Sair</a> <!-- Adiciona opção para logout -->
    <?php else: ?>
        <a href="Login.php">Login</a> <!-- Se não logado, exibe 'Login' -->
        <a href="Registro.php">Registro</a>
    <?php endif; ?>
</nav>


    <div class="container">
        <h2>Lista de Produtos</h2>

        <!-- Botão para adicionar novo produto -->
        <a href="AdicionarProduto.php" class="btn btn-adicionar">Adicionar Novo Produto</a>

        <?php if (!empty($erro)): ?>
            <p class="erro"><?php echo htmlspecialchars($erro); ?></p>
        <?php elseif (!empty($produtos)): ?>
            <div class="produtos-container">
                <?php foreach ($produtos as $produto): ?>
                    <div class="produto">
                        <a href="Produto.php?id=<?= (int) $produto['id']; ?>">
                            <img src="<?= htmlspecialchars($produto['imagem']); ?>" alt="<?= htmlspecialchars($produto['nome']); ?>">
                        </a>
                        <div class="produto-info">
                            <h3><?= htmlspecialchars($produto['nome']); ?></h3>
                            <p class="produto-preco">R$ <?= number_format($produto['preco'], 2, ',', '.'); ?></p>
                        </div>

                        <!-- Botão para adicionar ao carrinho com AJAX -->
                        <button class="btn-adicionar-carrinho" data-id="<?= (int) $produto['id']; ?>">Adicionar ao Carrinho</button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="erro">Nenhum produto encontrado.</p>
        <?php endif; ?>
    </div>

    <!-- Popup de Adicionado ao Carrinho -->
    <div id="popup" class="popup">
        <button id="fechar-popup">&times;</button>
        <span id="popup-mensagem">Produto adicionado ao carrinho!</span>
    </div>

    <script>
        $(document).ready(function() {
            $(".btn-adicionar-carrinho").click(function() {
                var idProduto = $(this).data("id");

                $.ajax({
                    url: "AdicionarAoCarrinho.php",
                    method: "POST",
                    data: { id_produto: idProduto },
                    success: function(response) {
                        $("#popup-mensagem").text(response);
                        $("#popup").fadeIn();

                        // Esconde automaticamente após 2 segundos
                        setTimeout(function() {
                            $("#popup").fadeOut();
                        }, 2000);
                    },
                    error: function() {
                        alert("Erro ao adicionar ao carrinho.");
                    }
                });
            });

            // Fechar popup manualmente
            $("#fechar-popup").click(function() {
                $("#popup").fadeOut();
            });
        });
    </script>

</body>
</html>
