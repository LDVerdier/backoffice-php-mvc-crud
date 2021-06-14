<?php

namespace App\Controllers;

use App\Models\Category;

class CategoryController extends CoreController
{
    public function list()
    {
        $this->checkAuthorization(['admin', 'catalog-manager']);
        $token = $this->generateCSRFToken();
        $categories = Category::findAll();
        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $this->show('category/list', [
            'categories' => $categories,
            'token' => $token,
            ]);
    }

    public function add()
    {
        $this->checkAuthorization(['admin', 'catalog-manager']);
        $this->generateCSRFToken();
        $category = new Category();
        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $this->show('category/add-update', [
            /*'categoryId' => $id,*/
            'category' => $category,
            ]);
    }

    public function addPost()
    {
        $this->checkAuthorization(['admin', 'catalog-manager']);
        $name = filter_input(INPUT_POST,'name', FILTER_SANITIZE_STRING);
        $subtitle = filter_input(INPUT_POST,'subtitle',FILTER_SANITIZE_STRING);
        $picture = filter_input(INPUT_POST,'picture',FILTER_VALIDATE_URL);
        $home_order = intval(filter_input(INPUT_POST,'home_order',FILTER_SANITIZE_NUMBER_INT));

        if ($name == ""){
            $errorList[] = "Le nom ne doit pas être vide";
        }
        if ($subtitle == ""){
            $errorList[] = "Le sous-titre ne doit pas être vide";
        }
        if ($picture === false){           
            $errorList[] = "L'image doit être une url valide";
            $picture = filter_input(INPUT_POST,'picture',FILTER_SANITIZE_URL);
        } else {
            $picture = trim($picture); 
        }

        $newCategory = new Category();

        $newCategory->setName($name);
        $newCategory->setSubtitle($subtitle);
        $newCategory->setPicture($picture);
        $newCategory->setHomeOrder($home_order);
        
        if (empty($errorList)){
            $inserted = $newCategory->save();

            if ($inserted) {
                global $router;
                $urlToRedirect = $router->generate('category-list');
                header('Location: ' . $urlToRedirect);
                exit();
            } else{
                $errorList[] = "Un problème a eu lieu lors de l'insertion";
            }
        } else {
            $this->show(
                'category/add-update', 
                [
                    'errorList' => $errorList,
                    'category' => $newCategory,
                ]
            );
        }
    }

    public function update($id)
    {
        $this->checkAuthorization(['admin', 'catalog-manager']);
        $category = Category::find($id);
        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $this->show('category/add-update', [
            /*'categoryId' => $id,*/
            'category' => $category,
            ]);
    }

    public function updatePost()
    {
        $this->checkAuthorization(['admin', 'catalog-manager']);
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $name = filter_input(INPUT_POST,'name', FILTER_SANITIZE_STRING);
        $subtitle = filter_input(INPUT_POST,'subtitle',FILTER_SANITIZE_STRING);
        $picture = filter_input(INPUT_POST,'picture',FILTER_SANITIZE_URL);
        $home_order = filter_input(INPUT_POST,'home_order',FILTER_SANITIZE_NUMBER_INT);

        $currentCategory = Category::find($id);
        // dump($currentCategory);
        $currentCategory->setName($name);
        $currentCategory->setSubtitle($subtitle);
        $currentCategory->setPicture($picture);
        $currentCategory->setHomeOrder($home_order);
        // dump($currentCategory);
        $updated = $currentCategory->save();
        
        if ($updated) {
            global $router;
            $urlToRedirect = $router->generate('category-update', ['categoryId' => $id]);
            header('Location: ' . $urlToRedirect);
            exit();
        }
    }

    public function delete($id)
    {
        $this->checkAuthorization(['admin', 'catalog-manager']);
        $currentCategory = Category::find($id);

        $deleted = $currentCategory->delete();

        if ($deleted) {
            global $router;
            $urlToRedirect = $router->generate('category-list');
            header('Location: ' . $urlToRedirect);
            exit();
        }
    } 

    public function homeOrder()
    {
        $this->checkAuthorization(['admin', 'catalog-manager']);
        $this->generateCSRFToken();
        $categories = Category::findAll();

        $this->show('category/home-order', [
            'categories' => $categories,
            ]);
    }

    public function homeOrderPost()
    {
        $emplacements = filter_var_array($_POST['emplacement'], FILTER_VALIDATE_INT);
        // dump($emplacements);
        
        $errorList = [];
        if (in_array(false, $emplacements)){
            $errorList[] = 'Vous ne pouvez pas laisser d\'emplacement vide !';
        }

        if (count($emplacements) !== count(array_unique($emplacements))){
            $errorList[] = 'Vous ne pouvez pas sélectionner plusieurs fois le même emplacement !';
        }
        
        if (empty($errorList)) {
            
            //on ajoute une valeur à 0 à l'index 0
            //désormais l'index 1 correspond à la position 1 du home order etc
            array_unshift($emplacements, 0);
            //on parcourt les 5 emplacements
            for ($index = 1 ; $index <=5 ; $index++){
                //on récupère la catégorie située en home_order = $index
                $oldCategory = Category::findByHomeOrder($index);
                //s'il y en a une
                if ($oldCategory){
                    //on lui assigne un home_order à 0
                    $oldCategory->setHomeOrder(0);
                    $oldCategory->update();
                }
                //on récupère la nouvelle catégorie qui doit aller en home_order = $index
                $newCategory = Category::find($emplacements[$index]);
                //s'il y en a une
                if ($newCategory){
                    //on la met en home order = $index
                    $newCategory->setHomeOrder($index);
                    $newCategory->update();
                }
            }
    
            global $router;
            $urlToRedirect = $router->generate('category-homeOrder');
            header("Location: {$urlToRedirect}");
            exit();
        } else {
            $categories = Category::findAll();
            
            $this->generateCSRFToken();
            $this->show(
                'category/home-order',
                [
                    'errorList' => $errorList,
                    'categories' => $categories,
                ]
            );
        }
    }

}