<?php declare(strict_types=1);

namespace Becklyn\CertKeyChecker;

use Becklyn\CertKeyChecker\Exception\DigestGenerationException;

class Signatures
{
    /**
     * @var array[]
     */
    private $detectedFiles = [];


    /**
     */
    public function addDetectedFile (string $type, string $fileName, ?string $digest) : void
    {
        if (null === $digest)
        {
            throw new DigestGenerationException(\sprintf(
                "Digest generation failed for file '%s' of type '%s'",
                $fileName,
                $type
            ));
        }

        $this->detectedFiles[] = [
            "type" => $type,
            "fileName" => $fileName,
            "digest" => $digest,
        ];
    }


    /**
     * Returns whether the signatures are valid
     */
    public function isValid () : bool
    {
        if ($this->isInconclusive())
        {
            return false;
        }

        $first = \reset($this->detectedFiles)["digest"];

        foreach ($this->detectedFiles as $file)
        {
            if ($file["digest"] !== $first)
            {
                return false;
            }
        }

        return true;
    }


    /**
     * Returns whether the check is significant
     */
    public function isInconclusive () : bool
    {
        return 1 >= \count($this->detectedFiles);
    }


    /**
     */
    public function hasDetectedFiles () : bool
    {
        return !empty($this->detectedFiles);
    }


    /**
     * Formats the data as table
     */
    public function formatAsTable () : array
    {
        return \array_map(
            function ($data)
            {
                return [
                    \sprintf("<fg=yellow>%s</>", $data["type"]),
                    $data["fileName"],
                    \sprintf("<fg=blue>%s</>", \md5($data["digest"])),
                ];
            },
            $this->detectedFiles
        );
    }
}
