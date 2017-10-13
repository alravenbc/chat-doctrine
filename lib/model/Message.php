<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 */
class Message
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
     * @return Message
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
     * @return Message
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
