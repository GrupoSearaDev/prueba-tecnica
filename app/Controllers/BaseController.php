<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use CodeIgniter\API\ResponseTrait;
use App\Models\User;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{

    use ResponseTrait;
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

       

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();

        $this->userModel = new User();

        $this->validation = \Config\Services::validation();
    }

    

    /**
     * The function takes an incoming request object, retrieves the body of the request, and returns it
     * as a decoded JSON array.
     * 
     * @param IncomingRequest request The parameter `request` is an instance of the `IncomingRequest`
     * class. It represents an incoming HTTP request made to the server.
     * 
     * @return mixed the JSON decoded body of the incoming request as an associative array.
     */
    public function getRequest(IncomingRequest $request){
        return json_decode($request->getBody(), true);
    }



    
    /**
     * The store function saves data to the database and returns the saved data if successful, or
     * returns validation errors if validation fails.
     * 
     * @return Array either a response with the created data if the validation passes, or a response with the
     * validation errors if the validation fails. If an exception is caught, it will return a response
     * with the error message from the exception.
     */
    public function store(){
        try {
            $data = $this->getRequest($this->request);
            if($this->validation->run($data, $this->validatorKeyToCreate)){
                $this->classModel->save($data);
                $dataSaved = $this->classModel->where($this->fieldToSearch, $data[$this->fieldToSearch])->first();
                return $this->respondCreated($dataSaved);
            }else{
                return $this->fail($this->validation->getErrors());
            }
        } catch (\Exception $e) {
            return $this->failNotFound($e->getMessage());
        }
       
    }


    /**
     * The function updates a record with the given ID, after performing authorization and validation
     * checks.
     * 
     * @param int id The `id` parameter is an integer that represents the unique identifier of the
     * element that needs to be updated.
     * 
     * @return array different responses based on the conditions:
     */
    public function update(int $id){
        try {
            $user = $this->getUserAuthenticated($this->request->getServer('HTTP_AUTHORIZATION'));
            if($this->isAllowedUser($user, $id)){
                $data = $this->getRequest($this->request);
                if($this->validation->run($data, $this->validatorKeyToUpdate)){
                    $this->classModel->update($id, $data);
                    $elementUpdated = $this->classModel->findElementById($id);            
        
                    return $this->respondCreated($elementUpdated);
                }else{
                    return $this->fail($this->validation->getErrors());
                }
            }
            return $this->failUnauthorized("Permission denied");
        } catch (\Exception $e) {
            return $this->failNotFound($e->getMessage());
        }
    }


   



    /**
     * The function deletes a record from the database if the authenticated user has permission to do
     * so, otherwise it returns an unauthorized error message.
     * 
     * @param id The "id" parameter represents the unique identifier of the element that needs to be
     * deleted.
     * 
     * @return array either a response indicating that the element has been deleted, or a response indicating
     * that permission is denied or the element was not found.
     */
    public function delete($id){
        try {
            $user = $this->getUserAuthenticated($this->request->getServer('HTTP_AUTHORIZATION'));
            if($this->isAllowedUser($user, $id)){
                $this->classModel->findElementById($id);
                $this->classModel->delete($id);
                return $this->respondDeleted(['msg'=> substr($this->classModel->table, 0, -1) . ' eliminado']);
            }
            return $this->failUnauthorized("Permission denied");
        } catch (\Exception $e) {
            return $this->failNotFound($e->getMessage());
        }
    }


    /**
     * The function checks if a user is allowed based on their type and user ID.
     * 
     * @param User The "user" parameter is an array that contains information about the user. It likely
     * includes details such as the user's type (e.g., "Administrador") and user ID.
     * @param id The "id" parameter represents the ID of a user or an element in the system.
     * 
     * @return Boolean a boolean value. It returns true if the user is an administrator or if the user's ID
     * matches the given ID. Otherwise, it returns false.
     */
    private function isAllowedUser($user, $id){
        if($user['type'] == "Administrador"){ return true; }
        if($this->classModel->table == 'users'){
            return $user['id'] == $id;
        }
        $UserIdFromModel= $this->classModel->findElementById($id)['user_id'];
        return $user['id'] == $UserIdFromModel;
    }

    /**
     * The function `getUserAuthenticated` retrieves the user information from a JSON Web Token (JWT)
     * extracted from the authorization header.
     * 
     * @param String authorizationHeader The `authorizationHeader` parameter is a string that represents the
     * authorization header value of an HTTP request. It typically contains the authentication token or
     * credentials needed to authenticate the user making the request.
     * 
     * @return User the user object obtained from the token.
     */
    protected function getUserAuthenticated($authorizationHeader){
        try {
            helper('jwt');
            $encodedToken = getJWTFromRequest($authorizationHeader);
            return getUserFromToken($encodedToken);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
