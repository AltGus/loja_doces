<?php
// Configurações do banco de dados
define('DB_HOST', 'localhost');   // Endereço do banco de dados (normalmente 'localhost' para ambiente local)
define('DB_NAME', 'loja_doces');  // Nome do banco de dados
define('DB_USER', 'root');        // Usuário do banco de dados (padrão no XAMPP é 'root')
define('DB_PASS', '');            // Senha do banco de dados (no XAMPP, normalmente é vazia '')

// Configurações gerais do site
define('BASE_URL', 'http://localhost/loja_doces/'); // URL base do projeto

// Configuração de fuso horário para evitar problemas com datas
date_default_timezone_set('America/Sao_Paulo');

// Exibir erros durante o desenvolvimento (remova em produção)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
