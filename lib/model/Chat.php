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
     * @var \User
     */
    protected $Users;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $Log;


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
        $this->getLinesFromEnd();
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
     * get last 50 lines from log
     * @return array
     */
    public function getLinesFromEnd()
    {
        $query = Framework::getInstance()->getEntityManager()->createQuery('SELECT u FROM Log u ORDER BY u.id DESC');
        $query->setMaxResults(50);
        $result = $query->getResult();
        $result = array_reverse($result);
        return $result;
    }



}
