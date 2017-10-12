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
    public function getLinesFromEnd( $file, $n = 50 )
    {
        $result = array();
        $handle = fopen($file, "r");
        while(!feof($handle)){
            $result[] = fgets($handle);
            if (count($result) > 1000)
            {
                // slice to last $n items to better consume memory
                $result = array_slice($result,-$n);
            }
        }
        fclose($handle);
        // finally, slice to last $n items
        $result = array_slice($result,-$n);
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


}
