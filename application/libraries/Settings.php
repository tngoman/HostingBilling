<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
    
class Settings {

    private $config = array( 
        'default_input_type' => 'form_input',
        'default_input_container_class' => 'form-group',
        'bootstrap_required_input_class' => 'form-control',
        'default_dropdown_class' => 'valid',
        'default_control_label_class' => 'col-sm-5 control-label',
        'default_no_label_class' => 'col-sm-offset-5',
        'default_form_control_class' => 'col-sm-7',
        'default_form_class' => 'form-horizontal col-sm-12',
        'default_button_classes' => 'btn btn-primary',
        'default_date_post_addon' => '',  
        'default_date_format' => 'Y-m-d',
        'default_date_today_if_not_set' => FALSE,
        'default_datepicker_class' => '', 
        'empty_value_html' => '<div class="form-control" style="border:none;"></div>',
        'use_testing_value' => true
    );

    private $func;  
    private $data_source;  
    private $elm_options;  
    private $elm_options_help;
    private $print_string = '';  
     
    private $input_addons = array(
        'exists' => false,  
        'pre' => array(),  
        'pre_html' => '',
        'post' => array(),  
        'post_html' => ''
    );

    function __construct($config = array()) {
        if (!empty($config)) {
            $this->init($config);
        } else {
            $this->func = $this->config['default_input_type'];
        }
    }

    function init($config = array()) {
        if (!empty($config)) {
            foreach ($config as $k => $v) {
                $this->config[$k] = $v;
            }
            $this->func = $this->config['default_input_type'];
        }
    }

    function get_config() {
        return $this->config;
    }

    function open_form($options) { 
        $action = '';
        if (isset($options['action'])) {
            $action = $options['action'];
            unset($options['action']);
        } else {
            show_error('No action set for form. Please include array(\'action\' => \'\') in the open_form(...) function call');
        }

        $class = $this->config['default_form_class'];
        if (isset($options['class'])) {
            $class = $options['class'];
        }
        $options['class'] = $class;
        $options['autocomplete'] = 'on';

        return $this->_build_form_open($action, $options);
    }

    function close_form() {
        return form_close();
    }

  
    function auto_db_to_options($ary, $custom_options = array()) {
        $options = array();

        foreach ($ary as $k => $v) {
            $elm_options = array(
                'id' => $k,
                'value' => $v
            );

          
            if (is_json($v)) {
                $elm_options['type'] = 'json';
            } else { 
                if (strpos(strtolower($k), 'date') !== FALSE) {
                    $k = 'date';
                }
                switch ($k) {
                    case 'id':
                        $elm_options['readonly'] = 'readonly';
                        break;
                    case 'date':
                        $elm_options['type'] = 'date';
                        break;
                    case 'modified':
                    case 'created':
                        $elm_options['type'] = 'date';
                        $elm_options['readonly'] = 'readonly';
                        break;
                    case 'active':
                        $elm_options['type'] = 'dropdown';
                        $elm_options['options'] = array(
                            '1' => 'Active',
                            '0' => 'De-Active'
                        );
                        $elm_options['readonly'] = 'readonly';
                        break;
                    case 'log':
                        $elm_options['type'] = 'json';
                        break;
                }
            }

           
            if (isset($custom_options) && isset($custom_options[$k])) {
                if (is_array($custom_options[$k])) {
                    $elm_options = array_merge($elm_options, $custom_options[$k]);
                }
            }

            if (!(isset($custom_options) && isset($custom_options[$k]) && !is_array($custom_options[$k]) && $custom_options[$k] == 'unset')) {
                $options[] = $elm_options;
            }
        }

        return $options;
    }

    
    function change_pre_built(&$pre_built, $id, $vals_ary) {
        foreach ($pre_built as $k => $v) {
            if ($v['id'] == $id) {
                $pre_built[$k] = array_merge($pre_built[$k], $vals_ary);
                break;
            }
        }
        return;
    }
 


