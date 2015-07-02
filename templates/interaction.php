<div class="wpstats_container">
	<b class="wpstats_header"><?php echo $details->title ?></b>
	<?php
		require(plugin_dir_path(__FILE__) . 'staticContent.php');
	?>
	<div class="wpstats_counter">
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td class="wpstats_image">
					<img src="<?php echo $this->wpstats_path ?>img/love.png">
				</td>
				<td class="wpstats_text">
					<span class="wpstats_label">Love Rate</span>
					<span class="wpstats_amount">
						<span class="wpstats_odometer" data-value="<?php echo (($stats->totals->likes / $stats->totals->media / $stats->totals->followers) * 100) ?>">						
							0
						</span>
						<span class="wpstats_suffix">%</span>						
					</span>
				</td>
			</tr>
		</table>	
	</div>	
	<div class="wpstats_counter">
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td class="wpstats_image">
					<img src="<?php echo $this->wpstats_path ?>img/talk.png">
				</td>
				<td class="wpstats_text">
					<span class="wpstats_label">Talk Rate</span>
					<span class="wpstats_amount">
						<span class="wpstats_odometer" data-value="<?php echo (($stats->totals->comments / $stats->totals->media / $stats->totals->followers) * 100) ?>">
							0
						</span>
						<span class="wpstats_suffix">%</span>
					</span>
				</td>
			</tr>
		</table>	
	</div>
	
	<div style="clear: both;"></div>
</div>
