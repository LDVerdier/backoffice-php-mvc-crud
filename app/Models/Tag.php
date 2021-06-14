<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

/**
 * Un modèle représente une table (un entité) dans notre base
 * 
 * Un objet issu de cette classe réprésente un enregistrement dans cette table
 */
class Tag extends CoreModel {
    // Les propriétés représentent les champs
    // Attention il faut que les propriétés aient le même nom (précisément) que les colonnes de la table
    
    /**
     * @var string
     */
    private $name;
   
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
     * Méthode permettant de récupérer un enregistrement de la table Tag en fonction d'un id donné
     * 
     * @param int $tagId ID du tag
     * @return Tag
     */
    public static function find($tagId)
    {
        // se connecter à la BDD
        $pdo = Database::getPDO();

        // écrire notre requête
        $sql = 'SELECT * FROM `tag` WHERE `id` =' . $tagId;

        //#afficher les tags avec une colonne groupant les produits auxquels ils sont associés pour un id de tag donné
        // $sql = '
        //   SELECT `tag`.*,  GROUP_CONCAT(`product_has_tag`.`product_id` SEPARATOR \',\') as \'productId\'
        //   FROM `tag`
        //   LEFT JOIN `product_has_tag` ON `product_has_tag`.`tag_id`=`tag`.`id`
        //   WHERE `product_has_tag`.`tag_id`=2
        // ';

        // exécuter notre requête
        $pdoStatement = $pdo->query($sql);

        // un seul résultat => fetchObject
        $type = $pdoStatement->fetchObject(self::class);

        // retourner le résultat
        return $type;
    }

    /**
     * Méthode permettant de récupérer tous les enregistrements de la table tag
     * 
     * @return Tag[]
     */
    public static function findAll()
    {
        $pdo = Database::getPDO();
        //#afficher les tags avec une colonne groupant les produits auxquels ils sont associés
        $sql = '
            SELECT `tag`.*,  GROUP_CONCAT(`product_has_tag`.`product_id` SEPARATOR \',\') as \'matchingProductsIds\'
            FROM `tag`
            LEFT JOIN `product_has_tag` ON `product_has_tag`.`tag_id`=`tag`.`id`
            GROUP BY `product_has_tag`.`tag_id`
            ORDER BY `tag`.`id`
          ';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);
        

        

        return $results;
    }

    public function insert()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête INSERT INTO
        $sql = "
            INSERT INTO `tag` (name)
            VALUES (:name)
        ";

        $pdoStatement = $pdo->prepare($sql);

        $pdoStatement->bindValue(':name', $this->name);

        $insertedRows = $pdoStatement->execute();

        // Si au moins une ligne ajoutée
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

    /**
     * Méthode permettant de mettre à jour un enregistrement dans la table tag
     * L'objet courant doit contenir l'id, et toutes les données à ajouter : 1 propriété => 1 colonne dans la table
     * 
     * @return bool
     */
    public function update()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête UPDATE
        $sql = "
            UPDATE `tag`
            SET
                name = :name,
                updated_at = NOW()
            WHERE id = :id
            LIMIT 1
        ";

        $pdoStatement = $pdo->prepare($sql);

        //on associe les valeurs aux paramètres

        $pdoStatement->bindValue(':name', $this->name);
        $pdoStatement->bindValue(':id', $this->id, PDO::PARAM_INT);  
         
        $updatedRows = $pdoStatement->execute();

        // On retourne VRAI, si au moins une ligne ajoutée
        return ($updatedRows > 0);
    }

    public function delete()
    {
        $pdo = Database::getPDO();
        $sql = "
            DELETE FROM `tag`
            WHERE `id`=:id
            LIMIT 1;
        ";
        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->bindValue(':id', $this->id, PDO::PARAM_INT);   

        $deletedRows = $pdoStatement->execute();

        return ($deletedRows > 0);
    }

    public static function updateTagsOnProduct($productId)
    {
      $tags = [];
      foreach ($_POST as $key => $value){
          
          if (substr($key, 0, 3) === 'tag' && $value === 'on'){
              $tag = filter_var(substr($key, 3), FILTER_SANITIZE_NUMBER_INT);
              if($tag) {
                  $tags[] = $tag;
              }
          }
      }
      
      self::removeTagsFromProduct($productId);
      self::bindTagsToProduct($tags, $productId);

    }

    public static function removeTagsFromProduct($productId)
    {
  
      //supprimer tous les bindings existants entre un produit et des tags
      $pdo = Database::getPDO();
      $sql = '
        DELETE
        FROM `product_has_tag`
        WHERE `product_id`=:product_id
        ';

      $pdoStatement = $pdo->prepare($sql);

      //on associe les valeurs aux paramètres

      $pdoStatement->bindValue(':product_id', $productId, PDO::PARAM_INT);  
      $deletedRows = $pdoStatement->execute();
      return $deletedRows;
    }

    public static function bindTagstoProduct(array $tags, $productId)
    {
      $pdo = Database::getPDO();
      
      //constituer un tableau avec des strings sous la forme (2,1)
      $bindings = [];
      foreach ($tags as $tag){
        $bindings[] = '(' . $productId . ',' . $tag . ')';
      }
      // dump($bindings);
      //transformer le tableau en une string avec les éléments séparés par des virgules
      $values = implode(',', $bindings);
      // dump($values);
      $sql = '
        INSERT INTO `product_has_tag`
        VALUES ' . $values . ';
        ';

      // dump($pdoStatement);
      //on associe les valeurs aux paramètres
        
      // dd($pdoStatement);
      $insertedRows = $pdo->exec($sql);
      return $insertedRows;
    
      //refaire tous les bindings
      //
    }
}
