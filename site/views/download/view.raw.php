<?php
/**
 * View for downloading files for the Media Syndicator Component.
 * 
 * @package    Joomla.Joelrowley.Com
 * @subpackage Components
 * @link http://joomla.joelrowley.com/
 * @license		GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class MediaSyndicatorViewDownload extends JView
{
	function display($tpl = null)
	{
		
		$model		=& $this->getModel();
		
		$fileid = JRequest::getVar('fileid');
		
		if (!$fileid) {
			// this item was not specified.  We need to redirect to an error page.
			// TODO: Redirect to an error page
			return JError::raiseError( 500, 'No item ID was specified.' );
		}
		
		$records = $model->getRecords( $model->getWhere($fileid) );
		
//		$firephp = FirePHP::getInstance(true);
//		$firephp->log($records, 'records');

		if (count($records) == 0) {
			// item could not be found, redirect to an error page
			// TODO: Redirect to an error page
			return JError::raiseError( 500, 'No items were found.' );
		} else if (count($records) > 1) {
			// for some very odd reason this returned more than one result.  This should probably never happen
			// TODO: Redirect to an error page
			return JError::raiseError( 500, 'More than one item was returned from database.' );
		}
		
		// Assume that we have a valid record here and proceed with attempting to download it.
		
		include_once( JPATH_COMPONENT.DS.'helpers'.DS.'download.php' );
		
		$record = $records[0];
		
//		$firephp->log($record, 'record');
		
		$patterns[0] = '/[^[:print:]]+/'; // remove non-printable characters
		$patterns[1] = '/[ \t]+$/';  // remove whitespace at end of string
		$patterns[2] = '/^[ \t]+/';  // remove whitespace at beginning of string
		$patterns[4] = '/^[\\\\|\/]+/'; // remove leading slash if one exists
		$patterns[5] = '/^[\.\.\/|\.\.\\\\]+/'; // remove all ../ and all ..\ if any exist
												// from the beginning of the string.
		
		$cleanedPathOld = "";
		$cleanedPath = "";
		
		do {
			$cleanedPathOld = $cleanedPath;
			$cleanedPath = preg_replace($patterns, array(), $record->filename_large);
		} while (strcasecmp($cleanedPathOld, $cleanedPath)); // be sure all permutations of bad items are removed.
		
		$return = download_file($cleanedPath);
		
		if ($return != 0) { // an error occurred while downloading
			
			switch ($return) {
				case 404: // file not found
					// TODO: Redirect to an error page stating that the file was not found.
					return JError::raiseError( 500, 'File could not be found on the server.' );
					break;
				default:
					// TODO: Redirect to an error page stating that an unknown download error occurred.
					return JError::raiseError( 500, 'An unknown download error occurred.' );
//					$view->assignRef('page_title', 'Component Error');
//					$view->assignRef('msg', 'An unknown error occurred.  Sorry for the inconvenience.');
//					$view->display();
					break;
			}
		}
	}
}