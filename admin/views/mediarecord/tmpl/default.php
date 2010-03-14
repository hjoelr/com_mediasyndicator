<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('behavior.tooltip'); ?>

<?php
	$task = JRequest::getVar('task', 'add');
	JToolBarHelper::title( JText::_( 'Media Record' ) .': <small><small>[' .JText::_( ucwords( $task ) ). ']</small></small>', 'mediasyndicator.png' );
	JToolBarHelper::save();
	JToolBarHelper::apply();
	JToolBarHelper::cancel( 'cancel', 'Close' );
	//JToolBarHelper::help( 'screen.plugins.edit' );
?>

<?php
	// clean item data
	JFilterOutput::objectHTMLSafe( $this->mediarecord, ENT_QUOTES, '' );
?>

<script language="javascript" type="text/javascript">
	/*function submitbutton(pressbutton) {
		if (pressbutton == "cancel") {
			submitform(pressbutton);
			return;
		}
		// validation
		var form = document.adminForm;
		if (form.name.value == "") {
			alert( "<?php echo JText::_( 'Plugin must have a name', true ); ?>" );
		} else if (form.element.value == "") {
			alert( "<?php echo JText::_( 'Plugin must have a filename', true ); ?>" );
		} else {
			submitform(pressbutton);
		}
	}*/
</script>

<script language="javascript" type="text/javascript">

var jsonpath = '/administrator/index.php?option=com_mediasyndicator&task=getdircontents&format=raw';
function populateBrowseDialog(json) {
	if (json.error != '') {
		$('#browse_large').html('<div class="error">' + json.error + '</div><div class=\"browserpane\"><a href=\'javascript:getDirectoryContents(\"\");\'>Try Again</a></div>');
		return;
	}

	html = '<div class="browserpane" style="overflow: auto; height: 100%; width: 100%;"><table>';
	for (var i=0; i<json.totalitems; ++i) {
		item = json.items[i];
		html += '<tr><td><img src=\"' + item.image + '\" alt=\"' + item.name + '\" /></td><td>';
		if (eval(item.is_directory)) {
			html += '<a href=\'javascript:getDirectoryContents(\"' + item.path + '\");\'>' + item.name + '</a>';
		} else {
			html += '<a href=\'javascript:selectFile(\"' + item.path + '\", \"#filename_large\");\'>' + item.name + '</a>';
		}
		html += '</td></tr>';
	}
	html += '</table></div>';

	$('#browse_large').html(html);
}

function selectFile(file, targetElement) {
	$(targetElement).val(file);
	$('#filename_small').val(file);
	$('#browse_large').dialog("close");
}

function getDirectoryContents(directory) {
	$.getJSON(jsonpath, { dir: directory }, populateBrowseDialog);
}

$(function(){

	// Datepicker
	$('#performed_date').datepicker({ showOn: 'button', dateFormat: 'yy-mm-dd', onSelect: function(dateText, inst) { this.value = dateText + ' 08:00:00'; } });

	// Dialog			
	$('#browse_large').dialog({
		autoOpen: false,
		width: 600,
		height: 500,
		buttons: {
			"Ok": function() { 
				$(this).dialog("close"); 
			}, 
			"Cancel": function() { 
				$(this).dialog("close"); 
			} 
		}
	});

	// Dialog Link
	$('#browse_large_link').click(function(){
		getDirectoryContents('');
		$('#browse_large').dialog('open');
		return false;
	});

	//hover states on the static widgets
	$('#browse_large_link, #browse_small_link').hover(
		function() { $(this).addClass('ui-state-hover'); }, 
		function() { $(this).removeClass('ui-state-hover'); }
	);

	
});
</script>

