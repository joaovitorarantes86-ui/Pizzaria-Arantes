<?php
session_start();
require_once '../includes/conexao.php';

// so admin pode acessar
if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$sucesso = '';
$erro    = '';

// funcao de upload de imagem
function uploadImagem($arquivo) {
    if (!isset($arquivo) || $arquivo['error'] !== UPLOAD_ERR_OK) {
        if (isset($arquivo) && $arquivo['error'] !== UPLOAD_ERR_NO_FILE) {
            return 'Erro ao fazer upload (código: ' . $arquivo['error'] . ').';
        }
        return null;
    }

    $ext = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
    $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!in_array($ext, $permitidas)) {
        return 'Formato de imagem não permitido. Use: jpg, jpeg, png, gif, webp.';
    }

    if ($arquivo['size'] > 5 * 1024 * 1024) {
        return 'Imagem muito grande. Máximo 5MB.';
    }

    $nome_arquivo = uniqid('pizza_') . '.' . $ext;
    $destino = dirname(__DIR__) . '/uploads/' . $nome_arquivo;

    if (move_uploaded_file($arquivo['tmp_name'], $destino)) {
        return 'uploads/' . $nome_arquivo;
    }
    return 'Erro ao salvar a imagem.';
}

// salvar ou editar pizza
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id      = $_POST['id'] ?? null;
    $nome    = trim($_POST['nome'] ?? '');
    $desc    = trim($_POST['descricao'] ?? '');
    $preco   = floatval(str_replace(',', '.', $_POST['preco'] ?? 0));
    $ativo   = isset($_POST['ativo']) ? 1 : 0;

    if (empty($nome) || $preco <= 0) {
        $erro = 'Nome e preço são obrigatórios.';
    } else {
        $imagem = '';

        // upload de arquivo
        if (!empty($_FILES['imagem_arquivo']['name'])) {
            $upload = uploadImagem($_FILES['imagem_arquivo']);
            if (is_string($upload)) {
                if (str_starts_with($upload, 'uploads/')) {
                    $imagem = $upload;
                } else {
                    $erro = $upload;
                }
            }
        }

        // fallback pra url
        if (!$imagem && !$erro) {
            $url = trim($_POST['imagem_url'] ?? '');
            if ($url) {
                $imagem = $url;
            }
        }

        if (!$erro) {
            if ($id) {
                // atualiza pizza existente
                if ($imagem !== '') {
                    $stmt = $pdo->prepare("UPDATE cardapio SET nome=?, descricao=?, preco=?, imagem=?, ativo=? WHERE id=?");
                    $stmt->execute([$nome, $desc, $preco, $imagem, $ativo, $id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE cardapio SET nome=?, descricao=?, preco=?, ativo=? WHERE id=?");
                    $stmt->execute([$nome, $desc, $preco, $ativo, $id]);
                }
                $msg = 'Pizza atualizada com sucesso!';
            } else {
                // cria pizza nova
                $stmt = $pdo->prepare("INSERT INTO cardapio (nome, descricao, preco, imagem, ativo) VALUES (?,?,?,?,?)");
                $stmt->execute([$nome, $desc, $preco, $imagem, $ativo]);
                $msg = 'Pizza adicionada com sucesso!';
            }
            $_SESSION['cardapio_msg'] = $msg;
        }
    }
    header('Location: cardapio.php');
    exit;
}

// excluir pizza
if (isset($_GET['excluir'])) {
    $pdo->prepare("DELETE FROM cardapio WHERE id=?")->execute([$_GET['excluir']]);
    $_SESSION['cardapio_msg'] = 'Pizza removida.';
    header('Location: cardapio.php');
    exit;
}

// mensagem flash
if (isset($_SESSION['cardapio_msg'])) {
    $sucesso = $_SESSION['cardapio_msg'];
    unset($_SESSION['cardapio_msg']);
}

// carrega dados da pizza pra editar
$editando = null;
if (isset($_GET['editar'])) {
    $editando = $pdo->prepare("SELECT * FROM cardapio WHERE id=?");
    $editando->execute([$_GET['editar']]);
    $editando = $editando->fetch();
}

// restaura pizzas padrao
if (isset($_GET['restaurar'])) {
    $pdo->exec("DELETE FROM cardapio");
    $padrao = [
        ['Margherita', 'Molho de tomate fresco, mussarela de búfala e manjericão. O clássico italiano.', 42.90, 'https://images.unsplash.com/photo-1574071318508-1cdbab80d002?w=600'],
        ['Carne Seca', 'Carne seca desfiada, catupiry, cebola caramelizada e azeitonas pretas.', 54.90, 'https://images.unsplash.com/photo-1590947132387-155cc02f3212?w=600'],
        ['Frango com Requeijão', 'Frango temperado, requeijão cremoso, milho e orégano fresquinho.', 48.90, 'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=600'],
        ['Alho e Óleo', 'Azeite extra virgem, alho dourado, parmesão e salsinha. Simples e irresistível.', 38.90, 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600'],
        ['Chocolate c/ Morango', 'Creme de chocolate belga, morangos frescos e granulado. A sobremesa perfeita.', 46.90, 'https://images.unsplash.com/photo-1571407970349-bc81e7e96d47?w=600'],
        ['Calabresa Especial', 'Calabresa artesanal fatiada, cebola roxa, mussarela e pimenta verde.', 44.90, 'https://images.unsplash.com/photo-1593560708920-61dd98c46a4e?w=600'],
    ];
    $stmt = $pdo->prepare("INSERT INTO cardapio (nome, descricao, preco, imagem) VALUES (?,?,?,?)");
    foreach ($padrao as $p) {
        $stmt->execute($p);
    }
    $_SESSION['cardapio_msg'] = 'Cardápio restaurado com as pizzas padrão!';
    header('Location: cardapio.php');
    exit;
}

// lista todas as pizzas
$pizzas = $pdo->query("SELECT * FROM cardapio ORDER BY id")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/style.css">
    <title>Admin - Cardápio</title>
</head>
<body class="dark-mode">

<div class="admin-header">
    <div class="container">
        <h1>Gerenciar <span>Cardápio</span></h1>
        <div style="display:flex; gap:20px; align-items:center;">
            <a href="index.php">Dashboard</a>
            <a href="../index.php">Ver site</a>
            <a href="logout.php">Sair</a>
        </div>
    </div>
</div>

<div class="admin-body">
    <div class="container">

        <?php if ($sucesso): ?>
            <div class="alerta sucesso" style="margin-bottom:20px;"><?= $sucesso ?></div>
        <?php endif; ?>
        <?php if ($erro): ?>
            <div class="alerta erro-box" style="margin-bottom:20px;"><?= $erro ?></div>
        <?php endif; ?>

        <!-- form adicionar/editar pizza -->
        <div class="tabela-wrap" style="margin-bottom:30px;">
            <h3><?= $editando ? 'Editar Pizza' : 'Adicionar Nova Pizza' ?></h3>
            <div style="padding:22px;">
                <form method="POST" enctype="multipart/form-data">
                    <?php if ($editando): ?>
                        <input type="hidden" name="id" value="<?= $editando['id'] ?>">
                    <?php endif; ?>

                    <div class="form-linha">
                        <div class="form-grupo">
                            <label>Nome *</label>
                            <input type="text" name="nome" required
                                   value="<?= htmlspecialchars($editando['nome'] ?? '') ?>"
                                   placeholder="Ex: Margherita">
                        </div>
                        <div class="form-grupo">
                            <label>Preço (R$) *</label>
                            <input type="text" name="preco" required
                                   value="<?= $editando ? number_format($editando['preco'], 2, ',', '') : '' ?>"
                                   placeholder="Ex: 42,90">
                        </div>
                    </div>

                    <div class="form-grupo">
                        <label>Descrição</label>
                        <textarea name="descricao" placeholder="Descreva os ingredientes..."><?= htmlspecialchars($editando['descricao'] ?? '') ?></textarea>
                    </div>

                    <div class="form-linha">
                        <div class="form-grupo">
                            <label>Imagem (arquivo)</label>
                            <input type="file" name="imagem_arquivo" accept="image/*">
                        </div>
                        <div class="form-grupo">
                            <label>Ou URL da Imagem</label>
                            <input type="text" name="imagem_url"
                                   value="<?= htmlspecialchars(isset($editando) && $editando && !str_starts_with($editando['imagem'] ?? '', 'uploads/') ? ($editando['imagem'] ?? '') : '') ?>"
                                   placeholder="https://...">
                        </div>
                    </div>

                    <?php if ($editando && $editando['imagem']): ?>
                        <?php $preview_src = (str_starts_with($editando['imagem'], 'uploads/') ? '../' : '') . $editando['imagem']; ?>
                        <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px;">
                            <img src="<?= htmlspecialchars($preview_src) ?>" alt="Preview"
                                 title="<?= htmlspecialchars($editando['imagem']) ?>"
                                 style="width:80px; height:60px; object-fit:cover; border-radius:6px; border:1px solid #ddd; background:#f5f5f5;"
                                 onerror="this.outerHTML='<span style=color:#e53935;font-size:12px;>Falha preview</span>'">
                            <span style="font-size:0.82rem; color:var(--texto);">Imagem atual</span>
                        </div>
                    <?php endif; ?>

                    <div class="form-grupo" style="flex-direction:row; align-items:center; gap:10px;">
                        <input type="checkbox" name="ativo" id="ativo" value="1"
                               <?= (!$editando || $editando['ativo']) ? 'checked' : '' ?>>
                        <label for="ativo" style="font-weight:400;">Pizza ativa (aparece no cardápio)</label>
                    </div>

                    <div style="display:flex; gap:12px; margin-top:10px;">
                        <button type="submit" class="btn btn-vermelho">
                            <?= $editando ? 'Salvar Alterações' : 'Adicionar Pizza' ?>
                        </button>
                        <?php if ($editando): ?>
                            <a href="cardapio.php" class="btn btn-sm btn-cinza">Cancelar</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- lista de pizzas cadastradas -->
        <div class="tabela-wrap">
            <h3>Pizzas Cadastradas (<?= count($pizzas) ?>)</h3>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Imagem</th>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Preço</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pizzas)): ?>
                        <tr><td colspan="7" style="text-align:center; padding:30px; color:#aaa;">
                            Nenhuma pizza cadastrada.
                            <a href="?restaurar=1" class="btn btn-sm" style="background:var(--laranja); color:#fff;">Restaurar pizzas padrão</a>
                        </td></tr>
                    <?php else: ?>
                    <?php foreach ($pizzas as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td>
                            <?php if ($p['imagem']): ?>
                                <?php $src = str_starts_with($p['imagem'], 'uploads/') ? '../' . $p['imagem'] : $p['imagem']; ?>
                                <img src="<?= htmlspecialchars($src) ?>" alt="<?= $p['nome'] ?>"
                                     title="<?= htmlspecialchars($p['imagem']) ?>"
                                     style="width:60px; height:45px; object-fit:cover; border-radius:6px;"
                                     onerror="this.outerHTML='<span style=color:#e53935;font-size:11px;>Falha ao carregar</span>'">
                            <?php else: ?>
                                <span style="color:#aaa;">Sem imagem</span>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= htmlspecialchars($p['nome']) ?></strong></td>
                        <td style="max-width:200px; font-size:0.82rem; color:#666;">
                            <?= htmlspecialchars(substr($p['descricao'], 0, 60)) ?>...
                        </td>
                        <td><strong style="color:var(--laranja)">R$ <?= number_format($p['preco'], 2, ',', '.') ?></strong></td>
                        <td>
                            <span class="badge <?= $p['ativo'] ? 'badge-confirmado' : 'badge-pendente' ?>">
                                <?= $p['ativo'] ? 'Ativa' : 'Inativa' ?>
                            </span>
                        </td>
                        <td>
                            <div style="display:flex; gap:8px;">
                                <a href="?editar=<?= $p['id'] ?>" class="btn btn-sm btn-cinza">Editar</a>
                                <a href="?excluir=<?= $p['id'] ?>" class="btn btn-sm btn-vermelho-claro" onclick="return confirm('Tem certeza que deseja excluir esta pizza?')">Excluir</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<script src="../JS/main.js"></script>
</body>
</html>
