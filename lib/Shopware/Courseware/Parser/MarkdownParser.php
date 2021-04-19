<?php declare(strict_types=1);

namespace Shopware\Courseware\Parser;

class MarkdownParser
{
    /**
     * @param string $content
     * @return string
     */
    public function parse(string $content): string
    {
        if (!preg_match_all('/{%(.*)%}/', $content, $matches)) {
            return $content;
        }

        foreach ($matches[0] as $matchIndex => $match) {
            $instruction = trim($matches[1][$matchIndex]);
            $instructionParts = explode(' ', $instruction);
            if (preg_match('/^include\ (.*)/', $instruction)) {
                $content = str_replace($match, $this->getContentFromInclude($instructionParts), $content);
            }

            if (preg_match('/^embed\ (.*)/', $instruction)) {
                $content = str_replace($match, $this->getContentFromEmbed($instructionParts), $content);
            }

            if (preg_match('/^hint\ (.*)/', $instruction)) {
                $content = str_replace($match, $this->getContentFromHint($instructionParts), $content);
            }

            if (preg_match('/^endhint/', $instruction)) {
                $content = str_replace($match, '', $content);
            }
        }

        return $content;
    }

    /**
     * @param array $instructionParts
     * @return string
     */
    private function getContentFromEmbed(array $instructionParts): string
    {
        $url = $instructionParts[1];
        $url = str_replace('url=', '', $url);
        $url = str_replace('"', '', $url);
        return $url;
    }

    /**
     * @param array $instructionParts
     * @return string
     */
    private function getContentFromInclude(array $instructionParts): string
    {
        $content = file_get_contents($instructionParts[1]);
        if (is_numeric($instructionParts[2])) {
            $contentParts = explode("\n\n#", $content);
            $content = $contentParts[$instructionParts[2]];
        }

        $content = $this->parse($content);
        return $content;
    }

    /**
     * @param array $instructionParts
     * @return string
     */
    private function getContentFromHint(array $instructionParts): string
    {
        return 'HINT:';
    }
}
