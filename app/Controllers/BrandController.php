<?php

namespace App\Controllers;

use App\Models\Brand;

class BrandController extends CoreController
{
  public function list()
    {
        $this->checkAuthorization(['admin', 'catalog-manager']);
        $brands = Brand::findAll();
        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $this->show('brand/list', ['brands' => $brands]);
    }

    public function add()
    {
        $this->checkAuthorization(['admin', 'catalog-manager']);
        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $brand = new Brand();

        $this->show('brand/add-update', [
            /*'brandId' => $id,*/
            'brand' => $brand,
            ]);
    }

    public function addPost()
    {
        $this->checkAuthorization(['admin', 'catalog-manager']);
        $name = filter_input(INPUT_POST,'name', FILTER_SANITIZE_STRING);
        $footer_order = filter_input(INPUT_POST,'footer_order',FILTER_SANITIZE_NUMBER_INT);

        $newBrand = new Brand();
        $newBrand->setName($name);
        $newBrand->setFooterOrder($footer_order);
        
        $inserted = $newBrand->insert();
        
        if ($inserted) {
            global $router;
            $urlToRedirect = $router->generate('brand-list');
            header('Location: ' . $urlToRedirect);
            exit();
        }
    }

    public function update($id)
    {
        $this->checkAuthorization(['admin', 'catalog-manager']);
        $brand = Brand::find($id);
        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $this->show('brand/add-update', [
            /*'brandId' => $id,*/
            'brand' => $brand,
            ]);
    }

    public function updatePost()
    {
        $this->checkAuthorization(['admin', 'catalog-manager']);

        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $name = filter_input(INPUT_POST,'name', FILTER_SANITIZE_STRING);
        $footer_order = filter_input(INPUT_POST,'footer_order',FILTER_SANITIZE_NUMBER_INT);

        $currentBrand = Brand::find($id);
        // dump($currentBrand);
        $currentBrand->setName($name);
        $currentBrand->setFooterOrder($footer_order);
        // dump($currentBrand);
        $updated = $currentBrand->update();
        
        if ($updated) {
            global $router;
            $urlToRedirect = $router->generate('brand-update', ['brandId' => $id]);
            header('Location: ' . $urlToRedirect);
            exit();
        }
    }

    public function delete($id)
    {
        $this->checkAuthorization(['admin', 'catalog-manager']);
        $currentBrand = Brand::find($id);

        $deleted = $currentBrand->delete();

        if ($deleted) {
            global $router;
            $urlToRedirect = $router->generate('brand-list');
            header('Location: ' . $urlToRedirect);
            exit();
        }
    }
}