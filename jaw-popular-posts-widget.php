<?php
/*
Plugin Name: JAW Popular Posts Widget
Plugin URI: http://justaddwater.dk/wordpress-plugins/widgets/
Description: Adds a sidebar widget that shows the most popular posts. Requires the <a href="http://automattic.com/code/widgets/">Sidebar Widgets plugin</a> from Automattic and the <a href="http://www.alexking.org/index.php?content=software/wordpress/content.php">Popularity Contest plugin</a> by Alex King.
Version: 1.0.1 
Author: Thomas Watson Steen
Author URI: http://justaddwater.dk/
*/

class JAWPopularPostsWidget
{

        function widget($args)
        {
                extract($args);
		$options = get_option('JAWPopularPostsWidget');
		$title = empty($options['title']) ? __('Popular Posts Right Now') : $options['title'];
		$items = empty($options['items']) ? 5 : $options['items'];

                print($before_widget . $before_title . $title . $after_title);
		print("<ul>\n");
		if(function_exists("akpc_most_popular"))
			akpc_most_popular($items);
		print("</ul>\n");
                print($after_widget);
        }

	function control()
	{
		$options = $newoptions = get_option('JAWPopularPostsWidget');

		if ($_POST["popular-submit"]) 
		{
			$newoptions['title'] = trim(strip_tags(stripslashes($_POST["popular-title"])));
			$newoptions['items'] = (int) $_POST["popular-items"];
		}
		if ($options != $newoptions)
		{
			$options = $newoptions;
			update_option('JAWPopularPostsWidget', $options);
		}

		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$items = (int) $options['items'];
?>
		<p><label for="popular-title"><?php _e('Title:'); ?> </label><input style="width: 250px;" id="popular-title" name="popular-title" type="text" value="<?=$title?>" /></p>
		<p><label for="popular-items"><?php _e('How many posts would you like to display?'); ?> </label>
		<select id="popular-items" name="popular-items">
<?php 
		for ($i = 1; $i <= 10; ++$i) 
			print('<option value="' . $i . '" ' . ($items == $i ? 'selected="selected"' : '') . '>' . $i . '</option>');
?>
		</select>
		</p>
		<input type="hidden" id="popular-submit" name="popular-submit" value="1" />
<?php
	}

	function load()
	{
		if(function_exists("register_widget_control"))
			register_widget_control(__('Popular Posts'), array('JAWPopularPostsWidget', 'control'), 300, 120);
		if(function_exists("register_sidebar_widget") && function_exists("akpc_most_popular"))
			register_sidebar_widget(__('Popular Posts'), array('JAWPopularPostsWidget', 'widget'));
	}

}

add_action('plugins_loaded', array('JAWPopularPostsWidget', 'load'));

?>
