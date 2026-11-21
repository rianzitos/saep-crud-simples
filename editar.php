<?php
// UPDATE - altera um produto que já existe (o id vem na URL: editar.php?id=3)
require 'conexao.php';

$id = (int) ($_GET['id'] ?? 0);

// Busca o produto no banco; se não existir, volta para a lista
$busca = $pdo->prepare('SELECT * FROM produtos WHERE id = ?');
$busca->execute([$id]);
$produto = $busca->fetch();

if (!$produto) {
    header('Location: index.php');
    exit;
}

$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // [NOVA COLUNA] 4/5 -> pegue o campo novo aqui...
    $produto = [
        'nome'       => trim($_POST['nome'] ?? ''),
        'categoria'  => trim($_POST['categoria'] ?? ''),
        'preco'      => str_replace(',', '.', trim($_POST['preco'] ?? '')),
        'quantidade' => trim($_POST['quantidade'] ?? ''),
    ];

    $erros = validar($produto);

    if (!$erros) {
        // ...e coloque a coluna no UPDATE
        $sql = 'UPDATE produtos SET nome = ?, categoria = ?, preco = ?, quantidade = ? WHERE id = ?';
        $pdo->prepare($sql)->execute([
            $produto['nome'],
            $produto['categoria'],
            $produto['preco'],
            $produto['quantidade'],
            $id,
        ]);

        header('Location: index.php?msg=editado');
        exit;
    }
}

require 'cabecalho.php';
?>

<section class="card">
    <h2>Editar produto</h2>
    <?php $textoBotao = 'Salvar alterações'; require 'formulario.php'; ?>
</section>

<?php require 'rodape.php'; ?>
