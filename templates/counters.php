<div class="wpstats_container">
	<b class="wpstats_header"><?php echo $details->title ?></b>
	
	<?php
		require(plugin_dir_path(__FILE__) . 'staticContent.php');
	?>
		
	<div class="wpstats_counter">
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td class="wpstats_image">
					<img src="<?php echo $this->wpstats_path ?>img/media.png">
				</td>
				<td class="wpstats_text">
					<span class="wpstats_label">Media</span>
					<span class="wpstats_amount">
						<span class="wpstats_odometer" data-value="<?php echo $stats->totals->media ?>">
							0						
						</span>
						<span class="wpstats_suffix"></span>						
					</span>
				</td>
			</tr>
		</table>	
	</div>	
	<div class="wpstats_counter">
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td class="wpstats_image">
					<img src="<?php echo $this->wpstats_path ?>img/love.png">
				</td>
				<td class="wpstats_text">
					<span class="wpstats_label">Likes</span>
					<span class="wpstats_amount">
						<span class="wpstats_odometer" data-value="<?php echo $stats->totals->likes ?>">
							0
						</span>
						<span class="wpstats_suffix"></span>						
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
					<span class="wpstats_label">Comments</span>
					<span class="wpstats_amount">
						<span class="wpstats_odometer" data-value="<?php echo $stats->totals->comments ?>">
							0
						</span>
						<span class="wpstats_suffix"></span>						
					</span>
				</td>
			</tr>
		</table>	
	</div>	
	<div class="wpstats_counter">
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td class="wpstats_image">
					<img src="<?php echo $this->wpstats_path ?>img/followers.png">
				</td>
				<td class="wpstats_text">
					<span class="wpstats_label">Followers</span>
					<span class="wpstats_amount">
						<span class="wpstats_odometer" data-value="<?php echo $stats->totals->followers ?>">
							0
						</span>
						<span class="wpstats_suffix"></span>						
					</span>
				</td>
			</tr>
		</table>	
	</div>
	<div class="wpstats_counter">
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td class="wpstats_image">
					<img src="<?php echo $this->wpstats_path ?>img/followings.png">
				</td>
				<td class="wpstats_text">
					<span class="wpstats_label">Following</span>
					<span class="wpstats_amount">
						<span class="wpstats_odometer" data-value="<?php echo $stats->totals->following ?>">
							0
						</span>
						<span class="wpstats_suffix"></span>						
					</span>
				</td>
			</tr>
		</table>	
	</div>
	
	<div style="clear: both;"></div>	
</div>