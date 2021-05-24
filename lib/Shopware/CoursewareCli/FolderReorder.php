<?php declare(strict_types=1);

namespace Shopware\CoursewareCli;

use Shopware\Courseware\Util\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FolderReorder extends Command
{
    /**
     * Command definition
     */
    protected function configure()
    {
        $this->setName('folder:reorder')
            ->setDescription('Reorder all folders in a specific path')
            ->addArgument('path', InputArgument::REQUIRED, 'Path');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = (string)$input->getArgument('path');
        $coursewarePath = Config::getInstance()->getCoursewareDir();
        $path = $coursewarePath . '/' . $path;

        if (!is_dir($path)) {
            $output->writeln('<error>Path "' . $path . '" does not exist</error>');
            return Command::FAILURE;
        }


        $folders = glob($path . '/*');
        $i = 1;
        foreach ($folders as $folder) {
            if (!is_dir($folder)) {
                continue;
            }

            $baseName = preg_replace('/([0-9]+)-/', '', basename($folder));
            $number = str_pad((string)$i, 2, "0", STR_PAD_LEFT);
            $newFolder = dirname($folder) . '/' . $number . '-' . $baseName;

            if ($folder !== $newFolder && !is_dir($newFolder) && !is_file($newFolder)) {
                $output->writeln('Renaming "' . $folder . '" to "' . $newFolder . '"');
                rename($folder, $newFolder);
            }

            $i++;
        }

        return Command::SUCCESS;
    }
}