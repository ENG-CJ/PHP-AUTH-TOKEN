<?php
include_once "conn.php";
require_once 'vendor/autoload.php';

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

$action = $_POST['action'];

function validateToken($token): array
{
    $response = array();
    try {
        
        $decodedToken = JWT::decode($token, new Key("@nasra123", 'HS256'));
        $expiration = $decodedToken->exp;
        if ($expiration < time())
            $response = array("hasError" => true, "error" => 'expire', "message" => "token has been expired");
        else
            $response = array("hasError" => false, "message" => "Login Successfully", "token" => $token);
    } catch (\Firebase\JWT\ExpiredException $e) {
        $response = array("hasError" => true, "error" => 'expire', "message" => "UnAuthorize Request, token has been expired");
    } catch (\Firebase\JWT\SignatureInvalidException $e) {
        $response = array("hasError" => true, "error" => 'expire', "message" => "Token signature is invalid format");
    } catch (\Exception $e) {
        $response = array("hasError" => true, "error" => 'expire', "message" => "UnAuthorize Request or Invalid Token");
    }
    return $response;
}
function generateToken($payload): string
{
    $token = JWT::encode($payload, '@nasra123', 'HS256');
    return $token;
}
if (isset($action)) {

    extract($_POST);
    $response = array();
    if ($action == "loginAuth") {
        $q = "SELECT *From logs where username='$username' and password='$pass'";
        $c = getConnection();

        $result = $c->query($q);
        if ($result) {
            $r = mysqli_num_rows($result);
            if ($r > 0) {
                $tokenPayload = [
                    "username" => $username,
                    "pass"=> $pass,
                    "exp" => time() + (3 * 60)
                ];

                $generate = generateToken($tokenPayload);
                $validTokenResponse = validateToken($generate);
                $response = array($validTokenResponse);
            } else
                $response = array("hasError" => true, "message" => "invalid username or password");
        }
        echo json_encode($response);
    } else if ($action == "request") {
        extract($_POST);
        // $authorizationHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        // $token = str_replace('Bearer ', '', $authorizationHeader);
        $tokeResponse = validateToken($token);
        echo json_encode($tokeResponse);
    }
}
