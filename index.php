<?php $titulo = 'Home'; ?>
<?php include 'includes/header.php'; ?>

<!-- hero -->
<section class="hero">
    <div class="hero-bg">
        <div class="container">
            <div class="hero-texto">
                <div class="hero-tag">Delivery - Campo Grande, MS</div>
                <h1>Melhor<br><span>Pizzaria</span><br>da Cidade</h1>
                <p>Forno a lenha, ingredientes frescos e entrega rápida na sua porta. Todo dia, do jeito que você merece.</p>
                <div class="hero-btns">
                    <a href="pedidos.php" class="btn-hero-principal">Pedir Agora</a>
                    <a href="cardapio.php" class="btn-hero-secundario">Ver Cardápio</a>
                </div>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <strong>500+</strong>
                        <span>Pedidos por mês</span>
                    </div>
                    <div class="hero-divider"></div>
                    <div class="hero-stat">
                        <strong>4.9</strong>
                        <span>Avaliação média</span>
                    </div>
                    <div class="hero-divider"></div>
                    <div class="hero-stat">
                        <strong>30min</strong>
                        <span>Tempo de entrega</span>
                    </div>
                </div>
            </div>
            <div class="hero-img">
                <div class="hero-img-glow"></div>
                <img src="IMG/pizza.png" alt="Pizza Arantes">
            </div>
        </div>
    </div>
</section>

<!-- promocoes -->
<section class="promocoes">
    <div class="container">

        <div class="promocoes-header">
            <span class="promocoes-tag">So essa semana</span>
            <h2>Promoções que a gente fez<br>pensando em você</h2>
            <p>Sabor de verdade, sem pesar no bolso. Aproveite antes que acabe.</p>
        </div>

        <div class="promocoes-grid">

            <!-- card promocao -->
            <div class="promo-card">
                <div class="promo-img-wrap">
                    <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?w=600&q=80" alt="Pizza Margherita" loading="lazy">
                    <span class="promo-badge">-30%</span>
                </div>
                <div class="promo-corpo">
                    <h3>Margherita Clássica</h3>
                    <p>Molho artesanal, mussarela fresca e manjericão colhido na hora. A favorita de sempre.</p>
                    <div class="promo-precos">
                        <span class="preco-antigo">R$ 49,90</span>
                        <span class="preco-novo">R$ 34,90</span>
                    </div>
                    <a href="pedidos.php" class="btn-promo">Pedir agora</a>
                </div>
            </div>

            <!-- destaque -->
            <div class="promo-card destaque">
                <div class="promo-img-wrap">
                    <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600&q=80" alt="Pizza Calabresa" loading="lazy">
                    <span class="promo-badge badge-gold">Mais pedida</span>
                </div>
                <div class="promo-corpo">
                    <h3>Calabresa Especial</h3>
                    <p>Calabresa artesanal, cebola roxa e orégano. O sabor que todo Campo Grande ama.</p>
                    <div class="promo-precos">
                        <span class="preco-antigo">R$ 54,90</span>
                        <span class="preco-novo">R$ 39,90</span>
                    </div>
                    <a href="pedidos.php" class="btn-promo">Pedir agora</a>
                </div>
            </div>

            <div class="promo-card">
                <div class="promo-img-wrap">
                    <img src="https://images.unsplash.com/photo-1574071318508-1cdbab80d002?w=600&q=80" alt="Pizza Frango com Catupiry" loading="lazy">
                    <span class="promo-badge">-25%</span>
                </div>
                <div class="promo-corpo">
                    <h3>Frango com Catupiry</h3>
                    <p>Frango desfiado na brasa e catupiry cremoso. Combinação que nunca decepciona.</p>
                    <div class="promo-precos">
                        <span class="preco-antigo">R$ 57,90</span>
                        <span class="preco-novo">R$ 43,90</span>
                    </div>
                    <a href="pedidos.php" class="btn-promo">Pedir agora</a>
                </div>
            </div>

        </div>

        <!-- aviso -->
        <div class="promo-aviso">
            <p>Promoções válidas de <strong>segunda a sexta, das 18h às 22h</strong>. Não perca.</p>
            <a href="cardapio.php" class="btn-ver-mais">Ver cardápio completo</a>
        </div>

    </div>
</section>

<?php include 'includes/footer.php'; ?>