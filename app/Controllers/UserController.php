<?php

namespace App\Controllers;

use App\Models\User;
use CodeIgniter\API\ResponseTrait;
use Dompdf\Options;
use Dompdf\Dompdf;
use CodeIgniter\Files\File;

class Usercontroller extends BaseController{

    use ResponseTrait;

    /**
     * The index function returns a JSON response with a welcome message for the prueba-tecnica
     * project.
     * 
     * @return  array with a message "welcome to prueba-tecnica project" is being returned.
     */
    public function index(){
        return $this->respond([
            "msg"=> 'welcome to prueba-tecnica project'
        ]);
    }

    /**
     * The store function is responsible for saving user data and returning a response based on the
     * success or failure of the operation.
     * 
     * @return array a response with the created user data if the validation passes and the user is
     * successfully saved, or a response with the validation errors if the validation fails. If an
     * exception is caught, it will return a response with the error message.
     */
    public function store(){
        try {
            
            $data = $this->getRequest($this->request);

            if($this->validation->run($data, 'rulesToCreateUser')){
                $this->userModel->save($data);
                $userSaved = $this->userModel->where('email', $data['email'])->first();
    
                return $this->respondCreated($userSaved);
            }else{
                return $this->fail($this->validation->getErrors());
            }
        } catch (\Exception $e) {
            return $this->failNotFound($e->getMessage());
        }
       
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

    /**
     * The updateUser function updates a user's information if the user is an administrator or the user
     * themselves, using JWT authentication.
     * 
     * @param int id The parameter "id" is an integer that represents the unique identifier of the user
     * that needs to be updated.
     * 
     * @return array different responses based on certain conditions:
     */
    public function updateUser(int $id){
        try {
            $this->userModel->findUserById($id); 

            helper('jwt');
            $authorization = $this->request->getServer('HTTP_AUTHORIZATION');
            $encodedToken = getJWTFromRequest($authorization);
            $user = getUserFromToken($encodedToken);
            if($user['type'] == "Administrador" || $user['id'] == $id ){
                $data = $this->getRequest($this->request);
                if($this->validation->run($data, 'rulesToUpdateUser')){
                    $this->userModel->update($id, $data);
                    $userUpdated = $this->userModel->findUserById($id);            
        
                    return $this->respondCreated($userUpdated);
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
     * The deleteUser function deletes a user by their ID and returns a success message or a failure
     * message if the user is not found.
     * 
     * @param int id The parameter "id" is an integer that represents the unique identifier of the user
     * that needs to be deleted.
     * 
     * @return array response indicating that the user has been deleted, with a message "usuario
     * eliminado".
     */
    public function deleteUser(int $id){
        try {
            $this->userModel->findUserById($id);
            $this->userModel->delete($id);
            return $this->respondDeleted(['msg'=>'usuario eliminado']);
        } catch (\Exception $e) {
            return $this->failNotFound($e->getMessage());
        }

    }

    /**
     * The function generates a PDF file containing a list of users and saves it to a specified path.
     * 
     * @return  array with the key "path_pdf" and the value being the path to the generated PDF file.
     */
    public function generatePdfUsers(){
        try {
            $options = new Options();
            $options->setIsRemoteEnabled(true);
            $dompdf = new Dompdf($options);
            $users = $this->userModel->find();
            $dompdf->loadHtml(view('users_list', ["users"=>$users]));
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();
            $output = $dompdf->output();
            $pathPdf = ROOTPATH . 'public/assets/users_list.pdf';
            file_put_contents($pathPdf, $output);
            return $this->respond(['path_pdf'=>$pathPdf]);

        } catch (\Exception $e) {
            return $this->failNotFound($e->getMessage());
        }
        
    }

    /**
     * The function `uploadImage()` is used to validate and upload an image file in PHP.
     * 
     * @return an array with the name of the uploaded image file.
     */
    public function uploadImage(){
        $validationRule = [
            'userfile' => [
                'label' => 'Image File',
                'rules' => [
                    'uploaded[userfile]',
                    'is_image[userfile]',
                    'mime_in[userfile,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
                    'max_size[userfile,100]',
                    'max_dims[userfile,1024,768]',
                ],
            ],
        ];
        if (! $this->validate($validationRule)) {
            $data = ['errors' => $this->validator->getErrors()];

            return $this->fail($data);
        }

        $file = $this->request->getFile('userfile');
        $nameImgFile= $file->getName();
        $file->move(ROOTPATH.'public/photos/',$nameImgFile);
        return $this->respond(["name"=> $nameImgFile]);


    }


}