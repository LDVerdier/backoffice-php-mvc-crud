<?php 
//Définition de l'action du formulaire : add si ajout, update si modification d'une catégorie existante
$formActionUrl = ($brand->getId() !== null ? $router->generate('brand-updatePost') : $router->generate('brand-addPost'));
?>
<a href="<?=$router->generate('brand-list'); ?>" class="btn btn-success float-right">Retour</a>

<h2><?= $brand->getId() != null ? 'Modifier' : 'Ajouter'; ?> une marque</h2>

<form action="<?= $formActionUrl?>" method="post" class="mt-5">
    <!--Champ caché contenant l'id de la catégorie à modifier (si applicable)-->
    <div class="form-group d-none">
        <input type="hidden" name="id" value="<?= $brand->getId() ?>">
    </div>

    <div class="form-group">
        <label for="name">Nom</label>
        <input type="text" class="form-control" id="name" placeholder="Nom de la marque" name="name" value="<?= $brand->getName() ?>">
    </div>

    <div class="form-group">
        <label for="footer_order">Place dans le footer</label>
        <select class="form-control" id="footer_order" name ="footer_order">
            <option value="">--Choisir un emplacement dans le footer--</option>
            <option value="0" <?= ($brand->getFooterOrder() === 0) ? 'selected' : ''?>>0 - Non affichée dans le footer</option>
            <?php for($index = 1 ; $index <= 5 ; $index++):?>
                <option value="<?= $index;?>" <?= ($brand->getFooterOrder() === $index) ? 'selected' : ''?>><?= $index ;?>
            <?php endfor; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary btn-block mt-5">Valider</button>
</form>

