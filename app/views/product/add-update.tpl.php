<?php 
// dump($tags);
//Définition de l'action du formulaire : add si ajout, update si modification d'une catégorie existante
$formActionUrl = ($product->getId() !== null ? $router->generate('product-updatePost') : $router->generate('product-addPost'));
?>
<a href="<?=$router->generate('product-list'); ?>" class="btn btn-success float-right">Retour</a>
<h2><?= $product->getId() !== null ? 'Modifier' : 'Ajouter'; ?> un produit</h2>

<form action="<?= $formActionUrl?>" method="POST" class="mt-5">
    <!--Champ caché contenant l'id du produit à modifier (si applicable)-->
    <div class="form-group d-none">
        <input type="hidden" name="id" value="<?= $product->getId() ?>">
    </div>
    <div class="form-group">
        <label for="name">Nom</label>
        <input type="text" class="form-control" id="name" placeholder="Nom du produit" name="name" value="<?= $product->getName() ?>">
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" id="description" placeholder="Description du produit" name="description"><?= $product->getDescription() ?></textarea>
    </div>
    <div class="form-group">
        <label for="picture">Image</label>
        <input type="text" class="form-control" id="picture" placeholder="image jpg, gif, svg, png" aria-describedby="pictureHelpBlock" name="picture" value="<?= $product->getPicture() ?>">
        <small id="pictureHelpBlock" class="form-text text-muted">
            URL relative d'une image (jpg, gif, svg ou png) fournie sur <a href="https://benoclock.github.io/S06-images/" target="_blank">cette page</a>
        </small>
    </div>
    <div class="form-group">
        <label for="price">Prix</label>
        <input type="number" class="form-control" id="price" min="0" step=".01" name="price" placeholder="un nombre" value="<?= $product->getPrice() ?>">
    </div>
    <div class="form-group">
        <label for="rate">Note</label>
        <select class="form-control" id="rate" name ="rate">
            <option value="">--Choisir une note--</option>
            <?php for($index = 1 ; $index <= 5 ; $index++):?>
                <option value="<?= $index;?>" <?= ($product->getRate() === $index ) ? 'selected' : ''; ?>><?= $index ;?>
            <?php endfor; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="status">Disponibilité</label>
        <select class="form-control" id="status" name ="status">
            <option value="">--Choisir une disponibilité--</option>
            <option value="1" <?= ($product->getStatus() === 1 ) ? 'selected' : ''; ?>>Disponible</option>
            <option value="2" <?= ($product->getStatus() === 2 ) ? 'selected' : ''; ?>>Non disponible</option>
        </select>
    </div>
    <div class="form-group">
        <label for="brand">Marque</label>
        <select class="form-control" id="brand" name ="brand_id">
            <option value="">--Choisir une marque--</option>
            <?php foreach ($brands as $brand):?>
                <option value="<?= $brand->getId();?>" <?= ($product->getBrandId() === $brand->getId() ) ? 'selected' : ''; ?>><?= $brand->getName();?></option>
            <?php endforeach;?>
        </select>
    </div>
    <div class="form-group">
        <label for="category">Catégorie</label>
        <select class="form-control" id="category" name ="category_id">
            <option value="">--Choisir une catégorie--</option>
            <?php foreach ($categories as $category):?>
                <option value="<?= $category->getId();?>" <?= ($product->getCategoryId() === $category->getId() ) ? 'selected' : ''; ?>><?= $category->getName();?></option>
            <?php endforeach;?>
        </select>
    </div>
    <div class="form-group">
        <label for="type">Type</label>
        <select class="form-control" id="type" name ="type_id">
            <option value="">--Choisir un type--</option>
            <?php foreach ($types as $type):?>
                <option value="<?= $type->getId();?>" <?= ($product->getTypeId() === $type->getId() ) ? 'selected' : ''; ?>><?= $type->getName();?></option>
            <?php endforeach;?>
        </select>
    </div>
    <div class="form-group">
        <label for="">Tags</label>
        <?php 
        foreach($tags as $tag):
            $matchingProductsIds = explode(',', $tag->matchingProductsIds);
            // dump($matchingProductsIds);
            // dump($product->getId());
            ?>
            <div class="form-check">
                <input type="checkbox" id="tag<?=$tag->getId()?>" name="tag<?=$tag->getId()?>" class="form-check-input" <?= ($matchingProductsIds[0] !== '' && in_array($product->getId(), $matchingProductsIds)) ? 'checked' : ''?>>
                <label for="tag<?=$tag->getId()?>"><?= $tag->getName();?></label>
            </div>
        <?php 
        endforeach;?>
    </div>

    <input type="hidden" name="token" value="<?=$_SESSION['csrfToken']?>">

        
    <button type="submit" class="btn btn-primary btn-block mt-5">Valider</button>
</form>
