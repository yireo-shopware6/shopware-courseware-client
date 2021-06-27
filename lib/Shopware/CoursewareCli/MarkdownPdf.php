<?php declare(strict_types=1);

namespace Shopware\CoursewareCli;

use Shopware\Courseware\Util\Config;
use Shopware\Courseware\Util\PdfGenerator;
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
     * @throws \Exception
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

        $pdfGenerator = new PdfGenerator();
        $pdfGenerator->fromMarkdownFile($markdownFile);

        return 0;
    }
}
