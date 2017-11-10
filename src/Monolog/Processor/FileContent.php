<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule\Monolog\Processor;

final class FileContent
{
    /**
     * @var string
     */
    private $filePath;

    /**
     * @var string
     */
    private $field;

    public function __construct(string $filePath, string $field)
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException(
                sprintf('File "%s" doesn\'t exists', $filePath)
            );
        }

        $this->filePath = $filePath;
        $this->field = $field;
    }

    public function __invoke(array $record)
    {
        $record['extra'][$this->field] = file_get_contents($this->filePath);

        return $record;
    }
}
