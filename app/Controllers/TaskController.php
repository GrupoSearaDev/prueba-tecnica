<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\Task;

class TaskController extends BaseController
{

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        $this->classModel = new Task();
        $this->validatorKeyToCreate = 'rulesToCreateTask';
        $this->validatorKeyToUpdate = 'rulesToUpdateTask';
        $this->fieldToSearch = 'title';
        
       
    }


    /**
     * The function "listTasks" retrieves tasks based on the user's authentication status and returns
     * them as a response.
     * 
     * @return array the list of tasks.
     */
    public function listTasks(){
        try {
            $user = $this->getUserAuthenticated($this->request->getServer('HTTP_AUTHORIZATION'));
            $tasks = $user['type'] == 'Administrador' 
                ? $this->classModel->select($this->classModel->fieldsWithFormat)->find() 
                : $this->classModel->select($this->classModel->fieldsWithFormat)->where('user_id',$user['id'])->findAll();
            return $this->respond($tasks);
        } catch (\Exception $e) {
            return $this->failNotFound($e->getMessage());
        }
    }
}
