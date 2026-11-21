<?php
// Formulário usado por criar.php e editar.php (para não repetir o HTML).
// Espera receber: $produto (os valores dos campos), $erros e $textoBotao.
?>
<?php if ($erros): ?>
    <ul class="erros">
        <?php foreach ($erros as $erro): ?>
            <li><?= h($erro) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<!-- action="" = envia para a própria página -->
<form action="" method="post">

    <!-- [NOVA COLUNA] 3/5 -> adicione um <label> + <input> aqui (name = nome da coluna) -->
    <label>Nome
        <input type="text" name="nome" value="<?= h($produto['nome']) ?>" required>
    </label>

    <label>Categoria
        <input type="text" name="categoria" value="<?= h($produto['categoria']) ?>" required>
    </label>

    <label>Preço (R$)
        <input type="number" name="preco" step="0.01" min="0" value="<?= h($produto['preco']) ?>" required>
    </label>

    <label>Quantidade
        <input type="number" name="quantidade" step="1" min="0" value="<?= h($produto['quantidade']) ?>" required>
    </label>

    <div class="acoes">
        <button type="submit" class="btn btn-principal"><?= $textoBotao ?></button>
        <a href="index.php" class="btn btn-sec">Cancelar</a>
    </div>
</form>
