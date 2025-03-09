<?php
require_once('user.php');
class UserManager{
    private $FilePath;
    public function __construct($FilePath){
        $this->FilePath=$FilePath;
    }
    private function LoadUsers(){
        if(!file_exists($this->FilePath)){
            file_put_contents($this->FilePath, json_encode(['users'=>[], 'nextid'=>1], JSON_PRETTY_PRINT ));
        };
        $jsonContent=file_get_contents($this->FilePath);
        return json_decode($jsonContent, true);
    }
     private function SaveUsers($data){
        $jsonContent=json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($this->FilePath, $jsonContent);
    }
    public function ShowUsers(){
        $data=$this->LoadUsers();
        if(empty ($data['users'])){
            echo 'Отсуствуют пользователи';
        }else{
            foreach($data['users'] as $user){
                echo 'id:'. $user['id']. ' name:'. $user['name']. ' email:'. $user['email'];
            }
        }
    }
    public function CreateUser($name, $email){
        $data=$this->LoadUsers();
        $newUser= new User($data['nextid'], $name, $email);
        $data['users'][]=$newUser;
        $data['nextid']++;
        $this->SaveUsers($data);
        echo 'Добавлен пользователь:'. $newUser->id;

    }
   
    public function DeleteUser($id){
        $data=$this->LoadUsers();
        foreach($data['users'] as $index=>$user){
            if ($user['id']==$id){
                array_splice($data['users'], $index, 1);
                $this->SaveUsers($data);
                echo 'Пользователь удален';
                die;
            } 
        }
        echo 'Пользователь не найден';
    }
}
?>