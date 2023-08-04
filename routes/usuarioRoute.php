<?php 

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, token");
    header("Content-Type: application/json");

    require_once "../app/services/usuarios.php";
    require_once "../app/respuestas/respuesta.php";
    require_once "../app/auth/authClass.php";

    $usuario = new Usuario;
    $respuesta = new Respuesta;
    $auth = new authClass;

    
    $headers = getallheaders();
    if(isset($headers['token'])){
        $is_token = $auth->isAdmin($headers['token']);
        if($is_token){
            
            if($_SERVER["REQUEST_METHOD"] == "GET"){

                if(isset($_GET["page"])){
                    $pagina = $_GET["page"];
                    $usuarioLista = $usuario->getUsuarioPagina($pagina);
                    http_response_code(200);      
                    echo json_encode($usuarioLista);
                }else if(isset($_GET["id"])){
                    $id_usuario = $_GET["id"];
                    $usuario_data = $usuario->getUsuario($id_usuario);
                    http_response_code(200);      
                    echo json_encode($usuario_data);
                }else{
                    $usuarioLista = $usuario->getUsuarioLista();
                    http_response_code(200);      
                    echo json_encode($usuarioLista);
                }
        
            }else if($_SERVER["REQUEST_METHOD"] == "POST"){
                
                $body = file_get_contents("php://input");
                $result = $usuario->saveUsuario($body);
                if(isset($result["result"]["error_id"])){
                    $error_code = $result["result"]["error_id"];
                    http_response_code($error_code);
                }else{
                    http_response_code(200);
                }
                echo json_encode($result); 
        
            }else if($_SERVER["REQUEST_METHOD"] == "DELETE"){
                
                $result = '';
                if(isset($_GET["id"])){
                    $id_usuario = $_GET["id"];
                    $result = $usuario->deleteUsuario($id_usuario);
                }else{
                    $body = file_get_contents("php://input");
                    $body = json_decode($body, true);
                    $id_usuario = $body["id"];
                    $result = $usuario->deleteUsuario($id_usuario);
                }

                if(isset($result["result"]["error_id"])){
                    $error_code = $result["result"]["error_id"];
                    http_response_code($error_code);
                }else{
                    http_response_code(200);
                }
                echo json_encode($result);
                
            }else if($_SERVER["REQUEST_METHOD"] == "PUT"){
                
                $body = file_get_contents("php://input");
                $result = $usuario->updateUsuario($body);
                if(isset($result["result"]["error_id"])){
                    $error_code = $result["result"]["error_id"];
                    http_response_code($error_code);
                }else{
                    http_response_code(200);
                }
                echo json_encode($result);
                
            }else{
        
                $response_invalid = $respuesta->error405();
                echo json_encode($response_invalid);
        
            }

        }else{
            $response_invalid = $respuesta->error401("Token invalido");
            echo json_encode($response_invalid);
        }
    }else{
        $response_invalid = $respuesta->error401("No se ha encontrado ningun token");
        echo json_encode($response_invalid);
    }


?>