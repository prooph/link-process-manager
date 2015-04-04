<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/4/15 - 9:58 PM
 */
namespace Prooph\Link\ProcessManager\Infrastructure\Schema;
use Doctrine\DBAL\Schema\Schema;

/**
 * Class SchemaManager
 *
 * @package Prooph\Link\ProcessManager\Infrastructure\Schema
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class SchemaManager 
{
    public static function createSchema(Schema $schema)
    {
        $processManagerStream = $schema->createTable('link_process_manager_stream');

        $processManagerStream->addColumn('eventId', 'string', ['length' => 36]);
        $processManagerStream->addColumn('version', 'integer');
        $processManagerStream->addColumn('eventName', 'string', ['length' => 100]);
        $processManagerStream->addColumn('payload', 'text');
        $processManagerStream->addColumn('occurredOn', 'string', ['length' => 100]);
        $processManagerStream->addColumn('aggregate_id', 'string', ['length' => 36]);
        $processManagerStream->addColumn('aggregate_type', 'string', ['length' => 100]);
        $processManagerStream->setPrimaryKey(['eventId']);
        $processManagerStream->addUniqueIndex(['aggregate_id', 'aggregate_type', 'version'], 'link_pm_metadata_version_uix');

        $workflow = $schema->createTable('link_pm_read_workflow');

        $workflow->addColumn('uuid', 'string', ['length' => 36]);
        $workflow->addColumn('name', 'string', ['length' => 255]);
        $workflow->setPrimaryKey(['uuid']);
    }

    public static function dropSchema(Schema $schema)
    {
        $schema->dropTable('link_process_manager_stream');
        $schema->dropTable('link_pm_read_workflow');
    }
} 