<?php
require_once __DIR__ . "/db.class.php";
class Authorization{
    private $connection;
    private $errors = array();
    private $authorized = false;

    public function __construct(){
        //$this->checkFiles();
        $this->connection = new Connection();
        $this->checkKey();
    }
    private function setKey($id, $code){
        $key = $id . '_' . $code;
        setcookie('user_id', $key, time() + 604800);
    }
    private function checkKey(){
        if(!isset($_COOKIE['user_id'])) return false;

        $key = explode('_', $_COOKIE['user_id']);
        if(!$this->connection->checkKey($key[0], $key[1])){
            $this->errors[] = "Bad key!";
            $this->logout();
            return false;
        }
        $this->authorized = true;
        return true;
    }
    public function login(){
        $nick = trim(strip_tags($_POST['nick']));
        $pass = trim(strip_tags($_POST['pass']));

        if($nick == '' || $pass == ''){
            $this->errors[] = "Bad username or password";
            return false;
        }
        $id = $this->connection->checkUser($nick, $pass);
        if($id == -1){
            $this->errors[] = "Incorrect username or password";
            return false;
        }

        $code = $this->connection->keyExists($id);
        if(!$code) $code = $this->connection->setKey($nick);

        $this->setKey($id, $code);
        $this->authorized = true;
        return true;
    }
    public function printErrors(){
        if(!count($this->errors)) return false;
        echo "<ul>";
        foreach ($this->errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
        return true;
    }
    public function logout(){
        setcookie('user_id', '', time());
        $this->authorized = false;
    }
    public function isAuthorized(){
        return $this->authorized;
    }
    public function regen(){
        if($this->isAuthorized()){
            $id = explode('_', $_COOKIE['user_id'])[0];
            $code = $this->connection->setKey($id, 1);
            $this->setKey($id, $code);
        }
    }
    public function checkFiles(){
        $root = $_SERVER["DOCUMENT_ROOT"];
        $guest = "780d306afcd42265c4aba0f90c0f0b7d";
        $user = "4bc6a9eabd741430f0ba396255067f21";
        $check = "aaf1a268c684344ff11577da35b4e33e";
        $main = "5c4c7c05bbeb487a6be0271db1f0ffc7";

        if(md5(file_get_contents($root . "/guest.php")) != $guest ||
            md5(file_get_contents($root . "/user.php")) != $user ||
            md5(file_get_contents($root . "/index.php")) != $main ||
            md5(file_get_contents($root . "/checkLogin.php")) != $check){
                echo "Изменение файлов запрещено!";
                exit;
            }
    }
}