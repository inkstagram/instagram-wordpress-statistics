<div class="wpstats_progress_wrapper wpstats_geolocation" data-locations="<?php echo $stats->totals->locations->total ?>" data-media="<?php echo $stats->totals->media ?>"
	data-primary="<?php echo htmlspecialchars($details->primary_color) ?>"
	data-secondary="<?php echo htmlspecialchars($details->secondary_color) ?>"
	data-text="<?php echo htmlspecialchars($details->text_color) ?>"
	data-background="<?php echo htmlspecialchars($details->background_color) ?>">
	<b><?php echo $details->title ?></b>
	<?php
		require(plugin_dir_path(__FILE__) . 'staticContent.php');
	?>
</div>