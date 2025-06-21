<?php
session_start();
require '../db.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);

    if (empty($titulo) || empty($descricao)) {
        $erro = "Preencha todos os campos.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO tarefas (titulo, descricao, usuario_id, criada_em, concluida) VALUES (?, ?, ?, NOW(), 0)");
        $stmt->execute([$titulo, $descricao, $_SESSION['usuario_id']]);
        header('Location: ../dashboard.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Nova Tarefa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container py-5">
  <h3 class="mb-4">➕ Nova Tarefa</h3>

  <?php if (!empty($erro)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
  <?php endif; ?>

  <form method="post">
    <div class="mb-3">
      <label for="titulo" class="form-label">Título</label>
      <input type="text" class="form-control" id="titulo" name="titulo" required>
    </div>
    <div class="mb-3">
      <label for="descricao" class="form-label">Descrição</label>
      <textarea class="form-control" id="descricao" name="descricao" rows="4" required></textarea>
    </div>
    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-success">Salvar</button>
      <a href="../dashboard.php" class="btn btn-outline-secondary">Cancelar</a>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
