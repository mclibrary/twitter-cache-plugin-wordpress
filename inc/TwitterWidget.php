<?php 
 
class TwitterWidget extends WP_Widget
{
  function TwitterWidget()
  {
    $widget_ops = array('classname' => 'TwitterWidget', 'description' => 'Displays cached twitter feed' );
    $this->WP_Widget('TwitterWidget', 'Twitter Feed', $widget_ops);
  }
 
  function form($instance)
  {}
 
  function update($new_instance, $old_instance)
  {}
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;

    echo '<h1 class="twitter-icon">Recent Posts</h1>';
	   echo '<div id="twitter" class="textwidget">';
	     do_action( 'twtcache' );
	   echo '</div>';

    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("TwitterWidget");') );?>
