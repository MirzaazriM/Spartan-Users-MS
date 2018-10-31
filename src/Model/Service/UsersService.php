<?php
/**
 * Created by PhpStorm.
 * User: mirza
 * Date: 6/28/18
 * Time: 10:19 AM
 */

namespace Model\Service;


use Model\Core\Helper\Monolog\MonologSender;
use Model\Entity\ResponseBootstrap;
use Model\Entity\User;
use Model\Mapper\UsersMapper;
use Model\Service\Facade\GetUsersFacade;

class UsersService
{

    private $usersMapper;
    private $monologHelper;

    public function __construct(UsersMapper $usersMapper)
    {
        $this->usersMapper = $usersMapper;
        $this->configuration = $usersMapper->getConfiguration();
        $this->monologHelper = new MonologSender();
    }


    /**
     * Get single user
     *
     * @param int $id
     * @return ResponseBootstrap
     */
    public function getUser(int $id):ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // create entity and set its values
            $entity = new User();
            $entity->setId($id);

            // get response
            $res = $this->usersMapper->getUser($entity);
            $id = $res->getId();

            // check data and set response
            if(isset($id)){
                $response->setStatus(200);
                $response->setMessage('Success');
                $response->setData([
                    'id' => $res->getId(),
                    'name' => $res->getName(),
                    'surname' => $res->getSurname(),
                    'email' => $res->getEmail(),
                    'location' => $res->getLocation(),
                ]);
            }else {
                $response->setStatus(204);
                $response->setMessage('No content');
            }

            // return data
            return $response;

        }catch (\Exception $e){
            // send monolog record
            $this->monologHelper->sendMonologRecord($this->configuration, 1000, "Get user service: " . $e->getMessage());

            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }
    }


    /**
     * Get users service
     *
     * @param string|null $app
     * @param string|null $like
     * @return ResponseBootstrap
     */
    public function getUsers(string $app = null, string $like = null):ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // create facade and call its functions for data
            $facade = new GetUsersFacade($app, $like, $this->usersMapper);
            $res = $facade->handleUsers();

            // convert collection to array
            $data = [];
            for($i = 0; $i < count($res); $i++){
                $data[$i]['id'] = $res[$i]->getId();
                $data[$i]['name'] = $res[$i]->getName();
                $data[$i]['surname'] = $res[$i]->getSurname();
                $data[$i]['email'] = $res[$i]->getEmail();
                $data[$i]['location'] = $res[$i]->getLocation();
            }

            // Check Data and Set Response
            if($res->getStatusCode() == 200){
                $response->setStatus(200);
                $response->setMessage('Success');
                $response->setData(
                    $data
                );
            }else {
                $response->setStatus(204);
                $response->setMessage('No content');
            }

            // return data
            return $response;

        }catch (\Exception $e){
            // send monolog record
            $this->monologHelper->sendMonologRecord($this->configuration, 1000, "Get users service: " . $e->getMessage());

            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }
    }


    /**
     * Add user service
     *
     * @param string $name
     * @param string $surname
     * @param string $email
     * @param string $location
     * @return ResponseBootstrap
     */
    public function addUser(string $name, string $surname, string $email, string $location):ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // create entity and set its values
            $entity = new User();
            $entity->setName($name);
            $entity->setSurname($surname);
            $entity->setEmail($email);
            $entity->setLocation($location);

            // get response
            $res = $this->usersMapper->createUser($entity)->getResponse();

            // check data and set response
            if($res[0] == 200){
                $response->setStatus(200);
                $response->setMessage('Success');
            }else {
                $response->setStatus(304);
                $response->setMessage('Not modified');
            }

            // return data
            return $response;

        }catch (\Exception $e){
            // send monolog record
            $this->monologHelper->sendMonologRecord($this->configuration, 1000, "Create user service: " . $e->getMessage());

            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }
    }


    /**
     * Edit user service
     *
     * @param int $id
     * @param string $name
     * @param string $surname
     * @param string $email
     * @param string $location
     * @return ResponseBootstrap
     */
    public function editUser(int $id, string $name, string $surname, string $email, string $location):ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // create entity and set its values
            $entity = new User();
            $entity->setId($id);
            $entity->setName($name);
            $entity->setSurname($surname);
            $entity->setEmail($email);
            $entity->setLocation($location);

            // get response
            $res = $this->usersMapper->editUser($entity)->getResponse();

            // check data and set response
            if($res[0] == 200){
                $response->setStatus(200);
                $response->setMessage('Success');
            }else {
                $response->setStatus(304);
                $response->setMessage('Not modified');
            }

            // return data
            return $response;

        }catch (\Exception $e){
            // send monolog record
            $this->monologHelper->sendMonologRecord($this->configuration, 1000, "Edit user service: " . $e->getMessage());

            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }

    }


    /**
     * Get total users
     *
     * @return ResponseBootstrap
     */
    public function getTotal():ResponseBootstrap {

        try {
            // create response object
            $response = new ResponseBootstrap();

            // call mapper for data
            $data = $this->usersMapper->getTotal();

            // check data and set response
            if(!empty($data)){
                $response->setStatus(200);
                $response->setMessage('Success');
                $response->setData([
                    $data
                ]);
            }else {
                $response->setStatus(204);
                $response->setMessage('No content');
            }

            // return data
            return $response;

        }catch (\Exception $e){
            // send monolog record
            $this->monologHelper->sendMonologRecord($this->configuration, 1000, "Get total users service: " . $e->getMessage());

            $response->setStatus(404);
            $response->setMessage('Invalid data');
            return $response;
        }
    }
}