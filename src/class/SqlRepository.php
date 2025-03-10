<?php
namespace repository;
use PDO;
use Exception;
class SqlRepository
{
   private $pdo;
   private function connectToPDO()
   {

      try {
         $this->pdo = new PDO('mysql:host=localhost; dbname=test', 'root', '');
         $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (Exception $e) {
         die('Error');
      }
   }
   public function createUserSql($name, $email)
   {
      $sql = 'INSERT INTO users (Name, Email) VALUES (:name, :email)';
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute(['name' => $name, 'email' => $email]);
      return $this->pdo->lastInsertId();
   }
   public function deleteUserSql($id)
   {
      $sql = 'DELETE FROM users WHERE ID=:id';
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute(['id' => $id]);
      return $id;
   }
   public function showUsersSql()
   {
      $sql = 'SELECT * FROM users';
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();
      $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $users;
   }
}