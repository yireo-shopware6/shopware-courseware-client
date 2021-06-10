<?php declare(strict_types=1);

namespace Shopware\CoursewareCli;

use Michelf\MarkdownExtra;
use Shopware\Courseware\Util\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MarkdownPdf extends Command
{
    /**
     * Command definition
     */
    protected function configure()
    {
        $this->setName('markdown:pdf')
            ->setDescription('Convert a single Markdown file to PDF')
            ->addArgument('file', InputArgument::REQUIRED, 'Markdown file');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $markdownFile = $input->getArgument('file');
        if (!is_file($markdownFile)) {
            $markdownFile = Config::getInstance()->getCoursewareDir().'/'.$markdownFile;
        }

        if (!is_file($markdownFile)) {
            $output->writeln('<error>Could not locate file: ' . $markdownFile . '</error>');
            return 0;
        }

        $markdown = file_get_contents($markdownFile);
        $html = $this->getHtmlWrapper();
        $html = str_replace('{body}', MarkdownExtra::defaultTransform($markdown), $html);
        $htmlFile = preg_replace('/\.md$/', '.html', $markdownFile);
        $pdfFile = preg_replace('/\.md$/', '.pdf', $markdownFile);
        file_put_contents($htmlFile, $html);

        exec('wkhtmltopdf -B 30mm -T 30mm ' . $htmlFile . ' ' . $pdfFile);

        return 0;
    }

    /**
     * @return string
     */
    private function getHtmlWrapper(): string
    {
        $html = '<html lang="en">';
        $html .= '<head>';
        $html .= '<style>' . $this->getInlineCss() . '</style>';
        $html .= '</head>';
        $html .= '<body>';
        $html .= '<div class="logo"><img width="80" src="' . $this->getLogoImageAsString() . '"/></div>';
        $html .= '{body}';
        $html .= '</body>';
        $html .= '</html>';
        return $html;
    }

    /**
     * @return string
     */
    private function getInlineCss(): string
    {
        $inlineCss = file_get_contents(__DIR__ . '/../../../pub/css/style.css');
        $inlineCss .= "\n\n";
        $inlineCss .= file_get_contents(__DIR__ . '/../../../pub/css/print.css');

        $fontsDir = Config::getInstance()->getBasePath().'/pub/fonts/';
        $inlineCss = str_replace('../fonts/', $fontsDir, $inlineCss);

        return $inlineCss;
    }

    /**
     * @return string
     */
    private function getLogoImageAsString(): string
    {
        $imageString = file_get_contents(__DIR__ . '/../../../pub/images/shopware.png');
        return 'data:image/png;base64,' . base64_encode($imageString);
    }
}