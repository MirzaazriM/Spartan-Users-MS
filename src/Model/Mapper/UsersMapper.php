<?php
/**
 * Created by PhpStorm.
 * User: mirza
 * Date: 6/28/18
 * Time: 10:20 AM
 */

namespace Model\Mapper;

use PDO;
use PDOException;
use Component\DataMapper;
use Model\Entity\Shared;
use Model\Entity\User;
use Model\Entity\UsersCollection;

class UsersMapper extends DataMapper
{

    public function getConfiguration()
    {
        return $this->configuration;
    }


    /**
     * Fetch single user
     *
     * @param User $user
     * @return User
     */
    public function getUser(User $user):User {

        // create response object
        $response = new User();

        try {
            // set database instructions
            $sql = "SELECT * FROM users WHERE id = ?";
            $statement = $this->connection->prepare($sql);
            $statement->execute([
                $user->getId()
            ]);

            // fetch data
            $data = $statement->fetch();

            // set response values
            if($statement->rowCount() > 0){
                $response->setId($data['id']);
                $response->setName($data['name']);
                $response->setSurname($data['surname']);
                $response->setEmail($data['email']);
                $response->setLocation($data['location']);
            }

        }catch(PDOException $e){
            // send monolog record
            $this->monologHelper->sendMonologRecord($this->configuration, $e->errorInfo[1], "Get user mapper: " . $e->getMessage());
        }

        // return data
        return $response;
    }


    /**
     * Get all users
     *
     * @param User $user
     * @return UsersCollection
     */
    public function getUsers(User $user):UsersCollection {

        // create response object
        $userCollection = new UsersCollection();

        try {
            // set database instructions
            $sql = "SELECT * FROM users";
            $statement = $this->connection->prepare($sql);
            $statement->execute();

            // Floop through data
            while($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                // create new user
                $user = new User();

                // set its values
                $user->setId($row['id']);
                $user->setName($row['name']);
                $user->setSurname($row['surname']);
                $user->setEmail($row['email']);
                $user->setLocation($row['location']);

                // add user to user collection
                $userCollection->addEntity($user);
            }

            // set response according to result of previous actions
            if($statement->rowCount() == 0){
                $userCollection->setStatusCode(204);
            }else {
                $userCollection->setStatusCode(200);
            }

        }catch(PDOException $e){
            $userCollection->setStatusCode(204);

            // send monolog record
            $this->monologHelper->sendMonologRecord($this->configuration, $e->errorInfo[1], "Get users mapper: " . $e->getMessage());
        }

        // return data
        return $userCollection;
    }


    /**
     * Get users by app
     *
     * @param User $user
     * @return UsersCollection
     */
    public function getUsersByApp(User $user):UsersCollection { // TODO

        // create response object
        $userCollection = new UsersCollection();

        try {

            $sql = "";
            $statement = $this->connection->prepare($sql);
            $statement->execute();

            // Fetch Data
            while($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $user = new User();

                $user->setId();

                $userCollection->addEntity($user);

            }

            // set entity values
            if($statement->rowCount() == 0){
                $userCollection->setStatusCode(204);
            }else {
                $userCollection->setStatusCode(200);
            }

        }catch(PDOException $e){
            $userCollection->setStatusCode(204);
            // send monolog record
            $this->monologHelper->sendMonologRecord($this->configuration, $e->errorInfo[1], $e->getMessage());
        }

        return $userCollection;
    }


    /**
     * Get users by search term
     *
     * @param User $user
     * @return UsersCollection
     */
    public function searchUsers(User $user):UsersCollection {

        // create response object
        $userCollection = new UsersCollection();

        try {
            // set term
            $term = '%' . $user->getName() . '%';
            // set database instructions
            $sql = "SELECT * FROM users WHERE name LIKE ? OR surname LIKE ?";
            $statement = $this->connection->prepare($sql);
            $statement->execute([
                $term,
                $term
            ]);

            // loop through fetched data
            while($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                // create user entity
                $user = new User();

                // set its values
                $user->setId($row['id']);
                $user->setName($row['name']);
                $user->setSurname($row['surname']);
                $user->setEmail($row['email']);
                $user->setLocation($row['location']);

                // add user to user collection
                $userCollection->addEntity($user);
            }

            // set response according to result of previous actions
            if($statement->rowCount() == 0){
                $userCollection->setStatusCode(204);
            }else {
                $userCollection->setStatusCode(200);
            }

        }catch(PDOException $e){
            $userCollection->setStatusCode(204);

            // send monolog record
            $this->monologHelper->sendMonologRecord($this->configuration, $e->errorInfo[1], "Search users mapper: " . $e->getMessage());
        }

        // return data
        return $userCollection;
    }


    /**
     * Edit specified user
     *
     * @param User $user
     * @return Shared
     */
    public function editUser(User $user):Shared {

        // create response object
        $shared = new Shared();

        try {
            // set database instructions
            $sql = "UPDATE users SET
                      name = ?,
                      surname = ?,
                      email = ?,
                      location = ?
                     WHERE id = ?
                     ORDER BY email DESC";
            $statement = $this->connection->prepare($sql);
            $statement->execute([
                $user->getName(),
                $user->getSurname(),
                $user->getEmail(),
                $user->getLocation(),
                $user->getId()
            ]);

            // set response according to result of previous action
            if($statement->rowCount() > 0){
                $shared->setResponse([200]);
            }else {
                $shared->setResponse([304]);
            }

        }catch(PDOException $e){
            $shared->setResponse([304]);

            // send monolog record
            $this->monologHelper->sendMonologRecord($this->configuration, $e->errorInfo[1], "Edit user mapper: " . $e->getMessage());
        }

        // return response
        return $shared;
    }


    /**
     * Insert user
     *
     * @param User $user
     * @return Shared
     */
    public function createUser(User $user):Shared {

        // create response object
        $shared = new Shared();

        try {
            // set database instructions
            $sql = "INSERT INTO users
                        (name, surname, email, location)
                    VALUES (?,?,?,?)";
            $statement = $this->connection->prepare($sql);
            $statement->execute([
                $user->getName(),
                $user->getSurname(),
                $user->getEmail(),
                $user->getLocation()
            ]);

            // set response according to result of previous action
            if($statement->rowCount() > 0){
                $shared->setResponse([200]);
            }else {
                $shared->setResponse([304]);
            }

        }catch(PDOException $e){
            $shared->setResponse([304]);

            // send monolog record
            $this->monologHelper->sendMonologRecord($this->configuration, $e->errorInfo[1], "Create user mapper: " . $e->getMessage());
        }

        // return response
        return $shared;
    }


    /**
     * Get total number of users
     *
     * @return null
     */
    public function getTotal() {

        try {
            // set database instructions
            $sql = "SELECT COUNT(*) as total FROM oauth_users";
            $statement = $this->connection->prepare($sql);
            $statement->execute();

            // set total number
            $total = $statement->fetch(PDO::FETCH_ASSOC)['total'];

        }catch(PDOException $e){
            $total = null;

            // send monolog record
            $this->monologHelper->sendMonologRecord($this->configuration, $e->errorInfo[1], "Get total users mapper: " . $e->getMessage());
        }

        // return data
        return $total;
    }
}