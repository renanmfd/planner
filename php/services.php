<?php

define('TYPE_INCOME', 'income');
define('TYPE_OUTCOME', 'outcome');

function services_sumary_current_in($params) {
    $db = _database_get(TYPE_INCOME);
    return array_slice($db, -3);
}

function services_sumary_current_out($params) {
    $db = _database_get(TYPE_OUTCOME);
    return array_slice($db, -3);
}

function services_sumary_lastmonth($params) {
    return array('services', 'sumary', 'lastmonth', $params);
}

function services_month_list($params) {
    $db_in = _database_get(TYPE_INCOME);
    $db_out = _database_get(TYPE_OUTCOME);
    return array_merge($db_in, $db_out);
}

function services_add_income($params) {
    $data = array(
        'name' => $params['name'],
        'description' => $params['description'],
        'value' => $params['value'],
        'monthly' => ($params['isMonthly'] == 'false') ? 0 : intval($params['monthly']),
        'date' => ($params['isToday'] == 'false') ? $params['date'] : date('d-m-Y'),
        'user' => 1,
        'type' => 'income'
    );
    return database_save($data, TYPE_INCOME);
}

function services_add_outcome($params) {
    $data = array(
        'name' => $params['name'],
        'description' => $params['description'],
        'value' => $params['value'],
        'monthly' => ($params['isMonthly'] == 'false') ? 0 : intval($params['monthly']),
        'date' => ($params['isToday'] == 'false') ? $params['date'] : date('d-m-Y'),
        'user' => 1,
        'type' => 'outcome'
    );
    return database_save($data, TYPE_OUTCOME);
}

/**
 * HELPERS.
 */
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
}
