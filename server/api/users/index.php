<?php
include ('../../utils/Rest.php');

Class Users extends Rest
    {
        /*connect to db*/
        private $db = null;

        public function __construct()
        {
            $this->db=DateBase::getInstance();
        }

        /*get all users from DB*/
        public function getUsers()
        {
            $query = "SELECT id, login, role, email FROM booker_users";
            $sth = $this->db->query($query);
            if($sth)
            {
                $data = $sth->fetchAll(PDO::FETCH_ASSOC);
                $this->createResponse($data, 200);
            }
            else
                $this->createResponse(ERR_101, 404);
        }

        /*Registration new user */
        public function postUsers()
        {
            $login =  Validator::checkLogin($this->params['login'])? $this->params['login'] : false;
            $password = Validator::checkPassword($this->params['password'])? password_hash($this->params['password'], PASSWORD_BCRYPT):false;
            $email = Validator::checkEmail($this->params['email'])? $this->params['email'] : false;

            if($login && $password && $email)
            {
                $query = "SELECT id from `booker_users` where login = '$login'";
                $sth = $this->db->query($query);
                if(!$sth->fetchColumn()>0)
                {
                    $hash =  md5(mt_rand());
                    $time = strtotime("now");
                    $query = "INSERT INTO booker_users (login, email, password, hash, time) VALUES ('$login', '$email', '$password', '$hash', '$time')";
                    $sth = $this->db->prepare($query);
                    if($sth->execute())
                        $this->createResponse('Succsses operation registration', 201);
                    else
                        $this->createResponse('Incorrect request registration', 404);               
                }
                else
                    $this->createResponse('Login reserved', 404);
            }
            else
                $this->createResponse('I need params login, email and password', 404);
        }

        /*Get users by Id*/
        public function getUsersByParams()
        {
            list($id_user, $hash) = explode('/', $this->params, 2);
            $id_user = Validator::checkId($id_user)? $id_user : false;

            if($id_user && $hash && !empty($hash))
            {
                $sth = $this->db->prepare("SELECT hash from booker_users where id = '$id_user'");
                $sth->execute();
                if($sth->execute())
                {
                    $hashInput = strlen($hash)==32?$hash:false;
                    $hashUser = $sth->fetch(PDO::FETCH_ASSOC);
                    if($hashInput == $hashUser['hash'])
                        $this->createResponse(true, 200);
                    else
                        $this->createResponse(false, 200);
                }
                else
                    $this->createResponse('We didn\'t find user by this params', 404); 
            }
            else
                $this->createResponse('I need params id_user and hash', 404);               
        }

    }

$obj = new Users();
$obj->start();

