<?php

namespace App\Controllers;

use App\Models\User;
use CodeIgniter\API\ResponseTrait;
use Dompdf\Options;
use Dompdf\Dompdf;
use CodeIgniter\Files\File;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Usercontroller extends BaseController{

    use ResponseTrait;


    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        $this->classModel = new User() ;
        $this->validatorKeyToCreate = 'rulesToCreateUser';
        $this->validatorKeyToUpdate = 'rulesToUpdateUser';
        $this->fieldToSearch = 'email';
       
    }

    

    /**
     * The function retrieves users from the database and returns a response with the users' data or a
     * failure message if an exception occurs.
     * 
     * @return array if the `find()` method is successful returns the list of users.
     *  If there is an exception thrown, the function will return
     * the result of the `failNotFound()` method with the error message from the exception.
     */
    public function getUsers(){
        try {
            $users = $this->userModel->select($this->userModel->fieldsWithFormat)->find();
            return $this->respond($users);
        } catch (\Exception $e) {
            return $this->failNotFound($e->getMessage());
        }
    }



}