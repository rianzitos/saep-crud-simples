<?php
// Guarda o tema escolhido num cookie e volta para a página onde o usuário estava.
$permitidos = ['papel', 'terminal', 'oceano', 'brutal', 'floresta'];
$tema = $_GET['tema'] ?? '';

if (in_array($tema, $permitidos, true)) {
    setcookie('tema', $tema, time() + 60 * 60 * 24 * 365, '/');   // vale por 1 ano
}

header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
exit;
