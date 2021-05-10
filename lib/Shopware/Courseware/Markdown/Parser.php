<?php declare(strict_types=1);

namespace Shopware\Courseware\Markdown;

use Shopware\Courseware\Markdown\Parser\AutoHeading;
use Shopware\Courseware\Markdown\Parser\GitbookParser;
use Shopware\Courseware\Markdown\Parser\InfoParser;
use Shopware\Courseware\Markdown\Parser\TodoParser;

class Parser implements ParserInterface
{
    /**
     * @param string $content
     * @return string
     */
    public function parse(string $content): string
    {
        $parserClassess = [
            GitbookParser::class,
            TodoParser::class,
            InfoParser::class,
            AutoHeading::class,
        ];

        foreach ($parserClassess as $parserClass) {
            /** @var ParserInterface $parser */
            $parser = new $parserClass;
            $content = $parser->parse($content);
        }

        return $content;
    }
}
