<?php
session_start();
require_once '../includes/conexao.php';

if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// atualizar status do pedido
if (isset($_POST['alterar_status'])) {
    $id_pedido = (int) $_POST['id_pedido'];
    $novo_status = $_POST['novo_status'];
    $status_validos = ['pendente', 'confirmado', 'entregue', 'cancelado'];
    if (in_array($novo_status, $status_validos)) {
        $stmt = $pdo->prepare("UPDATE pedidos SET status = ? WHERE id = ?");
        $stmt->execute([$novo_status, $id_pedido]);
    }
    header('Location: index.php');
    exit;
}

// marcar mensagem como lida
if (isset($_GET['marcar_lida'])) {
    $id_msg = (int) $_GET['marcar_lida'];
    $pdo->prepare("UPDATE mensagens SET lida = 1 WHERE id = ?")->execute([$id_msg]);
    header('Location: index.php');
    exit;
}

$nome = $_SESSION['usuario_nome'];

// stats do dia
$pedidos_hoje    = $pdo->query("SELECT COUNT(*) FROM pedidos WHERE DATE(criado_em) = CURDATE()")->fetchColumn();
$faturado_hoje   = $pdo->query("SELECT COALESCE(SUM(total),0) FROM pedidos WHERE DATE(criado_em) = CURDATE()")->fetchColumn();
$pedidos_total   = $pdo->query("SELECT COUNT(*) FROM pedidos")->fetchColumn();
$faturado_total  = $pdo->query("SELECT COALESCE(SUM(total),0) FROM pedidos")->fetchColumn();
$total_clientes  = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE tipo = 'cliente'")->fetchColumn();
$pizzas_cardapio = $pdo->query("SELECT COUNT(*) FROM cardapio WHERE ativo = 1")->fetchColumn();
$msgs_nao_lidas  = $pdo->query("SELECT COUNT(*) FROM mensagens WHERE lida = 0")->fetchColumn();

// pedidos por status
$status_counts = $pdo->query("SELECT status, COUNT(*) as total FROM pedidos GROUP BY status")->fetchAll();
$pendentes = 0; $confirmados = 0; $entregues = 0; $cancelados = 0;
foreach ($status_counts as $s) {
    if ($s['status'] === 'pendente') $pendentes = $s['total'];
    if ($s['status'] === 'confirmado') $confirmados = $s['total'];
    if ($s['status'] === 'entregue') $entregues = $s['total'];
    if ($s['status'] === 'cancelado') $cancelados = $s['total'];
}

// pizza mais pedida
$pizza_top = $pdo->query("SELECT pizza, COUNT(*) as total FROM pedidos GROUP BY pizza ORDER BY total DESC LIMIT 1")->fetch();

$pedidos   = $pdo->query("SELECT * FROM pedidos ORDER BY criado_em DESC LIMIT 20")->fetchAll();
$mensagens = $pdo->query("SELECT * FROM mensagens ORDER BY criado_em DESC LIMIT 20")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/style.css">
    <title>Admin - Painel</title>
</head>
<body class="dark-mode">

<div class="admin-header">
    <div class="container">
        <h1>Painel <span>Admin</span></h1>
        <div style="display:flex; gap:20px; align-items:center;">
            <span style="color:#aaa; font-size:0.85rem;">Olá, <?= htmlspecialchars($nome) ?>!</span>
            <a href="cardapio.php">Cardápio</a>
            <a href="../index.php">Ver site</a>
            <a href="logout.php">Sair</a>
        </div>
    </div>
</div>

