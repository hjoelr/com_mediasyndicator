<?php
/**
 * MediaListMultiple model for the Media Syndicator Component.
 * 
 * @package    Joomla.Joelrowley.Com
 * @subpackage Components
 * @link http://joomla.joelrowley.com/
 * @license		GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.model' );

/**
 * MediaListMultiple Model
 *
 * @package    Joomla.Joelrowley.Com
 * @subpackage Components
 */
class MediaSyndicatorModelDownload extends JModel
{
	
	function getWhere($fileid)
	{
		$db =& JFactory::getDBO();
			
		$fileidCleaned = $db->Quote( $db->getEscaped( $fileid, true ), false );
		
		$where[] = 'f.id = ' . $fileidCleaned;
		
		$where[] = 'f.published = 1'; // make sure to only allow to download published items

		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
		
		return $where;
	}
	
	function getRecords($where)
	{
		$db =& JFactory::getDBO();
		
		$query = 'SELECT f.*'
			. ' FROM #__mediasyndicator_files as f'
			. ' INNER JOIN #__mediasyndicator_media_types as t ON t.id = f.media_type_id'
			. $where
			. ' GROUP BY f.id'
			;
		
		$db->setQuery( $query );
		//$record = $db->loadRow();
		$rows = $db->loadObjectList();
		if ($db->getErrorNum()) {
			return JError::raiseError( 500, $db->stderr() );
		}
		
		//return $record;
		return $rows;
	}
}
