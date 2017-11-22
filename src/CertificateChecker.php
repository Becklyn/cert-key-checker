<?php

namespace Becklyn\CertKeyChecker;


class CertificateChecker
{
    /**
     * @var string
     */
    private $dir;


    /**
     * @var string|null
     */
    private $key;


    /**
     * @var string|null
     */
    private $cert;


    /**
     * @var string|null
     */
    private $csr;


    /**
     * @param string $dir
     */
    public function __construct (string $dir)
    {
        $this->dir = rtrim($dir, "/");
        $this->key = $this->loadFile("key", "key");
        $this->cert = $this->loadFile("{crt,pem}", "cert");
        $this->csr = $this->loadFile("csr", "csr");
    }


    /**
     * @param string $extension
     * @param string $fileType
     * @return string
     */
    private function loadFile (string $extension, string $fileType) : ?string
    {
        $files = glob("{$this->dir}/*.{$extension}", \GLOB_BRACE);

        if (count($files) > 1)
        {
            throw new \RuntimeException("Multiple {$fileType} files found.");
        }

        return $files[0] ?? null;
    }


    /**
     * @return string
     */
    public function getSignatures () : Signatures
    {
        $signatures = new Signatures();

        if (null !== $this->key)
        {
            $signatures->addDetectedFile(
                "key",
                $this->relativeFilePath($this->key),
                shell_exec('openssl rsa -noout -modulus -in ' . escapeshellarg($this->key))
            );
        }

        if (null !== $this->cert)
        {
            $signatures->addDetectedFile(
                "cert",
                $this->relativeFilePath($this->cert),
                shell_exec('openssl x509 -noout -modulus -in ' . escapeshellarg($this->cert))
            );
        }

        if (null !== $this->csr)
        {
            $signatures->addDetectedFile(
                "csr",
                $this->relativeFilePath($this->csr),
                shell_exec('openssl req -noout -modulus -in ' . escapeshellarg($this->csr))
            );
        }

        return $signatures;
    }


    /**
     * Returns the relative path name
     *
     * @param string $fullPath
     * @return string
     */
    private function relativeFilePath (string $fullPath) : string
    {
        $dir = "{$this->dir}/";
        $length = strlen($dir);

        return ($dir === \substr($fullPath, 0, $length))
            ? substr($fullPath, $length)
            : $fullPath;
    }
}
