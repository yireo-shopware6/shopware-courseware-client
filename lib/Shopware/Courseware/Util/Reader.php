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
        exec('find . -type f -name *.md', $output);
        print_r($output);exit;

        return explode("\n", $output);
    }
}