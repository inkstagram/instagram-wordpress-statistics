<div class="updated" style="padding: 0; margin: 0; border: none; background: none;">	
	<div class="instagram_stats_activate">
		<div class="instagram_stats_bg">
			<div class="instagram_stats_button" onclick="displayInstagramStatsSetupInstructions();">
				Configure Statistics Widget
			</div>
			<div class="instagram_stats_description">
				Almost finished, all you need to do now is to configure your widget.			
			</div>
		</div>
	</div>
</div>

<div id="instagram_stats_plugin_instructions" style="display: none;">
	<h2>Setup Instructions</h2>
	
	<div class="instagram_stats_setup_instructions">
		<div class="instagram_stats_setup_img">
			<div class="instagram_stats_setup_img_message">
				<div>
					Hover to view image.
				</div>
			</div>
			<img src="http://wordpress.ink361.com/static/images/stats1.png">
		</div>
		<div class="instagram_stats_setup_text">
			<p>
				You can now find your widget listed within the Appearance -> Widgets section of your administration.
			</p>
			
			<p>
				To start configuring your widget simply drag and drop the widget to where you want it to go in your sidebar (see left).
			</p>
		
			<p>
				Once you have done this, wait until the widget has set itself up and then authenticate with Instagram using your username and password.	
			</p>
			
			<p>
				For more information about installing and setting up your widget see our <a href="http://wordpress.ink361.com" target="_blank">plugin website</a>.	
			</p>
			
			<a class="instagram_stats_setup_button" href="widgets.php">
				Manage Widgets
			</a>
		</div>	
	</div>
</div>

<script>
	function displayInstagramStatsSetupInstructions() {
		lightbox({
        content : jQuery('#instagram_stats_plugin_instructions').html(),
        frameCls : '',
        closeCallback: function() {
          
        }      
      });
	}	
</script>