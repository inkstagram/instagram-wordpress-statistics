<?php
	if ($instance['db_id']) {
		if ($details && $details->token && $details->token !== '' && $details->error_detected !== 1) {
			?>							
				<p>
					To configure this widget click on the <b>Configure Widget</b> button.
				</p>
				
				<input type="button" value="Configure Widget" onclick="openStatsSetup<?php print $instance['db_id'] ?>();" class="simpleSetupButton button-primary" id="statsSetupButton<?php print $instance['db_id'] ?>">
				
				<?php if ($details->user_id !== '' && $details->user_id !== NULL) { ?>
					<p style="margin-top: 0px;">
						Last time statistics generated: 
						<?php if ($stats === NULL) { ?> 
							<b>Never</b>							
						<?php } else { ?>
							<?php
								$parts = explode(' ', $stats->last_generated);
								
								$time = $parts[1];
								$dParts = explode('-', $parts[0]);
								
								$dateString = '';
								
								if ($dParts[2] === '1' || $dparts[2] == '21' || $dparts[2] === '31') {
									$dateString = $dParts[2] . 'st ';									
								} else if ($dParts[2] === '2' || $dParts[2] === '22') {
									$dateString = $dParts[2] . 'nd ';
								} else if ($dParts[2] === '3' || $dParts[2] === '23') {
									$dateString = $dParts[2] . 'rd ';
								} else {
									$dateString = $dParts[2] . 'th ';
								}
								
								$months = Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec');
								
								$dateString .= $months[intval($dParts[1]) - 1] . ' ';
								
								$dateString .= $dParts[0];
							?>						
							<b><?php echo $dateString ?> at <?php echo $time ?></b>
						<?php } ?>
					</p>
				
					<input type="button" value="Generate statistics" onclick="generateStats<?php print $instance['db_id'] ?>();" class="simpleSetupButton button-primary">
				<?php } else { ?>
					<p>
						You must configure your widget before you can generate your statistics.	
					</p>
				<?php }	?>								
				
				<div id="hiddenStatFields<?php print $instance['db_id'] ?>" style="display: none;">
					<input type="hidden" name="title"		value="<?php print htmlspecialchars($details->title) ?>">
					<input type="hidden" name="username" 	value="<?php print htmlspecialchars($details->username) ?>">
					<input type="hidden" name="user"		value="<?php print htmlspecialchars($details->user_id) ?>">
					<input type="hidden" name="shows"		value="<?php print htmlspecialchars($details->shows) ?>">
					<input type="hidden" name="primary"		value="<?php print htmlspecialchars($details->primary_color) ?>">
					<input type="hidden" name="secondary"	value="<?php print htmlspecialchars($details->secondary_color) ?>">
					<input type="hidden" name="text"		value="<?php print htmlspecialchars($details->text_color) ?>">
					<input type="hidden" name="background" 	value="<?php print htmlspecialchars($details->background_color) ?>">
					<input type="hidden" name="responsive"	value="<?php print htmlspecialchars($details->responsive) ?>">
					<input type="hidden" name="sharing"		value="<?php print htmlspecialchars($details->sharing) ?>">
					<input type="hidden" name="verbose"		value="<?php print htmlspecialchars($details->verbose) ?>">
					<input type="hidden" name="powered"		value="<?php print htmlspecialchars($details->powered) ?>">		
					<input type="hidden" name="statistics"  value="">			
				</div>
				
				<?php
					$customTitle = 'Instagram Statistics Widget';						
					
					if ($details->username !== '' && $details->username) {
						$customTitle = '@' . $details->username . ': ';
						if ($details->shows === 'counters') {
							$customTitle .= 'Standard counters';
						} else if ($details->shows === 'posthistory') {
							$customTitle .= 'Post history';
						} else if ($details->shows === 'interaction') {
							$customTitle .= 'Interaction stats';
						} else if ($details->shows === 'yearlydistribution') {
							$customTitle .= 'Yearly distribution';
						} else if ($details->shows === 'yearlydots') {
							$customTitle .= 'Posts per year';
						} else if ($details->shows === 'geolocation') {
							$customTitle .= 'Geolocation stats';
						} else if ($details->shows === 'tagged') {
							$customTitle .= 'Tag stats';
						} else if ($details->shows === 'likesreceived') {
							$customTitle .= 'Likes distribution';
						} else if ($details->shows === 'commentsreceived') {
							$customTitle .= 'Comments distribution';
						} else if ($details->shows === 'filterusage') {
							$customTitle .= 'Filter usage';
						} else if ($details->shows === 'filterinteraction') {
							$customTItle .= 'Filter interaction';	
						} else {
							$customTitle .= 'Instagram Stats';
						}
					}	
				?>
				
				<div id="statsSetupForm<?php print $instance['db_id'] ?>" style="display: none;">
					<?php require('formHeader.php'); ?>
					<div class="widget-content instagram-stats-widget-admin-form" id="formStats<?php print $instance['db_id'] ?>">
						<ul class="wp-tab-bar">
							<li data-tab="content" class="tabber active" id="contentTab">
								<a href="#" onclick="javascript:switchStatsTab(this);" data-tab="content" class="tabber active">
									Statistics
								</a>
							</li>	
							<li data-tab="display" class="tabber" id="displayTab">
								<a href="#" onclick="javascript:switchStatsTab(this);" data-tab="display" class="tabber">
									Display	
								</a>	
							</li>
							<li data-tab="settings" class="tabber" id="settingsTab">
								<a href="#" onclick="javascript:switchStatsTab(this);" data-tab="settings" class="tabber">
									Settings	
								</a>
							</li>
							<li data-tab="help" class="tabber">
								<a href="#" onclick="javascript:switchStatsTab(this);" data-tab="help" class="tabber">
									Help &amp; Support	
								</a>	
							</li>
						</ul>
						<div class="tabs-panel tabber active" data-tab="content">
							<p>
								<span class="errorMessage">
									<span class="block-arrow"></span>
									You must enter a title for your widget. If you do not want your widget title to be visible then you must customize your local CSS to hide it.
								</span>	
								
								<label>
									Title
									<span class="help-icon dashicons dashicons-info">
										<span class="block">
											<span class="block-arrow"></span>
											This title will appear above your Instagram Statistics widget.
										</span>
									</span>
								</label>
								<input type="text" name="title" id="title" value="<?php print htmlspecialchars($details->title) ?>" class="widefat">
							</p>
							
							<div id="anotherUser">
					            <p>
						              <span class="errorMessage">
							                <span class="block-arrow"></span>
							                You must search for and select a user if you want to show their statistics.
						              </span>
						          
						              <label>
							                Instagram user to show
							                <span class="help-icon dashicons dashicons-info">
								                  <span class="block">
									                    <span class="block-arrow"></span>
									                    Search for an Instagram user to display in your widget. Start typing the username and then select one of the users listed in the drop down box.
									                 </span>
							                </span>
						              </label>
						              <input type="hidden" name="user" id="otherUserId" placeholder="Start typing a username to search" value="<?php print $details->user_id ?>">
						              <input class="widefat" type="text" name="username" id="otherUser" placeholder="Start typing a username to search" value="<?php print htmlspecialchars($details->username) ?>">
									  <div class="wpstats_widget_loader"></div>
						        </p>
					            <div id="otherUserResults"></div>
					    	</div>
							
							<p>
								<label>
									Statistics to show
									<span class="help-icon dashicons dashicons-info">
										<span class="block">
											<span class="block-arrow"></span>
											Select the type of statistic to show in this widget.
										</span>
									</span>
								</label>	
								<select class="widefat" name="shows" id="shows">
									<option value="counters"			<?php if ($details->shows === 'counters') 			{ echo "SELECTED"; } ?>>Standard Counters</option>
									<option value="interaction"			<?php if ($details->shows === 'interaction') 		{ echo "SELECTED"; } ?>>Interaction counters</option> 
									<option value="posthistory"			<?php if ($details->shows === 'posthistory') 		{ echo "SELECTED"; } ?>>Post history</option> 
									<option value="yearlydistribution"	<?php if ($details->shows === 'yearlydistribution') { echo "SELECTED"; } ?>>Yearly distribution</option> 
									<option value="yearlydots"			<?php if ($details->shows === 'yearlydots') 		{ echo "SELECTED"; } ?>>Posts per year</option> 
									<option value="geolocation"			<?php if ($details->shows === 'geolocation') 		{ echo "SELECTED"; } ?>>Geolocation</option> 
									<option value="tagged"				<?php if ($details->shows === 'tagged') 			{ echo "SELECTED"; } ?>>Tags</option> 
									<option value="likesreceived"		<?php if ($details->shows === 'likesreceived') 		{ echo "SELECTED"; } ?>>Likes distribution</option> 
									<option value="commentsreceived"	<?php if ($details->shows === 'commentsreceived')	{ echo "SELECTED"; } ?>>Comments distribution</option> 
									<option value="filterusage"			<?php if ($details->shows === 'filterusage') 		{ echo "SELECTED"; } ?>>Filter usage</option> 
									<option value="filterinteraction"	<?php if ($details->shows === 'filterinteraction') 	{ echo "SELECTED"; } ?>>Filter interaction</option>										
								</select>
							</p>
							
							<input type="button" class="button button-primary widget-control-save right" onclick="saveStatsPlugin<?php print $instance['db_id'] ?>(true);" value="Save">
						</div>
						<div class="tabs-panel tabber" data-tab="display">
							<p>				
								<span class="errorMessage">
									<span class="block-arrow"></span>
									Your color must be entered as a hexadecimal value (eg. #FF9900)
								</span>	
												
								<label>
									Primary Color
									<span class="help-icon dashicons dashicons-info">
										<span class="block">
											<span class="block-arrow"></span>
											Enter the primary color for your widget as a hexadecimal color value (eg. #FF9900)
										</span>
									</span>
								</label>
								<input type="text" name="primary" id="primary" class="widefat" value="<?php print htmlspecialchars($details->primary_color) ?>">
							</p>
							
							<p>			
								<span class="errorMessage">
									<span class="block-arrow"></span>
									Your color must be entered as a hexadecimal value (eg. #FF9900)
								</span>	
													
								<label>									
									Secondary Color
									<span class="help-icon dashicons dashicons-info">
										<span class="block">
											<span class="block-arrow"></span>
											Enter the secondary color for your widget as a hexadecimal color value (eg. #FF9900)
										</span>
									</span>
								</label>
								<input type="text" name="secondary" id="secondary" class="widefat" value="<?php print htmlspecialchars($details->secondary_color) ?>">
							</p>
							
							<p>			
								<span class="errorMessage">
									<span class="block-arrow"></span>
									Your color must be entered as a hexadecimal value (eg. #FF9900)
								</span>	
													
								<label>
									Text Color
									<span class="help-icon dashicons dashicons-info">
										<span class="block">
											<span class="block-arrow"></span>
											Enter the text color for your widget as a hexadecimal color value (eg. #FF9900)
										</span>
									</span>
								</label>
								<input type="text" name="text" id="text" class="widefat" value="<?php print htmlspecialchars($details->text_color) ?>">
							</p>
							
							<p>		
								<span class="errorMessage">
									<span class="block-arrow"></span>
									Your color must be entered as a hexadecimal value (eg. #FF9900)
								</span>
														
								<label>
									Background Color
									<span class="help-icon dashicons dashicons-info">
										<span class="block">
											<span class="block-arrow"></span>
											Enter the background color for your widget as a hexadecimal color value (eg. #FF9900)
										</span>
									</span>
								</label>
								<input type="text" name="background" id="background" class="widefat" value="<?php print htmlspecialchars($details->background_color) ?>">
							</p>
							
							<input type="button" class="button button-primary widget-control-save right" onclick="saveStatsPlugin<?php print $instance['db_id'] ?>(true);" value="Save">
						</div>
						<div class="tabs-panel tabber" data-tab="settings">
							 <p>
            					<label>
              						Enable social sharing icons
              						<span class="help-icon dashicons dashicons-info">
                						<span class="block">
                  							<span class="block-arrow"></span>
                  							Enable or disable the social sharing icons on the widget.                                    
                						</span>
              						</span>
            					</label>
            					<select name="sharing" class="widefat">
              						<option value="1" <?php if ($details->sharing === '1') { echo "SELECTED"; } ?>>Yes</option>
              						<option value="0" <?php if ($details->sharing === '0') { echo "SELECTED"; } ?>>No</option>              
            					</select>
          					</p>          					
          					<p>
            					<label>
              						Responsive
              						<span class="help-icon dashicons dashicons-info">
                						<span class="block">
                  							<span class="block-arrow"></span>
                  							Either enable or disable responsive mode to have the widget automatically resize itself.
                						</span>
              						</span>
            					</label>
            					<select name="responsive" class="widefat">
              						<option value="1" <?php if ($details->responsive === "1") { echo "SELECTED"; } ?>>Yes</option>
              						<option value="0" <?php if ($details->responsive === "0") { echo "SELECTED"; } ?>>No</option>
            					</select>
          					</p>          
          					<p>
            					<label>
              						Show warnings
              						<span class="help-icon dashicons dashicons-info">
                						<span class="block">
                  							<span class="block-arrow"></span>
                  							If you want the system to display warnings when something has gone wrong turn this on, if you want the plugin to be silent then leave this turned off.
                						</span>
              						</span>
            					</label>
            					<select name="verbose" class="widefat">
              						<option value="1" <?php if ($details->verbose === "1") { echo "SELECTED"; } ?>>Yes</option>
              						<option value="0" <?php if ($details->verbose === "0") { echo "SELECTED"; } ?>>No</option>
            					</select>
          					</p>
							  
							<p>
            					<label>
              						Show credits
              						<span class="help-icon dashicons dashicons-info">
                						<span class="block">
                  							<span class="block-arrow"></span>
                  							Show the "Powered by INK361" footer on your widget.
                						</span>
              						</span>
            					</label>
            					<select name="powered" class="widefat">              						
              						<option value="0" <?php if ($details->powered === "0") { echo "SELECTED"; } ?>>No</option>
									<option value="1" <?php if ($details->powered === "1") { echo "SELECTED"; } ?>>Yes</option>
            					</select>
          					</p>
							  
							<input type="button" class="button button-primary widget-control-save right" onclick="saveStatsPlugin<?php print $instance['db_id'] ?>(true);" value="Save">
						</div>
						<div class="tabs-panel tabber" data-tab="help">
							<div class="linkBar">
            					<b>More Help</b>
						            
						        <ul>
						        	<li>
						                <a href="http://wordpress.ink361.com/help/stats/configuring" target="_blank">Configuration help &raquo;</a>
						            </li>  
						            <li>
						                <a href="http://wordpress.ink361.com/help/stats/faq" target="_blank">FAQ &raquo;</a>
						            </li>
						            <li class="break"></li>
						            <li>
						                <a href="http://wordpress.ink361.com" target="_blank">Visit main website &raquo;</a>
						            </li>
						            <li>
						                <a href="http://ink361.com" target="_blank">INK361.com</a>
						            </li>
						          </ul>
						      </div>
						          
						      <div class="contactMessage">
						      	  <p>
						              <b>Got an issue?</b>
						          </p>
						          
						          <p>
						              The INK361 team is here to help!
						          </p>
						              
						          <p>
						              Let us know about your issue by contacting us <a href="mailto:support@ink361.com">via email</a>.
						          </p>
						            
						          <p> 
						              Alternatively, you may find your answer in the links to the right.
						    	  </p>
							</div>     
						</div>
					</div>
					<?php require('formFooter.php'); ?>
				</div>
			<?php				
		} else {
			?>
			<p>
				<?php 
					if ($details->error_detected === 1) {
				?>
					To re-authenticate your widget with Instagram click the <b>Connect To Instagram</b> button. During this process you will be redirected to Instagram to authenticate your widget with the Instagram API. 										
				<?php } else {?>
					To start the Instagram authentication process please click the <b>Connect To Instagram</b> button. During this process you will be redirected to Instagram to authenticate your widget with the Instagram API.
				<?php 
					} 
				?>
			</p>
			
			<input type="button" value="Connect To Instagram" onclick="openStatsTokenConnect<?php print $instance['db_id'] ?>();" class="simpleSetupButton button-primary" id="statsTokenButton<?php print $instance['db_id'] ?>">
			
			<input type="hidden" name="instance_token" id="statsInstanceToken<?php print $instance['db_id'] ?>">
			
			<?php include('message.php'); ?>
			
			<?php
		}
		?>		
		 <script>
			function customiseStatsTitle<?php print $instance['db_id'] ?>(title) {
		      try {
		        var elem  = jQuery('#statsSetupButton<?php print $instance['db_id'] ?>');
		        elem.parent().parent().parent().parent().find('h4').html(title);
		      } catch(e) {
		        
		      }
		    }
		 		 
		    function saveStatsPlugin<?php print $instance['db_id'] ?>(close) {            
		      if (copyStatsFields<?php print $instance['db_id'] ?>()) {
		        jQuery('#statsSetupButton<?php print $instance['db_id'] ?>').parent().parent().find('input[type=submit]').click();
		        if (close) {
		          jQuery('.lboxWrapper').remove();
		        }
		      }
		    }
		
		    function openStatsTokenConnect<?php print $instance['db_id'] ?>() {   
		      location.href='https://api.instagram.com/oauth/authorize/?client_id=fda05624fb064c7ba5d8d8f18e05e4ca&response_type=code&redirect_uri=' + encodeURIComponent('http://wordpress.ink361.com/setup?loc=' + (location.href.split('#')[0].split('?')[0] + '?statswidget=<?php print $instance['db_id'] ?>')) + '&scope=basic';
		    }   
			
			window.statsGenerationg<?php print $instance['db_id'] ?> = false;
			window.shouldGenerate<?php print $instance['db_id'] ?>  = false;
			window.nextUrl<?php print $instance['db_id'] ?> = null;
			window.stats<?php print $instance['db_id'] ?> = {};
			window.processed<?php print $instance['db_id'] ?> = [];
						
			function generateStats<?php print $instance['db_id'] ?>() {
				lightbox({
					content: '<div style="padding: 20px; text-align: center; font-weight: bold;"><div class="wpstats_big_loader"></div>Generating statistics, please do not close this window.</div>',
					frameCls : '',
					closeCallback: stopGenerating<?php print $instance['db_id'] ?>,					
				});								
				
				window.shouldGenerate<?php print $instance['db_id'] ?>= true;
				window.generated<?php print $instance['db_id'] ?>Profile = false;
				window.generated<?php print $instance['db_id'] ?>Media = false;
				window.statsGenerating<?php print $instance['db_id'] ?> = true;
				window.nextUrl<?php print $instance['db_id'] ?> = null;
				window.stats<?php print $instance['db_id'] ?> = {
					totals: {
						likes			: 0,
						comments		: 0,
						locations		: {
							total		: 0,
						},
						filters			: {},
						unusedfilters	: {},
						media			: 0,
						followers		: 0,
						following		: 0
					},
					tags: {
						top				: [],
						tagged			: 0,
						untagged		: 0
					},
					media: {
						topliked		: [],
						topcommented	: [],
					},
					times: {
						days			: {},
						hours			: {}
					},
					monthly: {
						
					},
					alltags: {}
				};
				window.processed<?php print $instance['db_id'] ?> = [];
				window._fetchImages<?php print $instance['db_id'] ?>();
				window._fetchProfile<?php print $instance['db_id'] ?>();
			}
			
			function _fetchProfile<?php print $instance['db_id'] ?>() {				
				var token = '<?php print $details->token ?>';
				var userid = '<?php print $details->user_id ?>';
				
				var url = 'https://api.instagram.com/v1/users/' + userid + '?callback=?&access_token=' + token;
				
				jQuery.getJSON(url, {}, function(response) {					
					if (response && response.data) {
						window.stats<?php print $instance['db_id'] ?>.totals.followers = response.data.counts.followed_by;
						window.stats<?php print $instance['db_id'] ?>.totals.following = response.data.counts.follows;
												
						window.generated<?php print $instance['db_id'] ?>Profile = true;
						
						window._saveStats<?php print $instance['db_id'] ?>();
					}
				});
			}
			
			function _fetchImages<?php print $instance['db_id'] ?>() {
				if (!window.shouldGenerate<?php print $instance['db_id'] ?>) {
					window.statsGenerating<?php print $instance['db_id'] ?> = false;
					window.nextUrl<?php print $instance['db_id'] ?> = null;
					jQuery('.lboxWrapper').remove();
					return;
				}
				
				var token = '<?php print $details->token ?>';
				var userid = '<?php print $details->user_id ?>';
				
				var url = 'https://api.instagram.com/v1/users/' + userid + '/media/recent?callback=?&access_token=' + token;
				
				if (window.nextUrl<?php print $instance['db_id'] ?> != null) {
					url = window.nextUrl<?php print $instance['db_id'] ?> + '&callback=?';
				}
				
				jQuery.getJSON(url, {}, function(response) {					
					if (response) {
						var statt = window.stats<?php print $instance['db_id'] ?>['totals'];
						
						if (response.data && response.data.length > 0) {
							for (var i = 0; i < response.data.length; i++) {
								var photo = response.data[i];
								
								photo.comments.data = [];
								photo.users_in_photo = [];
								photo.likes.data = [];
								photo.caption = {};
																
								if (window.processed<?php print $instance['db_id'] ?>.indexOf(photo.id) > 0) {
									continue;
								}
								
								window.processed<?php print $instance['db_id'] ?>.push(photo.id);
								
								var when = new Date(photo.created_time * 1000);																
								var month = (when.getYear() + 1900) + '-' + (when.getMonth() < 10 ? '0' + when.getMonth() : when.getMonth());
								
								var dayofweek = when.getDay();
								
								if (dayofweek == 0) {
									dayofweek = 7;									
								}
								
								window.stats<?php print $instance['db_id'] ?>.totals.media += 1;																
								
								if (!window.stats<?php print $instance['db_id'] ?>.media.last) {
									window.stats<?php print $instance['db_id'] ?>.media.last = photo;									
								}
																
								if (!window.stats<?php print $instance['db_id'] ?>.media.first) {
									window.stats<?php print $instance['db_id'] ?>.media.first = photo;
								} else {
									if (window.stats<?php print $instance['db_id'] ?>.media.first.created_time > photo.created_time) {
										window.stats<?php print $instance['db_id'] ?>.media.first = photo;
									}
								}															
								
								if (!window.stats<?php print $instance['db_id'] ?>['times']['days']['' + dayofweek]) {
									window.stats<?php print $instance['db_id'] ?>['times']['days']['' + dayofweek] = 0;									
								}
								
								if (!window.stats<?php print $instance['db_id'] ?>['times']['hours']['' + when.getHours()]) {
									window.stats<?php print $instance['db_id'] ?>['times']['hours']['' + when.getHours()] = 0;
								}
								
								window.stats<?php print $instance['db_id'] ?>['times']['days']['' + dayofweek] += 1;
								window.stats<?php print $instance['db_id'] ?>['times']['hours']['' + when.getHours()] += 1;
								
								if (!window.stats<?php print $instance['db_id'] ?>['monthly'][month]) {
									window.stats<?php print $instance['db_id'] ?>['monthly'][month] = {
										likes		: 0,
										comments	: 0,
										photos		: 0,
										filters		: {}										
									};
								}
								
								var statm = window.stats<?php print $instance['db_id'] ?>['monthly'][month];
								
								statm['likes'] += photo.likes.count;
								statm['comments'] += photo.comments.count;
								statm['photos'] += 1;
								
								if (photo.filter && typeof(photo.filter) == 'string') {
									if (!statm['filters'][photo.filter]) {
										statm['filters'][photo.filter] = 0;
									}
									
									statm['filters'][photo.filter] += 1;
									
									if (!statt.filters[photo.filter]) {
										statt.filters[photo.filter] = {
											photos	: 0,
											likes	: 0,
											comments: 0	
										};
									}
									
									statt.filters[photo.filter].photos += 1;
									statt.filters[photo.filter].comments += photo.comments.count;
									statt.filters[photo.filter].likes += photo.likes.count;
								}
								
								statt['likes'] += photo.likes.count;
								statt['comments'] += photo.comments.count;
								
								if (photo.location && photo.location.latitude) {
									statt['locations']['total'] += 1;
								}																									
								
								if (photo.tags && photo.tags.length > 0) {
									window.stats<?php print $instance['db_id'] ?>['tags']['tagged'] += 1;
									
									for (var j = 0; j < photo.tags.length; j++) {
										var tag = photo.tags[j];
										
										if (!window.stats<?php print $instance['db_id'] ?>.alltags[tag]) {
											window.stats<?php print $instance['db_id'] ?>.alltags[tag] = 0;
										}
										
										window.stats<?php print $instance['db_id'] ?>.alltags[tag] += 1;
									}
								} else {
									window.stats<?php print $instance['db_id'] ?>['tags']['untagged'] += 1;
								}			
								
								if (window.stats<?php print $instance['db_id'] ?>.media.topliked.length < 5) {
									window.stats<?php print $instance['db_id'] ?>.media.topliked.push(photo);
								} else {
									window.stats<?php print $instance['db_id'] ?>.media.topliked.sort(function(a, b) {
										return a.likes.count - b.likes.count;
									});
									
									if (window.stats<?php print $instance['db_id'] ?>.media.topliked[0].likes.count < photo.likes.count) {
										window.stats<?php print $instance['db_id'] ?>.media.topliked[0] = photo;
									}
								}
								
								if (window.stats<?php print $instance['db_id'] ?>.media.topcommented.length < 5) {
									window.stats<?php print $instance['db_id'] ?>.media.topcommented.push(photo);
								} else {
									window.stats<?php print $instance['db_id'] ?>.media.topcommented.sort(function(a, b) {
										return a.comments.count - b.comments.count;
									});
									
									if (window.stats<?php print $instance['db_id'] ?>.media.topcommented[0].comments.count < photo.comments.count) {
										window.stats<?php print $instance['db_id'] ?>.media.topcommented[0] = photo;
									}
								}
							}
							
							window.stats<?php print $instance['db_id'] ?>.media.topliked.sort(function(a,b) {
								return b.likes.count - a.likes.count;
							});		
							
							window.stats<?php print $instance['db_id'] ?>.media.topcommented.sort(function(a, b) {
								return b.comments.count - a.comments.count;
							});
							
							var alltags = [];
							
							for (var k in window.stats<?php print $instance['db_id'] ?>.alltags) {
								alltags.push([k, window.stats<?php print $instance['db_id'] ?>.alltags[k]]);
							}
							
							alltags.sort(function(a, b) {
								return b[1] - a[1];
							});
							
							window.stats<?php print $instance['db_id'] ?>.tags.top = [];
							
							for (var i = 0; i < alltags.length && i < 100; i++) {
								window.stats<?php print $instance['db_id'] ?>.tags.top.push(alltags[i]);
							}	
							
							//update yearly stats
							window.stats<?php print $instance['db_id'] ?>.yearly = {};
							window.stats<?php print $instance['db_id'] ?>.years = [];
							
							for (var k in window.stats<?php print $instance['db_id'] ?>.monthly) {
								var y = parseInt(k.split('-')[0]);
								
								if (window.stats<?php print $instance['db_id'] ?>.years.indexOf(y) < 0) {
									window.stats<?php print $instance['db_id'] ?>.years.push(parseInt(y));
								}							
								
								if (!window.stats<?php print $instance['db_id'] ?>.yearly[y]) {
									window.stats<?php print $instance['db_id'] ?>.yearly[y] = { comments : 0, likes : 0, photos : 0 };
								}
								
								window.stats<?php print $instance['db_id'] ?>.yearly[y].comments += window.stats<?php print $instance['db_id'] ?>.monthly[k].comments;
								window.stats<?php print $instance['db_id'] ?>.yearly[y].likes += window.stats<?php print $instance['db_id'] ?>.monthly[k].likes;
								window.stats<?php print $instance['db_id'] ?>.yearly[y].photos += window.stats<?php print $instance['db_id'] ?>.monthly[k].photos;
							}													
							
							window.stats<?php print $instance['db_id'] ?>.years.sort();
							
							//update our filter stats
							var allfilters = ['Normal', 'Valencia', 'Amaro', 'X-Pro II', 'Mayfair', 'Hudson', 'Rise', 'Sierra', 'Lo-fi', 'Nashville', 'Walden', 'Willow', 'Inkwell', 'Earlybird', 'Hefe', 'Brannan', 'Sutro', 'Toaster', '1977', 'Lord Kelvin', 'Poprocket', 'Lomo-fi', 'Apollo', 'No filter', 'LordKelvin', 'Gotham', 'Hipstamatic'];
							window.stats<?php print $instance['db_id'] ?>.totals.unusedfilters = [];
							
							for (var i = 0; i < allfilters.length; i++) {
								var filters = window.stats<?php print $instance['db_id'] ?>.totals.filters;
								var filter = allfilters[i];
								
								if (!filters[filter] || filters[filter].photo == 0) {
									window.stats<?php print $instance['db_id'] ?>.totals.unusedfilters.push(filter);
								}
							}							
						}												
										
						if (response.pagination && response.pagination.next_url) {
							window.nextUrl<?php print $instance['db_id'] ?> = response.pagination.next_url;
							_fetchImages<?php print $instance['db_id'] ?>();
						} else {
							delete(window.stats<?php print $instance['db_id'] ?>['alltags']); 
							
							window.generated<?php print $instance['db_id'] ?>Media = true;
							
							window._saveStats<?php print $instance['db_id'] ?>();
						}			
					}	
				})
			}
			
			function _saveStats<?php print $instance['db_id'] ?>() {				
				if (window.generated<?php print $instance['db_id'] ?>Media && window.generated<?php print $instance['db_id'] ?>Profile) {
					jQuery('#hiddenStatFields<?php print $instance['db_id'] ?> input[name="statistics"]').val(JSON.stringify(window.stats<?php print $instance['db_id'] ?>));										
					jQuery('#statsSetupButton<?php print $instance['db_id'] ?>').parent().parent().find('input[type=submit]').click();
					
					//close our lightbox
					jQuery('.lboxWrapper').remove();
				}
			}
			
			function stopGenerating<?php print $instance['db_id'] ?>() {
				window.shouldGenerate<?php print $instance['db_id'] ?> = false;				
			}
		
		    function openStatsSetup<?php print $instance['db_id'] ?>() {     			  				 
		      lightbox({
		        content : window.setupStats<?php print $instance['db_id'] ?>,
		        frameCls : '',
		        closeCallback: function() {
		          if (confirm('Would you like to save your changes?')) {
		            saveStatsPlugin<?php print $instance['db_id'] ?>();
		          }
		        }      
		      });
		      configureStatsForm<?php print $instance['db_id'] ?>();
		    
		      jQuery('.instagram-stats-widget-admin-form input, .instagram-stats-widget-admin-form select').change(function() {
		        configureStatsForm<?php print $instance['db_id'] ?>();
		      });
		
		      //our dropdown search
		      jQuery('#formStats<?php print $instance['db_id'] ?> #otherUser').keyup(function(event) {			  	
		        clearTimeout(window.searchTimeout);
				window.searchTimeout = setTimeout(function() {
		          searchUserHandler<?php print $instance['db_id'] ?>();
		        }, 200);
		      });
		      jQuery('#formStats<?php print $instance['db_id'] ?> #otherUser').blur(function(event) {
		        setTimeout(function() {
		          jQuery('#formStats<?php print $instance['db_id'] ?> #otherUserResults').removeClass('visible');
		        }, 250);
		      });
		    }
		  
		    function copyStatsFields<?php print $instance['db_id'] ?>(self) {
		      var error = false;
		      var fields = [];
		      var tabs = [];
		      
		      var data = {
		        title		: jQuery('#formStats<?php print $instance['db_id'] ?> input[name=title]').val(),	
				user		: jQuery('#formStats<?php print $instance['db_id'] ?> input[name=user]').val(),	      
				primary		: jQuery('#formStats<?php print $instance['db_id'] ?> input[name=primary]').val(),
				secondary	: jQuery('#formStats<?php print $instance['db_id'] ?> input[name=secondary]').val(),
				text		: jQuery('#formStats<?php print $instance['db_id'] ?> input[name=text]').val(),
				background	: jQuery('#formStats<?php print $instance['db_id'] ?> input[name=background]').val()
		      };        		     
		      
		      //check always entered values
		      if (data.title.replace(' ', '') == '') {
		        error = true;
		        fields.push(jQuery('#formStats<?php print $instance['db_id'] ?> input[name=title]'));
		        tabs.push(jQuery('#formStats<?php print $instance['db_id'] ?> #contentTab'));
		      }		      		    
		     
		      //user needs to be a number
		      if (data.user.replace(' ', '') == '') {
		        error = true;
		        fields.push(jQuery('#formStats<?php print $instance['db_id'] ?> input[name=user]'));
		        tabs.push(jQuery('#formStats<?php print $instance['db_id'] ?> #contentTab'));
		      }
			  
			  //primary needs to be a HEX colour
			  if (!/^#[0-9A-F]{6}$/i.test(data.primary)) {
				  error = true;
				  fields.push(jQuery('#formStats<?php print $instance['db_id'] ?> input[name=primary]'));
				  tabs.push(jQuery('#formStats<?php print $instance['db_id'] ?> #displayTab'))
			  }
			  
			  //secondary needs to be a HEX colour
			  if (!/^#[0-9A-F]{6}$/i.test(data.secondary)) {
				  error = true;
				  fields.push(jQuery('#formStats<?php print $instance['db_id'] ?> input[name=secondary]'));
				  tabs.push(jQuery('#formStats<?php print $instance['db_id'] ?> #displayTab'))
			  }
			  
			  //text needs to be a HEX colour
			  if (!/^#[0-9A-F]{6}$/i.test(data.text)) {
				  error = true;
				  fields.push(jQuery('#formStats<?php print $instance['db_id'] ?> input[name=text]'));
				  tabs.push(jQuery('#formStats<?php print $instance['db_id'] ?> #displayTab'))
			  }
			  
			  //background needs to be a HEX colour
			  if (!/^#[0-9A-F]{6}$/i.test(data.background)) {
				  error = true;
				  fields.push(jQuery('#formStats<?php print $instance['db_id'] ?> input[name=background]'));
				  tabs.push(jQuery('#formStats<?php print $instance['db_id'] ?> #displayTab'))
			  }
		
		      //clear all formattings
		      jQuery('#formStats<?php print $instance['db_id'] ?> p').removeClass('error');
		      jQuery('#formStats<?php print $instance['db_id'] ?> .tabber').removeClass('error');
		      
		      if (error) {
		        for (var i = 0; fields.length > i; i++) {
		          jQuery(fields[i]).parent().addClass('error');
		        }
		        
		        for (var i = 0; tabs.length > i; i++) {
		          jQuery(tabs[i]).addClass('error');
		        }
		      } else {    
		        jQuery('#hiddenStatFields<?php print $instance['db_id'] ?>').html('');
		        
		        var elems = jQuery('#formStats<?php print $instance['db_id'] ?>').find('input');
		      
		        for (var i = 0; elems.length > i; i++) {
		          jQuery('#hiddenStatFields<?php print $instance['db_id'] ?>').append(elems[i]);
		        }
		
		        var elems = jQuery('#formStats<?php print $instance['db_id'] ?>').find('select');
		    
		        for (var i = 0; elems.length > i; i++) {
		          jQuery('#hiddenStatFields<?php print $instance['db_id'] ?>').append(elems[i]);
		        }
		      }
		      
		      return !error;
		    }
		  
		    jQuery(document).ready(function() {      
		      if (!window.switchStatsTab) {
		        window.switchStatsTab = function(obj) {
		          var tab = jQuery(obj).attr('data-tab');
		        
		          jQuery('.tabber').removeClass('active');
		        
		          jQuery('[data-tab="' + tab + '"]').addClass('active');
		        }
		      }
		    
		      if (!window.configureStatsForm<?php print $instance['db_id'] ?>) {
		        window.configureStatsForm<?php print $instance['db_id'] ?> = function() {
		        
		        }
		      }
		  
		      window.setupStats<?php print $instance['db_id'] ?> = jQuery('#statsSetupForm<?php print $instance['db_id'] ?>').html();
		      
			  setTimeout(function() {
				jQuery('#statsSetupForm<?php print $instance['db_id'] ?>').html('');					  		      
			  }, 50);
		  
		      var token = '<?php print $instance['db_id'] ?>';
		      if (location.href.replace('statswidget=' + token, '') != location.href) {
		        setTimeout(function() {
		          var parts = location.search.replace('?', '').replace('#', '').split('&');
		        
		          for (var i = 0; parts.length > i; i++) {
		            var p = parts[i].split('=');
		          
		            if (p[0] == 'token') {
		              var access_token = p[1];
		            
		              jQuery('#statsInstanceToken<?php print $instance['db_id'] ?>').val(access_token);            
		              jQuery('#statsTokenButton<?php print $instance['db_id'] ?>').parent().parent().find('input[type=submit]').click();
		
		              setTimeout(function() {
		                location.href = location.href.split('#')[0].split('?')[0] + '?openStatsWidget=' + this;
		              }.bind('<?php print $this->id ?>'), 100);
		            }
		          }
		        }, 100);
		      }
		    
		      if (location.search != '' && location.search.replace('openStatsWidget', '') != location.search) {      
		        setTimeout(function() {
		          var parts = location.search.replace('?', '').replace('#', '').split('&');
		          for (var i = 0; parts.length > i; i++) {
		            var p = parts[i].split('=');
		            
		            if (p[0] == 'openStatsWidget') {
		              var widgetId = p[1];
		            
		              var elems = jQuery('.widget');
		              for (var j = 0; elems.length > j; j++) {
		                if (jQuery(elems[j]).attr('id').indexOf(widgetId) > 0) {
		                  jQuery(elems[j]).addClass('open');
		                  jQuery(elems[j]).find('.widget-inside').show();
		                }
		              }
		            }
		          }
		        }, 100);
		      }
		    
		      if (!window.searchUserHandler<?php print $instance['db_id'] ?>) {				
		        window.searchUserHandler<?php print $instance['db_id'] ?> = function() {
		          var keywords = jQuery('#formStats<?php print $instance['db_id'] ?> #otherUser').val();
		          				  
		          if (keywords.length > 2) {
					//show the loader
            		jQuery('#formStats<?php print $instance['db_id'] ?> #anotherUser').addClass('wploading');
					  
		            jQuery.ajax({
		              url	: 'https://api.instagram.com/v1/users/search',
		              jsonp 	: "callback",
		              dataType	: "jsonp",
		              data 	: {
		                access_token : '<?php print $details->token ?>', 
		                q : keywords
		              },
		              success	: function(response) {
		                //reset the id
		                jQuery('#formStats<?php print $instance['db_id'] ?> #otherUserId').val('');
		                                   
		                if (response && response.data && response.data.length > 0) {
		                  var html = '';
		                  for (var i = 0; i < response.data.length; i++) {
		                    html += '<div class="result" data-id="' + response.data[i].id + '" data-name="' + response.data[i].username + '">' + response.data[i].username + '</div>';
		                  }
		                  jQuery('#formStats<?php print $instance['db_id'] ?> #otherUserResults').html(html);
		                       
		                  jQuery('#formStats<?php print $instance['db_id'] ?> #otherUserResults').find('.result').click(function() {
		                    jQuery('#formStats<?php print $instance['db_id'] ?> #otherUser').val(jQuery(this).attr('data-name'));
		                    jQuery('#formStats<?php print $instance['db_id'] ?> #otherUserId').val(jQuery(this).attr('data-id'));
		                  }); 
		                  
		                  jQuery('#formStats<?php print $instance['db_id'] ?> #otherUserResults').addClass('visible');
		                } else {
		                  //show no users
		                  noUsersFound<?php print $instance['db_id'] ?>();
		                }       
						
						jQuery('#formStats<?php print $instance['db_id'] ?> #anotherUser').removeClass('wploading');       
		              }
		            });
		          }    
		        }
		      }
		    
		      if (!window.noUsersFound<?php print $instance['db_id'] ?>) {
		        window.noUsersFound = function() {
		          jQuery('#formStats<?php print $instance['db_id'] ?> #otherUserResults').html('<div class="noResults">Nobody found</div>');
		          jQuery('#formStats<?php print $instance['db_id'] ?> #otherUserResults').addClass('visible');                         
		        }
		      }  
			  
			  //customise with our title
      		  customiseStatsTitle<?php print $instance['db_id'] ?>('<?php print $customTitle ?>');           
		    });
  		</script>
  <?php
		
	} else {
		?>
		<p id="wpstats-widget-__i__" class="pDetect">
			Please wait while we create a database entry for this widget, your page may refresh during this process.	
		</p>

		<script>
			jQuery(document).ready(function() {
				var elems = jQuery('.pDetect');
				
				for (var i = 0; i < elems.length; i++) {
					if (jQuery(elems[i]).attr('id').replace('__i__', '') == jQuery(elems[i]).attr('id')) {
						setTimeout(function() {
							location.href = location.href.split('#')[0].split('?')[0] + '?openStatsWidget=' + jQuery(this).attr('id');
						}.bind(elems[i]), 1000);
					}	
				}
			});
		</script>
		<?php
	}
	?>