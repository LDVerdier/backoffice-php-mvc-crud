<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class AppUser extends CoreModel
{

  private $email;

  private $password;

  private $firstname;

  private $lastname;

  private $role;

  private $status;


  public static function findAll()
  {
    $pdo = Database::getPDO();
    $sql = 'SELECT * FROM `app_user`';
    $pdoStatement = $pdo->query($sql);
    $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);
    
    return $results;
  }

  public static function find($id)
  {
      // se connecter à la BDD
      $pdo = Database::getPDO();

      // écrire notre requête
      $sql = '
          SELECT *
          FROM `app_user`
          WHERE `id` = ' . $id;

      // exécuter notre requête
      $pdoStatement = $pdo->query($sql);

      // un seul résultat => fetchObject
      $appUser = $pdoStatement->fetchObject(self::class);

      // retourner le résultat
      return $appUser;
  }

  public static function findByEmail($email)
  {
    $pdo = Database::getPDO();
    // écrire notre requête
    $sql = "
        SELECT *
        FROM `app_user`
        WHERE `email` =:email
        " ;

    // exécuter notre requête
    $pdoStatement = $pdo->prepare($sql);
    $pdoStatement->bindValue(':email', $email);
    //retourne true si la requête a été correctement exécutée
    $requestExecuted = $pdoStatement->execute();
    
    // un seul résultat => fetchObject
    if ($requestExecuted){
      $foundUser = $pdoStatement->fetchObject(self::class);
      if ($foundUser){
        return $foundUser;
      }
    }
    return false;
  }

  public function insert()
  {
    $pdo = Database::getPDO();
    //on écrit une requête préparée avec des emplacements prévus pour recevoir des valeurs
    $sql = "
        INSERT INTO `app_user` (`email`, `password`, `firstname`, `lastname`, `role`, `status`)
        VALUES (:email, :password, :firstName, :lastName, :role, :status)
    ;";

    //on récupère la requête préparée
    $pdoStatement = $pdo->prepare($sql);

    //on associe les valeurs aux paramètres


    $insertedRows = $pdoStatement->execute([
      ':email' => $this->email,
      ':password' => $this->password,
      ':firstName' => $this->firstname,
      ':lastName' => $this->lastname,
      ':role' => $this->role,
      ':status' => $this->status
      ]);
    
    if ($insertedRows > 0) {
        // Alors on récupère l'id auto-incrémenté généré par MySQL
        $this->id = $pdo->lastInsertId();

        // On retourne VRAI car l'ajout a parfaitement fonctionné
        return true;
        // => l'interpréteur PHP sort de cette fonction car on a retourné une donnée
    }
    
    // Si on arrive ici, c'est que quelque chose n'a pas bien fonctionné => FAUX
    return false;
  }
  
  public function update()
  {
    $pdo = Database::getPDO();
    $sql = "
        UPDATE `app_user`
        SET `email`=:email,
        `password`=:password,
        `firstname`=:firstName,
        `lastname`=:lastName,
        `role`=:role,
        `status`=:status,
        `updated_at`=NOW()
        WHERE `id`=:id
        LIMIT 1;
    ";
    $pdoStatement = $pdo->prepare($sql);

    //on associe les valeurs aux paramètres

    $updatedRows = $pdoStatement->execute([
      ':email' => $this->email,
      ':password' => $this->password,
      ':firstName' => $this->firstname,
      ':lastName' => $this->lastname,
      ':role' => $this->role,
      ':status' => $this->status,
      ':id' => $this->id
      ]);
    
    if ($updatedRows > 0) {
        // On retourne VRAI car l'ajout a parfaitement fonctionné
        
        return true;
        // => l'interpréteur PHP sort de cette fonction car on a retourné une donnée
    }
    // Si on arrive ici, c'est que quelque chose n'a pas bien fonctionné => FAUX
    return false;
  }
  
  public function delete()
  {
    $pdo = Database::getPDO();
    $sql = "
        DELETE FROM `app_user`
        WHERE `id`=:id
        LIMIT 1;
    ";
    $pdoStatement = $pdo->prepare($sql);
    $pdoStatement->bindValue(':id', $this->id, PDO::PARAM_INT);   

    $deletedRows = $pdoStatement->execute();

    return ($deletedRows > 0);
  }

  /**
   * Get the value of email
   */ 
  public function getEmail() :?string
  {
    return $this->email;
  }

  /**
   * Set the value of email
   *
   * @return  self
   */ 
  public function setEmail($email)
  {
    $this->email = $email;

    return $this;
  }

  /**
   * Get the value of password
   */ 
  public function getPassword() :?string
  {
    return $this->password;
  }

  /**
   * Set the value of password
   *
   * @return  self
   */ 
  public function setPassword($password)
  {
    $this->password = $password;

    return $this;
  }

  /**
   * Get the value of firstname
   */ 
  public function getFirstName() :?string
  {
    return $this->firstname;
  }

  /**
   * Set the value of firstname
   *
   * @return  self
   */ 
  public function setFirstName($firstName)
  {
    $this->firstname = $firstName;

    return $this;
  }

  /**
   * Get the value of lastname
   */ 
  public function getLastName() :?string
  {
    return $this->lastname;
  }

  /**
   * Set the value of lastname
   *
   * @return  self
   */ 
  public function setLastName($lastName)
  {
    $this->lastname = $lastName;

    return $this;
  }

  /**
   * Get the value of role
   */ 
  public function getRole() :?string
  {
    return $this->role;
  }

  /**
   * Set the value of role
   *
   * @return  self
   */ 
  public function setRole($role)
  {
    $this->role = $role;

    return $this;
  }

  /**
   * Get the value of status
   */ 
  public function getStatus() :?int
  {
    return $this->status;
  }

  /**
   * Set the value of status
   *
   * @return  self
   */ 
  public function setStatus($status)
  {
    $this->status = $status;

    return $this;
  }
}