<?php
// ============================================================
//  cabecalho.php  -  parte de CIMA de todas as páginas
//  Cada página faz  require 'cabecalho.php';  no começo do HTML
//  e  require 'rodape.php';  no fim. Assim o topo é escrito uma vez só.
//
//  [ADAPTAR] Troque o título da aba e o nome/subtítulo do sistema.
// ============================================================
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Estoque</title>  <!-- [ADAPTAR] título da aba do navegador -->

    <!-- Único arquivo de estilo do site (para mudar o visual, veja o style.css) -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="topo">
    <div>
        <h1>Estoque</h1>                   <!-- [ADAPTAR] nome do sistema -->
        <p class="sub">Cadastro de produtos</p>   <!-- [ADAPTAR] subtítulo -->
    </div>
    <?php if (!empty($_SESSION['usuario_id'])): ?>
        <a href="logout.php" class="btn btn-sec">Sair</a>
    <?php endif; ?>
</header>

<main>
