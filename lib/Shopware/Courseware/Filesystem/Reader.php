<?php declare(strict_types=1);

namespace Shopware\Courseware\Filesystem;

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
     * @return MarkdownFile[]
     */
    public function getMarkdownFiles(): array
    {
        chdir($this->path);
        exec('find ' . $this->path . ' -type f -name \*.md', $files);

        $markdownFiles = [];
        foreach ($files as $file) {
            $markdownFiles[] = new MarkdownFile($file);
        }

        return $markdownFiles;
    }
}