<?php
require_once __DIR__ . '/../classes/Database.php';

class Produto {
    /**
     * Lista todos os produtos do banco de dados.
     */
    public static function listarTodos() {
        try {
            $pdo = Database::conectar();
            $sql = "SELECT * FROM produtos ORDER BY data_adicionado DESC";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar produtos: " . $e->getMessage());
        }
    }

    /**
     * Busca um produto pelo ID.
     */
    public static function buscarPorId($id) {
        try {
            $pdo = Database::conectar();
            $sql = "SELECT * FROM produtos WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar produto: " . $e->getMessage());
        }
    }

    /**
     * Adiciona um novo produto ao banco de dados.
     */
    public static function adicionar($nome, $descricao, $preco, $estoque, $imagem) {
        try {
            $pdo = Database::conectar();
            $sql = "INSERT INTO produtos (nome, descricao, preco, estoque, imagem) 
                    VALUES (:nome, :descricao, :preco, :estoque, :imagem)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nome' => $nome,
                ':descricao' => $descricao,
                ':preco' => $preco,
                ':estoque' => $estoque,
                ':imagem' => $imagem
            ]);
            return $pdo->lastInsertId(); // Retorna o ID do novo produto
        } catch (PDOException $e) {
            throw new Exception("Erro ao adicionar produto: " . $e->getMessage());
        }
    }

    /**
     * Atualiza um produto existente no banco de dados.
     */
    public static function atualizar($id, $nome, $descricao, $preco, $estoque, $imagem) {
        try {
            $pdo = Database::conectar();
            $sql = "UPDATE produtos SET 
                        nome = :nome, 
                        descricao = :descricao, 
                        preco = :preco, 
                        estoque = :estoque, 
                        imagem = :imagem
                    WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':nome' => $nome,
                ':descricao' => $descricao,
                ':preco' => $preco,
                ':estoque' => $estoque,
                ':imagem' => $imagem
            ]);
            return $stmt->rowCount(); // Retorna o número de linhas afetadas
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar produto: " . $e->getMessage());
        }
    }

    /**
     * Exclui um produto do banco de dados pelo ID.
     */
    public static function excluir($id) {
        try {
            $pdo = Database::conectar();
            $sql = "DELETE FROM produtos WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->rowCount(); // Retorna o número de linhas afetadas
        } catch (PDOException $e) {
            throw new Exception("Erro ao excluir produto: " . $e->getMessage());
        }
    }
}
?>
