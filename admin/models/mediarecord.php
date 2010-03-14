<?php
/**
 * Media Syndicator model for the the media record view.
 * 
 * @package    Joomla.Joelrowley.Com
 * @subpackage Components
 * @link http://joomla.joelrowley.com/
 * @license		GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.model' );
jimport( 'joomla.error.error' );

/**
 * Media Syndicator Model
 *
 * @package    Joomla.Joelrowley.Com
 * @subpackage Components
 */
class MediaSyndicatorModelMediaRecord extends JModel
{
	function getMediaTypes()
	{
		$db =& JFactory::getDBO();
		
		$query = 'SELECT t.*'
			. ' FROM #__mediasyndicator_media_types as t'
			. ' ORDER BY t.media_type_name ASC'
			;
		
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if ($db->getErrorNum()) {
			return JError::raiseError( 500, $db->stderr() );
		}
		
		return $rows;
	}
	
	function getItunesCategories()
	{
		$db =& JFactory::getDBO();
		
		$query = 'SELECT c.*'
			. ' FROM #__mediasyndicator_itunes_categories as c'
			. ' WHERE c.published = 1'
			. ' ORDER BY c.id ASC'
			;
		
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		if ($db->getErrorNum()) {
			return JError::raiseError( 500, $db->stderr() );
		}
		
		return $rows;
	}
}