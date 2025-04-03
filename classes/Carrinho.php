<?php
class Carrinho {
    public static function iniciarSessao() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // ✅ Adicionar produto ao carrinho
    public static function adicionarProduto($id_produto) {
        self::iniciarSessao();

        $id_produto = filter_var($id_produto, FILTER_VALIDATE_INT);
        if ($id_produto === false || $id_produto <= 0) {
            return false; // ID inválido
        }

        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }

        $_SESSION['carrinho'][$id_produto] = ($_SESSION['carrinho'][$id_produto] ?? 0) + 1;

        return true; // Produto adicionado com sucesso
    }

    // ✅ Listar todos os produtos no carrinho
    public static function listarProdutos() {
        self::iniciarSessao();
        return $_SESSION['carrinho'] ?? [];
    }

    // ✅ Diminuir quantidade de um item no carrinho
    public static function removerUnidade($id_produto) {
        self::iniciarSessao();

        $id_produto = filter_var($id_produto, FILTER_VALIDATE_INT);
        if ($id_produto === false || $id_produto <= 0) {
            return false;
        }

        if (isset($_SESSION['carrinho'][$id_produto])) {
            $_SESSION['carrinho'][$id_produto]--;

            if ($_SESSION['carrinho'][$id_produto] <= 0) {
                unset($_SESSION['carrinho'][$id_produto]);
            }
        }

        return true;
    }

    // ✅ Remover um produto completamente do carrinho
    public static function removerProduto($id_produto) {
        self::iniciarSessao();

        $id_produto = filter_var($id_produto, FILTER_VALIDATE_INT);
        if ($id_produto === false || $id_produto <= 0) {
            return false;
        }

        unset($_SESSION['carrinho'][$id_produto]);

        return true;
    }

    // ✅ Esvaziar carrinho (para finalizar compra)
    public static function esvaziarCarrinho() {
        self::iniciarSessao();
        $_SESSION['carrinho'] = [];
    }
}
?>
