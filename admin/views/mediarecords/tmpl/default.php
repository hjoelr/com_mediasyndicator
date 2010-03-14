<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php JHTML::_('behavior.tooltip'); ?>

<?php
	JToolBarHelper::title( JText::_( 'Media Syndicator' ), 'mediasyndicator.png' );
	JToolBarHelper::addNewX();
	JToolBarHelper::deleteList( JText::_( 'ASK_IF_SURE_TO_DELETE_ITEMS' ) );
	JToolBarHelper::publishList();
	JToolBarHelper::unpublishList();
	JToolBarHelper::editListX();
	JToolBarHelper::preferences( 'com_mediasyndicator' );
	//JToolBarHelper::help( 'screen.plugins' );
	//$ordering = ($this->lists['order'] == 'f.performed_date');
	$rows =& $this->items;

?>

<form action="index.php" method="post" name="adminForm">
<table>
	<tr>
		<td align="left" width="100%">
			<?php echo JText::_( 'FILTER' ); ?>:
			<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
			<button onclick="this.form.submit();"><?php echo JText::_( 'GO' ); ?></button>
			<button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'RESET' ); ?></button>
		</td>
		<td nowrap="nowrap">
			<?php
			//echo $this->lists['type'];
			echo $this->lists['state'];
			?>
		</td>
	</tr>
</table>

<table class="adminlist">
<thead>
	<tr>
		<th width="20">
			<?php echo JText::_( 'NUMBER_ABBREVIATION' ); ?>
		</th>
		<th width="20">
			<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows );?>);" />
		</th>
		<th class="title">
			<?php echo JHTML::_('grid.sort',   'TITLE', 'f.title', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
		</th>
		<th nowrap="nowrap" width="5%">
			<?php echo JHTML::_('grid.sort',   'PUBLISHED', 'f.published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
		</th>
		<th class="artist">
			<?php echo JHTML::_('grid.sort',   'ARTIST', 'f.artist', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
		</th>
		<th class="album">
			<?php echo JHTML::_('grid.sort',   'ALBUM', 'f.album', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
		</th>
		<th class="venue">
			<?php echo JHTML::_('grid.sort',   'VENUE', 'f.venue', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
		</th>
		<th width="20">
			<?php echo JHTML::_('grid.sort',   'TRACK', 'f.track', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
		</th>
		<th class="date">
			<?php echo JHTML::_('grid.sort',   'DATE', 'f.performed_date', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
		</th>
	</tr>
</thead>
<tfoot>
	<tr>
		<td colspan="9">
			<?php echo $this->pagination->getListFooter(); ?>
		</td>
	</tr>
</tfoot>
<tbody>
<?php
	$k = 0;
	for ($i=0, $n=count( $rows ); $i < $n; $i++) {
	$row 	= $rows[$i];

	$link = JRoute::_( 'index.php?option=com_mediasyndicator&view=mediarecord&task=edit&cid[]='. $row->id );

	$access 	= JHTML::_('grid.access',   $row, $i );
	$checked 	= JHTML::_('grid.checkedout',   $row, $i );
	$published 	= JHTML::_('grid.published', $row, $i );

?>
	<tr class="<?php echo "row$k"; ?>">
		<td align="right">
			<?php echo $this->pagination->getRowOffset( $i ); ?>
		</td>
		<td>
			<?php echo $checked; ?>
		</td>
		<td>
			<?php
			if (  JTable::isCheckedOut($this->user->get ('id'), $row->checked_out ) ) {
				echo $row->title;
			} else {
			?>
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'EDIT_MEDIA_RECORD' );?>::<?php echo $row->title; ?>">
				<a href="<?php echo $link; ?>">
					<?php echo $row->title; ?></a></span>
			<?php } ?>
		</td>
		<td align="center">
			<?php echo $published;?>
		</td>
		<td align="center">
			<?php echo $row->artist;?>
		</td>
		<td align="center">
			<?php echo $row->album;?>
		</td>
		<td align="center">
			<?php echo $row->venue;?>
		</td>
		<td align="center">
			<?php echo $row->track;?>
		</td>
		<td align="center">
			<?php echo $row->date;?>
		</td>
	</tr>
	<?php
		$k = 1 - $k;
	}
	?>
</tbody>
</table>

	<input type="hidden" name="option" value="com_mediasyndicator" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_client" value="<?php echo $this->client;?>" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>