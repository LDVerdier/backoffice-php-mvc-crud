<?php

namespace App\Controllers;

use App\Models\AppUser;

class UserController extends CoreController
{
  public function login()
  {
    $appUser = new AppUser();

    $this->show('user/login',[
      'appUser' => $appUser
    ]);
  }

  public function loginPost()
  {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password');
    // dump($password);

    $errorList = [];

    if (!$email) {
        $errorList[] = "Veuillez saisir une adresse email valide";
    }
    if ($password == "") {
        $errorList[] = "Veuillez saisir un mot de passe";
    }
    //S'il n'y a pas d'erreur
    if (empty($errorList)) {
        //récupérer en BDD l'utilisateur correspondant à l'email
        $userToLogin = AppUser::findByEmail($email);
        // dd($userToLogin);
        //si utilisateur trouvé :
        if ($userToLogin && $password === $userToLogin->getPassword()) {
        // if ($userToLogin && password_verify($password, $userToLogin->getPassword())) {
            //si mot de passe OK
            global $router;
            $_SESSION['userId'] = $userToLogin->getId();
            $_SESSION['userObject'] = $userToLogin;
            $urlToRedirect = $router->generate('main-home');
            header("Location: {$urlToRedirect}");
            exit();
        } else {
            //si utilisateur pas trouvé et/ou mdp erroné
            $errorList[] = 'Nom d\'utilisateur inconnu et/ou mot de passe erroné !';
        }
    }
    //on arrive ici = il y a eu des erreurs. On les affiche et on réaffiche la page de login
    $appUser = new AppUser();
    $sanitizedEmail = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $appUser->setPassword($sanitizedEmail);
    $this->show('user/login', [
    'errorList' => $errorList,
    'appUser' => $appUser
    ]);
  }

  public function logout()
  {
    unset($_SESSION['userId']);
    unset($_SESSION['userObject']);

    global $router;
    
    $urlToRedirect = $router->generate('user-login');
    header("Location: {$urlToRedirect}");

  }

  public function list()
  {
    $appUsers = AppUser::findAll();
    // On appelle la méthode show() de l'objet courant
    // En argument, on fournit le fichier de Vue
    // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
    $this->show('user/list', ['appUsers' => $appUsers]);
  }

  public function add()
  {
    $this->generateCSRFToken();
    
    $appUser = new AppUser();

    $this->show('user/add-update', [
        /*'appUserId' => $id,*/
        'appUser' => $appUser,
        ]);
  }

