<?php
session_start();
$titulo = 'Cadastro';

// redireciona se ja estiver logado
if (isset($_SESSION['usuario_tipo'])) {
    header('Location: pedidos.php');
    exit;
}

// flash messages
$erros = $_SESSION['cadastro_erros'] ?? [];
$dados = $_SESSION['cadastro_dados'] ?? [];
unset($_SESSION['cadastro_erros'], $_SESSION['cadastro_dados']);
?>
<?php include 'includes/header.php'; ?>

<!-- cadastro -->
<section class="login-section">
    <div class="container">
        <div class="login-box">
            <div class="form-wrap">
                <h2>Criar <span>conta</span></h2>
                <p class="sub">Cadastre-se para fazer pedidos mais rápido</p>

                <?php if (!empty($erros)): ?>
                    <div class="alerta erro-box">
                        <?php foreach ($erros as $e): ?>
                            <div><?= $e ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="process/cadastro.php" method="POST" novalidate>
                    <div class="form-grupo">
                        <label for="nome">Nome completo *</label>
                        <input type="text" id="nome" name="nome" required
                               value="<?= htmlspecialchars($dados['nome'] ?? '') ?>"
                               placeholder="Seu nome">
                        <span class="msg-erro"></span>
                    </div>
                    <div class="form-grupo">
                        <label for="email">E-mail *</label>
                        <input type="email" id="email" name="email" required data-val="email"
                               value="<?= htmlspecialchars($dados['email'] ?? '') ?>"
                               placeholder="seu@email.com">
                        <span class="msg-erro"></span>
                    </div>
                    <div class="form-grupo">
                        <label for="telefone">Telefone</label>
                        <input type="text" id="telefone" name="telefone" data-val="tel"
                               value="<?= htmlspecialchars($dados['telefone'] ?? '') ?>"
                               placeholder="(67) 99999-9999">
                        <span class="msg-erro"></span>
                    </div>
                    <div class="form-grupo">
                        <label for="senha">Senha *</label>
                        <input type="password" id="senha" name="senha" required data-val="senha"
                               placeholder="Mínimo 6 caracteres">
                        <span class="msg-erro"></span>
                    </div>
                    <div class="form-grupo">
                        <label for="confirma">Confirmar senha *</label>
                        <input type="password" id="confirma" name="confirma" required
                               placeholder="Repita a senha">
                        <span class="msg-erro"></span>
                    </div>
                    <button type="submit" class="btn btn-vermelho btn-full">Cadastrar</button>
                </form>

                <p class="link-alt">Já tem conta? <a href="login.php">Entrar</a></p>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>