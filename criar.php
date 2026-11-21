<?php
// ============================================================
//  criar.php  -  CREATE (C do CRUD): cadastra um registro novo
//
//  Esta página funciona em 2 momentos:
//    1) GET  -> o usuário abriu a página: mostra o formulário vazio.
//    2) POST -> o usuário clicou em "Cadastrar": valida, grava no banco
//               e volta para a lista. Se tiver erro, mostra o formulário de novo.
//
//  [ADAPTAR] Troque "produtos" pelo nome da sua tabela e as colunas
//  nos 3 lugares marcados com [NOVA COLUNA] 4/5.
// ============================================================
require 'conexao.php';

$erros = [];

// [NOVA COLUNA] 4/5 (a) -> valores iniciais do formulário: uma chave para cada coluna, vazia
$produto = ['nome' => '', 'categoria' => '', 'preco' => '', 'quantidade' => ''];

// $_SERVER['REQUEST_METHOD'] diz se a página foi aberta (GET) ou enviada (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // [NOVA COLUNA] 4/5 (b) -> pega cada campo do formulário ($_POST['name_do_input']).
    // trim() tira espaços do começo e do fim. "?? ''" evita erro se o campo não vier.
    $produto = [
        'nome'       => trim($_POST['nome'] ?? ''),
        'categoria'  => trim($_POST['categoria'] ?? ''),
        'preco'      => str_replace(',', '.', trim($_POST['preco'] ?? '')),   // aceita 10,50 e 10.50
        'quantidade' => trim($_POST['quantidade'] ?? ''),
    ];

    $erros = validar($produto);   // função do conexao.php

    if (!$erros) {   // sem erros: grava

        // [NOVA COLUNA] 4/5 (c) -> o INSERT: coloque a coluna na lista E um "?" a mais em VALUES.
        // Os "?" são preenchidos pelos valores do execute(), na mesma ordem.
        // (Isso se chama "prepared statement": é o que protege contra SQL Injection.
        //  Nunca coloque $_POST direto dentro do texto do SQL.)
        $sql = 'INSERT INTO produtos (nome, categoria, preco, quantidade) VALUES (?, ?, ?, ?)';
        $pdo->prepare($sql)->execute([
            $produto['nome'],
            $produto['categoria'],
            $produto['preco'],
            $produto['quantidade'],
        ]);

        // Redireciona para a lista. O exit é obrigatório: sem ele o PHP continuaria rodando.
        header('Location: index.php?msg=criado');
        exit;
    }
    // Se chegou aqui, teve erro: a página continua e mostra o formulário com os erros
    // e com os valores que o usuário já tinha digitado (não perde o que escreveu).
}

require 'cabecalho.php';
?>

<section class="card">
    <h2>Novo produto</h2>   <!-- [ADAPTAR] título -->
    <?php $textoBotao = 'Cadastrar'; require 'formulario.php'; ?>
</section>

<?php require 'rodape.php'; ?>
