<div class="updated" style="padding: 0; margin: 0; border: none; background: none;">	
	<div class="instagram_stats_activate instagram_stats_review">
		<div class="instagram_stats_bg">
			<div class="instagram_stats_button instagram_stats_review_button" onclick="displayInstagramStatsReviewInstructions();">
				Review Statistics Widget
			</div>
			<div class="instagram_stats_description">
				You've been using our widget for a while now, would you review it please?						
			</div>
			<span class="instagram_stats_hide_review">
				<a href="javascript:hideDisplayInstagramStatsReview();">Never show this message again</a>						
			</span>	
		</div>
	</div>
</div>

<script>
	function hideDisplayInstagramStatsReview() {
		var href = location.href;
		if (href.indexOf('?') == -1) {
			href += '?';
		}
		
		href += '&instagram_stats_disable_message=1';
		
		location.href = href;
	}
	
	function displayInstagramStatsReviewInstructions() {
		window.open('https://wordpress.org/support/view/plugin-reviews/instagram-statistics');
		
		hideDisplayInstagramStatsReview();
	}	
</script>