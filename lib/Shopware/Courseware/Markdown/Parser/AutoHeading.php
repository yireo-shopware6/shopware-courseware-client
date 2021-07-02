<?php declare(strict_types=1);

namespace Shopware\Courseware\Markdown\Parser;

use Shopware\Courseware\Markdown\ParserInterface;

class AutoHeading implements ParserInterface
{
    /**
     * @param string $content
     * @return string
     */
    public function parse(string $content): string
    {
        $slides = explode('---', $content);
        foreach ($slides as &$slide) {
            $slide = trim($slide);
            if ($this->hasOnlyHeading($slide)) {
                $slide = "class: center, middle\n" . $slide;
            }
        }

        return implode("\n\n---\n", $slides);
    }

    /**
     * @param string $content
     * @return bool
     */
    private function hasOnlyHeading(string $content): bool
    {
        $lines = explode("???", $content);
        $slide = trim($lines[0]);

        $lines = explode("\n", $slide);
        foreach ($lines as $line) {
            $line = trim($line);
            $line = preg_replace('/^name: (.*)$/', '', $line);
            if (empty($line)) continue;
            if (!preg_match('/^#/', $line)) return false;
        }

        return true;
    }
}
