<?php

define('TYPE_INCOME', 'income');
define('TYPE_OUTCOME', 'outcome');

function services_sumary_current_in($params) {
    // Get date params.
    $year = $params['date']['year'];
    $month = $params['date']['month'];

    $result = array();
    $result['sumary'] = Entry::load_month($year, $month, Entry::$type_income, 3);
    $result['total'] = n_format(Entry::load_sum($year, $month, Entry::$type_income));

    return $result;
}

function services_sumary_current_out($params) {
    // Get date params.
    $year = $params['date']['year'];
    $month = $params['date']['month'];

    $result = array();
    $result['sumary'] = Entry::load_month($year, $month, Entry::$type_income, 3);
    $result['total'] = n_format(Entry::load_sum($year, $month, Entry::$type_income));

    return $result;
}

function services_sumary_lastmonth($params) {
    // Get date params.
    $year = $params['date']['year'];
    $month = intval($params['date']['month']) - 1;
    if ($month <= 0) {
        $year = $year - 1;
        $month = 12;
    }

    $result = array();
    $result['sumary'] = Entry::load_month($year, $month, Entry::$type_all, 3);

    $result['total'] = array();
    $total_in = Entry::load_sum($year, $month, Entry::$type_income);
    $total_out = Entry::load_sum($year, $month, Entry::$type_outcome);
    $result['total']['in'] = n_format($total_in);
    $result['total']['out'] = n_format($total_out);
    $result['total']['total'] = n_format($total_in - $total_out);

    return $result;
}

function services_month_list($params) {
    // Get first day of the month
    $day = new DateTime('first day of this month');
    $day->setTime(0, 0, 0);
    $day_stamp = $day->format('U');

    $result = array();
    $result['result'] = Entry::load_month($year, $month, Entry::$type_all);
    $result['total'] = array();
    $total_in = Entry::load_sum($year, $month, Entry::$type_income);
    $total_out = Entry::load_sum($year, $month, Entry::$type_outcome);
    $result['total']['in'] = n_format($total_in);
    $result['total']['out'] = n_format($total_out);
    $result['total']['total'] = n_format($total_in - $total_out);

    return $result;
}

function services_add_income($params) {
    global $db, $config, $user;

    $data = array(
        'title' => $params['name'],
        'description' => $params['description'],
        'value' => $params['value'],
        'monthly' => ($params['isMonthly'] == 'false') ? 0 : intval($params['monthly']),
        'date' => ($params['isToday'] == 'false') ? $params['date'] : time(),
        'group_code' => $user->getGroup(),
        'type' => 'income',
        'created' => time()
    );

    $data['id'] = $db->insert($config->prefix . 'entries', $data);
    return $data;
}

function services_add_outcome($params) {
    global $db, $config, $user;

    $data = array(
        'title' => $params['name'],
        'description' => $params['description'],
        'value' => $params['value'],
        'monthly' => ($params['isMonthly'] == 'false') ? 0 : intval($params['monthly']),
        'date' => ($params['isToday'] == 'false') ? $params['date'] : time(),
        'group_code' => $user->getGroup(),
        'type' => 'outcome',
        'created' => time()
    );

    $data['id'] = $db->insert($config->prefix . 'entries', $data);
    return $data;
}

function services_logout($params) {
    unset($_SESSION['user']);
    session_destroy();
}

/**
 * Database Operations.
 */

function database_get_entries($type, $time_start, $time_end, $limit = 20, $offset = 0) {
    global $db, $config, $user;

    $query = $db
        ->select('*')
        ->from($config->prefix . 'entries');
    if ($type == 'income' or $type == 'outcome') {
        $query = $query->where('type', $type);
    }
    $results = $query
        ->where('date >', $time_start)
        ->where('date <', $time_end)
        ->where('group_code', $user->getGroup())
        ->limit($limit, $offset)
        ->order_by('date', 'desc')
        ->fetch();
    return $results;
}

function database_get_entries_sum($type, $time_start, $time_end) {
    global $db, $config, $user;

    $results = $db
        ->select_sum('value')
        ->from($config->prefix . 'entries')
        ->where('type', $type)
        ->where('date >', $time_start)
        ->where('date <', $time_end)
        ->where('group_code', $user->getGroup())
        ->fetch();

    if (empty($result)) {
        return 0;
    }
    return array_shift($results)['value'];
}
