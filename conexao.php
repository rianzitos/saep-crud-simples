<?php
// ============================================================
//  Conexão com o banco + 2 funções que as outras páginas usam
//  (no XAMPP o usuário é "root" e a senha é vazia)
// ============================================================

$pdo = new PDO(
    'mysql:host=localhost;dbname=saep_produtos;charset=utf8mb4',
    'root',   // usuário
    '',       // senha
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,        // mostra erro de SQL na tela
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,   // resultados como array ['nome' => ...]
    ]
);

// Escreve um texto na tela com segurança (evita que o usuário injete HTML)
// Uso no HTML:  echo h($produto['nome']);
function h($texto)
{
    return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}

// Confere os dados do formulário. Devolve uma lista de erros (vazia = tudo certo).
// Usada tanto em criar.php quanto em editar.php.
function validar($produto)
{
    $erros = [];

    // [NOVA COLUNA] 2/5 -> se a coluna for obrigatória, valide aqui
    if ($produto['nome'] === '') {
        $erros[] = 'Informe o nome do produto.';
    }
    if ($produto['categoria'] === '') {
        $erros[] = 'Informe a categoria.';
    }
    if (!is_numeric($produto['preco']) || $produto['preco'] < 0) {
        $erros[] = 'O preço precisa ser um número maior ou igual a 0.';
    }
    if (filter_var($produto['quantidade'], FILTER_VALIDATE_INT) === false || $produto['quantidade'] < 0) {
        $erros[] = 'A quantidade precisa ser um número inteiro maior ou igual a 0.';
    }

    return $erros;
}
