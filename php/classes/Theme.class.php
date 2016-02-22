<?php

class Theme {

    public static function script($source, $attr = array()) {
        $attr['src'] = $source;
        $attr = static::arrayToAttr($attr);
        return "<script" . $attr . "></script>";
    }

    public static function style($href, $attr = array()) {
        $attr['href'] = $href;
        $attr['rel'] = 'stylesheet';
        $attr['type'] = 'text/css';
        $attr = static::arrayToAttr($attr);
        return "<link" . $attr . ">";
    }

    public static function form_select($list, $attr = array()) {
        $attr = static::arrayToAttr($attr);
        $select = '<select' . $attr . '>';
        foreach ($list as $opt) {
            $opt_attr = isset($opt['attr']) ? $opt['attr'] : array();
            $select .= static::form_select_item($opt['label'], $opt['value'], $opt_attr);
        }
        $select .= '</select>';
        return $select;
    }

    public static function form_select_item($label, $value, $attr = array()) {
        $attr['value'] = $value;
        $attr = static::arrayToAttr($attr);
        return '<option' . $attr . '>' . $label . '</option>';
    }

    public static function box($service, $title, $list, $attr = array()) {
        $attr['class'][] = 'box collapsed';
        $attr['id'] = $service;
        $attr['data-ajax'] = $service;
        $vars = array(
            'attr' => static::arrayToAttr($attr),
            'title' => $title,
            'list' => $list
        );
        $file = new File('templates/blocks/box.tpl.php');
        return $file->template($vars);
    }

    public static function date_widget() {
        $vars = array();
        $vars['year'] = date('Y');
        $vars['month_number'] = intval(date('m'));
        $list = array();
        for ($i = 0; $i < 12; $i += 1) {
            $list[$i]['value'] = $i + 1;
            $list[$i]['label'] = date('M', mktime(0, 0, 0, $i + 1));
        }
        $vars['months'] = Theme::form_select($list, array(
            'id' => 'dateMonth',
            'class' => 'form-control'
        ));
        $file = new File('templates/blocks/date-widget.tpl.php');
        return $file->template($vars);
    }

    /** HELPERS **/

    public static function arrayToAttr($attributes) {
        $attr_string = '';
        foreach ($attributes as $label => $value) {
            if (is_array($value)) {
                $value = implode(' ', $value);
            }
            $attr_string .= " $label=\"$value\"";
        }
        return $attr_string;
    }

}
