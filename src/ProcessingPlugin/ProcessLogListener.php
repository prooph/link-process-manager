<?php
/*
* This file is part of prooph/link.
 * (c) prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 21.01.15 - 16:40
 */

namespace Prooph\Link\ProcessManager\ProcessingPlugin;

use Prooph\Common\Event\ActionEvent;
use Prooph\Processing\Environment\Environment;
use Prooph\Processing\Environment\Plugin;
use Prooph\Processing\Processor\ProcessId;
use Prooph\EventStore\PersistenceEvent\PostCommitEvent;

/**
 * Class ProcessLogListener
 *
 * This class is a Prooph\Processing\Environment\Plugin and acts as a listener for workflow processor events and process events
 * which are persisted by the event store. Main goal is to populate a read model with status information about triggered
 * processes.
 *
 * @package Prooph\Link\ProcessManager\ProcessingPlugin
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class ProcessLogListener implements Plugin
{
    const PLUGIN_NAME = 'prooph.link.pm.process_log_listener';

    /**
     * @var ProcessLogger
     */
    private $processLogger;

    /**
     * @param ProcessLogger $processLogger
     */
    public function __construct(ProcessLogger $processLogger)
    {
        $this->processLogger = $processLogger;
    }

    /**
     * Return the name of the plugin
     *
     * @return string
     */
    public function getName()
    {
        return self::PLUGIN_NAME;
    }

    /**
     * Register the plugin on the workflow environment
     *
     * @param Environment $workflowEnv
     * @return void
     */
    public function registerOn(Environment $workflowEnv)
    {
        $workflowEnv->getWorkflowProcessor()->events()->attachListener('process_was_started_by_message', [$this, 'onProcessWasStartedByMessage']);
        $workflowEnv->getWorkflowProcessor()->events()->attachListener('process_did_finish', [$this, 'onProcessDidFinish']);
        $workflowEnv->getEventStore()->getActionEventDispatcher()->attachListener('commit.post', [$this, 'onEventStorePostCommit']);
    }

    /**
     * @param ActionEvent $e
     */
    public function onProcessWasStartedByMessage(ActionEvent $e)
    {
        $this->processLogger->logProcessStartedByMessage(
            ProcessId::fromString($e->getParam('process_id')),
            $e->getParam('message_name')
        );
    }

    /**
     * @param PostCommitEvent $e
     */
    public function onEventStorePostCommit(PostCommitEvent $e)
    {
        foreach ($e->getRecordedEvents() as $recordedEvent) {
            if ($recordedEvent->messageName() === 'Prooph\Processing\Processor\Event\ProcessWasSetUp') {
                $this->processLogger->logProcessStartedAt(
                    ProcessId::fromString($recordedEvent->metadata()['aggregate_id']),
                    $recordedEvent->createdAt()
                );
            }
        }
    }

    /**
     * @param ActionEvent $e
     */
    public function onProcessDidFinish(ActionEvent $e)
    {
        if ($e->getParam('succeed')) {
            $this->processLogger->logProcessSucceed(
                ProcessId::fromString($e->getParam('process_id')),
                \DateTimeImmutable::createFromFormat(\DateTime::ISO8601, $e->getParam('finished_at'))
            );
        } else {
            $this->processLogger->logProcessFailed(
                ProcessId::fromString($e->getParam('process_id')),
                \DateTimeImmutable::createFromFormat(\DateTime::ISO8601, $e->getParam('finished_at'))
            );
        }
    }
}
 