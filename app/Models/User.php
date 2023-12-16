<?php
namespace App\Models;

//use CodeIgniter\Model;
use App\Models\CustomModel;

class User extends CustomModel{

    protected $table = 'users';
    //protected $primaryKey = 'id';
    //protected $useAutoIncrement = true;
    //protected $useSoftDeletes = true;

    protected $allowedFields = ['name','lastname', 'phone', 'email', 'photo', 'password', 'type', 'last_login'];

    //protected $useTimestamps = true;
    //protected $dateFormat = 'datetime';
    //protected $createdField = 'created_at';
    //protected $updatedField = 'updated_at';
    //protected $deletedField = 'deleted_at';


    protected $beforeInsert = ['before'];
    protected $beforeUpdate = ['before'];

    protected $fieldsWithFormat = [
        'name as Nombre', 
        'lastname as Apellidos', 
        'phone as Celular', 
        'email as "Correo electronico"', 
        'photo as Fotografia', 
        'type as "Tipo de usuario"', 
        'created_at as "Fecha de registro"', 
        'updated_at as "Fecha de ultima actualizacion"', 
        'last_login as "Fecha de ultimo login"'
    ];


    protected function before(array $data){
        return $this->hashedPassword($data);
    }



    /**
     * The function takes an array of data and hashes the password value using the bcrypt algorithm.
     * 
     * @param array data The "data" parameter is an array that contains the information to be hashed.
     * It is expected to have a key called "password" which holds the password string that needs to be
     * hashed.
     * 
     * @return array return the modified array , where the password value has been hashed using the
     * password_hash() function.
     */
    private function hashedPassword(array $data){
        if(isset($data['data']['password'])){
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_BCRYPT);
        }
        return $data;
    }


    /**
     * The function "findUserById" finds a user by their ID and throws an exception if the user is not
     * found.
     * 
     * @param id The parameter "id" is the unique identifier of the user that we want to find.
     * 
     * @return User return the user object that matches the given id.
     */
    public function findUserById($id){
        $user = $this->find($id);
        if(!$user){
            throw new \Exception("User not found");
        }
        return $user;

    }

    /**
     * The function finds a user by their email address and returns the user if found, otherwise it
     * throws an exception.
     * 
     * @param string email The parameter `email` is a string that represents the email address of the
     * user you want to find.
     * 
     * @return array|Exception the user object that matches the given email.
     */
    public function findUserByEmail(string $email){
        $user = $this->asArray()->where('email', $email)->first();
        if(!$user){
            throw new \Exception("User not found");
        }
        return $user;

    }
    


}