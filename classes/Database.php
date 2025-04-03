<?php
require_once __DIR__ . '/../config.php';

class Database {
    private static $pdo = null;

    public static function conectar() {
        if (self::$pdo === null) {
            try {
                // Verifica se as constantes do banco estão definidas corretamente
                if (!defined('DB_HOST') || !defined('DB_NAME') || !defined('DB_USER') || !defined('DB_PASS')) {
                    die("Erro: Configurações do banco de dados não definidas corretamente.");
                }

                // Conexão com o banco de dados
                self::$pdo = new PDO(
                    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", 
                    DB_USER, 
                    DB_PASS, 
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Ativa exceções para erros
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Retorna arrays associativos
                        PDO::ATTR_EMULATE_PREPARES => false // Segurança contra SQL Injection
                    ]
                );
            } catch (PDOException $e) {
                error_log("Erro ao conectar ao banco de dados: " . $e->getMessage()); // Registra o erro
                die("Erro ao conectar ao banco de dados. Tente novamente mais tarde."); // Evita expor detalhes ao usuário
            }
        }
        return self::$pdo;
    }
}
?>
