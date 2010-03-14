<?php
/**
 * Media Syndicator default controller
 * 
 * @package    Joomla.Joelrowley.Com
 * @subpackage Components
 * @link http://joomla.joelrowley.com/
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * Media Syndicator Component backend Controller
 *
 * @package    Joomla.Joelrowley.Com
 * @subpackage Components
 */
class MediaSyndicatorController extends JController
{
	/**
	 * Custom Constructor
	 */
	function __construct( $default = array())
	{
		parent::__construct( $default );

		$this->registerTask( 'apply', 		'save');
		$this->registerTask( 'unpublish', 	'publish');
		$this->registerTask( 'edit' , 		'display' );
		$this->registerTask( 'add' , 		'display' );
		$this->registerTask( 'remove',		'delete');
		
		//$this->registerTask( 'upload' , 'upload' );

		$this->registerTask( 'accesspublic' 	, 	'access' );
		$this->registerTask( 'accessregistered'  , 	'access' );
		$this->registerTask( 'accessspecial' 	, 	'access' );
		
		
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_mediasyndicator'.DS.'tables');

	}

	function display( )
	{
		switch($this->getTask())
		{
			case 'add'     :
			case 'edit'    :
			{
				JRequest::setVar( 'hidemainmenu', 1 );
				//JRequest::setVar( 'layout', 'form'  );
				JRequest::setVar( 'view', 'mediarecord' );
			} break;
			case 'getdircontents':
				JRequest::setVar( 'view', 'jsondirectorycontents');
				//$document =& JFactory::getDocument();
				//$document->setType('raw');
				break;
			default:
				JRequest::setVar( 'view', 'mediarecords' );
				break;
		}

		parent::display();
	}
	
	function publish( )
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$db		=& JFactory::getDBO();
		$user	=& JFactory::getUser();
		$cid     = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		JArrayHelper::toInteger($cid, array(0));
		$publish = ( $this->getTask() == 'publish' ? 1 : 0 );
		//$client  = JRequest::getWord( 'filter_client', 'site' );

		if (count( $cid ) < 1) {
			$action = $publish ? JText::_( 'publish' ) : JText::_( 'unpublish' );
			JError::raiseError(500, JText::_( 'Select a plugin to '.$action ) );
		}

		$cids = implode( ',', $cid );

		$query = 'UPDATE #__mediasyndicator_files SET published = '.(int) $publish
			. ' WHERE id IN ( '.$cids.' )'
			. ' AND ( checked_out = 0 OR ( checked_out = '.(int) $user->get('id').' ))'
			;
		$db->setQuery( $query );
		if (!$db->query()) {
			JError::raiseError(500, $db->getErrorMsg() );
		}

		if (count( $cid ) == 1) {
			$row =& JTable::getInstance('mediasyndicatorfiles');
			$row->checkin( $cid[0] );
		}

		$this->setRedirect( 'index.php?option=com_mediasyndicator' );
	}
	
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$db   =& JFactory::getDBO();
		$row  =& JTable::getInstance('mediasyndicatorfiles');
		$task = $this->getTask();

		if (!$row->bind(JRequest::get('post'))) {
			JError::raiseError(500, $row->getError() );
		}
		if (!$row->check()) {
			JError::raiseError(500, $row->getError() );
		}
		if (!$row->store()) {
			JError::raiseError(500, $row->getError() );
		}
		$row->checkin();
		
		switch ( $task )
		{
			case 'apply':
				$msg = JText::_( 'SUCCESSFULLY_SAVED_CHANGES' );
				$this->setRedirect( 'index.php?option=com_mediasyndicator&view=mediarecord&task=edit&cid[]='. $row->id, $msg );
				break;

			case 'save':
			default:
				$msg = JText::sprintf( 'SUCCESSFULLY_SAVED', $row->title );
				$this->setRedirect( 'index.php?option=com_mediasyndicator', $msg );
				break;
		}
	}
	
	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'INVALID_TOKEN' );

		$db   =& JFactory::getDBO();
		$row  =& JTable::getInstance('mediasyndicatorfiles');
		$task = $this->getTask();
		
		$cid     = JRequest::getVar( 'cid', array(), 'post', 'array' );
		
		if (count($cid) == 0) {
			$msg = JText::_( 'NO_ITEM_WAS_SELECTED' );
			$this->setRedirect( 'index.php?option=com_mediasyndicator', $msg );
			return;
		}
		
		foreach ($cid as $id) {
			if ($row->load($id)) {
				if (!$row->delete( $id ) ) {
					JError::raiseError(500, $row->getError() );
				}
			}
		}
		
		
		$msg = JText::_( 'ITEMS_SUCCESSFULLY_DELETED' );
		$this->setRedirect( 'index.php?option=com_mediasyndicator', $msg );
	}
}