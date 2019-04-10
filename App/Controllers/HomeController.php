<?php

namespace App\Controllers;

use App\Models\Repository\UserRepository;
use Core\View;
use Core\Controller;
use App\Models\Entity\User;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class HomeController extends Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {

        //View::render('home.php');
    }

    /**
     * Show the login page
     *
     * @return void
     */

    public function loginAction()
    {
        // if user is logged in no need to reenter credentials
        //$this->checkUserAuthenticatedAction();
        $result = [];
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            if ($this->authenticateUser($username, $password)) {
                // redirect to home
                $this->redirect('chat');
            } else {
                $result['error'] = 'Bad credentials';
            }
        }

        View::render('login.html.php', [
            'viewVars' => $result
        ]);
    }

    public function registerAction()
    {

        $userRepo =  new UserRepository();
        $result = [];
        if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['re_password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $rePassword = $_POST['re_password'];

            if ($password !== $rePassword) {
                $result['error'] = 'Passwords do not match';

            } else if ($user = $userRepo->findBy(['username' => $username], 1)) {
                $result['error'] = 'Oops username is already taken, try another one';

            } else if ($this->registerUser($username, $password)) {
                $result['success'] = 'Account Created!, <a href="./">login</a> to start chatting';

            } else {
                $result['error'] = 'Something wrong happened';
            }
        }

        View::render('register.html.php', [
            'viewVars' => $result
        ]);

    }

    public function logoutAction()
    {

        unset($_SESSION['user']);

        $this->redirect('./');
    }


    public function usersAction()
    {
        $userRepo =  new UserRepository();
        $userRepo->updateLastSeen($this->getUser());
        $result = [];
        /** @var User[] $users */
        $users = $userRepo->findBy([], null, ['last_seen' => 'desc']);
        $currentUser = $this->getUser();
        foreach ($users as $user) {
            if ($currentUser->getId() == $user->getId()) {
                continue;
            }
            $result[] = [
                'username' => $user->getUsername(),
                'id' => $user->getId(),
                // user is active since last 5 seconds
                'active' => (time() - strtotime($user->getLastSeen())) < 50000,
            ];
        }
        $this->returnJson($result);
    }

    public function checkUserAuthenticatedAction()
    {
        if (!$this->getUser()) {
            $this->redirect('');
        }
    }

    private function authenticateUser($username, $password)
    {

        $userRepo =  new UserRepository();
        $user = $userRepo->findBy(['username' => $username], 1);
        if (!$user) {
            return false;
        }
        if (password_verify($password, $user->getPassword())) {
            $_SESSION['user'] = $user;
            return true;
        }

        return false;

    }

    private function registerUser($username, $password)
    {
        $userRepo =  new UserRepository();
        $user = $userRepo->hydrate(['username' => $username, 'password' => $password]);
        return $userRepo->saveUser($user);
    }
}
