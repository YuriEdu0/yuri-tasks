<?php
session_start();
require 'db.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM tarefas WHERE usuario_id = ? ORDER BY criada_em DESC");
$stmt->execute([$_SESSION['usuario_id']]);
$tarefas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Painel de Tarefas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
  </style>
</head>
<body>
  
  <div class="container py-5">
    <h2 class="mb-4">Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?> 👋</h2>
    
    <div class="mb-3 d-flex justify-content-between">
      <a href="tarefas/adicionar.php" class="btn btn-success">➕ Nova Tarefa</a>
      <a href="logout.php" class="btn btn-outline-danger">Sair</a>
    </div>

    <?php if (count($tarefas) > 0): ?>
      <div class="row">
        <?php foreach ($tarefas as $tarefa): ?>
          <div class="col-md-6">
            <div class="card shadow-sm task-card">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($tarefa['titulo']) ?></h5>
                <p class="card-text"><?= nl2br(htmlspecialchars($tarefa['descricao'])) ?></p>
                <small class="text-muted">Criada em: <?= $tarefa['criada_em'] ?></small><br>
                <div class="mt-3">
                  <?php if (!$tarefa['concluida']): ?>
                    <a href="tarefas/concluir.php?id=<?= $tarefa['id'] ?>" class="btn btn-sm btn-primary">✅ Concluir</a>
                  <?php else: ?>
                    <span class="badge bg-success">✔️ Concluída</span>
                  <?php endif; ?>
                  <a href="tarefas/editar.php?id=<?= $tarefa['id'] ?>" class="btn btn-sm btn-warning">✏️ Editar</a>
                  <a href="tarefas/excluir.php?id=<?= $tarefa['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir esta tarefa?')" class="btn btn-sm btn-danger">🗑️ Excluir</a>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="alert alert-info">Você ainda não tem nenhuma tarefa.</div>
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
