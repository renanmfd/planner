<?php

/**
 * @class File
 * @author renanmfd
 * @
 */
class File {

    private $filepath;

    /**
     * CONSTRUCTOR
     *
     * @constructor
     * @param {string} $filepath Path of the file on the system.
     */
    public function __construct($filepath) {
        $this->filepath = $filepath;
    }

    /**
     * Check if file exists.
     *
     * @return {boolean} Whether file exists.
     */
    public function exists() {
        return file_exists($this->filepath) ? true : false;
    }

    /**
     * Include the file to the current request if it exists.
     *
     * @return {boolean} Whether the file was included or not.
     */
    public function includeOnce() {
        if ($this->exists() and ($this->getExtention() == 'php' or $this->getExtention() == 'inc')) {
            include_once $this->filepath;
            return true;
        }
        return false;
    }

    /**
     * Include the file to the current request if it exists.
     *
     * @return {boolean} Whether the file was included or not.
     */
    public function execute() {
        if ($this->exists() and ($this->getExtention() == 'php' or $this->getExtention() == 'inc')) {
            ob_start();
            require $this->filepath;
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
        return false;
    }

    /**
     * Include the file to the current request if it exists.
     *
     * @return {boolean} Whether the file was included or not.
     */
    public function template($vars) {
        if ($this->exists()) {
            ob_start();
            extract($vars);
            require $this->filepath;
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
        return "File $this->filepath do not exists.";
    }

    /**
     * Get the content of the file as a string.
     *
     * @return {string} String with file content or empty if file could be read or doesn't exist.
     */
    public function getContent() {
        if ($this->exists()) {
            return file_get_contents($this->filepath);
        }
        return "";
    }
    
    /**
     * Get the content of the JSON file.
     *
     * @return {Object} Object with file content of JSON file.
     */
    public function getJSON() {
        if ($this->getExtention() == 'json') {
            $json = $this->getContent();
            return json_decode($json);
        }
        return "";
    }

    /**
     * Get the directory path of the file.
     *
     * @return {string} Path of the file or empty string if doesn't exists.
     */
    public function getDirectory() {
        if ($this->exists()) {
            return pathinfo($this->filepath, PATHINFO_DIRNAME);
        }
        return "";
    }

    /**
     * Get the base name of the file.
     *
     * @return {string} Basename of the file or empty string if doesn't exists.
     */
    public function getBasename() {
        if ($this->exists()) {
            return pathinfo($this->filepath, PATHINFO_BASENAME);
        }
        return "";
    }

    /**
     * Get the extention of the file.
     *
     * @return {string} Extention of the file or empty string if doesn't exists.
     */
    public function getExtention() {
        if ($this->exists()) {
            return strtolower(pathinfo($this->filepath, PATHINFO_EXTENSION));
        }
        return "";
    }

    /**
     * Get the name of the file.
     *
     * @return {string} Name of the file or empty string if doesn't exists.
     */
    public function getFilename() {
        if ($this->exists()) {
            return pathinfo($this->filepath, PATHINFO_FILENAME);
        }
        return "";
    }

    /**
     * Class printer.
     *
     * @return string Object description as string.
     */
    public function __toString() {
        $exists = $this->exists() ? 'true' : 'false';
        $dir = $this->getDirectory();
        $base = $this->getBasename();
        $ext = $this->getExtention();
        $name = $this->getFilename();
        return 'class File<br><ul>' .
            "<li>- [prop] filepath = '$this->filepath'</li>" .
            "<li>- [method] exists = '$exists'</li>" .
            "<li>- [method] extention = '$ext'</li>" .
            "<li>- [method] directory = '$dir'</li>" .
            "<li>- [method] name = '$name'</li>" .
            '</ul>';
    }
}
