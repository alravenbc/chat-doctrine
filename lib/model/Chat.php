<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Class Chat
 */
class Chat
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $topic;


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
     * Set topic
     *
     * @param string $topic
     * @return Chat
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
        $this->getLinesFromEnd( "chat.log" );
        $this->getTopic();
        $this->getUsersOnline();
    }

    /**
     * Add user
     *
     * @param \User $user
     * @return Chat
     */
    public function addUser(\User $user)
    {
        $this->user[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \User $user
     */
    public function removeUser(\User $user)
    {
        $this->user->removeElement($user);
    }

    /**
     * returns online users from 'online.log'
     * @return array
     */
    public function getUsersOnline()
    {
        $users = array_map( "trim", file('online.log', FILE_SKIP_EMPTY_LINES));
        return $users;
    }

    /**
     *
     * @return string
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * get last $n lines from $file
     * @param $file
     * @param int $n
     * @return array
     */
    public function getLinesFromEnd()
    {
        $query = Framework::getInstance()->getEntityManager()->createQuery('SELECT u FROM Log u ORDER BY u.id DESC');
        $query->setMaxResults(32);
        $result = $query->getResult();
        $result = array_reverse($result);
        return $result;
    }
    /**
     * @var \User
     */
    protected $Users;


    /**
     * Set Users
     *
     * @param \User $users
     * @return Chat
     */
    public function setUsers(\User $users = null)
    {
        $this->Users = $users;

        return $this;
    }

    /**
     * Get Users
     *
     * @return \User 
     */
    public function getUsers()
    {
        return $this->Users;
    }


    /**
     * @var string
     */
    private $log;


    /**
     * Set log
     *
     * @param string $log
     * @return Chat
     */
    public function setLog($log)
    {
        $this->log = $log;

        return $this;
    }

    /**
     * Get log
     *
     * @return string 
     */
    public function getLog()
    {
        return $this->log;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $Log;


    /**
     * Add Log
     *
     * @param \Log $log
     * @return Chat
     */
    public function addLog(\Log $log)
    {
        $this->Log[] = $log;

        return $this;
    }

    /**
     * Remove Log
     *
     * @param \Log $log
     */
    public function removeLog(\Log $log)
    {
        $this->Log->removeElement($log);
    }
}
