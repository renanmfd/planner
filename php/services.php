<?php

define('TYPE_INCOME', 'income');
define('TYPE_OUTCOME', 'outcome');

function services_sumary_current_in($params) {
    // Get first day of the month
    $day = new DateTime('first day of this month');
    $day->setTime(0, 0, 0);
    $day_stamp = $day->format('U');

    $result = array();
    $result['sumary'] = database_get_entries('income', $day_stamp, time(), 3);
    $result['total'] = database_get_entries_sum('income', $day_stamp, time());

    return $result;
}

function services_sumary_current_out($params) {
    // Get first day of the month
    $day = new DateTime('first day of this month');
    $day->setTime(0, 0, 0);
    $day_stamp = $day->format('U');

    $result = array();
    $result['sumary'] = database_get_entries('outcome', $day_stamp, time(), 3);
    $result['total'] = database_get_entries_sum('outcome', $day_stamp, time());

    return $result;
}

function services_sumary_lastmonth($params) {
    // Get first day of the month
    $first_day = new DateTime('first day of last month');
    $first_day->setTime(0, 0, 0);
    $first_day_stamp = $first_day->format('U');
    $last_day = new DateTime('last day of last month');
    $last_day->setTime(23, 59, 59);
    $last_day_stamp = $last_day->format('U');

    $result = array();
    $result['sumary'] = database_get_entries('all', $first_day_stamp, $last_day_stamp, 3);
    $result['total'] = array();
    $result['total']['in'] = database_get_entries_sum('income', $first_day_stamp, $last_day_stamp);
    $result['total']['out'] = database_get_entries_sum('outcome', $first_day_stamp, $last_day_stamp);
    //$result['total']['total'] = $result['total']['in'] - $result['total']['out'];

    return $result;
}

function services_month_list($params) {
    // Get first day of the month
    $day = new DateTime('first day of this month');
    $day->setTime(0, 0, 0);
    $day_stamp = $day->format('U');

    $result = array();
    $result['result'] = database_get_entries('all', $day_stamp, time(), 9999);
    $result['total'] = array();
    $result['total']['in'] = database_get_entries_sum('income', $day_stamp, time());
    $result['total']['out'] = database_get_entries_sum('outcome', $day_stamp, time());

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
