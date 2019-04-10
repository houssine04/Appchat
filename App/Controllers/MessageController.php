<?php

namespace App\Controllers;

use App\Models\Repository\MessageRepository;
use App\Models\Repository\UserRepository;
use Core\View;
use Core\Controller;
use App\Models\Entity\Message;

/**
 * Message controller
 *
 * PHP version 7.0
 */

class MessageController extends Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {

        View::render('chat.html.php');
    }

    public function getConversationAction(){
        $id = $_POST['id'];

        $messageRepo = new MessageRepository();
        /** @var Message[] $conversations */
        $conversations = $messageRepo->getConversations($this->getUser()->getId(), $id);
        $result = [];
        foreach ($conversations as $conversation) {
            $result[] = ['id' => $conversation->getId(),
                'msg' => htmlspecialchars($conversation->getMsg()),
                'time' => $conversation->getCreatedAt(),
                'its_me' => $conversation->getSender()->getId() == $this->getUser()->getId()
            ];

        }
        $this->returnJson($result);
    }

    public function sendMessageAction()
    {
        $id = $_POST['id'];
        $msg = $_POST['msg'];
        if (!isset($id) || !isset($msg)){
            $this->returnJson(['success' => 'false']);
        }
        $messageRepo = new MessageRepository();
        $userRepo = new UserRepository();
        $message = new Message();
        $message->setSender($this->getUser());
        $message->setReceiver($userRepo->find($id));
        $message->setMsg($msg);
        $messageRepo->saveChat($message);

        $this->returnJson(['success'=> 'true']);
    }



}