<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Media
 * @subpackage ID3
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com) 
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Tory.php 153 2009-05-30 11:58:10Z svollbehr $
 * @deprecated ID3v2.3.0
 */

/**#@+ @ignore */
require_once 'Zend/Media/Id3/DateFrame.php';
/**#@-*/

/**
 * The <i>Original release year</i> frame is intended for the year when the
 * original recording, if for example the music in the file should be a cover of
 * a previously released song, was released. The field is formatted as in the
 * {@link Zend_Media_Id3_Frame_Tyer TYER} frame.
 *
 * @category   Zend
 * @package    Zend_Media
 * @subpackage ID3
 * @author     Sven Vollbehr <sven@vollbehr.eu>
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com) 
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Tory.php 153 2009-05-30 11:58:10Z svollbehr $
 * @deprecated ID3v2.3.0
 */
final class Zend_Media_Id3_Frame_Tory extends Zend_Media_Id3_DateFrame
{
    /**
     * Constructs the class with given parameters and parses object related
     * data.
     *
     * @param Zend_Io_Reader $reader The reader object.
     * @param Array $options The options array.
     */
    public function __construct($reader = null, &$options = array())
    {
        parent::__construct($reader, $options, 'Y');
    }

    /**
     * Returns the year.
     *
     * @return integer
     */
    public function getYear()
    {
        return intval($this->getText());
    }

    /**
     * Sets the year.
     *
     * @param integer $year The year given in four digits.
     */
    public function setYear($year)
    {
        $this->setText(strval($year));
    }
}