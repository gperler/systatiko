<?php

declare(strict_types=1);

namespace Systatiko\Reader;

use Civis\Common\File;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

class PHPClassScanner implements LoggerAwareInterface
{
    const DEFAULT_PHP_SUFFIX = ".php";

    /**
     * @var PHPClass[]
     */
    private $phpClassList;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     *
     */
    public function __construct()
    {
        $this->phpClassList = array();
    }

    /**
     * @param string $baseDirPath
     * @param string $suffix
     *
     * @return bool
     */
    public function addBaseDir(string $baseDirPath, string $suffix = self::DEFAULT_PHP_SUFFIX): bool
    {
        $baseDir = new File($baseDirPath);

        // make sure file exists and is dir
        if (!$baseDir->exists() or !$baseDir->isDir()) {
            return false;
        }

        // scan for all php files
        $phpFileList = $baseDir->findFileList($suffix);
        foreach ($phpFileList as $phpFile) {
            $this->extractPHPClassList($phpFile);
        }
        return true;
    }

    /**
     * @return PHPClass[]
     */
    public function getPHPClassList()
    {
        return $this->phpClassList;
    }

    /**
     * @param File $phpFile
     * @throws \ReflectionException
     */
    private function extractPHPClassList(File $phpFile)
    {
        $phpFileContent = $phpFile->getContents();
        $definedClassList = $this->getDefinedPhpClasses($phpFileContent);

        foreach ($definedClassList as $definedClass) {
            $phpClass = new PHPClass($phpFile, $definedClass);

            if ($phpClass->hasError()) {
                $this->logger->warning("Ignored file " . $phpFile->getAbsoluteFileName());
                $this->logger->warning($phpClass->getErrorMessage());
                return;
            }

            $this->phpClassList[] = $phpClass;
        }
    }

    /**
     * @param string $phpcode
     *
     * @return array
     */
    private function getDefinedPhpClasses(string $phpcode): array
    {
        // taken from stackoverflow
        // http://stackoverflow.com/questions/928928/determining-what-classes-are-defined-in-a-php-class-file

        $classList = [];

        $namespace = 0;
        $tokens = token_get_all($phpcode);
        $count = count($tokens);
        $dlm = false;
        for ($i = 2; $i < $count; $i++) {
            if ((isset($tokens[$i - 2][1]) && ($tokens[$i - 2][1] == "phpnamespace" || $tokens[$i - 2][1] == "namespace")) || ($dlm && $tokens[$i - 1][0] == T_NS_SEPARATOR && $tokens[$i][0] == T_STRING)) {
                if (!$dlm) $namespace = 0;
                if (isset($tokens[$i][1])) {
                    $namespace = $namespace ? $namespace . "\\" . $tokens[$i][1] : $tokens[$i][1];
                    $dlm = true;
                }
            } elseif ($dlm && ($tokens[$i][0] != T_NS_SEPARATOR) && ($tokens[$i][0] != T_STRING)) {
                $dlm = false;
            }
            if (($tokens[$i - 2][0] == T_CLASS || (isset($tokens[$i - 2][1]) && $tokens[$i - 2][1] == "phpclass")) && $tokens[$i - 1][0] == T_WHITESPACE && $tokens[$i][0] == T_STRING) {
                $class_name = $tokens[$i][1];
                $classList[] = $namespace . "\\" . $class_name;
            }
        }
        return $classList;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

}