<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Config\Config;

/**
 * Description of Country
 *
 * @author Tam
 */
class Country extends DbObject{
  
    /** @var string */
    protected $nameCountry;
    
    /**
     * @param int $id
     * @return DbObject
     */
    public function __construct($nameCountry = '', $id = 0, $inserted = '') {
        $this->nameCountry = $nameCountry;

        parent::__construct($id, $inserted);
    }
   
    public static function get($id) {
        $sql = '
		SELECT cou_name, cou_id, cou_inserted
		FROM country
		WHERE cou_id = :id			
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        if ($stmt->execute() === false) {
            throw new InvalidSqlQueryException($sql, $stmt);
        } else {
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!empty($row)) {
                $currentObject = new Country(
                        $row['cou_name'],
                        new Country($row['cou_id']),                         
                        $row['cou_inserted']
                );
                return $currentObject;
            }
        }

        return false;
    }
 
    /**
     * @return DbObject[]
     */
    public static function getAll() {
        $returnList = array();

        $sql = '
                SELECT cou_name, cou_id, cou_inserted
		FROM country
                WHERE cou_id > 0			
                ';

        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        if ($stmt->execute() === false) {
            throw new InvalidSqlQueryException($sql, $stmt);
        } else {
            $allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($allDatas as $row) {
                $currentObject = new City(
                        $row['cou_name'],
                        new Country($row['cou_id']),                         
                        $row['cou_inserted']
                );
                $returnList[] = $currentObject;
            }
        }

        return $returnList;
    }
    /**
     * @return array
     */
    public static function getAllForSelect() {
        $returnList = array();

        $sql = '
                SELECT cou_name, cou_id, cou_inserted
		FROM country
                WHERE cou_id > 0
               ';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        if ($stmt->execute() === false) {
            print_r($stmt->errorInfo());
        } else {
            $allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($allDatas as $row) {
                $returnList[$row['cou_id']] = $row['cou_name'];
            }
        }

        return $returnList;
    }
    
    /**
     * @return bool
     */
    public function saveDB() {
        if ($this->id > 0) {
            $sql = '
                    UPDATE country
                    SET cou_name = :couName,
                    WHERE cou_id = :id
                    ';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
            $stmt->bindValue(':couName', $this->nameCountry, \PDO::PARAM_STR);
            
            if ($stmt->execute() === false) {
                throw new InvalidSqlQueryException($sql, $stmt);
            } else {
                return true;
            }
        } else {
            $sql = '
                    INSERT INTO country (cou_name, cou_id)
                    VALUES (:couName, :id)
                   ';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
            $stmt->bindValue(':couName', $this->cityName, \PDO::PARAM_STR);
          
            if ($stmt->execute() === false) {
                throw new InvalidSqlQueryException($sql, $stmt);
            } else {
                $this->id = Config::getInstance()->getPDO()->lastInsertId();
                return true;
            }
        }

        return false;
    }
    
    /**
     * @param int $id
     * @return bool
     */
    public static function deleteById($id) {
      //  Implement deleteById() method.
       $sql = '
               DELETE FROM country WHERE cou_id = :id
              ';
       
       $stmt = Config::getInstance()->getPDO()->prepare($sql);
       $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
       if ($stmt->execute() === false) {
           print_r($stmt->errorInfo());
       }
       else {
          return true;
        }
          return false;  
        
    } 
    
    function getNameCountry() {
        return $this->nameCountry;
    }

   
}
