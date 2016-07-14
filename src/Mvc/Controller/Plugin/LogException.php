<?php
/**
 * Polder Knowledge / LogModule (http://polderknowledge.nl)
 *
 * @link http://developers.polderknowledge.nl/gitlab/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016 Polder Knowledge (http://www.polderknowledge.nl)
 * @license http://polderknowledge.nl/license/proprietary proprietary
 */

namespace PolderKnowledge\LogModule\Mvc\Controller\Plugin;

use Exception;
use PolderKnowledge\LogModule\TaskService\ExceptionLogger;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

final class LogException extends AbstractPlugin
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
