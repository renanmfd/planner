<?php

function init_db() {
    // Create connection
    $config = get_config();
    $database = mysqli_connect($config['host'], $config['username'], $config['password'], $config['database']);

    // Check connection
    if (!$database) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $tables = get_tables();
    foreach ($tables as $table) {
        $name = $config['prefix'] . $table['name'];
        print 'Table = ' . $name . '<br>';

        // Check if table exists.
        $val = table_exists($database, $name);

        if ($val) {
            print("Table $name already exists!");
        } else {
            print create_table($database, $name, $table);
        }
        print '<hr>';
    }

    mysqli_close($database);
}

function get_config() {
    return array(
        'host' => 'server1i.tursites.com.br',
        'username' => 'tursites_tursite',
        'password' => 'tur.001',
        'database' => 'tursites_tursites',
        'prefix' => '111_'
    );
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
                'description' => 'VARCHAR(255)',
                'value' => array(
                    'type' => 'FLOAT',
                    'not_null' => true
                ),
                'monthly' => 'INT',
                'date' => array(
                    'type' => 'INT',
                    'not_null' => true
                ),
                'user' => array(
                    'type' => 'INT',
                    'not_null' => true
                ),
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
                    'primary_key' => true
                ),
                'name' => array(
                    'type' => 'VARCHAR(30)',
                    'not_null' => true
                ),
                'email' => array(
                    'type' => 'VARCHAR(30)',
                    'not_null' => true
                ),
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
    $val = mysqli_query($db, "select 1 from `" . $table. "`");

    if ($val !== FALSE) {
       return true;
    }
    return false;
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
        if (isset($field['not_null']) and $field['not_null'] == true) {
            $aux .= ' NOT NULL';
        }
        $query_fields[] = $aux;
    }
    $query_fields = implode(', ', $query_fields);
    $query = 'CREATE TABLE ' . $name . ' (' . $query_fields . ')';
    $result = mysqli_query($db, $query);
    if ($result) {
        return "Table $name created successfully!";
    }
    return "Error creating table $name: " . mysqli_error($db);
}
