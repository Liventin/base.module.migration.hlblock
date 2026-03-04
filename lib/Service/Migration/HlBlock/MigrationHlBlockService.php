<?php

namespace Base\Module\Service\Migration\HlBlock;

interface MigrationHlBlockService
{
    public const SERVICE_CODE = 'base.module.migration.hlblock.service';
    public function setHlBlockEntities(array $entities): self;
    public function install(): void;
    public function reInstall(): void;
}