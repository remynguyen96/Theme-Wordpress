<?php if(!$in_shortcode):?>
<div class="wrap">
	<h2><?php printf(__("Users who submitted the exam '%s'", 'watu'), $exam->name); ?></h2>
	
	<p><?php _e("A lot more detailed reports, filters, exports, and other important features are available in", 'watu')?> <a href="http://calendarscripts.info/watupro" target="_blank">WatuPRO</a></p>
	
	<p><a href="admin.php?page=watu_exams"><?php _e('Back to quizzes list', 'watu')?></a>
	<?php if($count):?>&nbsp;|&nbsp;
	<a href="#" onclick="jQuery('#filterForm').toggle('slow');return false;"><?php _e('Filter/search these records', 'watu')?></a> 
	&nbsp;|&nbsp;
	<a href="admin.php?page=watu_takings&exam_id=<?php echo $exam->ID?>&watu_export=1&noheader=1&<?php echo $filters_url;?>"><?php _e('Export as CSV (semicolon delimited)', 'watu');?><?php if($display_filters):?> <?php _e('(Filters apply)', 'watu')?><?php endif;?></a>
	&nbsp;|&nbsp;
	<a href="#" onclick="WatuDelAll();return false;"><?php _e('Delete all user-submitted data for this exam', 'watu')?></a><?php endif;?></p>	
	
	<p><?php _e('Shortcode to publish a simplified version of this page:', 'watu');?> <input type="text" size="30" value='[watu-takings quiz_id=<?php echo $exam->ID?>]' readonly="readonly" onclick="this.select();"></p>

	<div class="postbox-container" style="width:73%;margin-right:2%;">	
	<div id="filterForm" style="display:<?php echo $display_filters?'block':'none';?>;margin-bottom:10px;padding:5px;" class="widefat">
	<form method="get" class="watu-admin" action="admin.php">
	<input type="hidden" name="page" value="watu_takings">
	<input type="hidden" name="exam_id" value="<?php echo $exam->ID?>">
		<div><label><?php _e('Username', 'watu')?></label> <select name="dnf">
			<option value="equals" <?php if(empty($_GET['dnf']) or $_GET['dnf']=='equals') echo "selected"?>><?php _e('Equals', 'watu')?></option>
			<option value="starts" <?php if(!empty($_GET['dnf']) and $_GET['dnf']=='starts') echo "selected"?>><?php _e('Starts with', 'watu')?></option>
			<option value="ends" <?php if(!empty($_GET['dnf']) and $_GET['dnf']=='ends') echo "selected"?>><?php _e('Ends with', 'watu')?></option>
			<option value="contains" <?php if(!empty($_GET['dnf']) and $_GET['dnf']=='contains') echo "selected"?>><?php _e('Contains', 'watu')?></option>
		</select> <input type="text" name="dn" value="<?php echo @$_GET['dn']?>"></div>
		<div><label><?php _e('Email', 'watu')?></label> <select name="emailf">
			<option value="equals" <?php if(empty($_GET['emailf']) or $_GET['emailf']=='equals') echo "selected"?>><?php _e('Equals', 'watu')?></option>
			<option value="starts" <?php if(!empty($_GET['emailf']) and $_GET['emailf']=='starts') echo "selected"?>><?php _e('Starts with', 'watu')?></option>
			<option value="ends" <?php if(!empty($_GET['emailf']) and $_GET['emailf']=='ends') echo "selected"?>><?php _e('Ends with', 'watu')?></option>
			<option value="contains" <?php if(!empty($_GET['emailf']) and $_GET['emailf']=='contains') echo "selected"?>><?php _e('Contains', 'watu')?></option>
		</select> <input type="text" name="email" value="<?php echo @$_GET['email']?>"></div>
		<div><label><?php _e('IP Address', 'watu')?></label> <select name="ipf">
			<option value="equals" <?php if(empty($_GET['ipf']) or $_GET['ipf']=='equals') echo "selected"?>><?php _e('Equals', 'watu')?></option>
			<option value="starts" <?php if(!empty($_GET['ipf']) and $_GET['ipf']=='starts') echo "selected"?>><?php _e('Starts with', 'watu')?></option>
			<option value="ends" <?php if(!empty($_GET['ipf']) and $_GET['ipf']=='ends') echo "selected"?>><?php _e('Ends with', 'watu')?></option>
			<option value="contains" <?php if(!empty($_GET['ipf']) and $_GET['ipf']=='contains') echo "selected"?>><?php _e('Contains', 'watu')?></option>
		</select> <input type="text" name="ip" value="<?php echo @$_GET['ip']?>"></div>
		<div><label><?php _e('Date Taken', 'watu')?></label> <select name="datef">
			<option value="equals" <?php if(empty($_GET['datef']) or $_GET['datef']=='equals') echo "selected"?>><?php _e('Equals', 'watu')?></option>
			<option value="before" <?php if(!empty($_GET['datef']) and $_GET['datef']=='before') echo "selected"?>><?php _e('Is before', 'watu')?></option>
			<option value="after" <?php if(!empty($_GET['datef']) and $_GET['datef']=='after') echo "selected"?>><?php _e('Is after', 'watu')?></option>			
		</select> <input type="text" name="date" value="<?php echo @$_GET['date']?>"> <i>YYYY-MM-DD</i></div>
		<div><label><?php _e('Points received', 'watu')?></label> <select name="pointsf">
			<option value="equals" <?php if(empty($_GET['pointsf']) or $_GET['pointsf']=='equals') echo "selected"?>><?php _e('Equal', 'watu')?></option>
			<option value="less" <?php if(!empty($_GET['pointsf']) and $_GET['pointsf']=='less') echo "selected"?>><?php _e('Are less than', 'watu')?></option>
			<option value="more" <?php if(!empty($_GET['pointsf']) and $_GET['pointsf']=='more') echo "selected"?>><?php _e('Are more than', 'watu')?></option>			
		</select> <input type="text" name="points" value="<?php echo @$_GET['points']?>"></div>
		
		<div><label><?php _e('Grade/result equals:', 'watu')?></label> <select name="grade_id">
				<option value="0"><?php _e('- Any grade / result -', 'watu')?></option>
				<?php foreach($grades as $grade):?>
					<option value="<?php echo $grade->ID?>" <?php if(!empty($_GET['grade_id']) and $_GET['grade_id'] == $grade->ID) echo 'selected'?>><?php echo stripslashes($grade->gtitle)?></option>
				<?php endforeach;?>	
		</select> <?php _e('(This filter will exclude grades earned prior to Watu version 2.4.7)', 'watu')?></div>
				
		<div><input type="submit" value="<?php _e('Search/Filter', 'watu')?>">
		<input type="button" value="<?php _e('Clear Filters', 'watu')?>" onclick="window.location='admin.php?page=watu_takings&exam_id=<?php echo $exam->ID;?>';"></div>
	</form>
	</div>
		<?php endif; // end if not in shortcode 
		if($count):?>
		
		<table class="<?php echo $in_shortcode ? 'watu-table' : 'widefat' ?> wp-list-table">
			<tr><th><a href="<?php echo $target_url?>&ob=tU.user_login&offset=<?php echo $offset?>&dir=<?php echo $odir?>&<?php echo $filters_url;?>"><?php _e('Name', 'watu');?></a></th>
			<?php if(!$in_shortcode):?><th><a href="<?php echo $target_url?>&ob=tT.ip&offset=<?php echo $offset?>&dir=<?php echo $odir?>&<?php echo $filters_url;?>"><?php _e('IP', 'watu');?></a></th><?php endif;?>			
			<th><a href="<?php echo $target_url?>&ob=tT.date&offset=<?php echo $offset?>&dir=<?php echo $odir?>&<?php echo $filters_url;?>"><?php _e('Date', 'watu')?></a></th>
			<th><a href="<?php echo $target_url?>&ob=tT.points&offset=<?php echo $offset?>&dir=<?php echo $odir?>&<?php echo $filters_url;?>"><?php _e('Points', 'watu')?></a></th><th><?php _e('Result', 'watu')?></th>
			<?php if(!$in_shortcode):?>
			<th><?php _e('Details', 'watu')?></th><th><?php _e('Delete', 'watu')?></th>
			<?php endif; // end if not in shortcode; ?></tr>
			
			<?php foreach($takings as $taking):
				$class = ('alternate' == @$class) ? "" : 'alternate';?>
				<tr class="<?php echo $class?>">
				<td><?php echo $taking->user_id ? '<a href="user-edit.php?user_id='.$taking->user_id.'">'.$taking->user_login.'</a>': _e('N/a', 'watu');?></td>
				<?php if(!$in_shortcode):?><td><?php echo $taking->ip;?></td><?php endif;?>
				<td><?php echo date(get_option('date_format'), strtotime($taking->date));?></td>
				<td><?php echo $taking->points?></td>
				<td><?php echo $in_shortcode ? stripslashes($taking->grade_title) : apply_filters(WATU_CONTENT_FILTER, $taking->result)?></td>
				<?php if(!$in_shortcode):?>				
					<td><?php if(empty($taking->snapshot)): _e('n/a', 'watu');
					else:?><a href="#" onclick="Watu.takingDetails('<?php echo $taking->ID?>');return false;"><?php _e('view', 'watupro')?></a><?php endif;?></td>
					<td><a href="#" onclick="WatuDelTaking(<?php echo $taking->ID?>);return false;"><?php _e('Delete', 'watu')?></a></td>
				<?php endif; // end if not in shortcode ?>	
				</tr>
			<?php endforeach;?>
		</table>
		
		<p align="center"><?php if($offset>0):?><a href="<?php echo $target_url?>&offset=<?php echo ($offset-10)?>&ob=<?php echo $ob?>&dir=<?php echo $dir?>&<?php echo $filters_url;?>"><?php _e('Previous page', 'watu')?></a><?php endif;?>
		
		<?php if($offset + 10 < $count):?> <a href="<?php echo $target_url?>&offset=<?php echo ($offset+10)?>&ob=<?php echo $ob?>&dir=<?php echo $dir?>&<?php echo $filters_url;?>"><?php _e('Next page', 'watu')?></a> <?php endif;?></p>
		
		<?php else:?>
			<p><?php _e('No results match your search criteria.','watu')?></p>
		<?php endif;?>
		
<?php if(!$in_shortcode):?>		
	</div>	
	
	<div id="watu-sidebar">
			<?php include(WATU_PATH."/views/sidebar.php");?>
	</div>

	<form id="cleanupTakingsForm" method="post">
		<input type="hidden" name="delete_all_takings" value="0">
		<?php wp_nonce_field('watu_delete_all');?>
	</form>
	<script type="text/javascript" >
	function WatuDelTaking(id) {
		if(confirm("<?php _e('Are you sure?', 'watu')?>")) {
			window.location = 'admin.php?page=watu_takings&exam_id=<?php echo $exam->ID?>&del_taking=1&id=' + id;
		} 
	}
	
	function WatuDelAll() {
		if(!confirm("<?php _e('Are you sure? This will delete ALL user results for this quiz!', 'watu')?>")) return false;
		
		jQuery('#cleanupTakingsForm input[name=delete_all_takings]').val("1");
		jQuery('#cleanupTakingsForm').submit();
	}
	</script>
</div>		
	<?php endif;?>


