<?php


/**
 * The function `getJWTFromRequest` extracts the JWT (JSON Web Token) from the authorization header of
 * a request.
 * 
 * @param string|null  The `authHeader` parameter is a string that represents the authorization header of
 * an HTTP request.
 * 
 * @return string|null the second element of the array created by exploding the  string by a space.
 */
function getJWTFromRequest($authHeader){
    if(is_null($authHeader)){
        throw new \Exception("jwt not found or not authorized");
    }
    return explode(' ', $authHeader)[1];
}

/**
 * The function generates a JSON Web Token (JWT) for a user with the given email.
 * 
 * @param string email The email parameter is a string that represents the user's email address.
 * 
 * @return string a JSON Web Token (JWT) generated for the given email.
 */
function generateJWTForUser(string $email){
    $today = time();
    $timeToLive = getEnv('JWT_TIME_TO_LIVE');
    $expiration = $today + $timeToLive;

    $payload = [
        'email' => $email,
        'iat' => $today,
        "exp" => $expiration
    ];

    $token = \Firebase\JWT\JWT::encode($payload, \Config\Services::getJwtSecretToken(), 'HS256');

    return $token;
}

/**
 * The function validates a token by decoding it using a secret key and then finding a user in the
 * database based on the decoded email.
 * 
 * @param string token The token parameter is a string that represents a JSON Web Token (JWT).
 */
function validateToken(string $token){
    $secretKey = \Config\Services::getJwtSecretToken();
    $jsonDecoded = \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key($secretKey, 'HS256'));
    $userModel = new App\Models\User();
    $userModel->findUserByEmail($jsonDecoded->email);
}

/**
 * The function retrieves a user from a token by decoding the token using a secret key and returning
 * the user found by email in the user model.
 * 
 * @param string token The `token` parameter is a string that represents a JSON Web Token (JWT). A JWT
 * is a compact, URL-safe means of representing claims to be transferred between two parties. It
 * typically consists of three parts: a header, a payload, and a signature.
 * 
 * @return array|Exception the user object that matches the email decoded from the JWT token.
 */
function getUserFromToken(string $token){
    $secretKey = \Config\Services::getJwtSecretToken();
    $jsonDecoded = \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key($secretKey, 'HS256'));
    $userModel = new App\Models\User();
    return $userModel->findUserByEmail($jsonDecoded->email);
}