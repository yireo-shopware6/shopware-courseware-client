<?php declare(strict_types=1);

namespace Shopware\CoursewareCli;

use Shopware\Courseware\Exception\FileNotFoundException;
use Shopware\Courseware\Exception\FolderNotFoundException;
use Shopware\Courseware\Filesystem\Reader;
use Shopware\Courseware\Util\Config;
use Shopware\Courseware\Util\GeneratorConfig;
use Shopware\Courseware\Util\HtmlGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CourseHtml extends Command
{
    /**
     * Command definition
     */
    protected function configure()
    {
        $this->setName('course:html')
            ->setDescription('Create a HTML-file for a course')
            ->addArgument('path', InputArgument::REQUIRED, 'Course path');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws FileNotFoundException
     * @throws FolderNotFoundException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = (string) $input->getArgument('path');
        if (empty($path)) {
            $output->writeln('<error>Path argument is required</error>');

            return Command::FAILURE;
        }

        $this->convertMarkdownToHtmlFile($path);
        return Command::SUCCESS;
    }

    /**
     * @param string $path
     * @return string
     * @throws FileNotFoundException
     * @throws FolderNotFoundException
     */
    protected function convertMarkdownToHtmlFile(string $path): string
    {
        $coursewareDir = Config::getInstance()->getCoursewareDir();
        $course = Reader::getInstance()->getCourseById($path);
        $htmlFile = $coursewareDir . '/' . $course->getId() . '/COURSEWARE.html';

        $markdown = $course->getMarkdown();

        $html = (new HtmlGenerator($this->getGeneratorConfig()))->setEntity($course)->fromMarkdown($markdown);
        file_put_contents($htmlFile, $html);

        return $htmlFile;
    }

    /**
     * @return GeneratorConfig
     */
    protected function getGeneratorConfig(): GeneratorConfig
    {
        return new GeneratorConfig();
    }
}
