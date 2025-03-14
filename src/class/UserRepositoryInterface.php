<?php
namespace repository;
interface UserRepositoryInterface{
    public function showUsers();
    public function deleteUser(int $id);
    public function createUser(string $name, string $email);
}