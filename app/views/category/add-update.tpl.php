<?php 
//Définition de l'action du formulaire : add si ajout, update si modification d'une catégorie existante
$formActionUrl = ($category->getId() !== null ? $router->generate('category-updatePost') : $router->generate('category-addPost'));
?>
<a href="<?=$router->generate('category-list'); ?>" class="btn btn-success float-right">Retour</a>

<h2><?= $category->getId() != null ? 'Modifier' : 'Ajouter'; ?> une catégorie</h2>

<form action="<?= $formActionUrl?>" method="post" class="mt-5">
    <!--Champ caché contenant l'id de la catégorie à modifier (si applicable)-->
    <div class="form-group d-none">
        <input type="hidden" name="id" value="<?= $category->getId() ?>">
    </div>

    <div class="form-group">
        <label for="name">Nom</label>
        <input type="text" class="form-control" id="name" placeholder="Nom de la catégorie" name="name" value="<?= $category->getName() ?>">
    </div>
    <div class="form-group">
        <label for="subtitle">Sous-titre</label>
        <input type="text" class="form-control" id="subtitle" placeholder="Sous-titre" aria-describedby="subtitleHelpBlock" name="subtitle" value="<?= $category->getSubtitle() ?>">
        <small id="subtitleHelpBlock" class="form-text text-muted">
            Sera affiché sur la page d'accueil comme bouton devant l'image
        </small>
    </div>
    <div class="form-group">
        <label for="picture">Image</label>
        <input type="text" class="form-control" id="picture" placeholder="image jpg, gif, svg, png" aria-describedby="pictureHelpBlock" name="picture" value="<?= $category->getPicture() ?>">
        <small id="pictureHelpBlock" class="form-text text-muted">
            URL relative d'une image (jpg, gif, svg ou png) fournie sur <a href="https://benoclock.github.io/S06-images/" target="_blank">cette page</a>
        </small>
    </div>
    <div class="form-group d-none">
        <label for="home_order">Place en page d'accueil</label>
        <select class="form-control" id="home_order" name ="home_order">
            <!-- <option value="">--Choisir un emplacement sur la page d'accueil--</option> -->
            <option value="0" <?= ($category->getHomeOrder() === 0) ? 'selected' : ''?>>0 - Non affichée en page d'accueil</option>
            <?php for($index = 1 ; $index <= 5 ; $index++):?>
                <option value="<?= $index;?>" <?= ($category->getHomeOrder() === $index) ? 'selected' : ''?>><?= $index ;?>
            <?php endfor;?>
        </select>
    </div>

    <input type="hidden" name="token" value="<?=$_SESSION['csrfToken']?>">

    <button type="submit" class="btn btn-primary btn-block mt-5">Valider</button>
</form>

