<?php

define('FOLDERPATH_CONFIG', '');
define('FOLDERPATH_SCRIPTS', 'js/');
define('FOLDERPATH_STYLES', 'css/');

function resolve_request() {
    init_session();

    check_logged();

    if (isset($_POST['services'])) {
        include_once 'php/services.php';

        header('Content-Type: application/json');

        $result = new stdClass();
        if (isset($_POST['action'])) {
            try {
                // Set service name and parameters.
                $action = 'services_' . str_replace('-', '_', $_POST['action']);
                $params = isset($_POST['params']) ? $_POST['params'] : array();

                // Check if service exists and call it.
                if (function_exists($action)) {
                    $result->data = call_user_func($action, $params);
                } else {
                    $result->data = array(
                        'action' => $action,
                        'error' => 'function `' . $action . '` not found.'
                    );
                }
            } catch (Exception $e) {
                $result->error = true;
                $result->desc = $e->toString();
            }
        } else {
            $result->error = true;
            $result->desc = 'Action not specified.';
        }
        $result->debug = debug_get();
        echo json_encode($result);
    }
    else {
        header('Content-Type: text/html');
        $vars = preprocess_html();
        $file = new File($vars['#template']);
        echo $file->template($vars);
    }
}

function init_session() {
    //$_SESSION = array();
    session_start();
    $_SESSION['debug'] = array();
}

function check_logged() {
    if (isset($_SESSION['user']) and $_SESSION['user'] > 0) {
        $GLOBALS['user'] = User::load($_SESSION['user']);
        if ($GLOBALS['user'] !== false) {
            $_SESSION['group'] = $GLOBALS['user']->getGroup();
            return true;
        }
    }
    unset($_SESSION['user']);
    session_destroy();
    // If user is not logged, go to login/register script.
    header('Location: user.php');
    exit(0);
    return false;
}

//========================


/*************************
 *
 * RESOUCES GETTERS
 *
 *************************/

function get_vars() {
    $vars = array();
    $vars['title'] = 'Decolar test';
    return $vars;
}

function get_js() {
    return array(
        'jquery' => array('src' => 'js/jquery/jquery.js'),
        'jquery_ui' => array('src' => 'js/jquery-ui/jquery-ui.min.js'),
        //'mask_money' => array('src' => 'js/maskMoney/jquery.maskMoney.min.js'),
        //'angular' => array('src' => 'https://ajax.googleapis.com/ajax/libs/angularjs/1.4.9/angular.min.js'),
        'modal' => array('src' => 'modal/modal.js'),
        'ajax' => array('src' => 'js/ajax.js'),
        'script' => array('src' => 'js/app.js')
    );
}

function get_css() {
    return array(
        'bootstrap' => array('href' => 'css/bootstrap/bootstrap.css'),
        'jquery_ui' => array('href' => 'js/jquery-ui/jquery-ui.min.css'),
        'icomoon' => array('href' => 'icomoon/style.css'),
        'modal' => array('href' => 'modal/modal.css'),
        'font' => array('href' => 'https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700|Slabo+27px'),
        'styles' => array('href' => 'css/app.css')
    );
}

function get_config() {
    $json = new File('config.json');
    return $json->getJSON();
}

//========================


/*************************
 *
 * PREPROCESS FUNCTIONS
 *
 *************************/

function preprocess_html() {
    $vars = get_vars();
    $vars['#template'] = 'templates/html.tpl.php';

    $vars['body_classes'] = 'html';

    //debug($GLOBALS, 'GLOBALS');

    $js_array = get_js();
    foreach ($js_array as $js) {
        $vars['scripts'][] = Theme::script($js['src']);
    }

    $css_array = get_css();
    foreach ($css_array as $css) {
        $vars['styles'][] = Theme::style($css['href']);
    }

    $vars_page = preprocess_page();
    $file = new File($vars_page['#template']);
    $vars['page'] = $file->template($vars_page);

    $vars_modal = preprocess_modal();
    $file = new File($vars_modal['#template']);
    $vars['modal'] = $file->template($vars_modal);

    return $vars;
}

function preprocess_page() {
    $vars = get_vars();
    $vars['#template'] = 'templates/page.tpl.php';

    $vars_header = preprocess_header();
    $file = new File($vars_header['#template']);
    $vars['header'] = $file->template($vars_header);

    $vars_sidebar = preprocess_sidebar();
    $file = new File($vars_sidebar['#template']);
    $vars['sidebar'] = $file->template($vars_sidebar);

    $vars_content = preprocess_content();
    $file = new File($vars_content['#template']);
    $vars['content'] = $file->template($vars_content);

    $vars_footer = preprocess_footer();
    $file = new File($vars_footer['#template']);
    $vars['footer'] = $file->template($vars_footer);

    $vars['debug'] = debug_get();

    return $vars;
}

