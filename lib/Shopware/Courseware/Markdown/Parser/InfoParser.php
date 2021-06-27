<?php declare(strict_types=1);

namespace Shopware\Courseware\Markdown\Parser;

use Michelf\MarkdownExtra;
use Shopware\Courseware\Markdown\ParserInterface;

class InfoParser implements ParserInterface
{
    /**
     * @param string $content
     * @return string
     */
    public function parse(string $content): string
    {
        if (preg_match_all('/^\^\^(.*)$/m', $content, $matches)) {
            foreach ($matches[0] as $matchIndex => $match) {
                $fullMatch = $matches[0][$matchIndex];
                $textMatch = $matches[1][$matchIndex];
                $textMatch = MarkdownExtra::defaultTransform($textMatch);
                $infoTag = '<div class="info">' . $textMatch . '</div>'."\n";
                $content = str_replace($fullMatch, $infoTag, $content);
            }
        }

        return $content;
    }
}
