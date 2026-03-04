<?php

namespace Base\Module\Service\Migration\HlBlock;

interface MigrationHlBlockEntity
{
    /**
     * @return string
     * must begin with a capital letter and consist only of Latin letters and numbers.
     */
    public static function getName(): string;
    public static function getRuLangName(): string;
    public static function getTableName(): string;
}