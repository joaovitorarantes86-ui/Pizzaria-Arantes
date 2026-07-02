<?php
session_start();
$titulo = 'Login';

// redireciona se ja estiver logado
if (isset($_SESSION['usuario_tipo'])) {
    $redir = $_SESSION['usuario_tipo'] === 'admin' ? 'admin/index.php' : 'pedidos.php';
    header("Location: $redir");
    exit;
}

// flash messages
$erros = $_SESSION['login_erros'] ?? [];
$email = $_SESSION['login_email'] ?? '';
unset($_SESSION['login_erros'], $_SESSION['login_email']);
?>
<?php include 'includes/header.php'; ?>

<!-- login -->
<section class="login-section">
    <div class="container">
        <div class="login-box">
            <div class="form-wrap">
                <h2>Entrar na <span>conta</span></h2>
                <p class="sub">Acesse para acompanhar seus pedidos</p>

                <?php if (!empty($erros)): ?>
                    <div class="alerta erro-box">
                        <?php foreach ($erros as $e): ?>
                            <div><?= $e ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="process/login.php" method="POST" novalidate>
                    <div class="form-grupo">
                        <label for="email">E-mail *</label>
                        <input type="email" id="email" name="email" required data-val="email"
                               value="<?= htmlspecialchars($email) ?>"
                               placeholder="seu@email.com">
                        <span class="msg-erro"></span>
                    </div>
                    <div class="form-grupo">
                        <label for="senha">Senha *</label>
                        <input type="password" id="senha" name="senha" required data-val="senha"
                               placeholder="Mínimo 6 caracteres">
                        <span class="msg-erro"></span>
                    </div>
                    <button type="submit" class="btn btn-vermelho btn-full">Entrar</button>
                </form>

                <p class="link-alt">Não tem conta? <a href="cadastro.php">Cadastre-se grátis</a></p>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>