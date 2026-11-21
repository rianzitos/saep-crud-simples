<?php
// READ - lista todos os produtos
require 'conexao.php';

$produtos = $pdo->query('SELECT * FROM produtos ORDER BY id DESC')->fetchAll();

// Mensagem que as outras páginas mandam pela URL (?msg=criado)
$mensagens = [
    'criado'  => 'Produto cadastrado!',
    'editado' => 'Produto atualizado!',
    'excluido' => 'Produto excluído.',
];
$msg = $mensagens[$_GET['msg'] ?? ''] ?? '';

require 'cabecalho.php';
?>

<section class="card">
    <div class="lista-topo">
        <h2>Produtos (<?= count($produtos) ?>)</h2>
        <a href="criar.php" class="btn btn-principal">+ Novo produto</a>
    </div>

    <?php if ($msg): ?>
        <p class="aviso"><?= h($msg) ?></p>
    <?php endif; ?>

    <?php if (count($produtos) === 0): ?>
        <p class="vazio">Nenhum produto cadastrado ainda.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Categoria</th>
                    <th>Preço</th>
                    <th>Qtd.</th>
                    <!-- [NOVA COLUNA] 5/5 -> adicione um <th> aqui e um <td> abaixo -->
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $p): ?>
                    <tr>
                        <td data-rotulo="Nome"><?= h($p['nome']) ?></td>
                        <td data-rotulo="Categoria"><?= h($p['categoria']) ?></td>
                        <td data-rotulo="Preço">R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
                        <td data-rotulo="Qtd.">
                            <span><?= $p['quantidade'] ?><?php if ($p['quantidade'] <= 5): ?><em class="etiqueta">baixo</em><?php endif; ?></span>
                        </td>
                        <td class="botoes">
                            <a href="editar.php?id=<?= $p['id'] ?>" class="btn btn-sec">Editar</a>

                            <!-- DELETE: formulário POST (nunca apague por link) -->
                            <form action="excluir.php" method="post" onsubmit="return confirm('Excluir este produto?')">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <button class="btn btn-perigo">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

<?php require 'rodape.php'; ?>
