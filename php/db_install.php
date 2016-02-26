<?php

function init_db() {
    // HTML Base.
    header('Content-Type: text/html');
    $vars = db_preprocess_html();
    $file = new File($vars['#template']);
    echo $file->template($vars);
}

function db_preprocess_html() {
    $vars = array();
    $vars['#template'] = 'templates/install.tpl.php';
    $vars['title'] = 'Install';
    $vars['scripts'] = array();
    $vars['styles'] = array('<link href="css/bootstrap/bootstrap.css" rel="stylesheet">');

    // Printing results.
    $results = array();

    // Create connection.
    $config = get_config();
    $database = mysqli_connect($config->host, $config->username, $config->password, $config->database);

    // Check connection.
    if (!$database) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $tables = get_tables();
    foreach ($tables as $name => $table) {
        $cell = new stdClass();
        $cell->name = $name;
        $name = $config->prefix . $name;
        $cell->prefixed_name = $name;

        // Icon set.
        $cell->geral = true;

        // Check if table exists.
        $val = table_exists($database, $name);

        if ($val !== false) {
            $cell->exists = true;
            $cell->created = false;
            $cell->message = '&nbsp;';
            $cell->entries = $val;
        } else {
            $cell->exists = false;
            $result = create_table($database, $name, $table);
            if ($result) {
                $cell->created = true;
                $cell->message = "Table created successfully!";
            } else {
                $cell->created = false;
                $cell->message = "Error: " . mysqli_error($database);
                $cell->geral = false;
            }
            $cell->entries = '&nbsp;';
        }
        $results[] = $cell;
    }

    $vars['results'] = $results;
    mysqli_close($database);
    return $vars;
}

/*function get_config() {
    return array(
        'host' => 'server1i.tursites.com.br',
        'username' => 'tursites_tursite',
        'password' => 'tur.001',
        'database' => 'tursites_tursites',
        'prefix' => '111_'
    );
}*/

function get_config() {
    $json = new File('config.json');
    return $json->getJSON();
}

function get_tables() {
    return array(
        'entries' => array(
            'name' => 'entries',
            'fields' => array(
                'id' => array(
                    'type' => 'INT',
                    'auto_increment' => true,
                    'primary_key' => true
                ),
                'title' => array(
                    'type' => 'VARCHAR(30)',
                    'not_null' => true
                ),
                'description' => 'TEXT',
                'value' => array(
                    'type' => 'FLOAT',
                    'not_null' => true
                ),
                'monthly' => 'INT',
                'date' => array(
                    'type' => 'INT',
                    'not_null' => true
                ),
                'group_code' => 'VARCHAR(255)',
                'type' => array(
                    'type' => 'VARCHAR(15)',
                    'not_null' => true
                ),
                'created' => array(
                    'type' => 'INT',
                    'not_null' => true
                )
            )
        ),
        'users' => array(
            'name' => 'users',
            'fields' => array(
                'id' => array(
                    'type' => 'INT',
                    'auto_increment' => true,
                    'unique' => true,
                    'primary_key' => true,
                    'not_null' => true
                ),
                'name' => array(
                    'type' => 'VARCHAR(30)',
                    'not_null' => true
                ),
                'email' => array(
                    'type' => 'VARCHAR(30)',
                    'unique' => true,
                    'not_null' => true
                ),
                'password' => array(
                    'type' => 'VARCHAR(255)',
                    'not_null' => true
                ),
                'group_code' => 'VARCHAR(255)',
                'created' => array(
                    'type' => 'INT',
                    'not_null' => true
                )
            )
        ),
        'variables' => array(
            'name' => 'variables',
            'fields' => array(
                'name' => array(
                    'type' => 'INT',
                    'auto_increment' => true,
                    'primary_key' => true
                ),
                'value' => array(
                    'type' => 'TEXT',
                    'not_null' => true
                )
            )
        )
    );
}

function table_exists($db, $table) {
    $result = mysqli_query($db, "SELECT COUNT(*) AS count FROM `" . $table. "`");
    if (!$result) {
       return false;
    }

    $count = mysqli_fetch_assoc($result);
    return $count['count'];
}

function create_table($db, $name, $data) {
    $query_fields = array();
    foreach ($data['fields'] as $label => $field) {
        $type = is_string($field) ? $field : $field['type'];
        $aux = $label . ' ' . $type;
        if (isset($field['auto_increment']) and $field['auto_increment'] == true) {
            $aux .= ' AUTO_INCREMENT';
        }
        if (isset($field['primary_key']) and $field['primary_key'] == true) {
            $aux .= ' PRIMARY KEY';
        }
        if (isset($field['unique']) and $field['unique'] == true) {
            $aux .= ' UNIQUE';
        }
        if (isset($field['not_null']) and $field['not_null'] == true) {
            $aux .= ' NOT NULL';
        }
        $query_fields[] = $aux;
    }
    $query_fields = implode(', ', $query_fields);
    $query = 'CREATE TABLE ' . $name . ' (' . $query_fields . ')';
    $result = mysqli_query($db, $query);
    if ($result) {
        return true;
    }
    return false;
}
