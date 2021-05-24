<?php declare(strict_types=1);

namespace Shopware\CoursewareCli;

use Shopware\Courseware\Util\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ChapterCreate extends Command
{
    /**
     * Command definition
     */
    protected function configure()
    {
        $this->setName('chapter:create')
            ->setDescription('Create files for a new chapter')
            ->addArgument('path', InputArgument::REQUIRED, 'Chapter path')
            ->addArgument('title', InputArgument::OPTIONAL, 'Chapter title');
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

        $title = (string)$input->getArgument('title');
        if (empty($title)) {
            $title = basename($path);
        }

        $coursewarePath = Config::getInstance()->getCoursewareDir();
        $path = $coursewarePath . '/' . $path;

        if (!is_dir($path)) {
            $output->writeln('Creating folder "' . $path . '"');
            mkdir($path);
        }

        if (!is_file($path . '/main.json')) {
            $output->writeln('Generating JSON file');
            $json = json_encode($this->getJsonData($title), JSON_PRETTY_PRINT);
            file_put_contents($path . '/main.json', $json);
        }

        return Command::SUCCESS;
    }

    /**
     * @param string $title
     * @return string[]
     */
    private function getJsonData(string $title): array
    {
        return [
            'type' => 'chapter',
            'title' => $title,
            'status' => 'pending'
        ];
    }
}