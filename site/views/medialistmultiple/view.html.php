<?php
/**
 * Media Syndicator default View for the Media Syndicator Component.
 * 
 * @package    Joomla.Joelrowley.Com
 * @subpackage Components
 * @link http://joomla.joelrowley.com/
 * @license		GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

/**
 * Default HTML View class for the Media Syndicator Component.
 *
 * @package    Joomla.Joelrowley.Com
 * @subpackage Components
 */
class MediaSyndicatorViewMediaListMultiple extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option;
		
		$db			=& JFactory::getDBO();
		$document	=& JFactory::getDocument();
		$model		=& $this->getModel();
		$params 	=& JComponentHelper::getParams( 'com_mediasyndicator' );
		
//		$firephp = FirePHP::getInstance(true);
// 
//		$firephp->log($params, 'params');
		
		
		
		//add javascript to document
		$document->addScriptDeclaration( $this->getColumnSortJavascript() );
		$document->addStyleDeclaration( $this->getTableStyle() );
		//$document->addScript('components'.DS.'com_mediasyndicator'.DS.'assets'.DS.'flash'.DS.'mediaplayer'.DS.'swfobject.js');
		
		$client = JRequest::getWord( 'filter_client', 'site' );

		$filter_order		= $mainframe->getUserStateFromRequest( "$option.$client.filter_order",		'filter_order',		'f.performed_date',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( "$option.$client.filter_order_Dir",	'filter_order_Dir',	'desc',					'word' );
		//$filter_type		= $mainframe->getUserStateFromRequest( "$option.$client.filter_type", 		'filter_type',		1,					'cmd' );
		$search				= $mainframe->getUserStateFromRequest( "$option.$client.search",			'search',			'',					'string' );
		$search				= JString::strtolower( $search );

		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart	= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );

		// get total number of records from DB
		$total = $model->getTotalRecordNumber($model->getWhere($search));

		jimport('joomla.html.pagination');
		$pagination = new JPagination( $total, $limitstart, $limit );
		
		// get the records from the DB
		$rows = $model->getAvailableRecords(
							$model->getWhere($search),
							$model->getOrderBy($filter_order, $filter_order_Dir),
							$pagination );


		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;

		// search filter
		$lists['search']= $search;

		$this->assign('client',			$client);
		
		$this->assign('dateFormat',		$params->get('dateFormat'));
		$this->assign('title',			$params->get('title'));
		$this->assign('showPlayer',		$params->get('showPlayer'));
		$this->assign('showFileSize',	$params->get('showFileSize'));
		
		$this->assignRef('lists',		$lists);
		$this->assignRef('items',		$rows);
		$this->assignRef('pagination',	$pagination);
		
		parent::display($tpl);
	}
	
	function getColumnSortJavascript()
	{
		$javascript =  "function tableOrdering( order, dir, task )";
		$javascript .=  "{";
		$javascript .=  "var form = document.adminForm;";
		$javascript .=  "";
		$javascript .=  "form.filter_order.value = order;";
		$javascript .=  "form.filter_order_Dir.value = dir;";
		$javascript .=  "document.adminForm.submit( task );";
		$javascript .=  "}";
		$javascript .=  "";

		return $javascript;
	}
	
	function getTableStyle()
	{
		$style =  "table.audiolist td.center";
		$style .=  "{";
		$style .=  "text-align:center;";
		$style .=  "}";
		$style .=  "";
		$style .=  ".audiolist .audioevenrow";
		$style .=  "{";
		$style .=  "background-color:#D6E3EB;";
		$style .=  "border-bottom:1px solid #C0C0C0;";
		$style .=  "text-align:left;";
		$style .=  "}";
		$style .=  "";
		$style .=  ".audiolist tr.audiotitles";
		$style .=  "{";
		$style .=  "vertical-align:middle;";
		$style .=  "background-color:#CCCCCC;";
		$style .=  "font-weight:bold;";
		$style .=  "margin-bottom:15px;";
		$style .=  "}";
		$style .=  "";
		$style .=  ".audiolist tr.audiotitles th";
		$style .=  "{";
		$style .=  "height:35px;";
		$style .=  "}";
		$style .=  "";
		$style .=  "table.audiolist td";
		$style .=  "{";
		$style .=  "text-align:left;";
		$style .=  "height:50px";
		$style .=  "}";
		$style .=  "";
		$style .=  ".audiolist td, .audiolist th";
		$style .=  "{";
		$style .=  "/*padding:1px;*/";
		$style .=  "vertical-align:middle;";
		$style .=  "}";
		$style .=  "";
		$style .=  ".audiorowplayer";
		$style .=  "{";
		$style .=  "text-align:right;";
		$style .=  "}";
		$style .=  ".audiolist .list-footer";
		$style .=  "{";
		$style .=  "text-align:center;";
		$style .=  "}";
		$style .=  ".audiotitles img";
		$style .=  "{";
		$style .=  "display:none;";
		$style .=  "}";
		$style .=  ".audiolistsearch_container";
		$style .=  "{";
		$style .=  "text-align:right;";
		$style .=  "padding-bottom:5px;";
		$style .=  "}";
		
		return $style;
	
	}
}
?>
