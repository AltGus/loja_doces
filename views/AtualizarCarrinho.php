<?php
session_start();
require_once "../classes/Carrinho.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id_produto"], $_POST["acao"])) {
    $idProduto = (int) $_POST["id_produto"];
    $acao = $_POST["acao"];

    if ($acao === "aumentar") {
        Carrinho::adicionarProduto($idProduto);
    } elseif ($acao === "diminuir") {
        Carrinho::removerUnidade($idProduto);
    }
}
?>
