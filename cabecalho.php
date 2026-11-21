<?php
// Parte de cima de TODAS as páginas: <head>, título e botões de tema.
// Cada página faz  require 'cabecalho.php';  no começo e  require 'rodape.php';  no fim.

// Os 5 estilos: 'nome do arquivo css' => 'texto do botão'
$temas = [
    'papel'    => 'Papel',
    'terminal' => 'Terminal',
    'oceano'   => 'Oceano',
    'brutal'   => 'Brutal',
    'floresta' => 'Floresta',
];

// Tema escolhido (fica guardado num cookie pelo tema.php)
$temaAtual = $_COOKIE['tema'] ?? 'papel';
if (!isset($temas[$temaAtual])) {
    $temaAtual = 'papel';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Estoque</title>

    <!-- base.css = layout igual para todos | css/<tema>.css = cores e jeitão de cada tema -->
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/<?= $temaAtual ?>.css">
</head>
<body>

<header class="topo">
    <div>
        <h1>Estoque</h1>
        <p class="sub">Cadastro de produtos</p>
    </div>

    <nav class="temas">
        <?php foreach ($temas as $arquivo => $nome): ?>
            <a href="tema.php?tema=<?= $arquivo ?>" class="<?= $arquivo === $temaAtual ? 'ativo' : '' ?>"><?= $nome ?></a>
        <?php endforeach; ?>
    </nav>
</header>

<main>