function preprocess_modal() {
    $vars = get_vars();
    $vars['#template'] = 'modal/modal.tpl.php';

    return $vars;
}

function preprocess_header() {
    $vars = get_vars();
    $vars['#template'] = 'templates/header.tpl.php';

    $vars['boxes'] = array();
    $vars['boxes']['box_lastmonth'] = Theme::box('sumary_lastmonth', 'Last Month', empty_box_list());
    $vars['boxes']['box_current_in'] = Theme::box('sumary_current_in', 'Incomes', empty_box_list());
    $vars['boxes']['box_current_out'] = Theme::box('sumary_current_out', 'Outcomes', empty_box_list());

    $vars['date_widget'] = Theme::date_widget();

    return $vars;
}

function preprocess_content() {
    $vars = get_vars();
    $vars['#template'] = 'templates/content.tpl.php';

    $tabs = array(
        array(
            'index' => 1,
            'label' => 'Month List',
            'icon' => 'glyphicon glyphicon-signal',
            'id' => 'tabMonthList',
            'content' => 'content_month_list',
        )
    );

    foreach ($tabs as $tab) {
        $index = $tab['index'];
        $tab_vars = array();
        $tab_vars['#template'] = 'templates/content/content-tab.tpl.php';
        $tab_vars['index'] = $index;
        $tab_vars['id'] = $tab['id'];
        if (isset($tab['content']) and function_exists($tab['content'])) {
            $tab_vars['content'] = call_user_func($tab['content']);
        } else {
            $tab_vars['content'] = 'Empty';
        }

        $file = new File($tab_vars['#template']);
        $vars['tabs'][$index] = array();
        $vars['tabs'][$index]['content'] = $file->template($tab_vars);
        $vars['tabs'][$index]['label'] = $tab['label'];
        $vars['tabs'][$index]['icon'] = $tab['icon'];
    }

    return $vars;
}

function preprocess_sidebar() {
    $vars = get_vars();
    $vars['#template'] = 'templates/sidebar.tpl.php';

    $file = new File('templates/sidebar/sidebar-entry-form.tpl.php');
    $vars['income_form'] = $file->template(array('type' => 'Income'));
    $vars['outcome_form'] = $file->template(array('type' => 'Outcome'));

    return $vars;
}

function preprocess_footer() {
    $vars = get_vars();
    $vars['#template'] = 'templates/footer.tpl.php';

    return $vars;
}

function content_month_list() {
    $vars['#template'] = 'templates/content/tabs/tab-month-list.tpl.php';
    $vars['content'] = 'Some content';
    $file = new File($vars['#template']);
    return $file->template($vars);
}

//========================


/*************************
 *
 * HELPER FUNCTIONS
 *
 *************************/

function c($name) {
    // to lowercase.
    $name = strtolower($name);
    // remove spaces.
    $name = preg_replace('/\s/', '-', $name);
    // remove unwanted chars.
    $name = preg_replace('/[^\d\w-_]/', '', $name);

    return $name;
}

function t_month($number, $short = false) {
    $number = is_string($number) ? intval($number) : $number;
    $months = array(
        1 => 'Janeiro',
        2 => 'Fevereiro',
        3 => 'Março',
        4 => 'Abril',
        5 => 'Maio',
        6 => 'Junho',
        7 => 'Julho',
        8 => 'Agosto',
        9 => 'Setembro',
        10 => 'Outubro',
        11 => 'Novembro',
        12 => 'Dezembro'
    );
    return ($short) ? substr($months[$number], 0, 3) : $months[$number];
}

function empty_box_list($name = 'data') {
    $list = array();
    for ($i = 2; $i >= 0; $i -= 1) {
        $list[] = array(
            'name' => $name . '_' . $i,
            'label' => '&nbsp',
            'value' => '0'
        );
    }
    $list[] = array(
        'name' => $name . '_total',
        'label' => 'Total',
        'value' => '0',
        'total' => true
    );
    return $list;
}

function n_format($value) {
    $value = round($value, 2);
    return number_format($value, 2, ',', '.');
}

//========================


/*************************
 *
 * DEBUGING FUNCTIONS
 *
 *************************/

function debug($data, $name = null) {
    $name = $name ? $name : uniqid();
    $_SESSION['debug'][$name] = $data;
}

function debug_get() {
    if (isset($_SESSION['debug']) and !empty($_SESSION['debug'])) {
        ob_start();
        krumo($_SESSION['debug']);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
    return null;
}
