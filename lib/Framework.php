<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class Framework
{

    /** @var  $entityManager EntityManager */
    /** @var  $session Session */
    private $entityManager, $session, $chat;
    private static $instance;

    /**
     * Framework constructor forbidden
     * We allow using singletone Framework::getInstance()
     */
    private function __construct()
    {
        $this->getEntityManager();
        $this->chat = $this->getChat();
        $this->session = new Session();
    }

    /**
     * @return Framework
     */
    public static function getInstance()
    {
        return isset(self::$instance)
            ? self::$instance
            : self::$instance = new self;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {

        if (!is_null($this->entityManager))
            return $this->entityManager;

        foreach (glob(__DIR__ . "/../lib/model/*.php") as $file)
            require_once $file;

        // Create a simple "default" Doctrine ORM configuration for Annotations
        $dev = true;
        $config = Setup::createYAMLMetadataConfiguration(array(__DIR__ . "/../config/yaml"), $dev);

        // database configuration parameters
        $conn = array(
            'driver' => 'pdo_mysql',
            'user' => 'root',
            'password' => 'root',
            'host' => 'localhost',
            'dbname' => 'doctrine',
            'charset' => 'utf8',
        );

        // obtaining the entity manager
        return $this->entityManager = EntityManager::create($conn, $config);


    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getUserTable()
    {
        return $this->getEntityManager()->getRepository("User");
    }

    /**
     * @return Chat
     */
    public function getChat()
    {
        return $this->chat;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getChatTable()
    {
        return $this->getEntityManager()->getRepository("Chat");
    }
    /**
     * Get active chat (for now, it's chat with ID 1)
     * @return Chat
     */
    public function getActiveChat()
    {
        return $this->getChatTable()->find(1);
    }
    /**
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
    }
    /**
     * Process request
     * @param $array ($_POST or $_GET)
     * @return bool   if true, redirect is needed. Of no, no redirects
     */
    public function processRequest($array)
    {
        // condition 1: user and password are set
        if (isset($array['user']) && isset($array['password'])) {
            $this->session->logIn($array['user'], $array['password']);
            return true;
        }

        // condition 2: logout is set
        if (isset($array['logout'])) {
            $this->session->logOut();
            return true;
        }

        //condition 3: text message is set
        if (isset($array['msg']))
        {
            $messaga = $array['msg'];
            if ($messaga[0] == '/') {
                // process any command begin with /
                $params = explode(" ", $messaga);
                $command = "execute" . ucfirst(substr($params[0], 1));

                if (method_exists($this, $command))
                    $this->$command($params);

            } else {
                $this->getActiveUser()->writeInChat($array['msg']);
            }

            return true;
        }


    }

    /**
     * Get current user
     * @return User
     */
    public function getActiveUser()
    {
        return $this->session->isAuthenticated()
                ? $this->getUserTable()->find( $this->session->getId() )
                : new User();
    }

    /**
     * execute me function
     * @param $params
     */
    public function executeMe($params)
    {
        $user = $this->getActiveUser()->getNick();
        $me = substr(implode(' ', $params), 3);
        $this->getActiveUser()->statusInChat("$user" . " " . "$me");
    }

    /**
     * clears chat.log
     */
    public function executeClear()
    {
        file_put_contents( "chat.log", '');
    }

    /**
     * sets topic
     * @param $params
     */
    public function executeTopic($params)
    {
        /** @var $chat Chat */
        unset($params[0]);
        $topic = implode(" ", $params);
        $this->getActiveUser()->statusInChat( $this->getActiveUser() . " sets topic to $topic" );
        $topic = $topic . " (set by " . $this->getActiveUser() . ")";
        $this->getActiveChat()->setTopic($topic);
        Framework::getInstance()->getEntityManager()->flush();
    }


    /**
     * remove inactive user from online list
     * @param $params
     */
    public function executeKick($params)
    {
        $nick = $params[1];
        unset($params[0], $params[1]);
        $message = implode(" ", $params);
        $users = array_map( "trim", file('online.log', FILE_SKIP_EMPTY_LINES));
        $key = array_search( $nick, $users);

        if ($key!==false)
        {
            $this->getActiveUser()->statusInChat( $this->getActiveUser() . " kicked $nick ($message)" );
            unset ($users[$key]);
            file_put_contents( "online.log", implode("\n", $users));
        }

    }


    /**
     * execute nickname change
     * @param $params
     */
    public function executeNick($params)
    {
        $nick = preg_replace("/[^a-zA-Z0-9\s]/","",$params[1]);

        if ($nick == '' or strlen($nick)>10)
        {
            $this->getActiveUser()->statusInChat( "Invalid nickname $params[1]" );
        }
        else
        {
            $this->getActiveUser()->statusInChat( $this->getActiveUser() . " changed nick to $nick" );
            $users = array_map( "trim", file('online.log', FILE_SKIP_EMPTY_LINES));
            $key = array_search( $this->getActiveUser()->getNick(), $users);

            if ($key!==false)
            {
                $users[$key] = $nick;
                file_put_contents( "online.log", implode("\n", $users));
            }

            $this->getActiveUser()->setNick($nick);
            Framework::getInstance()->getEntityManager()->flush();
        }
    }


}