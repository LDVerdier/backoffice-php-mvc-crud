<?php 
//Définition de l'action du formulaire : add si ajout, update si modification d'une catégorie existante
$formActionUrl = ($appUser->getId() !== null ? $router->generate('user-updatePost') : $router->generate('user-addPost'));
?>
<a href="<?=$router->generate('user-list'); ?>" class="btn btn-success float-right">Retour</a>

<h2><?= $appUser->getId() != null ? 'Modifier' : 'Ajouter'; ?> un utilisateur</h2>

<form action="<?= $formActionUrl?>" method="post" class="mt-5">
    <!--Champ caché contenant l'id de la catégorie à modifier (si applicable)-->
    <div class="form-group d-none">
        <input type="hidden" name="id" value="<?= $appUser->getId() ?>">
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" placeholder="Email de l'utilisateur" name="email" value="<?= $appUser->getEmail() ?>">
    </div>
    <div class="form-group">
        <label for="password">Mot de passe</label>
        <input type="password" class="form-control" id="password" placeholder="Mot de passe de l'utilisateur" name="password">
    </div>
    <div class="form-group">
        <label for="firstName">Prénom</label>
        <input type="text" class="form-control" id="firstName" placeholder="Prénom de l'utilisateur" name="firstName" value="<?= $appUser->getFirstName() ?>">
    </div>
    <div class="form-group">
        <label for="lastName">Nom de famille</label>
        <input type="text" class="form-control" id="lastName" placeholder="Nom de famille de l'utilisateur" name="lastName" value="<?= $appUser->getLastName() ?>">
    </div>
    <div class="form-group">
        <label for="role">Rôle</label>
        <select class="form-control" id="role" name ="role">
            <option value="">--Choisir un rôle--</option>
            <option value="admin" <?= ($appUser->getRole() === 'admin') ? 'selected' : ''?>>Admin</option>
            <option value="catalog-manager" <?= ($appUser->getRole() === 'catalog-manager') ? 'selected' : ''?>>Catalog manager</option>
        </select>
    </div>
    <div class="form-group">
        <label for="status">Statut</label>
        <select class="form-control" id="status" name ="status">
            <option value="">--Choisir un statut--</option>
            <option value="1" <?= ($appUser->getStatus() === 1) ? 'selected' : ''?>>Actif</option>
            <option value="2" <?= ($appUser->getStatus() === 2) ? 'selected' : ''?>>Inactif</option>
        </select>
    </div>

    <input type="hidden" name="token" value="<?=$_SESSION['csrfToken']?>">
    
    <button type="submit" class="btn btn-primary btn-block mt-5">Valider</button>
</form>

