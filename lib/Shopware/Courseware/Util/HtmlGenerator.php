<?php declare(strict_types=1);

namespace Shopware\Courseware\Util;

use League\CommonMark\CommonMarkConverter;
use Shopware\Courseware\Exception\FileNotFoundException;
use Shopware\Courseware\Markdown\Parser;

class HtmlGenerator
{
    /**
     * @param string $markdown
     * @param bool $includeNotes
     * @return string
     * @throws FileNotFoundException
     */
    public function fromMarkdown(string $markdown, bool $includeNotes = false): string
    {
        $html = '';
        $chunks = explode('---', $markdown);
        foreach ($chunks as $chunk) {
            $chunk = explode('???', $chunk);
            $html .= $this->parseMarkdown($chunk[0], 'slide');
            if ($includeNotes && isset($chunk[1]) && !empty($chunk[1])) {
                $html .= $this->parseMarkdown($chunk[1], 'notes');
            }

            $html .= '<div class="pagebreak"></div>';
        }

        return str_replace('{body}', $html, $this->getHtmlWrapper());
    }

    /**
     * @param string $markdown
     * @param string $type
     * @return string
     */
    private function parseMarkdown(string $markdown, string $type): string
    {
        $converter = new CommonMarkConverter();
        $cssClasses = [$type];

        $markdown = (new Parser())->parse($markdown);
        $markdown = trim($markdown);

        if (preg_match('/^class: (.*)/', $markdown, $match)) {
            $cssClasses = array_merge($cssClasses, explode(',', $match[1]));
            $markdown = str_replace($match[0], '', $markdown);
        }

        $markdown = str_replace('<?php ', '', $markdown);

        // Remove HTML wrappers .shell[], .file[], ...
        $markdown = preg_replace('/.(shell|file|chapters)\[/', '', $markdown);
        $markdown = str_replace("```\n]", "```\n\n", $markdown);
        $markdown = preg_replace("/]$/", "", $markdown);

        // Notices ^^Something
        if (preg_match_all('/^\^\^([^\n]+)/msi', $markdown, $matches)) {
            foreach ($matches[1] as $matchIndex => $match) {
                $inner = '<div class="info">';
                $inner .= $converter->convertToHtml($matches[1][$matchIndex]);
                $inner .= '</div>';
                $inner .= "\n";
                $markdown = str_replace($matches[0][$matchIndex], $inner, $markdown);
            }
        }

        // Strike-through text
        $markdown = preg_replace(
            '/~~([^~]+)~~/msi',
            '<span style="text-decoration: line-through;">$1</span>',
            $markdown
        );


        //return $markdown;
        $markdown = $converter->convertToHtml($markdown);
        $markdown = '<div class="'.implode(' ', $cssClasses).'"><div>' . $markdown . "</div></div>\n";

        return $markdown;

    }

    /**
     * @return string
     * @throws FileNotFoundException
     */
    private function getHtmlWrapper(): string
    {
        $html = '<html lang="en">';
        $html .= '<head>';
        $html .= '<style>' . $this->getInlineCss() . '</style>';
        $html .= '</head>';
        $html .= '<body>';
        $html .= '<div class="logo"><img width="80" src="' . $this->getLogoImageAsString() . '"/></div>'."\n";
        $html .= '{body}';
        $html .= '</body>';
        $html .= '</html>';
        return $html;
    }

    /**
     * @return string
     * @throws FileNotFoundException
     */
    private function getInlineCss(): string
    {
        $inlineCss = $this->getContentsFromFile($this->getAppRoot() . '/pub/css/style.css');
        $inlineCss .= "\n\n";
        $inlineCss .= $this->getContentsFromFile($this->getAppRoot() . '/pub/css/print.css');

        $fontsDir = Config::getInstance()->getBasePath() . '/pub/fonts/';
        return str_replace('../fonts/', $fontsDir, $inlineCss);
    }

    /**
     * @return string
     * @throws FileNotFoundException
     */
    private function getLogoImageAsString(): string
    {
        $imageString = $this->getContentsFromFile($this->getAppRoot() . '/pub/images/shopware.png');
        return 'data:image/png;base64,' . base64_encode($imageString);
    }

    /**
     * @param string $file
     * @return string
     * @throws FileNotFoundException
     */
    private function getContentsFromFile(string $file): string
    {
        if (!file_exists($file) || !is_readable($file)) {
            throw new FileNotFoundException($file);
        }

        return file_get_contents($file);
    }

    /**
     * @return string
     */
    private function getAppRoot(): string
    {
        return __DIR__ . '/../../../../';
    }
}