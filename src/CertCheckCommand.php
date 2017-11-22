<?php

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
    protected function configure ()
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


        try {
            $checker = new CertificateChecker(\getcwd());
            $signatures = $checker->getSignature();
            $detectedFiles = $signatures->getDetectedFiles();

            if (!empty($detectedFiles))
            {
                $io->table(
                    [
                        "Type", "Path"
                    ],
                    $this->formatTable($detectedFiles)
                );
            }

            if ($signatures->isValid())
            {
                $io->text(sprintf(
                    "Digest: <fg=blue>%s</>",
                    $signatures->getDigest()
                ));
                $io->success("Cert matched");
                return 0;
            }
            else
            {
                $io->error("Certificate mismatch");
                return 1;
            }
        }
        catch (\RuntimeException $e)
        {
            $io->error($e->getMessage());
            return 1;
        }
    }


    /**
     * Formats the table for detected files
     *
     * @param array $detectedFiles
     * @return array
     */
    private function formatTable (array $detectedFiles) : array
    {
        $rows = [];

        foreach ($detectedFiles as $type => $filePath)
        {
            $rows[] = [
                "<fg=yellow>{$type}</>",
                $filePath
            ];
        }

        return $rows;
    }
}
