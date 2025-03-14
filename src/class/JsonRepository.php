<?php
namespace repository;
use repository\UserRepositoryInterface;
class JsonRepository implements UserRepositoryInterface
{
public function __construct(private $FilePath){
}
    private function LoadUsers(): mixed
    {
        if (!file_exists($this->FilePath)) {
            file_put_contents($this->FilePath, json_encode(['users' => [], 'nextid' => 1], JSON_PRETTY_PRINT));
        }
        $jsonContent = file_get_contents($this->FilePath);
        return json_decode($jsonContent, true);
    }
    private function SaveUsers(array $data): void
    {
        $jsonContent = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($this->FilePath, $jsonContent);
    }
    public function ShowUsers(): mixed
    {
        $data = $this->LoadUsers();
        return $data['users'];
    }
    public function CreateUser(string $name, string $email): int
    {
        $data = $this->LoadUsers();
        $newUser = new User($data['nextid'], $name, $email);
        $data['users'][] = $newUser;
        $data['nextid']++;
        $this->SaveUsers($data);
        return (int) $newUser->id;

    }

    public function DeleteUser(int $id)
    {
        $data = $this->LoadUsers();
        foreach ($data['users'] as $index => $user) {
            if ($user['id'] == $id) {
                array_splice($data['users'], $index, 1);
                $this->SaveUsers($data);
                return (int)$id;
            }
        }
    }
}
?>