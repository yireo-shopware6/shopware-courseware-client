<?php declare(strict_types=1);

namespace Shopware\Courseware\Util;

class Reader
{
    /**
     * @var string
     */
    private $path;

    /**
     * Reader constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string[]
     */
    public function getFiles(): array
    {
        chdir($this->path);
        exec('find '.$this->path.' -type f -name \*.md', $output);
        return $output;
    }
}