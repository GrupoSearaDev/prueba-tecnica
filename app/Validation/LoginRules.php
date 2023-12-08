<?php

namespace App\Validation;
use App\Models\User;

class LoginRules
{
    

    /**
     * The function validates a user by checking if the provided email and password match the stored
     * user's email and hashed password.
     * 
     * @param string str required for the documentation.
     * @param string fields required for the documentation.
     * @param array data This parameter is an array that contains the user's email and password.
     * 
     * @return boolean It returns true if the password provided in the  array matches the
     * hashed password stored in the  array, and false otherwise.
     */
    public function validateUser(string $str, string $fields, array $data){
        try {
            $model = new User();
            $user = $model->findUserByEmail($data['email']);
            return password_verify($data['password'], $user['password']);
        } catch (\Exception $e) {
            return false;
        }
    }
}
