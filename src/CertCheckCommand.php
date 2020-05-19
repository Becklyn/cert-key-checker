<?php declare(strict_types=1);

namespace Becklyn\CertKeyChecker;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CertCheckCommand extends Command
{
    public static $defaultName = "cert:check";

    /**
     * @inheritDoc
     */
    protected function configure () : void
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription("Checks that a key / cert / CSR are valid.");
    }


    /**
     * @inheritDoc
     */
    protected function execute (InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->title("Check certificates");


        try
        {
            $checker = new CertificateChecker(\getcwd());
            $signatures = $checker->getSignatures();

            if ($signatures->hasDetectedFiles())
            {
                $io->table(
                    ["Type", "File Name", "Digest"],
                    $signatures->formatAsTable()
                );
            }

            if ($signatures->isInconclusive())
            {
                $io->error("Inconclusive result: not enough files found.");
                return 1;
            }

            if ($signatures->isValid())
            {
                $io->success("All digests match");
                return 0;
            }

            $io->error("Digest mismatch");
            return 1;
        }
        catch (\RuntimeException $e)
        {
            $io->error($e->getMessage());
            return 1;
        }
    }


    /**
     * Formats the table for detected files
     */
    private function formatTable (array $detectedFiles) : array
    {
        $rows = [];

        foreach ($detectedFiles as $type => $data)
        {
            $rows[] = [
                "<fg=yellow>{$type}</>",
                $data["fileName"],
                $data["digest"],
            ];
        }

        return $rows;
    }
}
