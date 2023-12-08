<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class Auth extends BaseController
{
    use ResponseTrait;

    /**
     * The login function checks if the provided data is valid and returns a JSON Web Token (JWT) for
     * the user's email if it is, otherwise it returns the validation errors.
     * 
     * @return array either a JSON Web Token (JWT) for the user if the validation passes, or it is returning
     * the validation errors if the validation fails.
     */
    public function login(){
        $data = $this->getRequest($this->request);

        if($this->validation->run($data, 'rulesToLogin')){
            return $this->getJWTForUser($data['email']);
        }
        return $this->fail($this->validation->getErrors());
        
    }

    /**
     * The function `getJWTForUser` retrieves a JSON Web Token (JWT) for a user based on their email,
     * and returns it along with the user's information.
     * 
     * @param string email The email parameter is the email address of the user for whom we want to generate a
     * JSON Web Token (JWT).
     * 
     * @return array return a response with a message, user data (with the password field unset), and an access
     * token generated using the email provided.
     */
    private function getJWTForUser($email){
        try {
            $user = $this->userModel->findUserByEmail($email);
            $this->userModel->update($user['id'], ['last_login'=>date("Y-m-d H:i:s")]);
            unset($user['password']);
            helper('jwt');
            return $this->respond([
                'msg'=> "login succesfully",
                'user'=> $user,
                'access_token'=> generateJWTForUser($email)
            ]);
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    
}
