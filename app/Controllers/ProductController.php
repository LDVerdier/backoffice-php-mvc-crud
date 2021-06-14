<?php

namespace App\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use App\Models\Type;

class ProductController extends CoreController
{
    public function list()
    {
        $this->checkAuthorization(['admin', 'catalog-manager']);
        $products = Product::findAll();
        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $this->show('product/list', ['products' => $products]);
    }

    public function add()
    {
        $this->generateCSRFToken();
        $this->checkAuthorization(['admin', 'catalog-manager']);
        $brands = Brand::findAll();
        $categories = Category::findAll();
        $types = Type::findAll();
        $tags = Tag::findAll();
        $product = new Product();
        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $this->show('product/add-update', [
            'brands' => $brands,
            'categories' => $categories,
            'types' => $types,
            'tags' => $tags,
            'product' => $product,
            ]);
    }

    public function addPost()
    {
        $this->checkAuthorization(['admin', 'catalog-manager']);
        // dump($_POST);
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        $picture = filter_input(INPUT_POST, 'picture', FILTER_SANITIZE_URL);
        $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
        $rate = filter_input(INPUT_POST, 'rate', FILTER_SANITIZE_NUMBER_INT);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_NUMBER_INT);
        $brand_id = filter_input(INPUT_POST, 'brand_id', FILTER_SANITIZE_NUMBER_INT);
        $category_id = filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT);
        $type_id = filter_input(INPUT_POST, 'type_id', FILTER_SANITIZE_NUMBER_INT);

        // $tags = '(';
        

        
        $newProduct = new Product();
        
        $newProduct->setName($name);
        $newProduct->setDescription($description);
        $newProduct->setPicture($picture);
        $newProduct->setPrice($price);
        $newProduct->setRate($rate);
        $newProduct->setStatus($status);
        $newProduct->setBrandId($brand_id);
        $newProduct->setCategoryId($category_id);
        $newProduct->setTypeId($type_id);
        
        // dump($newProduct);
        $productId = $newProduct->insert();
        
        if ($productId) {
            Tag::updateTagsOnProduct($productId);
            global $router;
            $redirectUrl = $router->generate('product-list');
            header("Location: {$redirectUrl}");
            exit;
        }
    }

    public function update($id)
    {
        $this->generateCSRFToken();
        $this->checkAuthorization(['admin', 'catalog-manager']);
        $brands = Brand::findAll();
        $categories = Category::findAll();
        $types = Type::findAll();
        $tags = Tag::findAll();

        $product = Product::find($id);
        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $this->show('product/add-update', [
            /*'categoryId' => $id,*/
            'product' => $product,
            'brands' => $brands,
            'categories' => $categories,
            'types' => $types,
            'tags' => $tags,
            ]);
    }

    public function updatePost()
    {    
        $this->checkAuthorization(['admin', 'catalog-manager']);
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        $picture = filter_input(INPUT_POST, 'picture', FILTER_SANITIZE_URL);
        $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
        $rate = filter_input(INPUT_POST, 'rate', FILTER_SANITIZE_NUMBER_INT);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_NUMBER_INT);
        $brand_id = filter_input(INPUT_POST, 'brand_id', FILTER_SANITIZE_NUMBER_INT);
        $category_id = filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT);
        $type_id = filter_input(INPUT_POST, 'type_id', FILTER_SANITIZE_NUMBER_INT);
        
        $currentProduct = Product::find($id);
        
        $currentProduct->setName($name);
        $currentProduct->setDescription($description);
        $currentProduct->setPicture($picture);
        $currentProduct->setPrice($price);
        $currentProduct->setRate($rate);
        $currentProduct->setStatus($status);
        $currentProduct->setBrandId($brand_id);
        $currentProduct->setCategoryId($category_id);
        $currentProduct->setTypeId($type_id);
        
        $updated = $currentProduct->update();

        if ($updated) {

            Tag::updateTagsOnProduct($id);

            global $router;
            $urlToRedirect = $router->generate('product-update', ['productId' => $id]);
            header('Location: ' . $urlToRedirect);
            exit();
        }
    }

    public function delete($id)
    {
        $this->checkAuthorization(['admin', 'catalog-manager']);
        $currentProduct = Product::find($id);

        $deleted = $currentProduct->delete();

        if ($deleted) {
            Tag::removeTagsFromProduct($id);
            global $router;
            $urlToRedirect = $router->generate('product-list');
            header('Location: ' . $urlToRedirect);
            exit();
        }
    }
}