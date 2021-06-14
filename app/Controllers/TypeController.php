<?php

namespace App\Controllers;

use App\Models\Type;

class TypeController extends CoreController
{
  public function list()
    {
        $this->checkAuthorization(['admin', 'catalog-manager']);
        $types = Type::findAll();
        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $this->show('type/list', ['types' => $types]);
    }

    public function add()
    {
        $this->checkAuthorization(['admin', 'catalog-manager']);
        $type = new Type();
        $this->show('type/add-update', [
            /*'typeId' => $id,*/
            'type' => $type,
            ]);
    }

    public function addPost()
    {
        $this->checkAuthorization(['admin', 'catalog-manager']);
        $name = filter_input(INPUT_POST,'name', FILTER_SANITIZE_STRING);
        $footer_order = filter_input(INPUT_POST,'footer_order',FILTER_SANITIZE_NUMBER_INT);

        $newType = new Type();
        $newType->setName($name);
        $newType->setFooterOrder($footer_order);
        
        $inserted = $newType->insert();
        
        if ($inserted) {
            global $router;
            $urlToRedirect = $router->generate('type-list');
            header('Location: ' . $urlToRedirect);
            exit();
        }
    }

    public function update($id)
    {
        $this->checkAuthorization(['admin', 'catalog-manager']);
        $type = Type::find($id);
        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $this->show('type/add-update', [
            /*'typeId' => $id,*/
            'type' => $type,
            ]);
    }

    public function updatePost()
    {
        $this->checkAuthorization(['admin', 'catalog-manager']);
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $name = filter_input(INPUT_POST,'name', FILTER_SANITIZE_STRING);
        $footer_order = filter_input(INPUT_POST,'footer_order',FILTER_SANITIZE_NUMBER_INT);

        $currentType = Type::find($id);
        // dump($currentType);
        $currentType->setName($name);
        $currentType->setFooterOrder($footer_order);
        // dump($currentType);
        $updated = $currentType->update();
        
        if ($updated) {
            global $router;
            $urlToRedirect = $router->generate('type-update', ['typeId' => $id]);
            header('Location: ' . $urlToRedirect);
            exit();
        }
    }

    public function delete($id)
    {
        $this->checkAuthorization(['admin', 'catalog-manager']);
        $currentType = Type::find($id);

        $deleted = $currentType->delete();

        if ($deleted) {
            global $router;
            $urlToRedirect = $router->generate('type-list');
            header('Location: ' . $urlToRedirect);
            exit();
        }
    }
}