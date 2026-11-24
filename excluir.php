<?php
// ============================================================
//  excluir.php  -  DELETE (D do CRUD): apaga um registro
//  Não mostra nada na tela: recebe o id, apaga e volta para a lista.
//  O id chega por POST, vindo do botão "Excluir" do index.php.
//
//  [ADAPTAR] Troque "produtos" pelo nome da sua tabela.
// ============================================================
require 'auth.php';      // exige login
require 'conexao.php';

// Só apaga se veio por POST (se alguém abrir excluir.php direto no navegador, não acontece nada)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);

    // [ADAPTAR] nome da tabela. O WHERE id = ? é essencial: sem ele apagaria TUDO.
    $pdo->prepare('DELETE FROM produtos WHERE id = ?')->execute([$id]);
}

header('Location: index.php?msg=excluido');
exit;
