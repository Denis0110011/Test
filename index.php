<?php
require_once('class/UserManager.php');
if($argc<2){
echo "show Список пользователей\n";
echo "add Добавить пользователя <name> <email>\n";
echo "delete Удалить пользователя <id>\n";
exit(1);
}
$command=$argv[1];
$UserManager=new UserManager('Json/Users.json');
switch ($command){
    case 'show':
        $UserManager->ShowUsers();
        break;
    case 'add':
        if(isset($argv[2]) and isset($argv[3])){
            $name=$argv[2];
            $email=$argv[3];
            $UserManager->CreateUser($name, $email);
        }else{
            echo 'Укажите имя и e-mail';
        }
    break;
    case 'delete':
        if (isset($argv[2])){
            $id=$argv[2];
            $UserManager->DeleteUser($id);
        }else{
            echo 'Укажите id';
        }
        break;
}
?>