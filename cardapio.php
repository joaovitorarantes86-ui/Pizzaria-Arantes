<?php
$titulo = 'Cardápio';

try {
    // busca pizzas ativas do banco
    require_once 'includes/conexao.php';
    $pizzas = $pdo->query("SELECT * FROM cardapio WHERE ativo = 1 ORDER BY nome")->fetchAll();
} catch (Exception $e) {
    $pizzas = [];
}

// fallback se banco vazio ou sem conexao
if (empty($pizzas)):
    $pizzas = [
        ['imagem' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=800', 'nome' => 'Margherita', 'descricao' => 'Molho de tomate fresco, mussarela de búfala e manjericão.', 'preco' => 42.90],
        ['imagem' => 'https://images.unsplash.com/photo-1590947132387-155cc02f3212?w=800', 'nome' => 'Carne Seca', 'descricao' => 'Carne seca desfiada, catupiry, cebola caramelizada.', 'preco' => 54.90],
        ['imagem' => 'https://images.unsplash.com/photo-1571407970349-bc81e7e96d47?w=800', 'nome' => 'Frango com Requeijão', 'descricao' => 'Frango temperado, requeijão cremoso, milho e orégano.', 'preco' => 48.90],
        ['imagem' => 'https://images.unsplash.com/photo-1511689660979-10d2b1aada49?w=800', 'nome' => 'Alho e Óleo', 'descricao' => 'Azeite extra virgem, alho dourado, parmesão e salsinha.', 'preco' => 38.90],
        ['imagem' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=800', 'nome' => 'Chocolate c/ Morango', 'descricao' => 'Creme de chocolate belga, morangos frescos e granulado.', 'preco' => 46.90],
        ['imagem' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=800', 'nome' => 'Calabresa Especial', 'descricao' => 'Calabresa artesanal fatiada, cebola roxa, mussarela.', 'preco' => 44.90],
    ];
endif;
?>
<?php include 'includes/header.php'; ?>

<!-- cardapio -->
<section class="cardapio">
    <div class="container">
        <div class="sec-titulo">
            <h2>Nosso <span>Cardápio</span></h2>
            <p>Pizzas artesanais feitas com amor e ingredientes selecionados</p>
        </div>

        <!-- grid de pizzas -->
        <div class="pizzas-grid">
            <?php foreach ($pizzas as $pizza): ?>
             <div class="pizza-card">
                <div class="pizza-thumb">
                     <?php $img_src = htmlspecialchars($pizza['imagem'] ?? ''); ?>
                     <img src="<?= $img_src ?>" alt="<?= htmlspecialchars($pizza['nome']) ?>"
                          loading="lazy"
                          onerror="this.parentElement.innerHTML='<div style=height:200px;display:flex;align-items:center;justify-content:center;background:#eee;color:#999;font-size:13px;>Sem imagem</div>'">
                </div>
                <div class="pizza-info">
                    <h3><?= htmlspecialchars($pizza['nome']) ?></h3>
                    <p><?= htmlspecialchars($pizza['descricao']) ?></p>
                    <div class="pizza-footer">
                        <span class="preco">R$ <?= number_format($pizza['preco'], 2, ',', '.') ?></span>
                        <a href="pedidos.php" class="btn btn-vermelho" style="padding:8px 18px; font-size:13px;">Pedir</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
