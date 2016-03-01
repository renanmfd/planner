<?php

class Entry {

    public static $type_all = 'all';
    public static $type_income = 'income';
    public static $type_outcome = 'outcome';

    /*private $id;
    private $title;
    private $description;
    private $value;
    private $monthly;
    private $date;
    private $group;
    private $type;
    private $created;*/

    public $id;
    public $title;
    public $description;
    public $value;
    public $monthly;
    public $date;
    public $group;
    public $type;
    public $created;

    public function Entry($title, $value, $group, $type) {
        $this->title = $title;
        $this->value = $value;
        $this->description = '';
        $this->monthly = false;
        $this->date = time();
        $this->group = $group;
        $this->type = $type;
        $this->created = time();
    }

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getTitle(){
        return $this->title;
    }

    public function setTitle($title){
        $this->title = $title;
    }

    public function getDescription(){
        return $this->description;
    }

    public function setDescription($description){
        $this->description = $description;
    }

    public function getValue(){
        return n_format($this->value);
    }

    public function setValue($value){
        $this->value = floatval($value);
    }

    public function getMonthly(){
        return $this->monthly;
    }

    public function setMonthly($monthly){
        $this->monthly = $monthly;
    }

    public function getDate(){
        return $this->date;
    }

    public function setDate($date){
        $this->date = $date;
    }

    public function getGroup(){
        return $this->group;
    }

    public function setGroup($group){
        $this->group = $group;
    }

    public function getType(){
        return $this->type;
    }

    public function setType($type){
        $this->type = $type;
    }

    public function getCreated(){
        return $this->created;
    }

    public function setCreated($created){
        $this->created = $created;
    }

    public function persist() {
        Entry::save($this);
    }

    public function toArray() {
        return Entry::_toArray($this);
    }

    /** STATIC FUNCTIONS **/

    public static function save($entry) {
        global $db, $config, $user;

        $entry->setCreated(time());
        $data = $entry->toArray();

        $id = $db->insert($config->prefix . 'entries', $data);

        $entry->setId($id);
        return $entry;
    }

    public static function load($id) {
        global $db, $config, $user;

        $results = $db
            ->select('*')
            ->from($config->prefix . 'entries')
            ->where('id', $id)
            ->where('group_code', $user->getGroup())
            ->fetch();

        if (!empty($results)) {
            $result = array_shift($results);
            return Entry::_map($result);
        }
        return null;
    }

    public static function load_month($year, $month,
                $type = 'all', $limit = 9999, $offset = 0, $order = 'desc') {

        global $db, $config, $user;

        $date = new DateTime();
        $date->setDate(intval($year), intval($month), 1);
        $date->setTime(0, 0, 0);
        $date_start = $date->format('U');

        $last_of_month = $date->format('t');
        $date->setDate(intval($year), intval($month), intval($last_of_month));
        $date->setTime(23, 59, 59);
        $date_end = $date->format('U');

        $query = $db
            ->select('*')
            ->from($config->prefix . 'entries');
        if ($type !== 'all') {
            $query = $query->where('type', $type);
        }
        $results = $query
            ->where('date >=', $date_start)
            ->where('date <', $date_end)
            ->where('group_code', $user->getGroup())
            ->limit($limit, $offset)
            ->order_by('date', $order)
            ->order_by('id', $order)
            ->fetch();

        if (!empty($results)) {
            $entries = array();
            foreach ($results as $result) {
                $entries[] = Entry::_map($result)->toArray();
            }
            return $entries;
        }
        return array();
    }

    public static function load_sum($year, $month, $type) {
        global $db, $config, $user;

        $date = new DateTime();
        $date->setDate(intval($year), intval($month), 1);
        $date->setTime(0, 0, 0);
        $date_start = $date->format('U');

        $last_of_month = $date->format('t');
        $date->setDate(intval($year), intval($month), intval($last_of_month));
        $date->setTime(23, 59, 59);
        $date_end = $date->format('U');

        $results = $db
            ->select_sum('value')
            ->from($config->prefix . 'entries')
            ->where('type', $type)
            ->where('date >=', $date_start)
            ->where('date <', $date_end)
            ->where('group_code', $user->getGroup())
            ->fetch();

        if (!empty($results)) {
            $value = array_shift($results)['value'];
            return floatval($value);
        }
        return 0;
    }

    private static function _map($result) {
        $entry = new Entry($result['title'], $result['value'], $result['group_code'], $result['type']);
        $entry->setId($result['id']);
        $entry->setDescription($result['description']);
        $entry->setMonthly($result['monthly']);
        $entry->setDate($result['date']);
        $entry->setCreated($result['created']);
        return $entry;
    }

    private static function _toArray($entry) {
        $data = array(
            'id' => $entry->getId(),
            'title' => $entry->getTitle(),
            'description' => $entry->getDescription(),
            'value' => $entry->getValue(),
            'monthly' => $entry->getMonthly(),
            'date' => $entry->getDate(),
            'group_code' => $entry->getGroup(),
            'type' => $entry->getType(),
            'created' => $entry->getCreated()
        );
        return $data;
    }
}
