<?php // no direct access
/**
 * SimpleDownload default View template.  This whole view should never be used.
 * 
 * @package    Joomla.Joelrowley.Com
 * @subpackage Components
 * @link http://joomla.joelrowley.com/
 * @license		GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<?php 
$rows =& $this->items;
?>

<form id="adminForm" action="<?php echo JRoute::_( 'index.php' );?>" method="post" name="adminForm">
  <div class="audiolistsearch_container">
	  <div class="audiolistsearch_box">
	  	<!-- <?php echo JText::_( 'FILTER' ); ?>:-->
		<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
		<button onclick="this.form.submit();"><?php echo JText::_( 'GO' ); ?></button>
		<button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'RESET' ); ?></button>
	  </div>
  </div>
  <table width="100%" cellspacing="0" cellpadding="0" border="0" class="audiolist">
  	<thead>
    <tr class="audiotitles">
      <th><?php echo JHTML::_( 'grid.sort', 'Date', 'f.performed_date', $this->lists['order_Dir'], $this->lists['order']); ?></th>
      <th><?php echo JHTML::_( 'grid.sort', 'Venue', 'f.venue', $this->lists['order_Dir'], $this->lists['order']); ?></th>
      <?php if ($this->title > 0) { // display title column ?>
      	<th><?php echo JHTML::_( 'grid.sort', 'Title', 'f.title', $this->lists['order_Dir'], $this->lists['order']); ?></th>
      <?php } ?>
      <?php if ($this->showPlayer == 1) { ?><th>Play</th><?php } ?>
      <th><?php echo JHTML::_( 'grid.sort', 'Speaker', 'f.artist', $this->lists['order_Dir'], $this->lists['order']); ?></th>
      <?php if ($this->showFileSize == 1) { ?> <th>Size</th> <?php } ?>
    </tr>
    </thead>
    
	<tfoot>
		<tr>
			<td colspan="6">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	
	<tbody>
	<?php 
	
	function get_file_size($filename, $size_in = 'MB')
	{
		$size_in_bytes = filesize($filename);
		
		// Precision: decimals at the end for each type of size
		
		if($size_in == 'B')
		{
			$size = $size_in_bytes;
			$precision = 0;
		}
		elseif($size_in == 'KB')
		{
			$size = (($size_in_bytes / 1024));
			$precision = 2;
		}
		elseif($size_in == 'MB')
		{
			$size = (($size_in_bytes / 1024) / 1024);
			$precision = 2;
		}
		elseif($size_in == 'GB')
		{
			$size = (($size_in_bytes / 1024) / 1024) / 1024;
			$precision = 2;
		}
		
		$size = round($size, $precision);
	
		return $size.' '.$size_in;
	}
	
	
	for ($i=0; $i < count($rows); ++$i)
	{
		$record = $rows[$i];
		$filesize = get_file_size(JPATH_SITE.DS.$record->filename_small);
	
	?>
    <tr class="audiorow <?php echo (($i%2 == 0) ? 'audiooddrow' : 'audioevenrow') ?> <?php echo 'audiorow'.$i; ?>">
      <td class="center audiorowdate"><?php echo date($this->dateFormat, strtotime($record->performed_date)); ?></td>
      <td class="center audiorowvenue"><?php echo htmlentities($record->venue)?></td>
      
      <?php if ($this->title > 0) { // display title column ?>
      <td class="center audiorowtitle">
      <?php
      switch ($this->title) { 
      	case 1:
      		echo htmlentities($record->title);
      		break;
      	case 2:
      		echo '<a href=\''.urlencode(substr($record->filename_small, 1)).'\'>'.htmlentities($record->title).'</a>';
      		break;
      	case 3:
      		echo '<a href=\''.JRoute::_( 'index.php?option=com_mediasyndicator&view=download&format=raw&fileid='.$record->id ).'\'>'.htmlentities($record->title).'</a>';
      		break;
      	default:
      }
      ?>
      </td>
      <?php } // title column ?>
      
      <?php if ($this->showPlayer == 1) { ?>
      <td class="audiorowplayer">
      	<object width="25" height="20" data="<?php echo JURI::base() ?>components/com_mediasyndicator/assets/flash/player_mp3_maxi.swf" type="application/x-shockwave-flash">
          <param value="<?php echo JURI::base() ?>components/com_mediasyndicator/assets/flash/player_mp3_maxi.swf" name="movie">
          <param value="mp3=<?php echo urlencode(substr(JURI::base(), 0, -1) . $record->filename_small); ?>&amp;showslider=0&amp;width=25" name="FlashVars">
        </object>
	  </td>
	  <?php } ?>
      <td class="center audiorowspeaker"><?php echo $record->artist; ?></td>
      <?php if ($this->showFileSize == 1) { ?><td class="center audiorowsize"><?php echo $filesize; ?></td> <?php } ?>
    </tr>
    <?php 
	} // end for loop
    ?>
    </tbody>
  </table>
  
  <input type="hidden" name="option" value="com_mediasyndicator" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
  <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>