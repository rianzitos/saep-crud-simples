<?php
// ============================================================
//  login.php  -  TELA DE LOGIN
//  Usuário padrão (cadastrado no banco.sql):  admin  /  admin123
//  A senha fica no banco como HASH (password_hash) e é conferida
//  com password_verify(): nunca guardamos a senha em texto puro.
// ============================================================
session_start();
require 'conexao.php';

// Já está logado? Vai direto para a lista.
if (!empty($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$erro = '';
$usuario = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $senha = $_POST['senha'] ?? '';

    $stmt = $pdo->prepare('SELECT id, usuario, senha FROM usuarios WHERE usuario = ?');
    $stmt->execute([$usuario]);
    $user = $stmt->fetch();

    if ($user && password_verify($senha, $user['senha'])) {
        session_regenerate_id(true);   // evita fixação de sessão
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['usuario'] = $user['usuario'];
        header('Location: index.php');
        exit;
    }

    // Mensagem genérica: não revela se errou o usuário ou a senha.
    $erro = 'Usuário ou senha inválidos.';
}

require 'cabecalho.php';
?>

<section class="card login">
    <h2>Entrar</h2>

    <?php if ($erro): ?>
        <ul class="erros"><li><?= h($erro) ?></li></ul>
    <?php endif; ?>

    <form method="post">
        <label>Usuário
            <input type="text" name="usuario" maxlength="50" required autofocus autocomplete="username" value="<?= h($usuario) ?>">
        </label>

        <label>Senha
            <input type="password" name="senha" required autocomplete="current-password">
        </label>

        <div class="acoes">
            <button type="submit" class="btn btn-principal">Entrar</button>
        </div>
    </form>
</section>

<?php require 'rodape.php'; ?>
