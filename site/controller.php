<?php
/**
 * MediaSyndicator default controller
 * 
 * @package    Joomla.Joelrowley.Com
 * @subpackage Components
 * @link http://joomla.joelrowley.com/
 * @license		GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

/**
 * Media Syndicator Component Controller
 *
 * @package		MediaSyndicator
 */
class MediaSyndicatorController extends JController
{
	
	/**
	 * Custom Constructor
	 */
	function __construct( $default = array())
	{
		parent::__construct( $default );

	}
	
	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	function display()
	{
		parent::display();
	}
	
//	/**
//	 * Method to initiate the download of a file.
//	 * 
//	 * @access public
//	 */
//	function download()
//	{
//		include_once( JPATH_COMPONENT.DS.'helpers'.DS.'download.php' );
//		
//		//$params	=& JComponentHelper::getParams( 'com_mediasyndicator' );
//		
//		$view	=& $this->getView('error', 'html');
//		
//		// get file ID from request
//		$fileId		= JRequest::getVar('fileid');
//		
//		
//		
//		// get file path from database based on file ID
//		
//		$return = download_file($cleanedPath);
//		
//		if ($return != 0) { // an error occurred while downloading
//			
//			switch ($return) {
//				case 404: // file not found
//					$view->assignRef('page_title', $params->get('title_filenotfound'));
//					$view->assignRef('msg', $params->get('msg_filenotfound'));
//					$view->display();
//					break;
//				default:
//					$view->assignRef('page_title', 'Component Error');
//					$view->assignRef('msg', 'An unknown error occurred.  Sorry for the inconvenience.');
//					$view->display();
//					break;
//			}
//		}
//	}

}
?>
