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
        $args[] = '-B 30mm';
        $args[] = '-T 20mm';
        $args[] = '-L 20mm';
        $args[] = '-R 30mm';
        $args[] = '--enable-local-file-access';

        $str = 'wkhtmltopdf ' . implode(' ', $args) . ' ' . $htmlFile . ' ' . $pdfFile;
        echo $str.PHP_EOL;
        exec($str);
    }
}
