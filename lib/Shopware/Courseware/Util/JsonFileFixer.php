<?php declare(strict_types=1);

namespace Shopware\Courseware\Util;

use Exception;
use Shopware\Courseware\Exception\InvalidJsonException;

/**
 * @todo: Currently not in use
 */
class JsonFileFixer
{
    /**
     * @return bool
     * @throws Exception
     */
    public function fixData(): bool
    {
        $data = $this->getData();
        $changed = false;

        if (!isset($data['type'])) {
            $data['type'] = $this->detectType();
            $changed = true;
        }

        if (empty($data['title'])) {
            $data['title'] = basename(dirname($this->getRelativePath()));
            $changed = true;
        }

        if ($changed === true) {
            $this->saveData($data);
            return true;
        }

        return false;
    }

    /**
     * @return bool
     * @throws InvalidJsonException
     * @throws Exception
     */
    public function isValidData(): bool
    {
        $data = $this->getData();
        if (!isset($data['type'])) {
            throw new InvalidJsonException('No type set for file "'.$this->getRelativePath().'"');
        }

        if (!isset($data['title']) && $data['type'] !== 'courseGroup') {
            throw new InvalidJsonException('No title set for file "'.$this->getRelativePath().'"');
        }

        return true;
    }

    /**
     * @param array $data
     */
    public function saveData(array $data)
    {
        $content = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($this->getAbsolutePath(), $content);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function detectType(): string
    {
        $pathParts = explode('/', $this->getRelativePath());
        if (count($pathParts) === 3) {
            return 'course';
        }

        if (count($pathParts) === 4) {
            return 'chapter';
        }

        if (count($pathParts) === 5) {
            return 'lesson';
        }

        throw new \Exception('Unknown type: '.$this->getRelativePath());
    }
}