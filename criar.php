<?php
// CREATE - cadastra um produto novo
require 'conexao.php';

$erros = [];
$produto = ['nome' => '', 'categoria' => '', 'preco' => '', 'quantidade' => ''];

// Só entra aqui quando o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // [NOVA COLUNA] 4/5 -> pegue o campo novo aqui...
    $produto = [
        'nome'       => trim($_POST['nome'] ?? ''),
        'categoria'  => trim($_POST['categoria'] ?? ''),
        'preco'      => str_replace(',', '.', trim($_POST['preco'] ?? '')),   // aceita vírgula
        'quantidade' => trim($_POST['quantidade'] ?? ''),
    ];

    $erros = validar($produto);

    if (!$erros) {
        // ...e coloque a coluna no INSERT (e um "?" a mais em VALUES)
        $sql = 'INSERT INTO produtos (nome, categoria, preco, quantidade) VALUES (?, ?, ?, ?)';
        $pdo->prepare($sql)->execute([
            $produto['nome'],
            $produto['categoria'],
            $produto['preco'],
            $produto['quantidade'],
        ]);

        header('Location: index.php?msg=criado');   // volta para a lista
        exit;
    }
}

require 'cabecalho.php';
?>

<section class="card">
    <h2>Novo produto</h2>
    <?php $textoBotao = 'Cadastrar'; require 'formulario.php'; ?>
</section>

<?php require 'rodape.php'; ?>
