<?php

namespace Becklyn\CertKeyChecker;


class Signatures
{
    /**
     * @var string
     */
    private $certSignature;


    /**
     * @var string
     */
    private $keySignature;


    /**
     * @var string
     */
    private $csrSignature;


    /**
     * @var array<string, string>
     */
    private $detectedFiles = [];


    /**
     *
     * @param string $certSignature
     * @param string $keySignature
     */
    public function __construct (string $certSignature, string $keySignature)
    {
        $this->certSignature = $certSignature;
        $this->keySignature = $keySignature;
    }


    /**
     * @param string $csrSignature
     */
    public function setCsrSignature (string $csrSignature) : void
    {
        $this->csrSignature = $csrSignature;
    }


    /**
     * @param string $type
     * @param string $fileName
     */
    public function addDetectedFile (string $type, string $fileName) : void
    {
        $this->detectedFiles[$type] = $fileName;
    }


    /**
     * Returns whether the signatures are valid
     *
     * @return bool
     */
    public function isValid () : bool
    {
        if ($this->keySignature !== $this->certSignature)
        {
            return false;
        }

        return (null === $this->csrSignature) || ($this->csrSignature === $this->certSignature);
    }


    /**
     * @return string
     */
    public function getDigest () : string
    {
        return md5($this->keySignature);
    }


    /**
     * @return array
     */
    public function getDetectedFiles () : array
    {
        return $this->detectedFiles;
    }
}
