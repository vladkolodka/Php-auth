<?php
class Connection{
    private $db; // PDO class
    const DB_NAME = "user_data";

    public function __construct(){
        $this->db = new PDO("sqlite:" . __DIR__ . '/' . Connection::DB_NAME, '', '', array(
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        )); // open connection
    }
    public function createBase($mode = 0){
        if(file_exists(Connection::DB_NAME) && $mode == 0)
            return false;
        if($mode == 1) unlink("Connection::DB_NAME");
        $result = $this->db->exec("CREATE TABLE users(
          id INTEGER PRIMARY KEY AUTOINCREMENT,
          nick TEXT NOT NULL UNIQUE,
          pass TEXT NOT NULL,
          keyCode TEXT DEFAULT 0,
          time TEXT DEFAULT 0)");
        if($result === false) return false;
        return true;
    }
    public function addUser($nick, $pass){
        $password = md5($pass);
        $stmt = $this->db->prepare("INSERT INTO users(nick, pass) VALUES (:nick, :pass)");
        $result = $stmt->execute(array(
            ':nick' => $nick,
            ':pass' => $password
        ));
        if($result){
            $this->setKey($nick);
            return true;
        }
        return false;
    }
    public function removeUser($nick){
        $this->db->exec("DELETE FROM users WHERE nick = '$nick'");
    }
    public function checkUser($nick, $pass){
        $password = md5($pass);
        $stmt = $this->db->prepare("SELECT count(*) AS COUNT, id FROM users WHERE nick = :nick AND pass = :pass");
        $stmt->execute(array(
            ':nick' => $nick,
            ':pass' => $password
        ));
        $data = $stmt->fetch();

        if($data->COUNT != 1) return -1;
        return $data->id;
    }
    public function setKey($data, $mode = 0){
        $str = 'nick';
        if($mode) $str = 'id';

        $code = md5(uniqid("key_", true) . mt_rand(0, 1000));
        $time = time();
        $result = $this->db->exec("UPDATE users SET keyCode = '$code', time = '$time' WHERE $str = '$data'");

        if($result === false) return -1;
        return $code;
    }
    public function checkKey($id, $key){
        $stmt = $this->db->prepare("SELECT COUNT(*) AS COUNT, time FROM users WHERE id = :id AND keyCode = :code");
        $stmt->execute(array(
            ':id' => $id,
            ':code' => $key
        ));
        $data = $stmt->fetch();
        if($data->COUNT != 1) return false;
        if(time() - $data->time >= 604800) return false; // key expired
        return true;
    }
    public function keyExists($id){
        $stmt = $this->db->prepare("SELECT keyCode, time FROM users WHERE id = :id");
        $stmt->execute(array(
            ':id' => $id
        ));

        $data = $stmt->fetch();

        if(time() - $data->time >= 604800) return false;
        return $data->keyCode;
    }
}