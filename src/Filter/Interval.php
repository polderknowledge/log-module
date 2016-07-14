<?php
/**
 * Polder Knowledge / LogModule (http://polderknowledge.nl)
 *
 * @link http://developers.polderknowledge.nl/gitlab/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016 Polder Knowledge (http://www.polderknowledge.nl)
 * @license http://polderknowledge.nl/license/proprietary proprietary
 */

namespace PolderKnowledge\LogModule\Filter;

use Zend\Log\Filter\FilterInterface;
use Zend\Log\Exception;

class Interval implements FilterInterface
{
    /**
     * @var string
     */
    protected $lockFile;

    /**
     * @var integer
     */
    protected $interval;

    /**
     * @var boolean
     */
    protected $lockedInCurrentRequest = false;

    /**
     * @param string  $lockFile
     * @param integer $interval
     */
    public function __construct($lockFile, $interval = null)
    {
        $this->lockFile = $lockFile;
        if (is_array($lockFile)) {
            $this->lockFile = isset($lockFile['lockFile']) ? $lockFile['lockFile'] : null;
            $interval = isset($lockFile['interval']) ? $lockFile['interval'] : null;
        }

        if (!is_string($this->lockFile) || $this->lockFile === null) {
            throw new Exception\InvalidArgumentException(sprintf(
                'lockFile must be an string; received "%s"',
                gettype($this->lockFile)
            ));
        }

        if (null !== $interval) {
            $this->interval = (int) $interval;
        }
    }

    /**
     * @param  array   $event
     * @return boolean
     */
    public function filter(array $event)
    {
        return !$this->checkLockFile();
    }

    /**
     * Check for a valid lock file. If one is found return true
     *
     * @return boolean
     * @throws Exception
     */
    protected function checkLockFile()
    {
        /**
         * Lock file doesn't exists
         */
        if (!file_exists($this->lockFile)) {
            if (!touch($this->lockFile)) {
                throw new Exception\RuntimeException(
                    sprintf("Could not write lock file '%s'", $this->lockFile)
                );
            }
            $this->lockedInCurrentRequest = true;

            return false;
        } elseif (filectime($this->lockFile) + $this->interval < time()) {
            /**
             * Lock file exists, but is expired
             */
            if (!unlink($this->lockFile) || !touch($this->lockFile)) {
                throw new Exception\RuntimeException(
                    sprintf("Could not remove and rewrite lock file '%s'", $this->lockFile)
                );
            }
            $this->lockedInCurrentRequest = true;

            return false;
        }

        /**
         * Errors in current request will be passed
         */

        return !$this->lockedInCurrentRequest;
    }
}
