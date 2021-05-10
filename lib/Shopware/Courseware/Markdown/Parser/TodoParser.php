<?php declare(strict_types=1);

namespace Shopware\Courseware\Markdown\Parser;

use Shopware\Courseware\Markdown\ParserInterface;

class TodoParser implements ParserInterface
{
    /**
     * @param string $content
     * @return string
     */
    public function parse(string $content): string
    {
        return preg_replace('/^@todo(.*)$/msi', '', $content);
    }
}