    function build_form_horizontal($options, $data_source = array()) {
        $this->_reset_builder();
        $this->data_source = (array) $data_source;

        foreach ($options as $elm_options) {
            $this->elm_options = $elm_options;

            if (is_array($this->elm_options)) {
                $this->_prep_options();
                switch ($this->func) {
                    case 'form_hidden':
                        $this->print_string .= $this->_build_input();
                        break;
                    case 'form_checkbox':
                    case 'form_radio': 
                        $link_to_input = ((count($this->elm_options['options']) === 1) && array_key_exists('label', $this->elm_options['options'][0]) && ($this->elm_options['options'][0]['label'] === ''));

                        $default_form_control_class = $this->config['default_form_control_class'];
                        if (!array_key_exists('label', $this->elm_options) || ($this->elm_options['label'] === 'none'))
                        {
                            $this->config['default_form_control_class'] .= ' '.$this->config['default_no_label_class'];
                        }

                        $this->print_string .= $this->_pre_elm();
                        $this->print_string .= $this->_label($link_to_input);
                        $this->print_string .= $this->_pre_input();

                        $this->config['default_form_control_class'] = $default_form_control_class;

                        $all_elm_options = $this->elm_options;

                        foreach ($all_elm_options['options'] as $elm_suboptions) {
                            $this->elm_options = $elm_suboptions;
                            $this->elm_options['name'] = $all_elm_options['name'];
                            $this->elm_options['id'] = $all_elm_options['id'];
 
                            array_key_exists('label', $this->elm_options) || $this->elm_options['label'] = $this->elm_options['value'];

                            $label_class = substr($this->func, 5).'-inline';
                            array_key_exists('disabled', $this->elm_options) && $label_class .= ' disabled';

                            $this->print_string .= '<label class="'.$label_class.'">';
                            $this->print_string .= $this->_build_input(FALSE);
                            $this->print_string .= ($this->elm_options['label'] === '') ? '&nbsp;' : $this->elm_options['label'].'</label>'; // Place a nbps to keep the radio button / checkbox aligned with the main label
                        }

                        $this->print_string .= $this->_post_input();
                        $this->print_string .= $this->_post_elm();

                        $this->elm_options = $all_elm_options;
                        break;
                    default:
                        $this->print_string .= $this->_pre_elm();
                        $this->print_string .= $this->_label();
                        $this->print_string .= $this->_build_input();
                        $this->print_string .= $this->_post_elm();
                        break;
                }
            }
        }
        return $this->squish_HTML($this->print_string);
    }

 
    function build_display($options, $data_source = array()) {
        $this->_reset_builder();
        $this->data_source = (array) $data_source;
 
        $this->config['default_control_label_class'] .= ' bold';

        $this->print_string .= $this->_build_form_open('', array('class' => $this->config['default_form_class']));

        foreach ($options as $elm_options) {
            $this->elm_options = $elm_options;

            if (is_array($this->elm_options)) {
                $this->_prep_options();
                if ($this->func != 'form_json') {
                    $this->func = 'form_label';  
                }
                $this->print_string .= $this->_pre_elm();
                $this->print_string .= $this->_label();
                $this->print_string .= $this->_build_input();
                $this->print_string .= $this->_post_elm();
            }
        }
        $this->print_string .= $this->close_form();
        return $this->squish_HTML($this->print_string);
    }

    private function _prep_options() {
        foreach ($this->elm_options as &$opt) { 
            if (is_object($opt)) {
                $opt = (array) $opt;
            }
        }
        $this->func = $this->config['default_input_type']; 
        if (isset($this->elm_options['type']) && !empty($this->elm_options['type'])) {
            $this->func = 'form_' . $this->elm_options['type'];
            unset($this->elm_options['type']);
        } else {
            $this->func = $this->config['default_input_type'];
        }
 
        $class = $this->config['bootstrap_required_input_class'];
        if (isset($this->elm_options['class'])) {
            $class .= ' ' . trim(str_replace($this->config['bootstrap_required_input_class'], '', $this->elm_options['class']));
        }
        $this->elm_options['class'] = $class;
 
        if (!isset($this->elm_options['name'])) { 
            if (isset($this->elm_options['id'])) {
                $this->elm_options['name'] = $this->elm_options['id'];
            } else {
                $this->elm_options['name'] = '';
            }
        }

 
        $default_value = '';
        if (isset($this->elm_options['name']) && isset($this->data_source[$this->elm_options['name']]) && empty($this->elm_options['value'])) {
            $default_value = $this->data_source[$this->elm_options['name']];
        } elseif (isset($this->elm_options['value'])) {
            $default_value = $this->elm_options['value'];
        }

        if (isset($this->elm_options['testing_value']) && $this->config['use_testing_value']) {
            $default_value = $this->elm_options['testing_value'];
        }

        $this->elm_options['value'] = $this->adv_set_value($this->elm_options['name'], $default_value);


      
        $this->input_addons = array(
            'exists' => false,
            'pre' => array(),
            'pre_html' => '',
            'post' => array(),
            'post_html' => ''
        );

 
        if (isset($this->elm_options['input_addon'])) {
            $this->elm_options['input_addons'] = $this->elm_options['input_addon'];
            unset($this->elm_options['input_addon']);
        }

  
        if (isset($this->elm_options['input_addons']) && !empty($this->elm_options['input_addons'])) {
  
            $this->input_addons['exists'] = true;

     
            if (isset($this->elm_options['input_addons']['pre']) && !empty($this->elm_options['input_addons']['pre'])) {
                $pre = $this->elm_options['input_addons']['pre'];
                if (!is_array($pre)) {  
                    $pre = array($pre);
                }
                $this->input_addons['pre'] = $pre;
            }

 
            if (isset($this->elm_options['input_addons']['post']) && !empty($this->elm_options['input_addons']['post'])) {
                $post = $this->elm_options['input_addons']['post'];
                if (!is_array($post)) {  
                    $post = array($post);
                }
                $this->input_addons['post'] = $post;
            }
 
            if (isset($this->elm_options['input_addons']['pre_html']) && !empty($this->elm_options['input_addons']['pre_html'])) {
                $this->input_addons['pre_html'] = $this->elm_options['input_addons']['pre_html'];
            }
            if (isset($this->elm_options['input_addons']['post_html']) && !empty($this->elm_options['input_addons']['post_html'])) {
                $this->input_addons['post_html'] = $this->elm_options['input_addons']['post_html'];
            }

 
            unset($this->elm_options['input_addons']);
        }
    
        $this->elm_options_help = (isset($this->elm_options['help']) && !empty($this->elm_options['help'])) ? $this->elm_options['help'] : '';
        unset($this->elm_options['help']);
        return;
    }

 
    function adv_set_value($field = '', $default = '') {
        if (FALSE === ($OBJ = & _get_validation_object())) {
            if (isset($_POST[$field])) {
                return html_escape($_POST[$field]);
            } elseif (isset($_GET[$field])) {
                return html_escape($_GET[$field]);
            }
            return $default;
        }

        return html_escape($OBJ->set_value($field, $default));
    }

