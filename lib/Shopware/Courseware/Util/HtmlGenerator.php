<?php declare(strict_types=1);

namespace Shopware\Courseware\Util;

use League\CommonMark\CommonMarkConverter;
use Shopware\Courseware\Entity\AbstractEntity;
use Shopware\Courseware\Entity\Course;
use Shopware\Courseware\Exception\FileNotFoundException;
use Shopware\Courseware\Markdown\Parser;

class HtmlGenerator
{
    /** @var AbstractEntity */
    private $entity;

    /**
     * @param string $markdown
     * @param bool $includeNotes
     * @return string
     * @throws FileNotFoundException
     */
    public function fromMarkdown(string $markdown, bool $includeNotes = false): string
    {
        $html = '';
        if ($this->entity && $this->entity instanceof Course) {
            $html .= '<div class="index" id="top">';
            $html .= '<h3>Index</h3>';
            $html .= '<ol>';
            foreach ($this->entity->getChapters() as $chapter) {
                $html .= '<li>';
                $jumpName = str_replace('/', '-', $chapter->getId());
                $html .= '<a href="#' . $jumpName . '">' . $chapter->getTitleWithoutMarkdown() . '</a>';
                $html .= '<ol>';
                foreach ($chapter->getLessons() as $lesson) {
                    $html .= '<li>';
                    $jumpName = str_replace('/', '-', $lesson->getId());
                    $html .= '<a href="#' . $jumpName . '">' . $lesson->getTitleWithoutMarkdown() . '</a>';
                    $html .= '</li>';
                }
                $html .= '</ol>';
                $html .= '</li>';
            }

            $html .= '</ol>';
            $html .= '</div>';
        }

        $chunksHtml = $this->getChunksHtmlArrayFromMarkdown($markdown, $includeNotes);

        $html .= implode('</li><div class="pagebreak"></div><li>', $chunksHtml);



        return str_replace('{body}', $html, $this->getHtmlWrapper());
    }

    /**
     * @param AbstractEntity $entity
     * @return HtmlGenerator
     */
    public function setEntity(AbstractEntity $entity): HtmlGenerator
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @param string $markdown
     * @param string $type
     * @return string
     * @todo Move this into separate parser classes (just like with Markdown\ParseInterface)
     */
    private function parseMarkdown(string $markdown, string $type): string
    {
        $converter = new CommonMarkConverter();
        $cssClasses = [$type];

        $markdown = (new Parser())->parse($markdown);
        $markdown = trim($markdown);

        if (preg_match('/class: (.*)/', $markdown, $match)) {
            $cssClasses = array_merge($cssClasses, explode(',', $match[1]));
            $markdown = str_replace($match[0], '', $markdown);
        }

        if (preg_match('/name: (.*)/', $markdown, $match)) {
            $jumpName = str_replace('/', '-', $match[1]);
            $markdown = str_replace($match[0], '<a name="'.$jumpName.'"></a>', $markdown);
        }

        if (preg_match('/\s--\s/', $markdown, $match)) {
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
        $markdown = '<div class="' . implode(' ', $cssClasses) . '"><div>' . $markdown . "</div></div><a href=\"#top\" class=\"toplink\">back to index</a>\n";

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
        $html .= '<div class="logo"><img width="80" src="' . $this->getLogoImageAsString() . '"/></div>' . "\n";
        $html .= '<ol><li>{body}</li></ol>';
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

    private function getChunksHtmlArrayFromMarkdown(string $markdown, bool $includeNotes): array
    {
        $chunks = explode('---', $markdown);
        $chunksHtml = [];
        foreach ($chunks as $chunk) {
            $chunk = explode('???', $chunk);
            $chunkHtml = '';
            $chunkHtml .= $this->parseMarkdown($chunk[0], 'slide');
            if ($includeNotes && isset($chunk[1]) && !empty($chunk[1])) {
                $chunkHtml .= $this->parseMarkdown($chunk[1], 'notes');
            }
            $chunksHtml[] = $chunkHtml;
        }

        return $chunksHtml;
    }
}
