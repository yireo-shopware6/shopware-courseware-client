<?php declare(strict_types=1);

namespace Shopware\CoursewareCli;

use Shopware\Courseware\Entity\Course;
use Shopware\Courseware\Filesystem\Reader;
use Shopware\Courseware\Util\Config;
use Shopware\Courseware\Util\GeneratorConfig;
use Shopware\Courseware\Util\HtmlGenerator;
use Shopware\Courseware\Util\PdfGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CoursePdf extends CourseHtml
{
    /**
     * Command definition
     */
    protected function configure()
    {
        $this->setName('course:pdf')
             ->setDescription('Create a PDF-file for a course')
             ->addArgument('path', InputArgument::REQUIRED, 'Course path');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = (string) $input->getArgument('path');
        if (empty($path)) {
            $output->writeln('<error>Path argument is required</error>');

            return Command::FAILURE;
        }

        $htmlFile = $this->convertMarkdownToHtmlFile($path);
        (new PdfGenerator($this->getGeneratorConfig()))->fromHtmlFile($htmlFile);

        return Command::SUCCESS;
    }
}
