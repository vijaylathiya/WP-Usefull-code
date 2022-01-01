<?php
// https://digwp.com/2010/04/call-widget-with-shortcode/
// Call [widget widget_name="Adverts_Widget_Categories" instance="hide_empty=0"]
// Widget name get from Register widget code by register_widget function 
//for multiple instance parameter  [widget widget_name="Adverts_Widget_Categories" instance="title=Pages&sortby=menu_order&exclude=2,41"]
[widget widget_name="null_instagram_widget" instance="username=erwan&number=6"]

function widget($atts) {
    
    global $wp_widget_factory;
    
    extract(shortcode_atts(array(
        'widget_name' => FALSE,
		'instance' => ''
    ), $atts));
    
    $widget_name = esc_html($widget_name);
    
    if (!is_a($wp_widget_factory->widgets[$widget_name], 'WP_Widget')):
        $wp_class = 'WP_Widget_'.ucwords(strtolower($class));
        
        if (!is_a($wp_widget_factory->widgets[$wp_class], 'WP_Widget')):
            return '<p>'.sprintf(__("%s: Widget class not found. Make sure this widget exists and the class name is correct"),'<strong>'.$class.'</strong>').'</p>';
        else:
            $class = $wp_class;
        endif;
    endif;
    
    ob_start();
    the_widget($widget_name, $instance, array('widget_id'=>'arbitrary-instance-'.$id,
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => ''
    ));
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
    
}
add_shortcode('widget','widget'); 

?>