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
 * @package    Zend_Filter
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * @namespace
 */
namespace Zend\Filter\File;
use Zend\Filter;

/**
 * Encrypts a given file and stores the encrypted file content
 *
 * @uses       \Zend\Filter\Encrypt\Encrypt
 * @uses       \Zend\Filter\Exception
 * @category   Zend
 * @package    Zend_Filter
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Encrypt extends Filter\Encrypt
{
    /**
     * New filename to set
     *
     * @var string
     */
    protected $_filename;

    /**
     * Returns the new filename where the content will be stored
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->_filename;
    }

    /**
     * Sets the new filename where the content will be stored
     *
     * @param  string $filename (Optional) New filename to set
     * @return Zend_Filter_File_Encryt
     */
    public function setFilename($filename = null)
    {
        $this->_filename = $filename;
        return $this;
    }

    /**
     * Defined by Zend\Filter\Filter
     *
     * Encrypts the file $value with the defined settings
     *
     * @param  string $value Full path of file to change
     * @return string The filename which has been set, or false when there were errors
     */
    public function _invoke($value)
    {
        if (!file_exists($value)) {
            throw new Filter\Exception("File '$value' not found");
        }

        if (!isset($this->_filename)) {
            $this->_filename = $value;
        }

        if (file_exists($this->_filename) and !is_writable($this->_filename)) {
            throw new Filter\Exception("File '{$this->_filename}' is not writable");
        }

        $content = file_get_contents($value);
        if (!$content) {
            throw new Filter\Exception("Problem while reading file '$value'");
        }

        $encrypted = parent::__invoke($content);
        $result    = file_put_contents($this->_filename, $encrypted);

        if (!$result) {
            throw new Filter\Exception("Problem while writing file '{$this->_filename}'");
        }

        return $this->_filename;
    }
}
