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
class MediaSyndicatorModelMediaListMultiple extends JModel
{
/**
	 * 
	 * @param $where is a SQL string to add as the WHERE in this query.  This
	 * is generally taken from the getWhere() function.
	 * @return a number indicating the total number of file records.
	 */
	function getTotalRecordNumber($where)
	{
		$db =& JFactory::getDBO();
		
		$query = 'SELECT COUNT(*)'
			. ' FROM #__mediasyndicator_files AS f'
			. $where
			;
		$db->setQuery( $query );
		$total = $db->loadResult();
		
		if ($db->getErrorNum()) {
			return JError::raiseError( 500, $db->stderr() );
		}
		
		return $total;
	}
	
	function getWhere($search)
	{
		if ( $search ) {
			$db =& JFactory::getDBO();
			
			$searchCleaned = $db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
			
			$where[] = '(LOWER( f.title ) LIKE '.$searchCleaned
						. ' OR LOWER( f.artist ) LIKE '.$searchCleaned
						. ' OR LOWER( f.album ) LIKE '.$searchCleaned
						. ' OR LOWER( f.venue ) LIKE '.$searchCleaned
						. ' OR LOWER( f.genre ) LIKE '.$searchCleaned
						. ' OR LOWER( f.it_keywords ) LIKE '.$searchCleaned
						. ' OR LOWER( f.comment ) LIKE '.$searchCleaned.')';
		}
		
		$where[] = 'f.published = 1'; // make sure to only display published items

		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
		
		return $where;
	}
	
	function getOrderBy($filter_order, $filter_order_Dir)
	{
		$orderby = ' ORDER BY '. $filter_order . ' '. $filter_order_Dir;
		
		return $orderby;
	}
	
	function getAvailableRecords($where, $orderby, $pagination)
	{
		$db =& JFactory::getDBO();
		
		$query = 'SELECT f.*'
			. ' FROM #__mediasyndicator_files as f'
			. ' INNER JOIN #__mediasyndicator_media_types as t ON t.id = f.media_type_id'
			. $where
			. ' GROUP BY f.id'
			. $orderby
			;
		
		$db->setQuery( $query, $pagination->limitstart, $pagination->limit );
		$rows = $db->loadObjectList();
		if ($db->getErrorNum()) {
			return JError::raiseError( 500, $db->stderr() );
		}
		
		return $rows;
	}
}
