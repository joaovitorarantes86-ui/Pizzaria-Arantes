<?php
session_start();
$titulo = 'Contato';

// flash messages
$sucesso = $_SESSION['contato_sucesso'] ?? null;
$erros   = $_SESSION['contato_erros'] ?? [];
$dados   = $_SESSION['contato_dados'] ?? [];

unset($_SESSION['contato_sucesso'], $_SESSION['contato_erros'], $_SESSION['contato_dados']);
?>
<?php include 'includes/header.php'; ?>

<!-- contato -->
<section class="contato-section">
    <div class="container">
        <div class="contato-grid">

            <!-- informacoes -->
            <div class="contato-info">
                <h2>Fale <span>Conosco</span></h2>
                <p>Tem alguma dúvida, sugestão ou reclamação? Nossa equipe responde em até 24 horas.</p>
                <div class="contato-item">
                    <span class="ic">📍</span>
                    <span>Rua das Pizzas, 123 - Campo Grande, MS</span>
                </div>
                <div class="contato-item">
                    <span class="ic">📞</span>
                    <span>(67) 3333-4444</span>
                </div>
                <div class="contato-item">
                    <span class="ic">📱</span>
                    <span>(67) 99999-8888 (WhatsApp)</span>
                </div>
                <div class="contato-item">
                    <span class="ic">✉️</span>
                    <span>contato@pizzaria.com.br</span>
                </div>
                <div class="contato-item">
                    <span class="ic">🕐</span>
                    <span>Seg–Dom: 18h às 23h30</span>
                </div>
            </div>

            <!-- formulario -->
            <div class="form-wrap" style="margin:0;">

                <?php if ($sucesso): ?>
                    <div class="alerta sucesso">✅ <?= $sucesso ?></div>
                <?php endif; ?>

                <?php if (!empty($erros)): ?>
                    <div class="alerta erro-box">
                        <?php foreach ($erros as $e): ?>
                            <div>⚠️ <?= $e ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="process/contato.php" method="POST" novalidate>

                    <!-- nome -->
                    <div class="form-grupo">
                        <label for="nome">Nome *</label>
                        <input type="text" id="nome" name="nome" required
                               value="<?= htmlspecialchars($dados['nome'] ?? '') ?>"
                               placeholder="Seu nome completo">
                        <span class="msg-erro"></span>
                    </div>

                    <!-- email e telefone -->
                    <div class="form-linha">
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
                    </div>

                    <!-- assunto -->
                    <div class="form-grupo">
                        <label for="assunto">Assunto *</label>
                        <select id="assunto" name="assunto" required>
                            <option value="">Selecione...</option>
                            <?php
                            $assuntos = ['Dúvida', 'Sugestão', 'Reclamação', 'Elogio', 'Outro'];
                            foreach ($assuntos as $a):
                                $sel = ($dados['assunto'] ?? '') === $a ? 'selected' : '';
                            ?>
                                <option value="<?= $a ?>" <?= $sel ?>><?= $a ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="msg-erro"></span>
                    </div>

                    <!-- mensagem -->
                    <div class="form-grupo">
                        <label for="mensagem">Mensagem *</label>
                        <textarea id="mensagem" name="mensagem" required
                                  placeholder="Escreva sua mensagem aqui..."><?= htmlspecialchars($dados['mensagem'] ?? '') ?></textarea>
                        <span class="msg-erro"></span>
                    </div>

                    <button type="submit" class="btn btn-vermelho btn-full">Enviar Mensagem</button>
                </form>
            </div>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>