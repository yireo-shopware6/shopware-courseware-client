<?php declare(strict_types=1);

namespace Shopware\CoursewareCli;

use Shopware\Courseware\Entity\Course;
use Shopware\Courseware\Filesystem\Reader;
use Shopware\Courseware\Html\Parser as HtmlParser;
use Shopware\Courseware\Util\Config;
use Shopware\Courseware\Util\GeneratorConfig;
use Shopware\Courseware\Util\HtmlGenerator;
use Shopware\Courseware\Util\PdfGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CoursePdf extends Command
{
    /**
     * Command definition
     */
    protected function configure()
    {
        $this->setName('course:pdf')
            ->setDescription('Create a PDF for a course')
            ->addArgument('path', InputArgument::REQUIRED, 'Course path');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = (string)$input->getArgument('path');
        if (empty($path)) {
            $output->writeln('<error>Path argument is required</error>');
            return Command::FAILURE;
        }

        $coursewareDir = Config::getInstance()->getCoursewareDir();
        $course = Reader::getInstance()->getCourseById($path);
        $htmlFile = $coursewareDir.'/'.$course->getId().'/COURSEWARE.html';

        $markdown = $course->getMarkdown();
        $generatorConfig = new GeneratorConfig();
        $html = (new HtmlGenerator($generatorConfig))->setEntity($course)->fromMarkdown($markdown);
        file_put_contents($htmlFile, $html);

        (new PdfGenerator($generatorConfig))->fromHtmlFile($htmlFile);

        return Command::SUCCESS;
    }

    private function getIndexHtml(Course $course): string
    {

    }
}