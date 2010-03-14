<?php 
/*
	Media Syndicator
	(c) 2010 Joel Rowley
*/
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');
jimport('joomla.filesystem.file');

class MediaSyndicatorViewJsonDirectoryContents extends JView
{
	function display($tpl = null)
	{
		$params	=& JComponentHelper::getParams( 'com_mediasyndicator' );
		$basebrowserpath	= $params->get('basebrowserpath', DS.'images');
		
		$dir = JRequest::getVar('dir', '');
		
		$firephp = FirePHP::getInstance(true);
		$firephp->log('%^'.preg_quote($basebrowserpath).'%', 'regex');
		
		$dir = preg_replace('%^'.preg_quote($basebrowserpath).'%', '', $dir);
		
		$document =& JFactory::getDocument();
		//$document->setMimeEncoding('application/json');
		$document->setMimeEncoding('application/x-javascript');

		//TODO: verify that this is a logged in user in the backend of Joomla!
		
		// include JSON service file
		$JSONfile = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_mediasyndicator'.DS.'libraries'.DS.'JSON.php';
		$includeFile = $this->includeFile($JSONfile);
		if ($includeFile) {
			print $includeFile;
			return;
		}
		
		$directoryarray = $this->createDirectoryArray($dir, $basebrowserpath);
		
		$directoryarray['requestdir'] = $dir;
		
		$json = new Services_JSON();
		$output = $json->encode($directoryarray);
		print $output; 
	}
	
	function createDirectoryArray($directory, $basebrowserpath)
	{
		$joomdirectorypath = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_mediasyndicator'.DS.'helpers'.DS.'JoomDirectory.class.php';
		$includeFile = $this->includeFile($joomdirectorypath);
		if ($includeFile) {
			print $includeFile;
			return;
		}
		
		try {
			$joomdir = new JoomDirectory($directory, JPATH_SITE.$basebrowserpath);
		} catch (Exception $ex) {
			return array('error' => $ex->getMessage());
		}
		
		$items = array(array('name'=>'Up a level...', 'path'=>$joomdir->getParentDirectory()->getPath(), 'image'=>$this->getImage('directory'), 'is_directory'=>'true', 'is_file'=>'false'));
		
		$dircontents = $joomdir->getDirectoryContentsSplit(array('mp3', 'wav', 'mov'));
		
		$directories = $dircontents['directories'];
		$files = $dircontents['files'];
		
		sort($directories);
		sort($files);
		
		$firephp = FirePHP::getInstance(true);
		foreach ($directories as $item) {
			$joomdirpath = $joomdir->getPath();
			
			$itemshortpath = $joomdir->_getPath($joomdir->_parse($joomdirpath.DS.$item));
			
			//$firephp->log('item: '.$item.'; shortpath: '.$itemshortpath, 'debug');
			
			$items[] = 	array('name'=>$item, 
								'path'=>$basebrowserpath.$itemshortpath,
								'image'=>$this->getImage('directory'),
								'is_directory'=>'true',
								'is_file'=>'false'
							);
		}
		
		foreach ($files as $item) {
			$joomdirpath = $joomdir->getPath();
			
			$itemshortpath = $joomdir->_getPath($joomdir->_parse($joomdirpath.DS.$item));
			
			$items[] = 	array('name'=>$item, 
								'path'=>$basebrowserpath.$itemshortpath,
								'filesize'=>$this->getFileSize($itemshortpath),
								'image'=>$this->getImage(JFile::getExt($itemshortpath)),
								'is_directory'=>'false',
								'is_file'=>'true'
							);
		}
		
		$toreturn['items'] = $items;
		$toreturn['totalitems'] = count($items);
		$toreturn['error'] = '';
		
		return $toreturn;
	}
	
	function getFileSize($file)
	{
		if (file_exists($file) && is_file($file)) {
			return $this->formatBytes(filesize($file));
		}
		
		return 'N/A';
	}
	
	function formatBytes($bytes, $precision = 2)
	{
		$units = array('B', 'KB', 'MB', 'GB', 'TB');
		
		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);
		  
		$bytes /= pow(1024, $pow);
		  
		return round($bytes, $precision) . ' ' . $units[$pow];
	} 
	
	function getImage($imageType)
	{
		$basepath = JURI::base(true);
		/*$tmp = explode(DS.'administrator', $basepath);
		
		foreach($tmp as $dir) {
			if ($dir != '') {
				$basepath = DS.$dir;
			}
		}*/
		
		$basepath = $basepath.DS.'components'.DS.'com_mediasyndicator'.DS.'assets'.DS.'images'.DS.'filebrowser';
		
		/*switch ($imageType) {
			case 'directory':
				return $basepath.DS.'folder.png';
				break;
			case 'mp3':
			case 'wav':
				return $basepath.DS.'mp3.png';
				break;
			case 'mov':
				return $basepath.DS.'mov.png';
				break;
			case '':
		}*/
		
		$toreturn = '';
		
		if ($imageType == 'directory') {
			$toreturn = $basepath.DS.'folder.png';
		} else {
			$toreturn = $basepath.DS.$imageType.'.png';
		}
		
		if (!file_exists(JPATH_SITE.$toreturn)) {
			$toreturn = $basepath.DS.'unknown.png';
		}
		
		return $toreturn;
	}
	
	function includeFile($file)
	{
		if (file_exists($file)) {
			require_once($file);
		} else {
			print '{\'error\':\''. JText::_('UNABLE_TO_INCLUDE_FILE') . ': \'' . $file . '\'\'}';
			return;
		}
	}
}