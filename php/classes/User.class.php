<?php

class User {

    private $id;
    private $name;
    private $email;
    private $password;
    private $created;

    public function User($name, $email, $password) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->id = 0;
        $this->created = time();
    }

    public static function save($user) {
        global $db, $config;
    }

}
