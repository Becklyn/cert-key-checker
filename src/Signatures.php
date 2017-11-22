<?php

namespace Becklyn\CertKeyChecker;


class Signatures
{
    /**
     * @var array<string, array>
     */
    private $detectedFiles = [];


    /**
     * @param string $type
     * @param string $fileName
     * @param string $digest
     */
    public function addDetectedFile (string $type, string $fileName, string $digest) : void
    {
        $this->detectedFiles[] = [
            "type" => $type,
            "fileName" => $fileName,
            "digest" => $digest,
        ];
    }


    /**
     * Returns whether the signatures are valid
     *
     * @return bool
     */
    public function isValid () : bool
    {
        if ($this->isInconclusive())
        {
            return false;
        }

        $first = reset($this->detectedFiles)["digest"];

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
     *
     * @return bool
     */
    public function isInconclusive () : bool
    {
        return 1 >= count($this->detectedFiles);
    }


    /**
     * @return bool
     */
    public function hasDetectedFiles () : bool
    {
        return !empty($this->detectedFiles);
    }


    /**
     * Formats the data as table
     *
     * @return array
     */
    public function formatAsTable () : array
    {
        return \array_map(
            function ($data)
            {
                return [
                    sprintf("<fg=yellow>%s</>", $data["type"]),
                    $data["fileName"],
                    sprintf("<fg=blue>%s</>", md5($data["digest"]))
                ];
            },
            $this->detectedFiles
        );
    }
}
