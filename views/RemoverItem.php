<?php
session_start();
require_once "../classes/Carrinho.php";

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id_produto"])) {
    $idProduto = (int) $_POST["id_produto"];

    if (Carrinho::removerProduto($idProduto)) {
        echo json_encode(["success" => true, "message" => "Item removido com sucesso"]);
    } else {
        echo json_encode(["success" => false, "message" => "Produto não encontrado no carrinho"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Requisição inválida"]);
}
exit;
?>
