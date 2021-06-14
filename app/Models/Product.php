<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

/**
 * Une instance de Product = un produit dans la base de données
 * Product hérite de CoreModel
 */
class Product extends CoreModel {
    
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $description;
    /**
     * @var string
     */
    private $picture;
    /**
     * @var float
     */
    private $price;
    /**
     * @var int
     */
    private $rate;
    /**
     * @var int
     */
    private $status;
    /**
     * @var int
     */
    private $brand_id;
    /**
     * @var int
     */
    private $category_id;
    /**
     * @var int
     */
    private $type_id;
    
    /**
     * Méthode permettant de récupérer un enregistrement de la table Product en fonction d'un id donné
     * 
     * @param int $productId ID du produit
     * @return Product
     */
    public static function find($productId)
    {
        // récupérer un objet PDO = connexion à la BDD
        $pdo = Database::getPDO();

        // on écrit la requête SQL pour récupérer le produit
        $sql = '
            SELECT *
            FROM product
            WHERE id = ' . $productId;

        // query ? exec ?
        // On fait de la LECTURE = une récupration => query()
        // si on avait fait une modification, suppression, ou un ajout => exec
        $pdoStatement = $pdo->query($sql);

        // fetchObject() pour récupérer un seul résultat
        // si j'en avais eu plusieurs => fetchAll
        $result = $pdoStatement->fetchObject('App\Models\Product');
        
        return $result;
    }

    /**
     * Méthode permettant de récupérer tous les enregistrements de la table product
     * 
     * @return Product[]
     */
    public static function findAll()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `product`';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Product');
        
        return $results;
    }

    public function delete()
    {
        $pdo = Database::getPDO();
        $sql = "
            DELETE FROM `product`
            WHERE `id`=:id
            LIMIT 1;
        ";
        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->bindValue(':id', $this->id, PDO::PARAM_INT);   

        $deletedRows = $pdoStatement->execute();

        return ($deletedRows > 0);
    }

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
     * Get the value of description
     *
     * @return  string
     */ 
    public function getDescription() : ?string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @param  string  $description
     */ 
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * Get the value of picture
     *
     * @return  string
     */ 
    public function getPicture() : ?string
    {
        return $this->picture;
    }

    /**
     * Set the value of picture
     *
     * @param  string  $picture
     */ 
    public function setPicture(string $picture)
    {
        $this->picture = $picture;
    }

    /**
     * Get the value of price
     *
     * @return  float
     */ 
    public function getPrice() : ?float
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @param  float  $price
     */ 
    public function setPrice(float $price)
    {
        $this->price = $price;
    }

    /**
     * Get the value of rate
     *
     * @return  int
     */ 
    public function getRate() : ?int
    {
        return $this->rate;
    }

    /**
     * Set the value of rate
     *
     * @param  int  $rate
     */ 
    public function setRate(int $rate)
    {
        $this->rate = $rate;
    }

    /**
     * Get the value of status
     *
     * @return  int
     */ 
    public function getStatus() : ?int
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @param  int  $status
     */ 
    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    /**
     * Get the value of brand_id
     *
     * @return  int
     */ 
    public function getBrandId() : ?int
    {
        return $this->brand_id;
    }

    /**
     * Set the value of brand_id
     *
     * @param  int  $brand_id
     */ 
    public function setBrandId(int $brand_id)
    {
        $this->brand_id = $brand_id;
    }

    /**
     * Get the value of category_id
     *
     * @return  int
     */ 
    public function getCategoryId() : ?int
    {
        return $this->category_id;
    }

    /**
     * Set the value of category_id
     *
     * @param  int  $category_id
     */ 
    public function setCategoryId(int $category_id)
    {
        $this->category_id = $category_id;
    }

    /**
     * Get the value of type_id
     *
     * @return  int
     */ 
    public function getTypeId() : ?int
    {
        return $this->type_id;
    }

    /**
     * Set the value of type_id
     *
     * @param  int  $type_id
     */ 
    public function setTypeId(int $type_id)
    {
        $this->type_id = $type_id;
    }

    /**
     * Récupérer les 3 catégories mises en avant sur la home
     * 
     * @return Product[]
     */
    public function findForHomePageBackOffice()
    {
        $pdo = Database::getPDO();
        $sql = '
            SELECT *
            FROM product
            LIMIT 3
        ';
        $pdoStatement = $pdo->query($sql);
        $products = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Product');
        
        return $products;
    }

    public function update()
    {
        $pdo = Database::getPDO();
        $sql = "
            UPDATE `product`
            SET `name`=:name,
            `description`=:description,
            `picture`=:picture,
            `price`=:price,
            `rate`=:rate,
            `status`=:status,
            `updated_at`=NOW(),
            `brand_id`=:brand_id,
            `category_id`=:category_id,
            `type_id`=:type_id
            WHERE `id`=:id
            LIMIT 1;
        ";

        $pdoStatement = $pdo->prepare($sql);

        $pdoStatement->bindValue(':name', $this->name);
        $pdoStatement->bindValue(':description', $this->description);
        $pdoStatement->bindValue(':picture', $this->picture);
        $pdoStatement->bindValue(':price', $this->price);
        $pdoStatement->bindValue(':rate', $this->rate, PDO::PARAM_INT);
        $pdoStatement->bindValue(':status', $this->status, PDO::PARAM_INT);
        $pdoStatement->bindValue(':brand_id', $this->brand_id, PDO::PARAM_INT);
        $pdoStatement->bindValue(':category_id', $this->category_id, PDO::PARAM_INT);
        $pdoStatement->bindValue(':type_id', $this->type_id, PDO::PARAM_INT);
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

    public function insert()
    {
        $pdo = Database::getPDO();
        $sql = "
            INSERT INTO `product` (`name`, `description`, `picture`, `price`, `rate`, `status`, `brand_id`, `category_id`, `type_id`)
            VALUES (:name, :description, :picture, :price, :rate, :status, :brand_id, :category_id, :type_id);
            ";
                    
        //on récupère la requête préparée
        $pdoStatement = $pdo->prepare($sql);

        //on associe les valeurs aux paramètres

        $pdoStatement->bindValue(':name', $this->name);
        $pdoStatement->bindValue(':description', $this->description);
        $pdoStatement->bindValue(':picture', $this->picture);
        $pdoStatement->bindValue(':price', $this->price);
        $pdoStatement->bindValue(':rate', $this->rate, PDO::PARAM_INT);
        $pdoStatement->bindValue(':status', $this->status, PDO::PARAM_INT);
        $pdoStatement->bindValue(':brand_id', $this->brand_id, PDO::PARAM_INT);
        $pdoStatement->bindValue(':category_id', $this->category_id, PDO::PARAM_INT);
        $pdoStatement->bindValue(':type_id', $this->type_id, PDO::PARAM_INT);

        $insertedRows = $pdoStatement->execute();
        
        if ($insertedRows > 0) {
            // Alors on récupère l'id auto-incrémenté généré par MySQL
            $this->id = $pdo->lastInsertId();

            // On retourne VRAI car l'ajout a parfaitement fonctionné
            return $this->id;
            // => l'interpréteur PHP sort de cette fonction car on a retourné une donnée
        }
        
        // Si on arrive ici, c'est que quelque chose n'a pas bien fonctionné => FAUX
        return false;
    }
}
