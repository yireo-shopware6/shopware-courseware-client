<?php declare(strict_types=1);

namespace Shopware\Courseware\Util;

class PdfGenerator
{
    public function fromMarkdownFile(string $markdownFile)
    {
        $markdown = file_get_contents($markdownFile);
        $htmlFile = preg_replace('/\.md$/', '.html', $markdownFile);
        $html = (new HtmlGenerator())->fromMarkdown($markdown);
        file_put_contents($htmlFile, $html);

        $this->fromHtmlFile($htmlFile);
    }

    /**
     * @param string $htmlFile
     */
    public function fromHtmlFile(string $htmlFile)
    {
        $pdfFile = preg_replace('/\.html$/', '.pdf', $htmlFile);

        $args = [];
        $args[] = '-B 20mm';
        $args[] = '-T 20mm';

        exec('wkhtmltopdf ' . implode(' ', $args) . ' ' . $htmlFile . ' ' . $pdfFile);
    }
}