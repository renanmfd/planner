<?php

define('TYPE_INCOME', 'income');
define('TYPE_OUTCOME', 'outcome');

function services_sumary_current_in($params) {
    // Get first day of the month
    $day = new DateTime('first day of this month');
    $day_stamp = $day->format('U');
    
    $result = array();
    $result['sumary'] = database_get_entries('income', $day_stamp, time(), 3);
    $result['total'] = database_get_entries_sum('income', $day_stamp, time());
    
    return $result;
}

function services_sumary_current_out($params) {
    // Get first day of the month
    $day = new DateTime('first day of this month');
    $day_stamp = $day->format('U');
    
    $result = array();
    $result['sumary'] = database_get_entries('outcome', $day_stamp, time(), 3);
    $result['total'] = database_get_entries_sum('outcome', $day_stamp, time());
    
    return $result;
}

function services_sumary_lastmonth($params) {
    return array('services', 'sumary', 'lastmonth', $params);
}

function services_month_list($params) {
    return array('services', 'sumary', 'month list', $params);
}

function services_add_income($params) {
    $config = $GLOBALS['config'];
    $db = new Database($config['host'], $config['username'], $config['password'], $config['database']);
    
    $data = array(
        'title' => $params['name'],
        'description' => $params['description'],
        'value' => $params['value'],
        'monthly' => ($params['isMonthly'] == 'false') ? 0 : intval($params['monthly']),
        'date' => ($params['isToday'] == 'false') ? $params['date'] : time(),
        'user' => 1,
        'type' => 'income',
        'created' => time()
    );
    
    $data['id'] = $db->insert('entries', $data);
    return $data;
}

function services_add_outcome($params) {
    $config = $GLOBALS['config'];
    $db = new Database($config['host'], $config['username'], $config['password'], $config['database']);
    
    $data = array(
        'title' => $params['name'],
        'description' => $params['description'],
        'value' => $params['value'],
        'monthly' => ($params['isMonthly'] == 'false') ? 0 : intval($params['monthly']),
        'date' => ($params['isToday'] == 'false') ? $params['date'] : time(),
        'user' => 1,
        'type' => 'outcome',
        'created' => time()
    );
    
    $data['id'] = $db->insert('entries', $data);
    return $data;
}

/**
 * HELPERS.
 *//*
function database_save($data, $type) {
    // Open file.
    $database = _database_get($type);

    // Set fields.
    $index = count($database);
    $data['id'] = $index;
    $data['timestamp'] = time();
    $database[$index] = $data;

    // Save file.
    return _database_set($database, $type);
}

function database_get_id($id, $type) {
    // Open file.
    $database = _database_get($type);

    // Search for id.
    foreach ($database as $data) {
        if ($data['id'] == $id) {
            return $data;
        }
    }
    return false;
}

function _database_get($type = TYPE_OUTCOME) {
    // Check if type is valid.
    if ($type != TYPE_INCOME and $type != TYPE_OUTCOME) {
        $type = TYPE_OUTCOME;
    }

    $filename = 'database/database_' . $type . '.json';
    $database = @file_get_contents($filename);

    if (!$database) {
        $database = '[]';
    }
    return json_decode($database);
}

function _database_set($data, $type = TYPE_OUTCOME) {
    // Check if type is valid.
    if ($type != TYPE_INCOME and $type != TYPE_OUTCOME) {
        $type = TYPE_OUTCOME;
    }

    $filename = 'database/database_' . $type . '.json';
    return file_put_contents($filename, json_encode($data)) ? $data : false;
}*/

function database_get_entries($type, $time_start, $time_end, $limit = 20, $offset = 0) {
    $config = $GLOBALS['config'];
    $db = new Database($config['host'], $config['username'], $config['password'], $config['database']);

    $query = $db
        ->select('*')
        ->from('entries');
    if ($type == 'income' or $type == 'outcome') {
        $query = $query->where('type', $type);
    }
    $results = $query
        ->where('date >', $time_start)
        ->where('date <', $time_end)
        ->limit($limit, $offset)
        ->fetch();
    return $results;
}

function database_get_entries_sum($type, $time_start, $time_end) {
    $config = $GLOBALS['config'];
    $db = new Database($config['host'], $config['username'], $config['password'], $config['database']);

    $results = $db
        ->select_sum('value')
        ->from('entries')
        ->where('type', $type)
        ->where('date >', $time_start)
        ->where('date <', $time_end)
        ->fetch();
    return array_shift($results);
}
