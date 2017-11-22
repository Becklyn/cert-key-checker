Certificate Key Checker
=======================

A simple CLI tool to quickly check that a certificate and a key match. Can also check for CSR validity.


Installation
------------

```bash
composer global require becklyn/cert-key-matcher
```


Usage
-----

Just call the CLI command in the directory containing the files. 


```bash
cert-key-checker
```


Checks for the following file extensions:

* `.key` for key files
* `.pem` or `.crt` for cert files
* `.csr` for csr files
