<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * This class is designed to make traveling through directorys on a Joomla!
 * install much more painless than normal.
 * 
 * @author Joel Rowley
 */

jimport('joomla.filesystem.file');

class JoomDirectory {
	
	private $_basedirectory = array();
	
	/**
	 * This is an array representing the current directory.  For example, this
	 * would be array('', 'path', 'to', 'my', 'directory') for the path
	 * '/path/to/my/directory'.
	 * 
	 * @var array of directory strings.
	 */
	private $_currentdirectory;
	
	/**
	 * Create a new directory starting at the specified directory.
	 * @param $basedirectory a full path to the top most directory we're allowed to navigate to.
	 * @param $directory a string representation of the directory to start at.
	 * @return Directory
	 */
	public function __construct( $directory, $basedirectory = JPATH_SITE )
	{
		$this->parse($directory, $basedirectory);
	}

	/**
	 * This function will take a string representation of a directory and populate
	 * this class with the appropriate values based on that string.
	 * 
	 * @param $directoryString a string representation of an absolute path
	 * (eg. '/this/is/my/path')
	 * @return Directory
	 * @throws Exception when the string is invalid or does not represent the real
	 * directory structure.
	 */
	public function parse($directoryString, $basedirectory = JPATH_SITE)
	{
		$this->_basedirectory = $this->_parse($basedirectory);
		$this->_currentdirectory = $this->_parse($directoryString);
		
		$this->isValidPath($this->_getFullPath($this->_currentdirectory, $this->_basedirectory));
		
		return $this;
	}
	
	/**
	 * Return a JoomDirectory of the parent directory from this directory.
	 * 
	 * @return JoomDirectory
	 */
	public function getParentDirectory()
	{
		$parentArray = $this->_currentdirectory;
		
		array_pop($parentArray);
		
		$parentDir = new JoomDirectory($this->_getPath($parentArray), $this->_getPath($this->_basedirectory));
		
		return $parentDir;
	}
	
	/**
	 * This function will get the directory contents and return an array that
	 * contains two different arrays: one for the directories and one for the files.
	 * These arrays will be named 'directories' and 'images'.
	 * 
	 * @param $filter an array of file extensions to include.  If the array is empty
	 * then it will include all files (eg. array('mp3', 'wav') will only include files
	 * with those file extensions).
	 * @param $fullpath boolean indicating whether to populate the arrays with the
	 * full paths or just the paths from the base path.
	 * @return unknown_type
	 */
	public function getDirectoryContentsSplit($filter = array(), $fullpath = false, $includeRelativeDirs=false)
	{
		$directories = array();
		$files = array();
		
		$d = dir($this->getFullPath());
		while (false !== ($file = $d->read())) {
			
			if ($includeRelativeDirs || ($file != '.' && $file != '..')) {
				if (is_dir( $d->path . DS . $file )) {
					$directories[] = ($fullpath) ? $this->getFullPath() . $file : $file;
					
				} elseif ( is_file( $d->path . DS . $file ) ) {
					
					$ext = JFile::getExt( $d->path . DS . $file );
					
					if (count($filter) == 0 || in_array(strtolower($ext), $filter)) {
						$files[] = ($fullpath) ? $this->getFullPath() . $file : $file;
					}
				}
			}
		}
		$d->close();
		
		return array('directories'=>$directories, 'files'=>$files);
	}
	
	/**
	 * This function returns the current path of this directory class
	 * past the base directory.
	 * 
	 * @return string the string value of the directory path.
	 */
	public function getPath()
	{   
        return $this->_getPath($this->_currentdirectory);
	}
	
	/**
	 * This function gets just the path of of this directory that is past
	 * the $_basedirectory path.  For example, if $_basedirectory is
	 * '/kunden/homepages/0/d182323456/htdocs' and $_currentdirectory is
	 * '/administrator/components' then the return from this function would
	 * '/kunden/homepages/0/d182323456/htdocs/administrator/components'.
	 * 
	 * @return string the string value of the full path of this directory.
	 * This is basically the string value of $_basedirectory concatenated with
	 * the string value of $_currentdirectory.
	 */
	public function getFullPath()
	{
		return $this->_getFullPath($this->_currentdirectory, $this->_basedirectory);
	}
	
	/**
	 * Returns the full path of the two arrays combined.
	 * 
	 * @param array $pathArray
	 * @param array $basePathArray
	 * @return string the full path
	 */
	private function _getFullPath($pathArray, $basePathArray = array()) {
		$fullPathArray = $this->cleanPathArray(array_merge($basePathArray, $pathArray));
		
		$fulldir = $this->_getPath($fullPathArray);
		
		return $fulldir;
	}
	
	/**
	 * This function determines if the given path array is valid.
	 * 
	 * @param array $path the full path to the folder to check
	 * @return boolean true if it's valid; false if it's not.
	 */
	private function isValidPath($path, $throwexception = true)
	{	
		if (!file_exists($path)) {
			if ($throwexception) {
				throw new Exception(JText::_('DIRECTORY_NOT_FOUND').': \''.$path.'\'');
			} else {
				return false;
			}
		}
		
		if (!is_dir($path)) {
			if ($throwexception) {
				throw new Exception(JText::_('THE_PATH_YOU_SPECIFIED_IS_NOT_A_DIRECTORY').': \''.$path.'\'');
			} else {
				return false;
			}
		}
		
		$fullPathArray = explode(DS, $path);
		
		// make sure the path is clean of '.' or '..' directories
		foreach ($fullPathArray as $dir) {
			if ($dir == '.' || $dir == '..') {
				if ($throwexception) {
					throw new Exception(JText::_('THE_PATH_CANNOT_INCLUDE_RELATIVE_DIRECTORIES'));
				} else {
					return false;
				}
			}
		}
		
		return true;
	}
	
	/**
	 * This function will take a string representation of a directory and populate
	 * return an array representation of this directory path
	 * @param $directoryString a string representation of an absolute path
	 * (eg. '/this/is/my/path')
	 * @return an array representing the path. (eg. '/this/is/my/path' would be returned
	 * as array('', 'is', 'my', 'path').
	 * @throws Exception when the string is invalid or does not represent the real
	 * directory structure.
	 */
	public function _parse($directoryString)
	{
		// make all slashes valid for this OS
		$directoryString = preg_replace('%(/|\\\\)%', DS, $directoryString);
		
		$dirs = explode(DS, $directoryString);
		
		$dirs = $this->cleanPathArray($dirs);
		
		return $dirs;
	}
	
	/**
	 * This function returns the given path array as a string.
	 * 
	 * @return string the string value of the directory path.
	 */
	public function _getPath($patharray)
	{
		$toreturn = DS; // root path (eg. '/')
        
        for ($i=0; $i<count($patharray); ++$i) {
        	if ($i==0 && $patharray[$i] != '') {
        		$toreturn .= $patharray[$i];
        	} else {
        		$toreturn .= (DS.$patharray[$i]);
        	}
        }
        
        return $toreturn;
	}
	
	/**
	 * Removes any empty elements ('') in the path array
	 * 
	 * @param array $pathArray array to clean
	 * @return array the cleaned array
	 */
	private function cleanPathArray($pathArray)
	{
		$toreturn = array();
		foreach($pathArray as $item) {
			if ($item != '') {
				$toreturn[] = $item;
			}
		}
		return $toreturn;
	}

	public function __toString()
    {
        return $this->getFullPath();
    }
	
}