    function squish_HTML($html) {
        $re = '%# Collapse whitespace everywhere but in blacklisted elements.
        (?>             # Match all whitespans other than single space.
            [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
        | \s{2,}        # or two or more consecutive-any-whitespace.
        ) # Note: The remaining regex consumes no text at all...
        (?=             # Ensure we are not in a blacklist tag.
            [^<]*+        # Either zero or more non-"<" {normal*}
            (?:           # Begin {(special normal*)*} construct
                <           # or a < starting a non-blacklist tag.
                (?!/?(?:textarea|pre|script)\b)
                [^<]*+      # more non-"<" {normal*}
            )*+           # Finish "unrolling-the-loop"
            (?:           # Begin alternation group.
                <           # Either a blacklist start tag.
                (?>textarea|pre|script)\b
            | \z          # or end of file.
            )             # End alternation group.
        ) # If we made it here, we are not in a blacklist tag.
        %Six';
        $text = preg_replace($re, " ", $html);
        if ($text === null) {
            return $html;
        }
        return $text;
    }

  

    private function _build_input($include_pre_post = true) {
        $input_html_string = '';
         
        if ($this->func == 'form_combine') {
            if (!isset($this->elm_options['elements'])) {
                dump($this->elm_options);
                show_error('Tried to create `form_combine` with no elements. (id="' . $this->elm_options['name'] . '")');
            }

            $elm_options_backup = $this->elm_options;  

            $counter = 0;
            foreach ($elm_options_backup['elements'] as $elm) {
                $this->elm_options = $elm; 
                $this->_prep_options();  
                if ($counter > 0 && !empty($elm_options_backup['combine_divider'])) {
                    $input_html_string .= $elm_options_backup['combine_divider'];
                }
                $input_html_string .= $this->_build_input(false);
                $counter++;
            }

            $this->elm_options = $elm_options_backup;  
            $this->_prep_options();  
        } else {
         
            switch ($this->func) {
      
                case 'form_json':
                    $input_html_string = $this->_recursive_build_json((array) json_decode($this->elm_options['value']));
                    break;
                case 'form_button':
                case 'form_anchor':
                case 'form_a':
                    $class = str_replace($this->config['default_button_classes'], '', $this->elm_options['class']);
                    $class = str_replace($this->config['bootstrap_required_input_class'], '', $class);  
                    if (strpos($class, $this->config['default_button_classes']) === FALSE) {
                        $class .= ' ' . $this->config['default_button_classes'];
                    }
                    $this->elm_options['class'] = trim($class);

                    $value = $this->elm_options['label'];
                    unset($this->elm_options['label']);

                    $input_html_string = anchor('', $value, $this->elm_options);
                    break;
                case 'form_label':
                    $input_html_string = form_label($this->_make_label($this->elm_options['value']), '', array(
                        'class' => 'control-label text-left'
                    ));
                    break;
                case 'form_date':
                    $this->elm_options['type'] = 'date';  
                    if ($this->config['default_date_post_addon'] != '') {
                        $this->input_addons['exists'] = TRUE;
                        $this->input_addons['post_html'] = $this->config['default_date_post_addon'];
                    }

                    try {
                        if (empty($this->elm_options['value'])) {
                            if ($this->config['default_date_today_if_not_set']) {
                                $dt = new DateTime('today');
                                $this->elm_options['value'] = $dt->format($this->config['default_date_format']);
                            }
                        } else {
                            $dt = new DateTime($this->elm_options['value']);
                            $this->elm_options['value'] = $dt->format($this->config['default_date_format']);
                        }
                    } catch (Exception $e) {
                        log_message('error', $e->getMessage().' at "'.$e->getFile().'" on line '.$e->getLine());

                        if ($this->config['default_date_today_if_not_set']) {
                            $dt = new DateTime('today');
                            $this->elm_options['value'] = $dt->format($this->config['default_date_format']);
                        }
                    }

                    $input_html_string = form_input($this->elm_options);
                    break;
                case 'form_email':
                    $this->elm_options['type'] = 'email';
                    $input_html_string = form_input($this->elm_options);
                    break;
                case 'form_tel':
                    $this->elm_options['type'] = 'tel';
                    $input_html_string = form_input($this->elm_options);
                    break;
                case 'form_number':
                    $this->elm_options['type'] = 'number';
                    $input_html_string = form_input($this->elm_options);
                    break;
                case 'form_input':
                    $input_html_string = form_input($this->elm_options);
                    break;
                case 'form_hidden':
                    return form_hidden($this->elm_options['id'], $this->elm_options['value']);
                case 'form_submit':
                    $name = $this->elm_options['id'];
                    $label = $this->_make_label((isset($this->elm_options['label']) ? $this->elm_options['label'] : $this->elm_options['id']));

                    unset($this->elm_options['id']);
                    unset($this->elm_options['label']);

                    $class = str_replace($this->config['default_button_classes'], '', $this->elm_options['class']);
                    $class = str_replace($this->config['bootstrap_required_input_class'], '', $class);  
                    if (strpos($class, $this->config['default_button_classes']) === FALSE) {
                        $class .= ' ' . $this->config['default_button_classes'];
                    }
                    $this->elm_options['class'] = trim($class);

                    $input_html_string = form_submit($name, $label, $this->_create_extra_string($this->elm_options));
                    break;
                case 'form_option':
                case 'form_dropdown': 
                    if (isset($this->elm_options['options']) && !empty($this->elm_options['options'])) {
                        $name = $this->elm_options['name'];
                        $options = $this->elm_options['options'];
                        $value = $this->elm_options['value'];

                        unset($this->elm_options['name']);
                        unset($this->elm_options['value']);
                        unset($this->elm_options['options']);

                        if (!empty($this->config['default_dropdown_class'])) {
                            $class = str_replace($this->config['bootstrap_required_input_class'], '', $this->elm_options['class']); 
                            if (strpos($class, $this->config['default_dropdown_class']) === FALSE) {
                                $class .= ' ' . $this->config['default_dropdown_class'];
                            }

                            if (strpos($class, $this->config['bootstrap_required_input_class']) === FALSE) {
                                $class .= ' ' . $this->config['bootstrap_required_input_class'];
                            }
                            $this->elm_options['class'] = trim($class);
                        }

                        $input_html_string = form_dropdown($name, $options, $value, $this->_create_extra_string());
                    } else {
                        dump($this->elm_options);
                        show_error('Tried to create `form_dropdown` with no options. (id="' . $this->elm_options['name'] . '")');
                    }
                    break;
                case 'form_html':
                    if (!isset($this->elm_options['html'])) {
                        dump($this->elm_options);
                        show_error('Tried to create `form_html` with no html. (id="' . $this->elm_options['id'] . '")');
                    }
                    $input_html_string = $this->elm_options['html'];
                    break;
                case 'form_textarea':
                    $this->elm_options['value'] = html_entity_decode($this->elm_options['value']);
                    $input_html_string = form_textarea($this->elm_options);
                    break;
                case 'form_file':
                    $input_html_string = form_upload($this->elm_options);
                    break;
                case 'form_checkbox':
                    $input_html_string = form_checkbox($this->elm_options);
                    break;
                case 'form_radio':
                    $input_html_string = form_radio($this->elm_options);
                    break;
                default:
                    if (function_exists($this->func)) {
                        $input_html_string = call_user_func($this->func, $this->elm_options);
                    } else {
                        show_error("Could not find function to build form element: '{$this->func}'");
                    }
                    break;
            }
        }
        $ret_string = '';
        $ret_string .= ($include_pre_post) ? $this->_pre_input() : '';
        $ret_string .= $this->_build_input_addons_pre();
        $ret_string .= (empty($input_html_string)) ? $this->config['empty_value_html'] : $input_html_string;
        $ret_string .= $this->_build_input_addons_post();
        $ret_string .= ($include_pre_post) ? $this->_build_help_block() : '';
        $ret_string .= ($include_pre_post) ? $this->_post_input() : '';

        return $ret_string;
    }

    private function _build_input_addons_pre() {
        $ret_string = '';
        if ($this->input_addons['exists']) {
            if (!empty($this->input_addons['pre_html'])) {
                $ret_string = $this->input_addons['pre_html'];
            } else {
                $ret_string .= '<div class="input-group">';
                foreach ($this->input_addons['pre'] as $pre_addon) {
                    $ret_string .= '<span class="input-group-addon">' . $pre_addon . '</span>';
                }
            }
        }
        return $ret_string;
    }

    private function _build_input_addons_post() {
        $ret_string = '';
        if ($this->input_addons['exists']) {
            if (!empty($this->input_addons['post_html'])) {
                $ret_string = $this->input_addons['post_html'];
            } else {
                foreach ($this->input_addons['post'] as $post_addon) {
                    $ret_string .= '<span class="input-group-addon">' . $post_addon . '</span>';
                }
            }
            $ret_string .= '</div>';
        }
        return $ret_string;
    }

    private function _create_extra_string() {
        $extra = '';
        foreach ($this->elm_options as $k => $v) {
            $extra .= " {$k}=\"{$v}\"";
        }
        return trim($extra);
    }

    private function _build_form_open($action, $attributes) {
        return form_open_multipart($action, $attributes);
    }

    private function _pre_elm() {
        return '<div class="' . $this->config['default_input_container_class'] . '">';
    }

    private function _post_elm() {
        return '</div>';
    }

    private function _pre_input() {
        if (($this->func === 'form_date') && ($this->config['default_datepicker_class'] !== '')) {
            return '<div class="date '.$this->config['default_datepicker_class'].' ' . $this->config['default_form_control_class'] . '" data-date="' . $this->elm_options['value'] . '" data-date-format="'.preg_replace(array('/Y/', '/m/', '/d/'), array('yyyy', 'mm', 'dd'), $this->config['default_date_format']).'" data-date-viewmode="years">';
        }
        return '<div class="' . $this->config['default_form_control_class'] . '">';
    }

    private function _build_help_block() {
        if (!empty($this->elm_options_help)) {
            return '<span class="help-block">' . $this->elm_options_help . '</span>';
        }
        return '';
    }

    private function _post_input() {
        return '</div>';
    }

 
    private function _label($link_to_input_id = TRUE) {
        $label = '';
        if (isset($this->elm_options['label']) && $this->elm_options['label'] == 'none') {
            return '';  
        } else if (isset($this->elm_options['label'])) {
            $label = $this->elm_options['label'];
        } elseif (isset($this->elm_options['id']) && $this->func != 'form_submit') {
            $label = $this->_make_label($this->elm_options['id']);
        }

        if ($this->func == 'form_submit') {
            $label = '';
        }

        return form_label($label, $link_to_input_id ? $this->elm_options['name'] : '', array(
            'class' => $this->config['default_control_label_class']
        ));
    }

    private function _make_label($str) {
        return ucwords(str_replace(array('_', '-', '[', ']'), array(' ', ' ', ' ', ' '), $str));
    }

    private function _reset_builder() {
        $this->print_string = '';
        $this->func = $this->config['default_input_type'];
    }

    private function _recursive_build_json($ary, $offset = 0) {
        $kv_str = '';
        foreach ($ary as $k => $v) { 
            $offset_class = '';
            if ($offset >= 1) {
                $offset_class = 'col-sm-offset-' . $offset;
            }

            if ((is_array($v) || is_object($v)) && !is_string($v)) {
                $new_offset = $offset + 1;
                $innter_str = $this->_recursive_build_json((array) $v, $new_offset);
                $kv_str .= '<div class="' . $offset_class . '"><strong>' . ucwords(strtolower(str_replace(array('_', '-'), ' ', $k))) . '</strong>' . $innter_str . '</div>';
            } else {
                $kv_str .= '<div class="' . $offset_class . '"><strong>' . ucwords(strtolower(str_replace(array('_', '-'), ' ', $k))) . '</strong>: ' . $v . '</div>';
            }
        }
        return $kv_str;
    }

   
}
