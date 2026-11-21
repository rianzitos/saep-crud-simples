<?php
// ============================================================
//  editar.php  -  UPDATE (U do CRUD): altera um registro que já existe
//  O id vem na URL:  editar.php?id=3   (o link "Editar" do index.php manda isso)
//
//  Igual ao criar.php, funciona em 2 momentos:
//    1) GET  -> busca o registro no banco e mostra o formulário já preenchido.
//    2) POST -> valida, faz o UPDATE e volta para a lista.
//
//  [ADAPTAR] Troque "produtos" pelo nome da sua tabela e as colunas
//  nos lugares marcados com [NOVA COLUNA] 4/5.
// ============================================================
require 'conexao.php';

// (int) transforma em número inteiro (qualquer lixo na URL vira 0). Segurança extra.
$id = (int) ($_GET['id'] ?? 0);

// Busca o registro pelo id. O "?" é preenchido pelo valor do execute().
// [ADAPTAR] nome da tabela
$busca = $pdo->prepare('SELECT * FROM produtos WHERE id = ?');
$busca->execute([$id]);
$produto = $busca->fetch();   // fetch() = uma linha só (ou false se não achou)

// Se o id não existe (ex.: alguém digitou editar.php?id=9999), volta para a lista
if (!$produto) {
    header('Location: index.php');
    exit;
}

$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // [NOVA COLUNA] 4/5 (a) -> pega cada campo do formulário (igual ao criar.php)
    $produto = [
        'nome'       => trim($_POST['nome'] ?? ''),
        'categoria'  => trim($_POST['categoria'] ?? ''),
        'preco'      => str_replace(',', '.', trim($_POST['preco'] ?? '')),
        'quantidade' => trim($_POST['quantidade'] ?? ''),

        // TINYINT sim/não com CHECKBOX (desmarcado não é enviado pelo navegador):
        // veio no $_POST = 1, não veio = 0. Sempre número, nunca true/false.
        //   'ativo' => isset($_POST['ativo']) ? 1 : 0,
        // Com SELECT (opções 1 e 0):  'ativo' => (int) ($_POST['ativo'] ?? 0),
    ];

    $erros = validar($produto);

    if (!$erros) {

        // [NOVA COLUNA] 4/5 (b) -> o UPDATE: acrescente  coluna = ?  na lista do SET.
        // O WHERE id = ? é essencial: sem ele, TODOS os registros seriam alterados!
        // Com TINYINT: acrescente  ativo = ?  no SET e  $produto['ativo']  no execute(), antes do $id.
        // Na hora de abrir o formulário, o valor salvo (1 ou 0) já vem do banco em $produto['ativo'],
        // e é isso que o checkbox do formulario.php usa para ficar marcado ou não.
        $sql = 'UPDATE produtos SET nome = ?, categoria = ?, preco = ?, quantidade = ? WHERE id = ?';
        $pdo->prepare($sql)->execute([
            $produto['nome'],
            $produto['categoria'],
            $produto['preco'],
            $produto['quantidade'],
            $id,   // o último "?" é o do WHERE
        ]);

        header('Location: index.php?msg=editado');
        exit;
    }
}

require 'cabecalho.php';
?>

<section class="card">
    <h2>Editar produto</h2>   <!-- [ADAPTAR] título -->
    <?php $textoBotao = 'Salvar alterações'; require 'formulario.php'; ?>
</section>

<?php require 'rodape.php'; ?>
