<?php declare(strict_types=1);

namespace Shopware\Courseware\Filesystem;

/**
 * Class JsonFile
 * @package Shopware\Courseware\Utils
 */
class JsonFile extends AbstractFile
{
    /**
     * @return string
     */
    public function getTitle(): string
    {
        return (string)$this->getDataByName('title');
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return (string)$this->getDataByName('type');
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return (string)$this->getDataByName('status');
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getDataByName(string $name)
    {
        $data = $this->getData();
        return (isset($data[$name])) ? $data[$name] : null;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $content = file_get_contents($this->getAbsolutePath());
        $data = json_decode($content, true);
        if (!is_array($data)) {
            throw new \RuntimeException('Invalid JSON for "' . $this->getAbsolutePath() . '"');
        }

        return $data;
    }

    /**
     * @return MarkdownFile
     */
    public function getMarkdownFile(): MarkdownFile
    {
        $path = preg_replace('/\.json$/', '.md', $this->getAbsolutePath());
        return new MarkdownFile($path);
    }
}