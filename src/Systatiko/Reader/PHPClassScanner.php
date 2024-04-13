<?php

declare(strict_types=1);

namespace Systatiko\Reader;

use Civis\Common\File;
use Codeception\Util\Debug;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use ReflectionException;

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
     * @throws ReflectionException
     */
    private function extractPHPClassList(File $phpFile): void
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
    public function getDefinedPhpClasses2(string $phpcode): array
    {
        // taken from stackoverflow
        // http://stackoverflow.com/questions/928928/determining-what-classes-are-defined-in-a-php-class-file

        $classList = [];

        $namespace = null;
        $tokens = token_get_all($phpcode);
        $count = count($tokens);
        $dlm = false;
        for ($i = 2; $i < $count; $i++) {
            //echo json_encode($tokens[$i], JSON_PRETTY_PRINT);
            if ((isset($tokens[$i - 2][1]) && ($tokens[$i - 2][1] == "phpnamespace" || $tokens[$i - 2][1] == "namespace")) || ($dlm && $tokens[$i - 1][0] == T_NS_SEPARATOR && $tokens[$i][0] == T_STRING)) {
                if (!$dlm) {
                    $namespace = null;
                }
                //echo json_encode($tokens[$i], JSON_PRETTY_PRINT);
                //echo $tokens[$i][1] . PHP_EOL;
                if (isset($tokens[$i][1]) && $namespace !== null) {
                    $namespace = $namespace ? trim($namespace . "\\" . $tokens[$i][1]) : trim($tokens[$i][1]);
                    echo $namespace;
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

    /**
     * @param string $phpCode
     * @return array
     */
    public function getDefinedPhpClasses(string $phpCode): array
    {
        $classList = [];
        $tokenList = token_get_all($phpCode);
        $namespace = $this->getNameSpaceFromToken($tokenList);
        $classNameList = $this->getClassNameFromFile($tokenList);
        foreach ($classNameList as $className) {
            $classList[] = $namespace . '\\' . $className;
        }
        return $classList;
    }


    /**
     * @param array $tokenList
     * @return string|null
     */
    private function getNameSpaceFromToken(array $tokenList): ?string
    {
        $i = 0;
        $namespace = '';
        $namespace_ok = false;
        for ($i = 0; $i < count($tokenList); $i++) {
            $token = $tokenList[$i];
            if (is_array($token) && $token[0] === T_NAMESPACE) {
                // Found namespace declaration
                while (++$i < count($tokenList)) {
                    if ($tokenList[$i] === ';') {
                        $namespace_ok = true;
                        $namespace = trim($namespace);
                        break;
                    }
                    $namespace .= is_array($tokenList[$i]) ? $tokenList[$i][1] : $tokenList[$i];
                }
                break;
            }
            $i++;
        }
        return $namespace_ok ? $namespace : null;
    }


    /**
     * @param array $tokenList
     * @return array
     */
    private function getClassNameFromFile(array $tokenList): array
    {
        $classList = [];
        $count = count($tokenList);
        for ($i = 2; $i < $count; $i++) {
            if ($tokenList[$i - 2][0] == T_CLASS && $tokenList[$i - 1][0] == T_WHITESPACE && $tokenList[$i][0] == T_STRING) {
                $classList[] = $tokenList[$i][1];
            }
        }

        return $classList;
    }


    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

}