<form action="index.php" method="post" name="adminForm">
<div class="col width-60">
	<fieldset class="adminform">
	<legend><?php echo JText::_( 'DETAILS' ); ?></legend>
	<table class="admintable">
		<tr>
			<td class="key">
				<label for="title">
					<?php echo JText::_( 'TITLE' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="title" id="title" size="100" value="<?php echo $this->mediarecord->title; ?>" />
			</td>
		</tr>
		<tr>
			<td valign="top" class="key">
				<?php echo JText::_( 'PUBLISHED' ); ?>:
			</td>
			<td>
				<?php echo $this->lists['published']; ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="filename_large">
					<?php echo JText::_( 'FILENAME_LARGE' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="filename_large" id="filename_large" size="100" value="<?php echo $this->mediarecord->filename_large; ?>" />
				<span><a class="ui-state-default ui-corner-all" id="browse_large_link" href="#">Browse...</a></span>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="filename_small">
					<?php echo JText::_( 'FILENAME_SMALL' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="filename_small" id="filename_small" size="100" value="<?php echo $this->mediarecord->filename_small; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="media_type">
					<?php echo JText::_( 'MEDIA_TYPE' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['media_types']; ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="artist">
					<?php echo JText::_( 'ARTIST' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="artist" id="artist" size="50" value="<?php echo $this->mediarecord->artist; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="album">
					<?php echo JText::_( 'ALBUM' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="album" id="album" size="50" value="<?php echo $this->mediarecord->album; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="track">
					<?php echo JText::_( 'TRACK' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="track" id="track" size="20" value="<?php echo $this->mediarecord->track; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="genre">
					<?php echo JText::_( 'GENRE' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="genre" id="genre" size="50" value="<?php echo $this->mediarecord->genre; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="performed_date">
					<?php echo JText::_( 'PERFORMED_DATE' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="performed_date" id="performed_date" size="50" value="<?php echo $this->mediarecord->performed_date; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="venue">
					<?php echo JText::_( 'VENUE' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="venue" id="venue" size="50" value="<?php echo $this->mediarecord->venue; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="duration">
					<?php echo JText::_( 'DURATION' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="duration" id="duration" size="50" value="<?php echo $this->mediarecord->duration; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="comment">
					<?php echo JText::_( 'COMMENT' ); ?>:
				</label>
			</td>
			<td>
				<textarea class="text_area" name="comment" id="comment" cols="50" rows="10"><?php echo $this->mediarecord->comment; ?></textarea>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="it_block">
					<?php echo JText::_( 'ITUNES_BLOCK' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['it_block']; ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="it_explicit">
					<?php echo JText::_( 'ITUNES_EXPLICIT' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['it_explicit']; ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="it_category_id">
					<?php echo JText::_( 'ITUNES_CATEGORY' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['it_category_id']; ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="it_keywords">
					<?php echo JText::_( 'ITUNES_KEYWORDS' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="it_keywords" id="it_keywords" size="50" value="<?php echo $this->mediarecord->it_keywords; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="it_subtitles">
					<?php echo JText::_( 'ITUNES_SUBTITLES' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="it_subtitles" id="it_subtitles" size="50" value="<?php echo $this->mediarecord->it_subtitles; ?>" />
			</td>
		</tr>
		</table>
	</fieldset>
</div>

<div id="browse_large" title="Browse">Loading...Please wait.</div>

<!-- <div class="col width-40">
	<fieldset class="adminform">
	<legend><?php echo JText::_( 'Parameters' ); ?></legend>
	<?php
		/*
		jimport('joomla.html.pane');
        // TODO: allowAllClose should default true in J!1.6, so remove the array when it does.
		$pane = &JPane::getInstance('sliders', array('allowAllClose' => true));
		echo $pane->startPane('plugin-pane');
		echo $pane->startPanel(JText :: _('Plugin Parameters'), 'param-page');
		if($output = $this->params->render('params')) :
			echo $output;
		else :
			echo "<div style=\"text-align: center; padding: 5px; \">".JText::_('There are no parameters for this item')."</div>";
		endif;
		echo $pane->endPanel();

		if ($this->params->getNumParams('advanced')) {
			echo $pane->startPanel(JText :: _('Advanced Parameters'), "advanced-page");
			if($output = $this->params->render('params', 'advanced')) :
				echo $output;
			else :
				echo "<div  style=\"text-align: center; padding: 5px; \">".JText::_('There are no advanced parameters for this item')."</div>";
			endif;
			echo $pane->endPanel();
		}

		if ($this->params->getNumParams('legacy')) {
			echo $pane->startPanel(JText :: _('Legacy Parameters'), "legacy-page");
			if($output = $this->params->render('params', 'legacy')) :
				echo $output;
			else :
				echo "<div  style=\"text-align: center; padding: 5px; \">".JText::_('There are no legacy parameters for this item')."</div>";
			endif;
			echo $pane->endPanel();
		}
		echo $pane->endPane();
		*/
	?>
	</fieldset>
</div> -->
<div class="clr"></div>

	<input type="hidden" name="option" value="com_mediasyndicator" />
	<input type="hidden" name="id" value="<?php echo $this->mediarecord->id; ?>" />
	<input type="hidden" name="cid[]" value="<?php echo $this->mediarecord->id; ?>" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>