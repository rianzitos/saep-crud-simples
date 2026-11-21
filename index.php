<?php
// ============================================================
//  index.php  -  READ (R do CRUD): lista tudo que está no banco
//
//  [ADAPTAR] Troque "produtos" pelo nome da sua tabela, os textos das
//  mensagens e as colunas da tabela HTML (procure [ADAPTAR] e [NOVA COLUNA]).
// ============================================================
require 'conexao.php';

// 1) Busca os registros. query() serve quando o SQL não tem valores vindos do usuário.
//    fetchAll() devolve uma lista com todas as linhas.
//    [ADAPTAR] nome da tabela. ORDER BY id DESC = o mais novo aparece primeiro.
$produtos = $pdo->query('SELECT * FROM produtos ORDER BY id DESC')->fetchAll();

// 2) Mensagem de aviso. Quando criar/editar/excluir terminam, elas voltam para cá
//    com ?msg=criado (ou editado / excluido) na URL, e a gente mostra o texto certo.
//    [ADAPTAR] os textos.
$mensagens = [
    'criado'   => 'Produto cadastrado!',
    'editado'  => 'Produto atualizado!',
    'excluido' => 'Produto excluído.',
];
$msg = $mensagens[$_GET['msg'] ?? ''] ?? '';   // "??" = se não existir, usa o valor da direita

require 'cabecalho.php';
?>

<section class="card">
    <div class="lista-topo">
        <h2>Produtos (<?= count($produtos) ?>)</h2>   <!-- [ADAPTAR] título -->
        <a href="criar.php" class="btn btn-principal">+ Novo produto</a>   <!-- [ADAPTAR] texto -->
    </div>

    <?php if ($msg): ?>
        <p class="aviso"><?= h($msg) ?></p>
    <?php endif; ?>

    <?php if (count($produtos) === 0): ?>
        <p class="vazio">Nenhum produto cadastrado ainda.</p>   <!-- [ADAPTAR] texto -->
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <!-- [NOVA COLUNA] 5/5 -> um <th> aqui para cada coluna (e um <td> igual lá embaixo).
                         Mantenha a MESMA ORDEM do <th> e do <td>. -->
                    <th>Nome</th>
                    <th>Categoria</th>
                    <th>Preço</th>
                    <th>Qtd.</th>
                    <th></th> <!-- coluna dos botões Editar/Excluir -->
                </tr>
            </thead>
            <tbody>
                <?php /* foreach = repete o bloco para cada produto da lista. $p é o produto da vez. */ ?>
                <?php foreach ($produtos as $p): ?>
                    <tr>
                        <?php /* data-rotulo só serve para o layout de celular (mostra o nome da coluna) */ ?>
                        <td data-rotulo="Nome"><?= h($p['nome']) ?></td>
                        <td data-rotulo="Categoria"><?= h($p['categoria']) ?></td>

                        <?php /* number_format(valor, casas, separador decimal, separador de milhar) */ ?>
                        <td data-rotulo="Preço">R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>

                        <?php /* [ADAPTAR] a etiqueta "baixo" é específica de estoque (qtd <= 5).
                             Se o assunto não for estoque, apague o "if" da etiqueta (a parte do
                             <em> ... </em>) e deixe só o valor da coluna. */ ?>
                        <td data-rotulo="Qtd.">
                            <span><?= $p['quantidade'] ?><?php if ($p['quantidade'] <= 5): ?><em class="etiqueta">baixo</em><?php endif; ?></span>
                        </td>

                        <td class="botoes">
                            <?php /* UPDATE: link que leva para editar.php mandando o id na URL */ ?>
                            <a href="editar.php?id=<?= $p['id'] ?>" class="btn btn-sec">Editar</a>

                            <?php /* DELETE: é um formulário POST (apagar nunca deve ser feito por link).
                                 O confirm() pergunta antes de enviar. */ ?>
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
