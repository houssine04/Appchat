<?php

namespace App\Models\Repository;


use App\Models\Entity\Message;

class MessageRepository extends Repository
{

    protected static $tableName = 'message';

    public function saveChat(Message $message)
    {
        $db = self::getDbConnection();
        $sql = 'INSERT INTO ' . static::$tableName . '(sender,receiver,msg, created_at) values (:sender,:receiver,:msg, :created_at)';
        $stmt = $db->prepare($sql);
        $stmt->execute(['sender' => $message->getSender()->getId(),
            'receiver' => $message->getReceiver()->getId(),
            'msg' => $message->getMsg(),
            'created_at' => $message->getCreatedAt()
        ]);

        return $stmt->rowCount();
    }

    public function getConversations($currentUserId, $partnerId)
    {

        //@todo not too clean , needs improvement
        $db = self::getDbConnection();
        $query = 'SELECT * FROM ' . static::$tableName
            . ' WHERE ((sender=:current_usr AND receiver=:partner) OR (sender=:partner1 AND receiver=:current_usr1))';
        $params = ['current_usr' => $currentUserId,
            'partner' => $partnerId,
            'current_usr1' => $currentUserId,
            'partner1' => $partnerId
        ];

        $stmt = $db->prepare($query);
        $stmt->execute($params);

        $result = [];
        while ($array = $stmt->fetch()) {
            $result[] = $this->hydrate($array);
        }


        return $result;

    }

    function hydrate($array)
    {
        //@todo improve this
        $userRepo = new UserRepository();

        $message = new Message();
        $message->setId($array['id']);
        $message->setMsg(isset($array['msg']) ? $array['msg'] : '');
        $message->setCreatedAt(isset($array['created_at']) ? $array['created_at'] : '');
        $message->setSender($userRepo->find($array['sender']));
        $message->setReceiver($userRepo->find($array['receiver']));

        return $message;
    }
}