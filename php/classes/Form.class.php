<?php

class Form {

    public $items;
    public $attr;

    public function Form() {
        $this->items = array();
        $this->attr = array(
            'action' => '',
            'method' => 'get'
        );
    }

    public function add($formItem) {
        $this->items[] = $formItem;
        return $this;
    }

    public function attr($name, $value) {
        $this->attr[$name] = $value;
        return $this;
    }

    public function generate() {
        $attr = FormItem::_attr($this->attr);
        $form = '<form' . $attr . '>';
        foreach ($this->items as $item) {
            $form .= $item->render();
        }
        $form .= '</form>';
        return $form;
    }
}

class FormContainer {

    public $tag;
    public $attr;
    public $items;

    public function FormContainer($tag = 'div', $classes = array()) {
        $this->tag = $tag;
        $this->items = array();
        $this->attr = array();
        foreach ($classes as $class) {
            $this->attr['class'][] = $class;
        }
    }

    public function add($formItem) {
        $this->items[] = $formItem;
        return $this;
    }

    public function attr($name, $value) {
        $this->attr[$name] = $value;
        return $this;
    }

    public function render() {
        $attr = FormItem::_attr($this->attr);
        $form = '<' . $this->tag . $attr . '>';
        foreach ($this->items as $item) {
            $form .= $item->render();
        }
        $form .= '</' . $this->tag . '>';
        return $form;
    }
}

class FormItem {

    private static $types = array(
        'text' => 'input',
        'email' => 'input',
        'number' => 'input',
        'date' => 'input',
        'datetime' => 'input',
        'datetime-local' => 'input',
        'month' => 'input',
        'time' => 'input',
        'week' => 'input',
        'url' => 'input',
        'search' => 'input',
        'tel' => 'input',
        'color' => 'input',
        'password' => 'input',
        'radio' => 'box',
        'checkbox' => 'box',
        'button' => 'button',
        'submit' => 'button',
        'textarea' => 'textarea',
        'select' => 'select'
    );

    // Minimum.
    public $title;
    public $type;

    //
    public $attr;
    public $have_label;
    public $label_attr;
    public $have_wrapper;
    public $wrapper_attr;
    public $options;

    public function FormItem($title, $type) {
        $this->title = $title;
        $this->type = $type;
        $this->attr = array(
            'class' => array(),
            'required' => false
        );
        $this->have_label = true;
        $this->have_wrapper = true;
        $this->options = ($type == 'select') ? array() : null;
    }

    /** SETTING FUNCTION - CHAINED */

    public function title($title) {
        $this->title = $title;
        return $this;
    }

    public function type($type) {
        $this->type = $type;
        return $this;
    }

    public function attr($name, $value) {
        if ($name == 'class') return $this;
        $this->attr[$name] = $value;
        return $this;
    }

    public function data($name, $value) {
        $this->attr['data-' . $name] = $value;
        return $this;
    }

    public function classes($class_name) {
        $this->attr['class'][] = $class_name;
        return $this;
    }

    public function id($id) {
        $this->attr['id'] = $id;
        return $this;
    }

    public function required() {
        $this->attr['required'] = true;
        return $this;
    }

    public function checked() {
        $this->attr['checked'] = true;
        return $this;
    }

    public function option($value, $label, $attr = array()) {
        if (is_array($this->options)) {
            $this->options[] = array(
                'value' => $value,
                'label' => $label,
                'attr' => $attr
            );
        }
        return $this;
    }

    public function options($options) {
        foreach ($options as $value => $label) {
            $this->options[] = array(
                'value' => $value,
                'label' => $label,
                'attr' => array()
            );
        }
        return $this;
    }

    /** RENDER FUNCTIONS **/

    public function render() {
        return $this->_render_wrapper();
    }

    private function _render_wrapper() {
        $element  = '<div class="form-group">';
        $element .=     $this->_render_control();
        $element .= '</div>';
        return $element;
    }

    private function _render_label($content, $hidden = false) {
        $this->label_attr['for'] = isset($this->attr['id']) ? $this->attr['id'] : false;
        if ($hidden) {
            $this->label_attr['class'][] = 'hidden';
        }
        $label_attr = static::_attr($this->label_attr);
        $required = $this->attr['required'] ? '<span class="required">*</span>' : '';
        return '<label' . $label_attr . '>' . $content . $required . '</label>';
    }

    private function _render_control() {
        $tag = isset(static::$types[$this->type]) ? static::$types[$this->type] : 'input';
        if ($tag == 'input') {
            return $this->_render_control_input();
        } else if ($tag == 'textarea') {
            return $this->_render_control_textarea();
        } else if ($tag == 'box') {
            return $this->_render_control_box();
        } else if ($tag == 'button') {
            return $this->_render_control_button();
        } else if ($tag == 'select') {
            return $this->_render_control_select();
        }
    }

    private function _render_control_input() {
        $label = $this->_render_label($this->title);
        $this->attr['type'] = $this->type;
        $this->attr['class'][] = 'form-control';
        $attr = static::_attr($this->attr);
        return $label . '<input' . $attr . '>';
    }

    private function _render_control_box() {
        $this->attr['type'] = $this->type;
        $this->attr['class'][] = 'form-control';
        $attr = static::_attr($this->attr);
        $box = '<input' . $attr . '>';
        $label = $this->_render_label($box . $this->title);
        return $label;
    }

    private function _render_control_button() {
        $this->attr['type'] = $this->type;
        $this->attr['value'] = $this->title;
        $this->attr['class'][] = 'btn';
        $this->attr['class'][] = 'btn-default';
        $attr = static::_attr($this->attr);
        return '<input' . $attr . '>';
    }

    private function _render_control_textarea() {
        $attr = static::_attr($this->attr);
        $label = $this->_render_label($this->title);
        return $label . '<textarea' . $attr . '></textarea>';
    }

    private function _render_control_select() {
        $this->attr['class'][] = 'form-control';
        $attr = static::_attr($this->attr);
        $label = $this->_render_label($this->title);
        $options = implode('', $this->_render_control_select_options());
        return $label . '<select' . $attr . '>' . $options . '</select>';
    }

    private function _render_control_select_options() {
        $result = array();
        foreach ($this->options as $opt) {
            $opt['attr']['value'] = $opt['value'];
            $attr = static::_attr($opt['attr']);
            $result[] = '<option' . $attr . '>' . $opt['label'] . '</option>';
        }
        return $result;
    }

    /** STATIC **/

    public static function _attr($attributes) {
        $result = '';
        foreach ($attributes as $name => $value) {
            if (is_bool($value)) {
                if ($value) {
                    $result .= " $name";
                }
            } else if (is_array($value)) {
                if (!empty($value)) {
                    $value = implode(' ', $value);
                    $result .= " $name=\"$value\"";
                }
            } else {
                $result .= " $name=\"$value\"";
            }
        }
        return $result;
    }
}
