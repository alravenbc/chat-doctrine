<?php

class Session
{


    /**
     * Authenticate user + check nickname and password
     * @param $user
     */
    public function logIn($username , $password)
    {
        unset($_SESSION['id']);

        $userRepository = Framework::getInstance()->getUserTable();
        $users = $userRepository->findAll();
        /** @var $user User */
        foreach ($users as $user)
        {

            if ($user && $user->getUsername() == $username && $user->getPassword() == $password)
            {
                $_SESSION['id'] = $user->getId();
                $user->setNick($username);
                Framework::getInstance()->save();
                $this->setFlash("Welcome, " . Framework::getInstance()->getActiveUser()->getNick() . ", U can now chat!");
                Framework::getInstance()->getActiveUser()->chat('User ' . Framework::getInstance()->getActiveUser()->getNick() . ' has logged in!');
                file_put_contents('online.log', "\n" . Framework::getInstance()->getActiveUser()->getNick() . "\n", FILE_APPEND);
                return true;
            }
        }
            $this->setFlash("Wrong username or password!");
            return false;

    }

    /**
     * Log out
     */
    public function logOut()
    {
        $this->setFlash("Logged out");
        Framework::getInstance()->getActiveUser()->chat('User ' . Framework::getInstance()->getActiveUser()->getUsername() . ' has logged out!');
        $users = array_map( "trim", file('online.log', FILE_SKIP_EMPTY_LINES));
        $user = Framework::getInstance()->getActiveUser()->getNick();
        $key = array_search( $user, $users);
        if ($key!==false)
        {
            unset ($users[$key]);
            file_put_contents( "online.log", implode("\n", $users));
        }
        unset( $_SESSION['id'] );
        header("Location: /");
        exit;
    }

    /**
     * Check if is this user authorized or not
     * @return bool
     */
    public function isAuthenticated()
    {
        return isset( $_SESSION['id'] );
    }

    /**
     * Get flash message and remove it
     * @return null
     */
    public function getFlash()
    {
        $message = isset($_SESSION['flash']) ? $_SESSION['flash'] : null;
        unset($_SESSION['flash']);
        return $message;
    }

    /**
     * Set flash message
     * @param $message
     */
    public function setFlash($message)
    {
        $_SESSION['flash'] = $message;
    }

    public function getId()
    {
        return isset($_SESSION['id']) ? $_SESSION['id'] : false;
    }

}