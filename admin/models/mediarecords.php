<?php
/**
 * Media Syndicator model for the the media records view.
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
class MediaSyndicatorModelMediaRecords extends JModel
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
	
	function getWhere($search, $filter_state)
	{
		if ( $search ) {
			$db =& JFactory::getDBO();
			
			$searchCleaned = $db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
			
			$where[] = '(LOWER( f.title ) LIKE '.$searchCleaned
						. ' OR LOWER( f.artist ) LIKE '.$searchCleaned
						. ' OR LOWER( f.album ) LIKE '.$searchCleaned
						. ' OR LOWER( f.venue ) LIKE '.$searchCleaned
						. ' OR LOWER( f.genre ) LIKE '.$searchCleaned
						. ' OR LOWER( f.comment ) LIKE '.$searchCleaned.')';
		}
		if ( $filter_state ) {
			if ( $filter_state == 'P' ) {
				$where[] = 'f.published = 1';
			} else if ($filter_state == 'U' ) {
				$where[] = 'f.published = 0';
			}
		}

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
			. ' LEFT JOIN #__users AS u ON u.id = f.checked_out'
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
