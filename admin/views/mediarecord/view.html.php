<?php
/**
* @version		$Id: view.html.php 1 2010-01-17 20:04:17Z joel $
* @package		Joomla.Joelrowley.Com
* @copyright	Copyright (C) 2010 Joel Rowley. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Plugins component
 *
 * @static
 * @package		Joomla
 * @subpackage	Plugins
 * @since 1.0
 */
class MediaSyndicatorViewMediaRecord extends JView
{
	function display( $tpl = null )
	{
		global $option;

		$db			=& JFactory::getDBO();
		$user 		=& JFactory::getUser();
		$document	=& JFactory::getDocument();
		$model 		=& $this->getModel();
		
		$document->addScript('components'.DS.'com_mediasyndicator'.DS.'assets'.DS.'js'.DS.'jquery-1.4.min.js');
		$document->addScript('components'.DS.'com_mediasyndicator'.DS.'assets'.DS.'js'.DS.'jquery-ui-1.7.2.custom.min.js');
		$document->addStyleSheet('components'.DS.'com_mediasyndicator'.DS.'assets'.DS.'css'.DS.'sunny'.DS.'jquery-ui-1.7.2.custom.css');
		//add css to document
		$document->addStyleSheet('components'.DS.'com_mediasyndicator'.DS.'assets'.DS.'css'.DS.'style.css');

		$client = JRequest::getWord( 'client', 'site' );
		$cid 	= JRequest::getVar( 'cid', array(0), '', 'array' );
		JArrayHelper::toInteger($cid, array(0));

		$lists 	= array();
		$row 	=& JTable::getInstance('mediasyndicatorfiles');

		// load the row from the db table
		$row->load( $cid[0] );

		// fail if checked out not by 'me'

		if ($row->isCheckedOut( $user->get('id') ))
		{
			$msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_( 'The media file' ), $row->title );
			$this->setRedirect( 'index.php?option='. $option .'&client='. $client, $msg, 'error' );
			return false;
		}

		/*if ($client == 'admin') {
			$where = "client_id='1'";
		} else {
			$where = "client_id='0'";
		}

		// get list of groups
		if ($row->access == 99 || $row->client_id == 1) {
			$lists['access'] = 'Administrator<input type="hidden" name="access" value="99" />';
		} else {
			// build the html select list for the group access
			$lists['access'] = JHTML::_('list.accesslevel',  $row );
		}*/
			
		$categoriesListValues = $this->getItunesCategoriesListValues();

		if ($cid[0])
		{ // editing an item
			$row->checkout( $user->get('id') );
			
			//$firephp = FirePHP::getInstance(true);
			//$firephp->log($model->getMediaTypes(), 'media_types_model');
			
			$lists['media_types'] = JHTML::_('select.genericlist',   $model->getMediaTypes(), 'media_type_id', 'class="inputbox" size="1"', 'id', 'media_type_name', intval( $row->media_type_id ) );
			$lists['it_block'] = JHTML::_('select.genericlist',   array( array( 'value' => '0', 'text' => 'No' ), array( 'value' => '1', 'text' => 'Yes' ) ), 'it_block', 'class="inputbox" size="1"', 'value', 'text', intval( $row->it_block ) );
			$lists['it_explicit'] = JHTML::_('select.genericlist',   array( array( 'value' => '0', 'text' => 'No' ), array( 'value' => '1', 'text' => 'Yes' ) ), 'it_explicit', 'class="inputbox" size="1"', 'value', 'text', intval( $row->it_explicit ) );
			$lists['it_category_id'] = JHTML::_('select.genericlist', $categoriesListValues, 'it_category_id', 'class="inputbox" size="1"', 'value', 'text', intval( $row->it_category_id ) );

		} else {
			// creating a new item
			
			$lists['media_types'] = JHTML::_('select.genericlist',   $model->getMediaTypes(), 'media_type_id', 'class="inputbox" size="1"', 'id', 'media_type_name' );
			$lists['it_block'] = JHTML::_('select.genericlist',   array( array( 'value' => '0', 'text' => 'No' ), array( 'value' => '1', 'text' => 'Yes' ) ), 'it_block', 'class="inputbox" size="1"', 'value', 'text' );
			$lists['it_explicit'] = JHTML::_('select.genericlist',   array( array( 'value' => '0', 'text' => 'No' ), array( 'value' => '1', 'text' => 'Yes' ) ), 'it_explicit', 'class="inputbox" size="1"', 'value', 'text' );
			$lists['it_category_id'] = JHTML::_('select.genericlist', $categoriesListValues, 'it_category_id', 'class="inputbox" size="1"', 'value', 'text', 37 );
			/*
			$row->folder 		= '';
			$row->ordering 		= 999;
			$row->published 	= 1;
			$row->description 	= '';
			*/
		}

		$lists['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $row->published );

		// get params definitions
		

		$this->assignRef('lists',		$lists);
		$this->assignRef('mediarecord',		$row);
		//$this->assignRef('params',		$params);

		parent::display($tpl);
	}
	
	function getItunesCategoriesListValues()
	{
		$model 		=& $this->getModel();
		$categories = $model->getItunesCategories();
				
		$categoriesListValues = array();
		foreach ($categories as $category) {
			$listtext = $category->name;
			if ($category->parent_category_id != null) {
				$listtext = $categories[$category->parent_category_id]->name . ' -> ' . $category->name;
			}
			
			$categoriesListValues[] = array('value' => $category->id, 'text' => $listtext);
		}
		
		return $categoriesListValues;
	}
}