  public function addPost()
  {
    $appUser = new AppUser();

    $email = filter_input(INPUT_POST,'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password');
    $firstName = filter_input(INPUT_POST,'firstName',FILTER_SANITIZE_STRING);
    $lastName = filter_input(INPUT_POST,'lastName',FILTER_SANITIZE_STRING);
    $role = filter_input(INPUT_POST,'role',FILTER_SANITIZE_STRING);
    $status = intval(filter_input(INPUT_POST,'status',FILTER_SANITIZE_NUMBER_INT));

    $appUser->setEmail($email);
    $appUser->setPassword(password_hash($password, PASSWORD_DEFAULT));
    $appUser->setFirstName($firstName);
    $appUser->setLastName($lastName);
    $appUser->setRole($role);
    $appUser->setStatus($status);

    //gestion des erreurs

    $errorList = [];
    if (!$email) {
        $errorList[] = 'L\'email saisi n\'est pas valide';
        $appUser->setEmail(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    } else if($this->userAlreadyExists(($email))) {
      $errorList[] = 'L\'adresse email est déjà utilisée.';
    }

    $passwordRegex = '/^(?=(?:.*[A-Z]))(?=(?:.*[a-z]))(?=(?:.*\d))(?=(?:.*[-_|%&*=@$]))([-A-Za-z0-9_|%&*=@$]{8,})$/';
    if ($password == ''){
        $errorList[] = 'Le mot de passe ne doit pas être vide';
    } else if (preg_match($passwordRegex, $password) !== 1 ||preg_match($passwordRegex, $password) === false){
      $errorList[] = 'Le mot de passe doit respecter les règles suivantes :<br>
      - au moins 8 caractères<br>
      - au moins une lettre en minuscule<br>
      - au moins une lettre en majuscule<br>
      - au moins un chiffre
      - au moins un caractère spécial parmi [_, -, |, %, &, *, =, @, $]';
    }

    if ($firstName == ''){
        $errorList[] = 'Le prénom ne doit pas être vide';
    }
    if ($lastName == ''){
        $errorList[] = 'Le nom de famille ne doit pas être vide';
    }
    if ($role !== 'admin' && $role !== 'catalog-manager') {           
        $errorList[] = 'Le role sélectionné n\'est pas valide';
    }
    if ($status !== 1 && $status !== 2) {           
        $errorList[] = 'Le statut sélectionné n\'est pas valide';
    }

    if (empty($errorList)){
      $inserted = $appUser->save();

      if ($inserted) {
          global $router;
          $urlToRedirect = $router->generate('user-list');
          header('Location: ' . $urlToRedirect);
          exit();
      } else{
          $errorList[] = "Un problème a eu lieu lors de l'insertion";
      }
    } else {
        $this->show(
            'user/add-update', 
            [
                'errorList' => $errorList,
                'appUser' => $appUser,
            ]
        );
        exit();
    }
  }

  public function update($id)
  {
      $this->checkAuthorization(['admin']);

      $appUser = AppUser::find($id);
      $this->show('user/add-update', [
          'appUser' => $appUser,
          ]);
  }

  public function updatePost()
  {
    $this->checkAuthorization(['admin']);
  
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $email = filter_input(INPUT_POST,'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password');
    $firstName = filter_input(INPUT_POST,'firstName',FILTER_SANITIZE_STRING);
    $lastName = filter_input(INPUT_POST,'lastName',FILTER_SANITIZE_STRING);
    $role = filter_input(INPUT_POST,'role',FILTER_SANITIZE_STRING);
    $status = intval(filter_input(INPUT_POST,'status',FILTER_SANITIZE_NUMBER_INT));

    $appUser = AppUser::find($id);

    $appUser->setEmail($email);
    $appUser->setPassword(password_hash($password, PASSWORD_DEFAULT));
    $appUser->setFirstName($firstName);
    $appUser->setLastName($lastName);
    $appUser->setRole($role);
    $appUser->setStatus($status);

    //gestion des erreurs

    $errorList = [];
    if (!$email) {
        $errorList[] = 'L\'email saisi n\'est pas valide';
        $appUser->setEmail(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    } 

    $passwordRegex = '/^(?=(?:.*[A-Z]))(?=(?:.*[a-z]))(?=(?:.*\d))(?=(?:.*[-_|%&*=@$]))([-A-Za-z0-9_|%&*=@$]{8,})$/';
    if ($password == ''){
        $errorList[] = 'Le mot de passe ne doit pas être vide';
    } else if (preg_match($passwordRegex, $password) !== 1 ||preg_match($passwordRegex, $password) === false){
      $errorList[] = 'Le mot de passe doit respecter les règles suivantes :<br>
      - au moins 8 caractères<br>
      - au moins une lettre en minuscule<br>
      - au moins une lettre en majuscule<br>
      - au moins un chiffre
      - au moins un caractère spécial parmi [_, -, |, %, &, *, =, @, $]';
    }

    if ($firstName == ''){
        $errorList[] = 'Le prénom ne doit pas être vide';
    }
    if ($lastName == ''){
        $errorList[] = 'Le nom de famille ne doit pas être vide';
    }
    if ($role !== 'admin' && $role !== 'catalog-manager') {           
        $errorList[] = 'Le role sélectionné n\'est pas valide';
    }
    if ($status !== 1 && $status !== 2) {           
        $errorList[] = 'Le statut sélectionné n\'est pas valide';
    }

    if (empty($errorList)){
      $updated = $appUser->save();

      if ($updated) {
          global $router;
          $urlToRedirect = $router->generate('user-list');
          header('Location: ' . $urlToRedirect);
          exit();
      } else{
          $errorList[] = "Un problème a eu lieu lors de la modification";
      }
    } else {
        $this->show(
            'user/add-update', 
            [
                'errorList' => $errorList,
                'appUser' => $appUser,
            ]
        );
        exit();
    }
  }

  public function delete($id)
  {
      $this->checkAuthorization(['admin']);

      $appUser = AppUser::find($id);

      $deleted = $appUser->delete();

      if ($deleted) {
          global $router;
          $urlToRedirect = $router->generate('user-list');
          header('Location: ' . $urlToRedirect);
          exit();
      }
  }

  public function userAlreadyExists($email)
  {
    return AppUser::findByEmail($email);
  }

}