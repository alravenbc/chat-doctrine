<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Log
 */
class Log
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $message;

    /**
     * @var \Chat
     */
    private $Chat;

    public function __toString()
    {
        return $this->message;
    }


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
     * Set message
     *
     * @param string $message
     * @return Log
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set Chat
     *
     * @param \Chat $chat
     * @return Log
     */
    public function setChat(\Chat $chat = null)
    {
        $this->Chat = $chat;

        return $this;
    }

    /**
     * Get Chat
     *
     * @return \Chat 
     */
    public function getChat()
    {
        return $this->Chat;
    }
}
