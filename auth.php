<?php
// ============================================================
//  auth.php  -  PROTEÇÃO DE LOGIN
//  Toda página interna faz  require 'auth.php';  na PRIMEIRA linha de PHP.
//  Se a pessoa não estiver logada, é mandada para o login.php.
// ============================================================
session_start();

if (empty($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
