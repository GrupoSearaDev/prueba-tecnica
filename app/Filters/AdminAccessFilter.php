<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class AdminAccessFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        
        $authorization = $request->getServer('HTTP_AUTHORIZATION');

        try {
            helper('jwt');
            $encodedToken = getJWTFromRequest($authorization);
            $user = getUserFromToken($encodedToken);
            if($user['type']== 'Administrador'){
                return $request;
            }else if($request->getMethod(true) == 'POST' && !in_array('create-user',explode('/',$_SERVER['PHP_SELF']))){
                $requestData = json_decode($request->getBody(), true);
                if($user['id'] == $requestData['user_id']){
                    return $request;
                }
            }
            return Services::response()->setJSON([
                'error'=>'Permission denied'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            return Services::response()->setJSON([
                'error'=>$e->getMessage()
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
