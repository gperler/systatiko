<?php

declare(strict_types=1);

namespace Systatiko\Reader;

use ReflectionClass;
use RuntimeException;

/**
 *
 */
class ExtendedReflectionClass extends ReflectionClass
{

    /**
     * Array of use statements for class.
     *
     * @var array|null
     */
    protected ?array $useStatementList = [];


    /**
     * @param $objectOrClass
     *
     * @throws \ReflectionException
     */
    public function __construct($objectOrClass)
    {
        parent::__construct($objectOrClass);
        $this->useStatementList = $this->parseUseStatementList();
    }


    /**
     * Return array of use statements from class.
     *
     * @return array
     */
    public function getUseStatementList(): array
    {
        return $this->useStatementList;
    }


    /**
     * Parse class file and get use statements from current namespace.
     *
     * @return string[]
     */
    protected function parseUseStatementList(): array
    {
        if (!$this->isUserDefined()) {
            throw new RuntimeException('Must parse use statements from user defined classes.');
        }

        return $this->tokenizeSource(
            file_get_contents($this->getFileName())
        );
    }


    /**
     * @param $source
     *
     * @return array
     */
    private function tokenizeSource($source): array
    {
        $tokenList = token_get_all($source);

        $useStatementList = [];

        $startBuilding = false;
        $asDefined = false;

        foreach ($tokenList as $token) {
            if ($token === ';' && $startBuilding) {
                $useStatementList[] = $currentUse;
                $startBuilding = false;
                $asDefined = false;
            }

            if (is_string($token)) {
                continue;
            }

            if ($token[0] === T_USE) {
                $startBuilding = true;
                $currentUse = [
                    'class' => null,
                    'as' => null
                ];
            }

            if ($startBuilding && $token[0] === T_NAME_QUALIFIED) {
                $currentUse['class'] = $token[1];
                $currentUse['as'] = $token[1];
            }

            if ($startBuilding && $token[0] === T_AS) {
                $asDefined = true;
            }

            if ($startBuilding && $asDefined && $token[0] === T_STRING) {
                $currentUse['as'] = $token[1];
            }

            if ($token[0] === T_CLASS) {
                break;
            }
        }

        return $useStatementList;
    }


}