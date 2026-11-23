<?php
// ============================================================
//  formulario.php  -  o formulário (HTML) usado por criar.php E editar.php
//  Fica num arquivo só para não repetir o mesmo HTML duas vezes.
//
//  Quem inclui este arquivo precisa ter definido antes:
//    $produto      -> array com o valor de cada campo (vazio em criar.php, preenchido em editar.php)
//    $erros        -> lista de erros da validação (vazia se não houver)
//    $textoBotao   -> texto do botão ("Cadastrar" ou "Salvar alterações")
// ============================================================
?>
<?php if ($erros): ?>
    <ul class="erros">
        <?php foreach ($erros as $erro): ?>
            <li><?= h($erro) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<!-- action="" = envia para a própria página (criar.php ou editar.php?id=...)
     method="post" = os dados vão escondidos, e chegam no PHP em $_POST -->
<form action="" method="post">

    <!-- [NOVA COLUNA] 3/5 -> um <label> + <input> para cada coluna.
         REGRA: o name="..." tem que ser IGUAL ao nome da coluna no banco.
         O value="..." mostra o valor atual (vazio em criar, preenchido em editar). -->
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

    <?php
    /*
      MODELOS DE OUTROS CAMPOS (copie, cole acima e troque o "xxx" pelo nome da coluna):

      Texto:          <label>Nome  <input type="text" name="xxx" value="<?= h($produto['xxx']) ?>" required></label>
      E-mail:         <input type="email" name="xxx" ...>
      Senha:          <input type="password" name="xxx" ...>
      Data:           <input type="date" name="xxx" ...>
      Número inteiro: <input type="number" name="xxx" step="1" ...>
      Número decimal: <input type="number" name="xxx" step="0.01" ...>

      TINYINT sim/não, versão CHECKBOX (a mais simples). O "checked" deixa marcado
      quando o valor do banco é 1. Use a classe "opcao" no label (alinha a caixinha com o texto):
        <label class="opcao">
            <input type="checkbox" name="ativo" value="1" <?= $produto['ativo'] ? 'checked' : '' ?>>
            Ativo
        </label>
      (Cuidado: checkbox desmarcado não é enviado pelo navegador. Quem resolve isso é o
       criar.php / editar.php: veja o comentário "TINYINT" lá.)

      TINYINT sim/não, versão SELECT (quando a prova pedir "Sim" e "Não" como lista):
        <label>Ativo
            <select name="ativo">
                <option value="1" <?= $produto['ativo'] == 1 ? 'selected' : '' ?>>Sim</option>
                <option value="0" <?= $produto['ativo'] == 0 ? 'selected' : '' ?>>Não</option>
            </select>
        </label>

      TINYINT número pequeno (idade, nota de 0 a 10...): é um número comum.
        <input type="number" name="idade" step="1" min="0" max="120" value="<?= h($produto['idade']) ?>">

      Texto longo:
        <label>Descrição
            <textarea name="xxx" rows="4"><?= h($produto['xxx']) ?></textarea>
        </label>

      Lista de opções (select). O "selected" marca a opção que já estava salva:
        <label>Turma
            <select name="xxx">
                <option value="A" <?= $produto['xxx'] === 'A' ? 'selected' : '' ?>>Turma A</option>
                <option value="B" <?= $produto['xxx'] === 'B' ? 'selected' : '' ?>>Turma B</option>
            </select>
        </label>
    */
    ?>

    <div class="acoes">
        <button type="submit" class="btn btn-principal"><?= $textoBotao ?></button>
        <a href="index.php" class="btn btn-sec">Cancelar</a>
    </div>
</form>
