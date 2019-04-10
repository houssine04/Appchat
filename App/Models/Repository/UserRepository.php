<?php

namespace App\Models\Repository;


use App\Models\Entity\User;

class UserRepository extends Repository
{

    protected static $tableName = 'user';

    public function saveUser(User $user)
    {
        $db = self::getDbConnection();
        $sql = 'INSERT INTO ' . static::$tableName . '(username,password) values (:username,:password)';
        $stmt = $db->prepare($sql);
        $stmt->execute(['username' => $user->getUsername(),
            'password' => password_hash($user->getPassword(), PASSWORD_DEFAULT)]);

        return $stmt->rowCount();
    }

    public function updateLastSeen(User $user){
        $db = self::getDbConnection();
        $sql = 'UPDATE ' . static::$tableName . ' SET last_seen = NOW() WHERE id=:id';
        $stmt = $db->prepare($sql);
        $stmt->execute(['id' => $user->getId()]);

        return $stmt->rowCount();
    }

    function hydrate($array)
    {
        $user = new User();
        $user->setId(isset($array['id']) ? $array['id'] : null);
        $user->setPassword(isset($array['password']) ? $array['password'] : '');
        $user->setUsername(isset($array['username']) ? $array['username'] : '');
        $user->setLastSeen(isset($array['last_seen']) ? $array['last_seen'] : null);

        return $user;
    }
}