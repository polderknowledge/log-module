<?php
/**
 * Polder Knowledge / LogModule (http://polderknowledge.nl)
 *
 * @link http://developers.polderknowledge.nl/gitlab/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016 Polder Knowledge (http://www.polderknowledge.nl)
 * @license http://polderknowledge.nl/license/proprietary proprietary
 */

namespace PolderKnowledge\LogModule\View\Helper;

use Exception;
use PolderKnowledge\LogModule\TaskService\ExceptionLogger;
use Zend\View\Helper\AbstractHelper;

final class LogException extends AbstractHelper
{
    private $exceptionLogger;

    public function __construct(ExceptionLogger $exceptionLogger)
    {
        $this->exceptionLogger = $exceptionLogger;
    }

    public function __invoke(Exception $exception)
    {
        return $this->exceptionLogger->logException($exception);
    }
}
