<?php

/** @noinspection PhpUnused */

namespace Base\Module\Src\Migration\HlBlock;

use Base\Module\Exception\ModuleException;
use Base\Module\Service\LazyService;
use Base\Module\Service\Migration\HlBlock\MigrationHlBlockEntity;
use Base\Module\Service\Migration\HlBlock\MigrationHlBlockService as IMigrateHlBlockService;
use Bitrix\Highloadblock\HighloadBlockLangTable;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Query\Query;
use Bitrix\Main\SystemException;
use Exception;

#[LazyService(serviceCode: IMigrateHlBlockService::SERVICE_CODE, constructorParams: [])]
class MigrateHlBlockService implements IMigrateHlBlockService
{
    /** @var MigrationHlBlockEntity[] */
    private array $hlBlockEntities = [];

    public function setHlBlockEntities(array $entities): self
    {
        $this->hlBlockEntities = $entities;
        return $this;
    }

    /**
     * @return void
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ModuleException
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws Exception
     */
    public function install(): void
    {
        Loader::requireModule('highloadblock');

        $existsHlBlocks = $this->getExistsHlBlocks();

        foreach ($this->hlBlockEntities as $entity) {
            if (in_array($entity::getTableName(), $existsHlBlocks, true)) {
                continue;
            }

            $addResult = HighloadBlockTable::add([
                'NAME' => $entity::getName(),
                'TABLE_NAME' => $entity::getTableName(),
            ]);

            if (!$addResult->isSuccess()) {
                throw new ModuleException(
                    'error add HL block: ' .
                    implode('; ', $addResult->getErrorMessages())
                );
            }

            $hlId = $addResult->getId();

            $addResult = HighloadBlockLangTable::add([
                'ID' => $hlId,
                'LID' => 'ru',
                'NAME' => $entity::getRuLangName(),
            ]);

            if (!$addResult->isSuccess()) {
                throw new ModuleException(
                    'error add HL block Ru Lang name: ' .
                    implode('; ', $addResult->getErrorMessages())
                );
            }
        }
    }

    /**
     * @throws LoaderException
     * @throws ModuleException
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function reInstall(): void
    {
        $this->install();
    }

    /**
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function getExistsHlBlocks(): array
    {
        /** @var Query $query */
        $query = HighloadBlockTable::query();

        return array_column(
            $query
                ->addSelect('TABLE_NAME')
                ->fetchAll(),
            'TABLE_NAME'
        );
    }
}