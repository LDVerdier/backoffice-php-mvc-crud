<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?= $router->generate('main-home'); ?>">oShop</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item <?= ($router->match()['name'] === 'main-home') ? 'active' : '';?>">
                    <a class="nav-link" href="<?= $router->generate('main-home'); ?>">Accueil
                    <?php if($router->match()['name'] === 'main-home') :?> 
                        <span class="sr-only">(current)</span>
                    <?php endif; ?>
                </a>
                </li>
                <?php
                if (isset($_SESSION['userObject'])):
                ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($router->match()['name'] === 'category-list') ? 'active' : '';?>" href="<?= $router->generate('category-list'); ?>">Catégories
                    <?php if($router->match()['name'] === 'category-list') :?> 
                            <span class="sr-only">(current)</span>
                        <?php endif; ?>
                    </a>
                    </li>
                    <li class="nav-item <?= ($router->match()['name'] === 'product-list') ? 'active' : '';?>">
                        <a class="nav-link" href="<?= $router->generate('product-list') ?>">Produits
                    <?php if($router->match()['name'] === 'product-list') :?> 
                            <span class="sr-only">(current)</span>
                        <?php endif; ?>
                    </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($router->match()['name'] === 'type-list') ? 'active' : '';?>" href="<?= $router->generate('type-list') ?>">Types
                    <?php if($router->match()['name'] === 'type-list') :?> 
                            <span class="sr-only">(current)</span>
                        <?php endif; ?>
                    </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($router->match()['name'] === 'brand-list') ? 'active' : '';?>" href="<?= $router->generate('brand-list') ?>">Marques
                    <?php if($router->match()['name'] === 'brand-list') :?> 
                            <span class="sr-only">(current)</span>
                        <?php endif; ?>
                    </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $router->generate('main-home') ?>">Tags
                    <?php if($router->match()['name'] === 'main-home') :?> 
                            <span class="sr-only">(current)</span>
                        <?php endif; ?>
                    </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($router->match()['name'] === 'category-homeOrder') ? 'active' : '';?>" href="<?= $router->generate('category-homeOrder') ?>">Sélections Accueil &amp; Footer
                    <?php if($router->match()['name'] === 'category-homeOrder') :?> 
                            <span class="sr-only">(current)</span>
                        <?php endif; ?>
                    </a>
                    <li class="nav-item">
                        <a class="nav-link <?= ($router->match()['name'] === 'user-list') ? 'active' : '';?>" href="<?= $router->generate('user-list') ?>">Utilisateurs
                    <?php if($router->match()['name'] === 'user-list') :?> 
                            <span class="sr-only">(current)</span>
                        <?php endif; ?>
                    </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?=$router->generate('user-logout')?>">Déconnexion</a>
                    </li>
                <?php                
                endif;
                ?>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Rechercher" aria-label="Rechercher">
                <button class="btn btn-outline-info my-2 my-sm-0" type="submit">Rechercher</button>
            </form>
        </div>
    </div>
</nav>