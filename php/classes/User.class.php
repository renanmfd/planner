<?php

class User {

    private $id;
    public $name;
    private $email;
    private $password;
    private $group;
    private $created;

    public function User($name, $email, $password, $created = 0) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->id = 0;
        $this->created = $created;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function setId($id) {
        $this->id = $id;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function setName($name) {
        $this->name = $name;
    }
    
    public function getEmail() {
        return $this->email;
    }
    
    public function setEmail($email) {
        $this->email = $email;
    }
    
    public function getGroup() {
        return $this->group;
    }
    
    public function setGroup($group) {
        $this->group = $group;
    }

    public function getCreated() {
        return $this->created;
    }

    public function persist() {
        return User::save($this);
    }
    
    public function createGroup() {
        $this->group = uniqid('group');
    }

    /** STATIC **/

    public static function save($user) {
        global $db, $config;

        $data = array(
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => $user->password,
            'group_code' => $user->getGroup(),
            'created' => time()
        );
        $data['id'] = $db->insert($config->prefix . 'users', $data);
        $user->setId($data['id']);
        return $data;
    }

    public static function exists($email) {
        global $db, $config;

        $results = $db
            ->select('*')
            ->from($config->prefix . 'users')
            ->where('email', $_POST['email'])
            ->fetch();
        return empty($results) ? false : true;
    }

    public static function login($email, $password) {
        global $db, $config;
        
        $result = new stdClass();
        $result->user = null;
        $users = $db
            ->select('*')
            ->from($config->prefix . 'users')
            ->where('email', $email)
            ->fetch();

        if (!empty($users)) {
            $user = array_shift($users);

            if ($user['password'] == crypt($password, $user['password'])) {
                $result->user = $user;
            } else {
                $result->error = 'E02';
            }
        } else {
            $result->error = 'E01';
        }
        return $result;
    }
    
    public static function load($id) {
        global $db, $config;
        
        $result = new stdClass();
        $result->user = null;
        $users = $db
            ->select('*')
            ->from($config->prefix . 'users')
            ->where('id', $id)
            ->fetch();
        
        if (empty($users)) {
            return false;
        }
        $udata = array_shift($users);
        $user = new User($udata['name'], $udata['email'], $udata['password'], $udata['created']);
        $user->setId($udata['id']);
        $user->setGroup($udata['group_code']);
        return $user;
    }
}
