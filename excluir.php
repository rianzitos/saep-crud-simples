<?php
// DELETE - remove um produto (recebe o id por POST, vindo do botão da lista)
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $pdo->prepare('DELETE FROM produtos WHERE id = ?')->execute([$id]);
}

header('Location: index.php?msg=excluido');
exit;
