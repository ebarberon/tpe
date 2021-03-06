<?php

require_once dirname(__FILE__). '/../views/usersView.php';
require_once dirname(__FILE__). '/../models/usersModel.php';

class usersController{

    private $viewUser;
    private $modelUser;


    function __construct(){
        $this->viewUser = new usersView();
        $this->modelUser = new usersModel();
    }


    function showLogin(){
        $this->viewUser->showLogin();
    }

    function loginUser(){
        $email=($_POST['email_input']);
        $password=($_POST['password_input']);

        $msg = "Campos vacios";
        $msg2 = "Datos incorrectos";

        if (empty($email) || empty($password)){
            $this->viewUser->showError($msg);
            die;
        }  

        $user= $this->modelUser->getByEmail($email);

        if ($user && password_verify($password, $user->password)){
            session_start();
            $_SESSION['ID_USER']=$user->id_user;
            $_SESSION['EMAIL_USER']=$user->email;

            if($user->admin == 1){
                $_SESSION['ADMIN']=$user->admin;
            }

            $this->viewUser->showHomeLocation();
        } else {
            $this->viewUser->showError($msg2);
        }

    }

    function logout(){
        session_start();
        session_destroy();
        header("Location: ". BASE_URL.'login');
    }

    function showSignUp(){
        $this->viewUser->showSignUp();
    }

    function userRegistration(){
        $inputEmail = ($_POST['email_user']);
        $inputPassword = ( $_POST['password_user']);

        $msg = "Campos vacios";
        $msg2 = "Ya hay un usuario registrado con ese email";

        if (empty($inputEmail) || empty($inputPassword)){
            $this->viewUser->showErrorSignUp($msg);
            die;
        } 

        $revisarEmail = $this->modelUser->revisionEmail($inputEmail);

        if (!empty($revisarEmail)){
            $this->viewUser->showErrorSignUp($msg2);
            die();
        }

        $passwordEncriptada = password_hash($inputPassword, PASSWORD_DEFAULT);

        $userID = $this->modelUser->registrarUsuario($inputEmail, $passwordEncriptada);

        session_start();
        $_SESSION['ID_USER']=$userID;
        $_SESSION['EMAIL_USER']=$inputEmail;

        $this->viewUser->showHomeLocation();
    }

    function usersList(){
        $this->checkLogged();
        $users = $this->modelUser->obtenerUsuarios();
        $this->viewUser->mostrarUsuarios($users);
        
    }

    function checkLogged(){
        session_start();
        if(!isset($_SESSION['ADMIN'])){
            header("Location: ". BASE_URL . "home");
            die();
        }
    }

    function makeAdmin($params = null){
        $id_user = $params[':ID'];
        $this->modelUser->makeAdmin($id_user);
        header("Location: ". BASE_URL . "users");
    }

    function makeUser($params = null){
        $id_user = $params[':ID'];
        $this->modelUser->makeUser($id_user);
        header("Location: ". BASE_URL . "users");
    }

    function deleteUser($params = null){
        $id_user = $params[':ID'];
        $this->modelUser->deleteUser($id_user);
        header("Location: ". BASE_URL . "users");
    }

}

?>