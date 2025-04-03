<?php
require_once '../classes/Carrinho.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_produto'])) {
    $id_produto = filter_var($_POST['id_produto'], FILTER_VALIDATE_INT);

    if ($id_produto && Carrinho::adicionarProduto($id_produto)) {
        echo "Produto adicionado ao carrinho!";
    } else {
        echo "Erro ao adicionar produto.";
    }
} else {
    echo "Requisição inválida.";
}
?>
