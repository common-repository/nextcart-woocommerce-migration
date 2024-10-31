<?php
function ncwm_display_template($template_name, $args = array()) {
    if (!empty($args) && is_array($args)) {
        extract($args);
    }
    include NCWM_PLUGIN_DIR . '/templates/' . $template_name;
}

function ncwm_get_template($template_name, $args = array()) {
    $template_path = NCWM_PLUGIN_DIR . '/templates/' . $template_name;
    if (file_exists($template_path)) {
        if (!empty($args) && is_array($args)) {
            extract($args);
        }
        ob_start();
        include $template_path;
        $output = ob_get_clean();
        return $output;
    }
}

function ncwm_option_to_html($options, $selected = '', $icon = false) {
    $html = '';
    if ($options) {
        $first = true;
        foreach ($options as $option_value => $option_label) {
            $html .= '<option value="' . esc_attr($option_value) . '"';
            if ($icon) {
                $html .= ' data-content="<i style=\'background-image: url(' . NCWM_PLUGIN_URL . '/assets/images/carts/' . esc_attr($option_value) . '.png);\' class=\'small-logo\'></i>' . esc_html($option_label) . '"';
            }
            if ($option_value == $selected) {
                $html .= ' selected="selected"';
            }
            $html .= '>' . esc_html($option_label) . '</option>';
            $first = false;
        }
    }
    return $html;
}
