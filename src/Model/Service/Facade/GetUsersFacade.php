<?php

namespace Model\Service\Facade;

use Model\Entity\User;
use Model\Entity\UsersCollection;
use Model\Mapper\UsersMapper;

class GetUsersFacade
{

    private $app;
    private $like;
    private $usersMapper;

    public function __construct(string $app = null, string $like = null, UsersMapper $usersMapper) {
        $this->app = $app;
        $this->like = $like;
        $this->usersMapper = $usersMapper;
    }


    /**
     * Handle users
     *
     * @return UsersCollection
     */
    public function handleUsers():UsersCollection {
        $data = null;

        // Calling By App
        if(!empty($this->app)){
            $data = $this->getUsersByApp();
        }
        // Calling by Search
        else if(!empty($this->like)){
            $data = $this->searchUsers();
        }
        // Calling by State
        else{
            $data = $this->getUsers();
        }

        // return data
        return $data;
    }


    /**
     * Get all
     *
     * @return UsersCollection
     */
    public function getUsers():UsersCollection {
        // create entity and set its values
        $entity = new User();

        // call mapper for data
        $collection = $this->usersMapper->getUsers($entity);

        // return data
        return $collection;
    }


    /**
     * Get by app
     *
     * @return UsersCollection
     */
    public function getUsersByApp():UsersCollection {  // TODO
        die("Function isnt finished.");
        // create entity and set its values
        $entity = new User();
        $entity->setApp($this->app);

        // call mapper for data
        $data = $this->usersMapper->getUsersByApp($entity);

        // return data
        return $data;
    }


    /**
     * Get by search term
     *
     * @return UsersCollection
     */
    public function searchUsers():UsersCollection {
        // create entity and set its values
        $entity = new User();
        $entity->setName($this->like);

        // call mapper for data
        $data = $this->usersMapper->searchUsers($entity);

        // return data
        return $data;
    }

}