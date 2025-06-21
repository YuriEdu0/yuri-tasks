<?php
session_start();
require '../db.php';

if (!isset($_SESSION['usuario_id']) || !isset($_GET['id'])) {
    header('Location: ../dashboard.php');
    exit;
}

$id = $_GET['id'];

$stmt = $pdo->prepare("UPDATE tarefas SET concluida = 1 WHERE id = ? AND usuario_id = ?");
$stmt->execute([$id, $_SESSION['usuario_id']]);

header('Location: ../dashboard.php');
exit;
?>
