<?php declare(strict_types=1);

namespace Shopware\Courseware\Markdown;

interface ParserInterface
{
    /**
     * @param string $content
     * @return string
     */
    public function parse(string $content): string;
}