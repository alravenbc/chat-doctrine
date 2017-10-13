<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 */
class User
{
    /**
     * @var integer @GeneratedValue
     */
    protected $id;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @var string
     */
    protected $password;


    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @var \Chat
     */
    protected $chat;


    /**
     * Set chat
     *
     * @param \Chat $chat
     * @return User
     */
    public function setChat(\Chat $chat = null)
    {
        $this->chat = $chat;

        return $this;
    }

    /**
     * Get chat
     *
     * @return \Chat 
     */
    public function getChat()
    {
        return $this->chat;
    }

    public function __toString()
    {
        return $this->nick ? (string) $this->nick : "Guest";
    }

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $nick;


    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set nick
     *
     * @param string $nick
     * @return User
     */
    public function setNick($nick)
    {
        $this->nick = $nick;

        return $this;
    }

    /**
     * Get nick
     *
     * @return string
     */
    public function getNick()
    {
        return $this->nick;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $Chat;

    /**
     * Add Chat
     *
     * @param \Chat $chat
     * @return User
     */
    public function addChat(\Chat $chat)
    {
        $this->Chat[] = $chat;

        return $this;
    }

    /**
     * Remove Chat
     *
     * @param \Chat $chat
     */
    public function removeChat(\Chat $chat)
    {
        $this->Chat->removeElement($chat);
    }

    /**
     * Log information
     * @param $message
     */
    public function chat($message)
    {
        $log = new Log();
        $log->setMessage($message)->setChat(Framework::getInstance()->getActiveChat());
        Framework::getInstance()->getEntityManager()->persist($log);
        Framework::getInstance()->save();
    }


    /**
     * write in chat
     * @param $msg
     */
    public function writeInChat($msg)
    {
        $this->chat($this->getUsername() . ': ' . $msg);
    }

    /**
     * write status in chat
     * @param $msg
     */
    public function statusInChat($msg)
    {
        $this->chat('*** ' . $msg);
    }

}
