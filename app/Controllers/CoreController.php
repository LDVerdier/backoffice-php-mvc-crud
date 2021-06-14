<?php

namespace App\Controllers;

abstract class CoreController {

    public function __construct()
    {
        //liste qui va contenir toutes les routes existantes et les roles qui ont le droit d'acceder à cette route
        //si une route n'a pas besoin d'être protégée, on ne l'ajoute pas à la liste (par ex. page de login)
        //acl pour access controls list

        $acl = [
            'main-home' => ['admin', 'catalog-manager'],
            'user-list' => ['admin'],
            'user-add' => ['admin'],
            'user-addPost' => ['admin'],
        ];

        //la variable $match contient les infos de la route courante.
        global $match;
        $routeName = $match['name'];

        if (array_key_exists($routeName, $acl)){
            $authorizedRoles = $acl[$routeName];
            $this->checkAuthorization(($authorizedRoles));
        }

        //lister les routes à protéger d'une attaque -> toutes les routes avec formulaires
        $csrfTokenToCheckInPost = [
            'user-addPost',
            'product-addPost',
            'category-addPost',
            'category-homeOrderPost',
        ];

        $csrfTokenToCheckInGet = [
            'category-delete',
        ];

        //si la route demandée fait partie des routes protégées par un csrfToken
        if (in_array($routeName, $csrfTokenToCheckInPost)){
            $this->checkCSRFToken(INPUT_POST);
        }

        if (in_array($routeName, $csrfTokenToCheckInGet)){
            $this->checkCSRFToken(INPUT_GET);
        }

    }

    public function checkAuthorization($roles=[])
    {
        global $router;

        if (!isset($_SESSION['userObject'])){
            $urlToRedirect = $router->generate('user-login');
            header("Location: {$urlToRedirect}");
            exit();
        }
        
        $userObject = $_SESSION['userObject'];
        $role = $userObject->getRole();

        if (in_array($role, $roles)){
            return true;
        }

        $this->show('error/err403');
        exit();
    }

    public function generateCSRFToken()
    {
        //générer un token (string) aléatoire
        $bytes = random_bytes(5);
        $token = bin2hex($bytes);
        //le stocker en session
        $_SESSION['csrfToken'] = $token;
        //renvoyer le token
        return $token;
    }

    public function checkCSRFToken($method)
    {
        $userToken = filter_input($method, 'token', FILTER_SANITIZE_STRING);
        $sessionToken = $_SESSION['csrfToken'];

        if(empty($userToken) || empty($sessionToken) || $userToken !== $sessionToken){
            http_response_code(403);
            $this->show('error/err403');
            exit();
        }
        unset($_SESSION['csrfToken']);
    }


    /**
     * Méthode permettant d'afficher du code HTML en se basant sur les views
     *
     * @param string $viewName Nom du fichier de vue
     * @param array $viewVars Tableau des données à transmettre aux vues
     * @return void
     */
    protected function show(string $viewName, $viewVars = [])
    {
        // On globalise $router car on ne sait pas faire mieux pour l'instant
        global $router;

        // Comme $viewVars est déclarée comme paramètre de la méthode show()
        // les vues y ont accès
        // ici une valeur dont on a besoin sur TOUTES les vues
        // donc on la définit dans show()
        $viewVars['currentPage'] = $viewName; 

        // définir l'url absolue pour nos assets
        $viewVars['assetsBaseUri'] = $_SERVER['BASE_URI'] . 'assets/';
        // définir l'url absolue pour la racine du site
        // /!\ != racine projet, ici on parle du répertoire public/
        $viewVars['baseUri'] = $_SERVER['BASE_URI'];

        // On veut désormais accéder aux données de $viewVars, mais sans accéder au tableau
        // La fonction extract permet de créer une variable pour chaque élément du tableau passé en argument
        extract($viewVars);
        // => la variable $currentPage existe désormais, et sa valeur est $viewName
        // => la variable $assetsBaseUri existe désormais, et sa valeur est $_SERVER['BASE_URI'] . '/assets/'
        // => la variable $baseUri existe désormais, et sa valeur est $_SERVER['BASE_URI']
        // => il en va de même pour chaque élément du tableau

        // $viewVars est disponible dans chaque fichier de vue

        //rajouter une condition pour ne pas ajouter le header et le footer en cas de 404 ?
        if ($viewName !== 'error/err404'){
            require_once __DIR__.'/../views/layout/header.tpl.php';
        }

        require_once __DIR__.'/../views/'.$viewName.'.tpl.php';

        if ($viewName !== 'error/err404') {
            require_once __DIR__.'/../views/layout/footer.tpl.php';
        }
    }
}
