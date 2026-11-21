<?php
// ============================================================
//  conexao.php
//  Todas as páginas dão  require 'conexao.php';  no começo.
//  Aqui ficam: a conexão com o banco, a função h() e a função validar().
//
//  [ADAPTAR] Troque o nome do banco ("dbname=") se você mudou no banco.sql.
//  No XAMPP o usuário é "root" e a senha é vazia.
// ============================================================

// ---------- 1) CONEXÃO ----------
// PDO é a forma moderna de falar com o MySQL. Depois desta linha, a variável
// $pdo representa a conexão e é com ela que as páginas fazem os comandos SQL.
$pdo = new PDO(
    'mysql:host=localhost;dbname=saep_produtos;charset=utf8mb4',   // onde está o banco
    'root',   // usuário
    '',       // senha
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,        // se o SQL der erro, mostra na tela
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,   // cada linha vem como ['nome' => 'Mouse', ...]
    ]
);

// ---------- 2) h(): escreve texto na tela com segurança ----------
// Se alguém cadastrar um nome como <script>alert(1)</script>, sem h() esse código
// rodaria no navegador. Com h() ele aparece só como texto.
// REGRA: todo valor que veio do banco ou do usuário é escrito com h().
// Uso no HTML:  echo h($produto['nome']);
function h($texto)
{
    return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}

// ---------- 3) validar(): confere os dados do formulário ----------
// Recebe os dados (array) e devolve uma lista de mensagens de erro.
// Lista vazia = está tudo certo. Usada em criar.php e em editar.php.
function validar($produto)
{
    $erros = [];

    // [NOVA COLUNA] 2/5 -> uma regra para cada coluna que precisar de validação.
    // Para cada coluna, escolha o modelo parecido e copie:
    //
    //   Texto obrigatório:
    //     if ($produto['nome'] === '') { $erros[] = 'Informe o nome.'; }
    //   Tamanho mínimo:
    //     if (mb_strlen($produto['nome']) < 3) { $erros[] = 'O nome precisa de 3 letras ou mais.'; }
    //   Número dentro de um limite (ex.: nota de 0 a 10):
    //     if (!is_numeric($produto['nota']) || $produto['nota'] < 0 || $produto['nota'] > 10) { ... }
    //   E-mail:
    //     if (filter_var($produto['email'], FILTER_VALIDATE_EMAIL) === false) { ... }
    //   Data (o <input type="date"> já manda no formato AAAA-MM-DD):
    //     if (strtotime($produto['nascimento']) === false) { ... }
    //   TINYINT(1) sim/não com CHECKBOX: não precisa validar (criar.php e editar.php já
    //     transformam em 1 ou 0, não tem como vir valor errado).
    //   TINYINT(1) sim/não com SELECT (opções 1 e 0): confira se veio uma das duas:
    //     if (!in_array($produto['ativo'], [0, 1], true)) { $erros[] = 'Escolha Sim ou Não.'; }
    //   TINYINT como número pequeno (ex.: idade de 0 a 120):
    //     if (filter_var($produto['idade'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 120]]) === false) { ... }

    if ($produto['nome'] === '') {
        $erros[] = 'Informe o nome do produto.';
    }
    if ($produto['categoria'] === '') {
        $erros[] = 'Informe a categoria.';
    }
    if (!is_numeric($produto['preco']) || $produto['preco'] < 0) {
        $erros[] = 'O preço precisa ser um número maior ou igual a 0.';
    }
    // inteiro: não aceita 1.5 nem texto
    if (filter_var($produto['quantidade'], FILTER_VALIDATE_INT) === false || $produto['quantidade'] < 0) {
        $erros[] = 'A quantidade precisa ser um número inteiro maior ou igual a 0.';
    }

    return $erros;
}
