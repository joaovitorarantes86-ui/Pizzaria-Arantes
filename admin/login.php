<?php
session_start();

// redireciona se ja for admin
if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'admin') {
    header('Location: index.php');
    exit;
}

// flash messages
$erros = $_SESSION['login_erros'] ?? [];
$email = $_SESSION['login_email'] ?? '';
unset($_SESSION['login_erros'], $_SESSION['login_email']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/style.css">
    <title>Admin - Login</title>
</head>
<body class="dark-mode">

<section class="login-section">
    <div class="container">
        <div class="login-box">
            <div class="form-wrap">
                <h2>Painel <span>Admin</span></h2>
                <p class="sub">Acesso restrito à equipe</p>

                <?php if (!empty($erros)): ?>
                    <div class="alerta erro-box">
                        <?php foreach ($erros as $e): ?>
                            <div>⚠️ <?= $e ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="../process/login.php" method="POST" novalidate>
                    <input type="hidden" name="origem" value="admin">
                    <div class="form-grupo">
                        <label for="email">E-mail *</label>
                        <input type="email" id="email" name="email" required data-val="email"
                               value="<?= htmlspecialchars($email) ?>"
                               placeholder="admin@pizzaria.com">
                        <span class="msg-erro"></span>
                    </div>
                    <div class="form-grupo">
                        <label for="senha">Senha *</label>
                        <input type="password" id="senha" name="senha" required data-val="senha"
                               placeholder="Sua senha">
                        <span class="msg-erro"></span>
                    </div>
                    <button type="submit" class="btn btn-vermelho btn-full">Entrar no Painel</button>
                </form>

                <p class="link-alt"><a href="../index.php">← Voltar ao site</a></p>
            </div>
        </div>
    </div>
</section>

<script src="../JS/main.js"></script>
</body>
</html>