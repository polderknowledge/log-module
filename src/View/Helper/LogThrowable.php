<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule\View\Helper;

use Exception;
use PolderKnowledge\LogModule\TaskService\ExceptionLogger;
use Zend\View\Helper\AbstractHelper;

final class LogThrowable extends AbstractHelper
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
