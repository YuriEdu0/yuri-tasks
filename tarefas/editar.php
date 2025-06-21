<?php
session_start();
require '../db.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: ../dashboard.php');
    exit;
}

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM tarefas WHERE id = ? AND usuario_id = ?");
$stmt->execute([$id, $_SESSION['usuario_id']]);
$tarefa = $stmt->fetch();

if (!$tarefa) {
    header('Location: ../dashboard.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);

    if (empty($titulo) || empty($descricao)) {
        $erro = "Preencha todos os campos.";
    } else {
        $stmt = $pdo->prepare("UPDATE tarefas SET titulo = ?, descricao = ? WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$titulo, $descricao, $id, $_SESSION['usuario_id']]);

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
  <title>Editar Tarefa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container py-5">
  <h3 class="mb-4">✏️ Editar Tarefa</h3>

  <?php if (!empty($erro)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
  <?php endif; ?>

  <form method="post">
    <div class="mb-3">
      <label for="titulo" class="form-label">Título</label>
      <input type="text" class="form-control" id="titulo" name="titulo" value="<?= htmlspecialchars($tarefa['titulo']) ?>" required>
    </div>
    <div class="mb-3">
      <label for="descricao" class="form-label">Descrição</label>
      <textarea class="form-control" id="descricao" name="descricao" rows="4" required><?= htmlspecialchars($tarefa['descricao']) ?></textarea>
    </div>
    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-primary">Salvar Alterações</button>
      <a href="../dashboard.php" class="btn btn-outline-secondary">Cancelar</a>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>