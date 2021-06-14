
    <a href="<?=$router->generate('user-add'); ?>" class="btn btn-success float-right">Ajouter</a>
    <h2>Liste des utilisateurs</h2>
    <table class="table table-hover mt-4">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Email</th>
                <th scope="col">Prénom</th>
                <th scope="col">Nom</th>
                <th scope="col">Rôle</th>
                <th scope="col">Statut</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($appUsers as $appUser): ?>
            <tr>
                <th scope="row"><?= $appUser->getId();?></th>
                <td><?= $appUser->getEmail();?></td>
                <td><?= $appUser->getFirstName();?></td>
                <td><?= $appUser->getLastName();?></td>
                <td><?= $appUser->getRole();?></td>
                <td><?php if($appUser->getStatus() === 1) {echo 'actif';} else if ($appUser->getStatus() === 2) {echo 'inactif';}?></td>
                
                <td class="text-right">
                    <a href="<?= $router->generate('user-update', ['userId' => $appUser->getId()]);?>" class="btn btn-sm btn-warning">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    </a>
                    <!-- Example single danger button -->
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="<?= $router->generate('user-delete', ['userId' => $appUser->getId()]) ?>">Oui, je veux supprimer</a>
                            <a class="dropdown-item" href="#" data-toggle="dropdown">Oups !</a>
                        </div>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
