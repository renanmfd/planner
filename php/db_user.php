<?php

function resolve_user() {
    // If user is already logged.
    if (isset($_SESSION['user'])) {
        redirect('index.php');
    }

    // If some post action is requested or just html forms.
    if (isset($_POST['action'])) {
        print_r($_POST);
        executeAction($_POST['action']);
    } else {
        header('Content-Type: text/html');
        $vars = preprocess_html();
        $file = new File($vars['#template']);
        echo $file->template($vars);
    }
}

function executeAction($action) {
    $config = get_config();
    $db = new Database($config['host'], $config['username'],
                       $config['password'], $config['database']);

    if ($action == 'login') {
        /*$results = $db
            ->select('*')
            ->from($config['prefix'] . 'users')
            ->where('email', $_POST['email'])
            ->fetch();

        if (!empty($results)) {
            $user = array_shift($results);

            if ($user['password'] == crypt($_POST['password'], $user['password'])) {
                $_SESSION['user'] = $user['id'];
                redirect('index.php');
            } else {
                redirect('user.php', array('e' => 'E02'));
            }
        } else {
            redirect('user.php', array('e' => 'E01'));
        }*/
        $result = User::login($_POST['email'], $_POST['password']);
        if ($result->user) {
            $_SESSION['user'] = $result->user['id'];
            redirect('index.php');
        } else {
            redirect('user.php', array('e' => $result->error));
        }
    } else if ($action == 'register') {
        $results = $db
            ->select('*')
            ->from($config['prefix'] . 'users')
            ->where('email', $_POST['email'])
            ->fetch();

        if (empty($results)) {
            if ($_POST['password'] == $_POST['password_conf']) {
                $data = array(
                    'name' => $_POST['name'],
                    'email' => $_POST['email'],
                    'password' => crypt($_POST['password']),
                    'created' => time()
                );
                $id = $db->insert($config['prefix'] . 'users', $data);

                if ($id > 0) {
                    $_SESSION['user'] = $id;
                    redirect('index.php');
                } else {
                    redirect('user.php', array('e' => 'E13'));
                }
            } else {
                redirect('user.php', array('e' => 'E12'));
            }
        } else {
            redirect('user.php', array('e' => 'E11'));
        }
    } else {
        echo 'Eror: Action ' . $action . ' is not valid or set.';
    }
}

function redirect($path, $query = array()) {
    $get = http_build_query($query);
    header("Location: $path?$get");
    exit(0);
}

function preprocess_html() {
    $vars['#template'] = 'templates/html.tpl.php';
    $vars['title'] = 'User | Planner';
    $vars['body_classes'] = 'html';
    $vars['scripts'] = array();
    $vars['styles'] = array('<link href="css/bootstrap/bootstrap.css" rel="stylesheet">');
    $vars['modal'] = '';

    $vars_page = preprocess_user();
    $file = new File($vars_page['#template']);
    $vars['page'] = $file->template($vars_page);

    return $vars;
}

function preprocess_user() {
    $vars['#template'] = 'templates/user.tpl.php';
    if (isset($_GET['e'])) {
        $vars['error'] = error_code($_GET['e']);
    }
    return $vars;
}

function error_code($num) {
    switch ($num) {
        case 'E01':
            return 'Login: E-mail is invalid';
        case 'E02':
            return 'Login: Passwords do not match.';
        case 'E11':
            return 'Register: This e-mail is already in use.';
        case 'E12':
            return 'Register: Passwords do not match.';
        case 'E13':
            return 'Register: Database error. Try again later.';
        default:
            return 'Unknown error';
    }
}

function get_config() {
    return array(
        'host' => 'server1i.tursites.com.br',
        //'host' => 'localhost',
        'username' => 'tursites_tursite',
        //'username' => 'planner',
        'password' => 'tur.001',
        //'password' => 'planner',
        'database' => 'tursites_tursites',
        //'database' => 'planner',
        'prefix' => '111_'
        //'prefix' => ''
    );
}
