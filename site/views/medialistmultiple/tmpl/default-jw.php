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
      <th><?php echo JHTML::_( 'grid.sort', 'Title', 'f.title', $this->lists['order_Dir'], $this->lists['order']); ?></th>
      <th>Play</th>
      <th><?php echo JHTML::_( 'grid.sort', 'Speaker', 'f.artist', $this->lists['order_Dir'], $this->lists['order']); ?></th>
      <th>Size</th>
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
      <td class="center audiorowdate"><?php echo date('F j, Y', strtotime($record->performed_date)); ?></td>
      <td class="center audiorowvenue"><?php echo htmlentities($record->venue)?></td>
      <td class="center audiorowdownload"><a href='<?php echo urlencode(substr($record->filename_small, 1)); ?>'><?php echo htmlentities($record->title); ?></a></td>
      <td class="audiorowplayer">
      	<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' width='38' height='30' id='single<?php echo $i; ?>' name='single<?php echo $i; ?>'>
		<param name='movie' value='<?php echo JURI::base() ?>components/com_mediasyndicator/assets/flash/mediaplayer/player.swf'>
		<param name='allowfullscreen' value='true'>
		<param name='allowscriptaccess' value='always'>
		<param name='wmode' value='transparent'>
		<param name='flashvars' value='file=<?php echo substr(JURI::base(), 0, -1) . $record->filename_small; ?>&controlbar=none&screencolor=FFFFFF'>
		<embed
		  id='single<?php echo $i; ?>'
		  name='single<?php echo $i; ?>'
		  src='<?php echo JURI::base() ?>components/com_mediasyndicator/assets/flash/mediaplayer/player.swf'
		  width='38'
		  height='30'
		  bgcolor='#ffffff'
		  allowscriptaccess='always'
		  allowfullscreen='true'
		  flashvars='file=<?php echo substr(JURI::base(), 0, -1) . $record->filename_small; ?>&controlbar=none&screencolor=FFFFFF'
		/>
		</object>
	  </td>
      <td class="center audiorowspeaker"><?php echo $record->artist; ?></td>
      <td class="center audiorowsize"><?php echo $filesize; ?></td>
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