<div class="admin-body">
    <div class="container">

        <!-- stats -->
        <div class="admin-stats">
            <div class="stat-card sc-orange">
                <div class="valor"><?= $pedidos_hoje ?></div>
                <div class="rotulo">Pedidos hoje</div>
            </div>
            <div class="stat-card sc-green">
                <div class="valor" style="font-size:1.2rem;">R$ <?= number_format($faturado_hoje, 2, ',', '.') ?></div>
                <div class="rotulo">Faturamento hoje</div>
            </div>
            <div class="stat-card sc-blue">
                <div class="valor"><?= $pedidos_total ?></div>
                <div class="rotulo">Total de Pedidos</div>
            </div>
            <div class="stat-card sc-purple">
                <div class="valor" style="font-size:1.2rem;">R$ <?= number_format($faturado_total, 2, ',', '.') ?></div>
                <div class="rotulo">Faturamento Total</div>
            </div>
            <div class="stat-card sc-teal">
                <div class="valor"><?= $total_clientes ?></div>
                <div class="rotulo">Clientes</div>
            </div>
            <div class="stat-card sc-amber">
                <div class="valor"><?= $pizzas_cardapio ?></div>
                <div class="rotulo">Pizzas no Cardápio</div>
            </div>
            <div class="stat-card sc-red">
                <div class="valor"><?= $msgs_nao_lidas ?></div>
                <div class="rotulo">Mensagens não lidas</div>
            </div>
        </div>

        <!-- status dos pedidos -->
        <div class="status-cards">
            <div class="status-card sc-pendente">
                <div class="sc-num"><?= $pendentes ?></div>
                <div class="sc-label">Pendentes</div>
                <?php $total_pedidos = $pendentes + $confirmados + $entregues + $cancelados; $pct = $total_pedidos > 0 ? round(($pendentes / $total_pedidos) * 100) : 0; ?>
                <div class="sc-bar"><div class="sc-bar-inner" style="width:<?= $pct ?>%"></div></div>
            </div>
            <div class="status-card sc-confirmado">
                <div class="sc-num"><?= $confirmados ?></div>
                <div class="sc-label">Confirmados</div>
                <?php $pct = $total_pedidos > 0 ? round(($confirmados / $total_pedidos) * 100) : 0; ?>
                <div class="sc-bar"><div class="sc-bar-inner" style="width:<?= $pct ?>%"></div></div>
            </div>
            <div class="status-card sc-entregue">
                <div class="sc-num"><?= $entregues ?></div>
                <div class="sc-label">Entregues</div>
                <?php $pct = $total_pedidos > 0 ? round(($entregues / $total_pedidos) * 100) : 0; ?>
                <div class="sc-bar"><div class="sc-bar-inner" style="width:<?= $pct ?>%"></div></div>
            </div>
            <div class="status-card sc-cancelado">
                <div class="sc-num"><?= $cancelados ?></div>
                <div class="sc-label">Cancelados</div>
                <?php $pct = $total_pedidos > 0 ? round(($cancelados / $total_pedidos) * 100) : 0; ?>
                <div class="sc-bar"><div class="sc-bar-inner" style="width:<?= $pct ?>%"></div></div>
            </div>
            <?php if ($pizza_top): ?>
            <div class="status-card sc-top">
                <div class="sc-num"><?= htmlspecialchars($pizza_top['pizza']) ?></div>
                <div class="sc-label"><?= $pizza_top['total'] ?> pedidos &middot; Mais pedida</div>
                <div class="sc-bar"><div class="sc-bar-inner" style="width:100%"></div></div>
            </div>
            <?php endif; ?>
        </div>

        <div class="tabela-wrap">
            <h3>Pedidos Recentes</h3>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Pizza</th>
                        <th>Tamanho</th>
                        <th>Pagamento</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pedidos)): ?>
                        <tr><td colspan="8" style="text-align:center; color:#aaa;">Nenhum pedido ainda.</td></tr>
                    <?php else: ?>
                        <?php foreach ($pedidos as $p): ?>
                        <tr>
                            <td>#<?= $p['id'] ?></td>
                            <td><?= htmlspecialchars($p['nome_cliente']) ?></td>
                            <td><?= htmlspecialchars($p['pizza']) ?></td>
                            <td><?= htmlspecialchars($p['tamanho']) ?></td>
                            <td><?= htmlspecialchars($p['pagamento']) ?></td>
                            <td>R$ <?= number_format($p['total'], 2, ',', '.') ?></td>
                            <td>
                                <span class="badge badge-<?= htmlspecialchars($p['status']) ?>"><?= ucfirst(htmlspecialchars($p['status'])) ?></span>
                                <form method="POST" style="display:inline; margin-left:6px;">
                                    <input type="hidden" name="id_pedido" value="<?= $p['id'] ?>">
                                    <select name="novo_status" onchange="this.form.submit()" style="font-size:11px; padding:2px 4px;">
                                        <option value="">Alterar</option>
                                        <option value="pendente">Pendente</option>
                                        <option value="confirmado">Confirmado</option>
                                        <option value="entregue">Entregue</option>
                                        <option value="cancelado">Cancelado</option>
                                    </select>
                                    <input type="hidden" name="alterar_status" value="1">
                                </form>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($p['criado_em'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- mensagens de contato -->
        <div class="tabela-wrap">
            <h3>Mensagens de Contato</h3>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Assunto</th>
                        <th>Mensagem</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($mensagens)): ?>
                        <tr><td colspan="7" style="text-align:center; color:#aaa;">Nenhuma mensagem ainda.</td></tr>
                    <?php else: ?>
                        <?php foreach ($mensagens as $m): ?>
                        <tr style="<?= !$m['lida'] ? 'font-weight:bold; background:#fff9e6;' : '' ?>">
                            <td>#<?= $m['id'] ?></td>
                            <td><?= htmlspecialchars($m['nome']) ?></td>
                            <td><?= htmlspecialchars($m['email']) ?></td>
                            <td><?= htmlspecialchars($m['assunto']) ?></td>
                            <td><?= htmlspecialchars(substr($m['mensagem'], 0, 60)) ?>...</td>
                            <td><?= date('d/m/Y H:i', strtotime($m['criado_em'])) ?></td>
                            <td>
                                <?php if (!$m['lida']): ?>
                                    <a href="?marcar_lida=<?= $m['id'] ?>" class="btn-sm btn-verde-claro" style="padding:4px 10px; font-size:11px; border-radius:4px; cursor:pointer;">Marcar lida</a>
                                <?php else: ?>
                                    <span style="color:#999; font-size:12px;">Lida</span>
                                <?php endif; ?>
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
<script>
// atualiza stats a cada 15s
setInterval(function() {
    fetch('stats.php')
        .then(function(r) { return r.json(); })
        .then(function(d) {
            var cards = document.querySelectorAll('.stat-card .valor');
            if (cards.length >= 7) {
                cards[0].textContent = d.pedidos_hoje;
                cards[1].textContent = 'R$ ' + d.faturado_hoje;
                cards[2].textContent = d.pedidos_total;
                cards[3].textContent = 'R$ ' + d.faturado_total;
                cards[4].textContent = d.total_clientes;
                cards[5].textContent = d.pizzas_cardapio;
                cards[6].textContent = d.msgs_nao_lidas;
            }

            var statusDivs = document.querySelectorAll('.status-card .sc-num');
            if (statusDivs.length >= 4) {
                statusDivs[0].textContent = d.pendentes;
                statusDivs[1].textContent = d.confirmados;
                statusDivs[2].textContent = d.entregues;
                statusDivs[3].textContent = d.cancelados;
            }

            if (d.pizza_top_nome && statusDivs.length >= 5) {
                var topDiv = document.querySelector('.status-card.sc-top .sc-num');
                if (topDiv) topDiv.textContent = d.pizza_top_nome;
                var topLabel = document.querySelector('.status-card.sc-top .sc-label');
                if (topLabel) topLabel.textContent = d.pizza_top_qtd + ' pedidos';
            }
        })
        .catch(function(e) { /* silencioso */ });
}, 15000);
</script>
</body>
</html>
