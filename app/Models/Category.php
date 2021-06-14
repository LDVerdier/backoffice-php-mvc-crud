<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class Category extends CoreModel {

    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $subtitle;
    /**
     * @var string
     */
    private $picture;
    /**
     * @var int
     */
    private $home_order;

    /**
     * Get the value of name
     *
     * @return  string
     */ 
    public function getName() : ?string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param  string  $name
     */ 
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get the value of subtitle
     */ 
    public function getSubtitle() : ?string
    {
        return $this->subtitle;
    }

    /**
     * Set the value of subtitle
     */ 
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
    }

    /**
     * Get the value of picture
     */ 
    public function getPicture() : ?string
    {
        return $this->picture;
    }

    /**
     * Set the value of picture
     */ 
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /**
     * Get the value of home_order
     */ 
    public function getHomeOrder() : ?int
    {
        return $this->home_order;
    }

    /**
     * Set the value of home_order
     */ 
    public function setHomeOrder($home_order)
    {
        $this->home_order = $home_order;
    }

    /**
     * Méthode permettant de récupérer un enregistrement de la table Category en fonction d'un id donné
     * 
     * @param int $categoryId ID de la catégorie
     * @return Category
     */
    public static function find($categoryId)
    {
        // se connecter à la BDD
        $pdo = Database::getPDO();

        // écrire notre requête
        $sql = 'SELECT * FROM `category` WHERE `id` =' . $categoryId;

        // exécuter notre requête
        $pdoStatement = $pdo->query($sql);

        // un seul résultat => fetchObject
        $category = $pdoStatement->fetchObject('App\Models\Category');

        // retourner le résultat
        return $category;
    }

    /**
     * Méthode permettant de récupérer tous les enregistrements de la table category
     * 
     * @return Category[]
     */
    public static function findAll()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `category`';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);
        
        return $results;
    }

    public function setHomeOrderToZero()
    {
        dump($this->getId());
    }

    /**
     * Récupérer les 5 catégories mises en avant sur la home
     * 
     * @return Category[]
     */
    public function findAllHomepage()
    {
        $pdo = Database::getPDO();
        $sql = '
            SELECT *
            FROM category
            WHERE home_order > 0
            ORDER BY home_order ASC
        ';
        $pdoStatement = $pdo->query($sql);
        $categories = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Category');
        
        return $categories;
    }

    /**
     * Récupérer les 3 catégories mises en avant sur la home
     * 
     * @return Category[]
     */
    public static function findForHomePageBackOffice()
    {
        $pdo = Database::getPDO();
        $sql = '
            SELECT *
            FROM category
            LIMIT 3
        ';
        $pdoStatement = $pdo->query($sql);
        $categories = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Category');
        
        return $categories;
    }

    public static function findByHomeOrder($homeOrder)
    {
        //on veut récupérer un tableau de la forme :
        /*$homeOrderCategories = [
            1 => id de la catégorie en position 1
            2 => id de la catégorie en position 2
            3 => 0 si aucune catégorie à cette position
            ...
        ];
        2 possibilité : 
        I. boucler 5 fois pour remplir un tableau avec un for
        II. faire une requête SQL qui retourne directement
        un tableau avec 5 entrées, avec des 0 ou des null si pas de 
        catégorie à telle ou telle position
        */

        $pdo = Database::getPDO();

        $sql = "
            SELECT *
            FROM `category`
            WHERE `home_order` =:homeOrder
            LIMIT 1
        " ;

        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->bindValue(':homeOrder', $homeOrder);

        $requestExecuted = $pdoStatement->execute();

        if ($requestExecuted){
            $foundCategory = $pdoStatement->fetchObject(self::class);
            if ($foundCategory){
              return $foundCategory;
            } else {
                return false;
            }
          }
          return false;
    }

    public function insert()
    {
        $pdo = Database::getPDO();
        //on écrit une requête préparée avec des emplacements prévus pour recevoir des valeurs
        $sql = "
            INSERT INTO `category` (`name`, `subtitle`, `picture`, `home_order`)
            VALUES (:name, :subtitle, :picture, :home_order)
        ;";

        //on récupère la requête préparée
        $pdoStatement = $pdo->prepare($sql);

        //on associe les valeurs aux paramètres

        $pdoStatement->bindValue(':name', $this->name);
        $pdoStatement->bindValue(':subtitle', $this->subtitle);
        $pdoStatement->bindValue(':picture', $this->picture);
        $pdoStatement->bindValue(':home_order', $this->home_order);

        $insertedRows = $pdoStatement->execute();
        
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
            UPDATE `category`
            SET `name`=:name,
            `subtitle`=:subtitle,
            `picture`=:picture,
            `home_order`=:home_order,
            `updated_at`=NOW()
            WHERE `id`=:id
            LIMIT 1;
        ";
        $pdoStatement = $pdo->prepare($sql);

        //on associe les valeurs aux paramètres

        $pdoStatement->bindValue(':name', $this->name);
        $pdoStatement->bindValue(':subtitle', $this->subtitle);
        $pdoStatement->bindValue(':picture', $this->picture);
        $pdoStatement->bindValue(':home_order', $this->home_order, PDO::PARAM_INT);   
        $pdoStatement->bindValue(':id', $this->id, PDO::PARAM_INT);   
        $updatedRows = $pdoStatement->execute();
        
        if ($updatedRows > 0) {
            // On retourne VRAI car l'ajout a parfaitement fonctionné
            
            return true;
            // => l'interpréteur PHP sort de cette fonction car on a retourné une donnée
        }
        //https://benoclock.github.io/S06-images/categ4.jpeg
        // Si on arrive ici, c'est que quelque chose n'a pas bien fonctionné => FAUX
        return false;
    }

    public function delete()
    {
        $pdo = Database::getPDO();
        $sql = "
            DELETE FROM `category`
            WHERE `id`=:id
            LIMIT 1;
        ";
        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->bindValue(':id', $this->id, PDO::PARAM_INT);   

        $deletedRows = $pdoStatement->execute();

        return ($deletedRows > 0);
    }
}
