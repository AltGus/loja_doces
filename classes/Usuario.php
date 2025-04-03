<?php
require_once __DIR__ . '/Database.php';

class Usuario {

    private static function conectar() {
        return Database::conectar();
    }

    // Função para registrar um novo usuário
    public static function registrar($nome, $email, $senha) {
        if (strlen($senha) < 8 || strlen($senha) > 50) {
            return "A senha deve ter entre 8 e 50 caracteres.";
        }

        $pdo = self::conectar();

        // Verificar se o email já existe
        if (self::emailExiste($email)) {
            return "Este e-mail já está em uso.";
        }

        // Gerar o hash da senha
        $hashSenha = password_hash($senha, PASSWORD_DEFAULT);

        // Preparar a instrução SQL para inserir o novo usuário
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
        
        // Executar a consulta
        if ($stmt->execute([':nome' => $nome, ':email' => $email, ':senha' => $hashSenha])) {
            return true;
        }
        return "Erro ao registrar usuário. Tente novamente.";
    }

    // ✅ Torna emailExiste() pública para evitar erro em Registro.php
    public static function emailExiste($email) {
        $pdo = self::conectar();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetchColumn() > 0;
    }

    // Função para fazer login do usuário
    public static function login($email, $senha) {
        session_start(); // Garante que a sessão esteja ativa

        $pdo = self::conectar();
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            // Salva os dados do usuário na sessão
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            return true;
        }
        return false;
    }

    // Função para verificar se o usuário está logado
    public static function verificarLogin() {
        session_start(); // Evita erro caso a sessão ainda não tenha sido iniciada
        return isset($_SESSION['usuario_id']);
    }

    // Função para pegar o nome do usuário logado
    public static function getNomeUsuario() {
        session_start();
        return $_SESSION['usuario_nome'] ?? null;
    }

    // Função para deslogar o usuário
    public static function logout() {
        session_start();  // Garante que a sessão esteja ativa
        session_unset();  // Remove todas as variáveis de sessão
        session_destroy();  // Destroi a sessão
        
        // Remove cookies de sessão
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }

        header("Location: Login.php");  // Redireciona para a página de login
        exit();
    }
}
?>
