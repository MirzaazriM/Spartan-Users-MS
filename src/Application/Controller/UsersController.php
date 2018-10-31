<?php
/**
 * Created by PhpStorm.
 * User: mirza
 * Date: 6/28/18
 * Time: 10:19 AM
 */

namespace Application\Controller;


use Model\Entity\ResponseBootstrap;
use Model\Service\UsersService;
use Symfony\Component\HttpFoundation\Request;

class UsersController
{

    private $usersService;

    public function __construct(UsersService $usersService)
    {
        $this->usersService = $usersService;
    }


    /**
     * Get user by id
     *
     * @param Request $request
     * @return ResponseBootstrap
     */
    public function get(Request $request):ResponseBootstrap {
        // get id from url
        $id = $request->get('id');

        // create response object
        $response = new ResponseBootstrap();

        // check if parameters are present
        if(isset($id)){
            return $this->usersService->getUser($id);
        }else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        // return data
        return $response;
    }


    /**
     * Get users by app or search term or all
     *
     * @param Request $request
     * @return ResponseBootstrap
     */
    public function getAll(Request $request):ResponseBootstrap {
        // get data
        $app = $request->get('app');
        $like = $request->get('like');

        // return data from service
        return $this->usersService->getUsers($app, $like);
    }


    /**
     * Add user
     *
     * @param Request $request
     * @return ResponseBootstrap
     */
    public function post(Request $request):ResponseBootstrap {
        // get data
        $data = json_decode($request->getContent(), true);
        $name = $data['name'];
        $surname = $data['surname'];
        $email = $data['email'];
        $location = $data['location'];

        // create response object in case of failure
        $response = new ResponseBootstrap();

        // check if data is set
        if(isset($name) && isset($surname) && isset($email) && isset($location)){
            return $this->usersService->addUser($name, $surname, $email, $location);
        }else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        // return data
        return $response;
    }


    /**
     * Edit user by id
     *
     * @param Request $request
     * @return ResponseBootstrap
     */
    public function put(Request $request):ResponseBootstrap {
        // get data
        $data = json_decode($request->getContent(), true);
        $id = $data['id'];
        $name = $data['name'];
        $surname = $data['surname'];
        $email = $data['email'];
        $location = $data['location'];

        // create response object in case of failure
        $response = new ResponseBootstrap();

        // check if data is set
        if(isset($id) && isset($name) && isset($surname) && isset($email) && isset($location)){
            return $this->usersService->editUser($id, $name, $surname, $email, $location);
        }else {
            $response->setStatus(404);
            $response->setMessage('Bad request');
        }

        // return data
        return $response;
    }


    /**
     * Get total number of users
     *
     * @param Request $request
     * @return ResponseBootstrap
     */
    public function getTotal(Request $request):ResponseBootstrap {
        // call service for response
        return $this->usersService->getTotal();
    }

}