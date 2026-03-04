<?php

namespace Base\Module\Install;

use Base\Module\Exception\ModuleException;
use Base\Module\Install\Interface\Install;
use Base\Module\Install\Interface\ReInstall;
use Base\Module\Service\Container;
use Base\Module\Service\Migration\HlBlock\MigrationHlBlockEntity;
use Base\Module\Service\Migration\HlBlock\MigrationHlBlockService;
use Base\Module\Service\Tool\ClassList;

class HlBlocksInstaller implements Install, ReInstall
{
    /**
     * @return array
     * @throws ModuleException
     */
    private function getHlBlocksClasses(): array
    {
        /** @var ClassList $classList */
        $classList = Container::get(ClassList::SERVICE_CODE);
        return $classList->setSubClassesFilter([MigrationHlBlockEntity::class])->getFromLib('Migration');
    }

    /**
     * @return void
     * @throws ModuleException
     */
    public function install(): void
    {
        /** @var MigrationHlBlockService $userFieldService */
        $userFieldService = Container::get(MigrationHlBlockService::SERVICE_CODE);
        $userFieldService->setHlBlockEntities($this->getHlBlocksClasses())->install();
    }

    /**
     * @return void
     * @throws ModuleException
     */
    public function reInstall(): void
    {
        /** @var MigrationHlBlockService $userFieldService */
        $userFieldService = Container::get(MigrationHlBlockService::SERVICE_CODE);
        $userFieldService->setHlBlockEntities($this->getHlBlocksClasses())->reInstall();
    }

    public function getInstallSort(): int
    {
        return 130;
    }

    public function getReInstallSort(): int
    {
        return 130;
    }
}
