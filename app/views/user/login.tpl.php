<h2>Connexion au Back-Office</h2>
<form action="<?= $router->generate('user-loginPost')?>" method="post" class="mt-5">
    <!--Champ caché contenant l'id de la catégorie à modifier (si applicable)-->
    <div class="form-group d-none">
        <input type="hidden" name="id" value="">
    </div>

    <div class="form-group">
        <label for="name">Votre email</label>
        <input type="email" class="form-control" id="email" placeholder="Votre email" name="email" value="<?= $appUser->getPassword()?>">
    </div>
    <div class="form-group">
        <label for="password">Mot de passe</label>
        <input type="password" class="form-control" id="password" placeholder="Votre mot de passe" name="password" value="">
    </div>
    <button type="submit" class="btn btn-primary btn-block mt-5">Connexion</button>
</form>