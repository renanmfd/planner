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
        $this->created = 0;
    }

    public static function save($user) {
        global $db, $config;

        $data = array(
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => crypt($user['password']),
            'created' => time()
        );
        $data['id'] = $db->insert($config['prefix'] . 'users', $data);
        return $data;
    }

    public static function exists($email) {
        global $db, $config;

        $results = $db
            ->select('*')
            ->from($config['prefix'] . 'users')
            ->where('email', $_POST['email'])
            ->fetch();
        return empty($results) ? false : true;
    }

    public static function login($email, $password) {
        $result = new stdClass();
        $result->user = null;
        $users = $db
            ->select('*')
            ->from($config['prefix'] . 'users')
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
}
