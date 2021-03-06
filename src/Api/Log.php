<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 5/24/15 - 11:30 PM
 */
namespace Prooph\Link\ProcessManager\Api;

use Assert\Assertion;
use Prooph\Common\Messaging\DomainEvent;
use Prooph\Link\Application\Projection\ProcessingConfig;
use Prooph\Link\Application\Service\AbstractRestController;
use Prooph\Link\Application\SharedKernel\ProcessToClientTranslator;
use Prooph\Link\Application\SharedKernel\ScriptLocation;
use Prooph\Link\ProcessManager\Projection\Log\ProcessLogFinder;
use Prooph\Link\ProcessManager\Projection\Process\ProcessStreamReader;
use Prooph\Processing\Processor\Task\TaskListPosition;
use Verraes\ClassFunctions\ClassFunctions;

/**
 * Resource Log
 *
 * This resource represents a process log of a performed workflow.
 *
 * @package Prooph\Link\ProcessManager\Api
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class Log extends AbstractRestController
{
    /**
     * @var ProcessLogFinder
     */
    private $processLogFinder;

    /**
     * @var ProcessStreamReader
     */
    private $processStreamReader;

    /**
     * @var ProcessingConfig
     */
    private $systemConfig;

    /**
     * @var ScriptLocation
     */
    private $scriptLocation;

    /**
     * @param ProcessLogFinder $processLogFinder
     * @param ProcessStreamReader $processStreamReader
     * @param ProcessingConfig $processingConfig
     * @param ScriptLocation $scriptLocation
     */
    public function __construct(ProcessLogFinder $processLogFinder,
                                ProcessStreamReader $processStreamReader,
                                ProcessingConfig $processingConfig,
                                ScriptLocation $scriptLocation)
    {
        $this->processLogFinder = $processLogFinder;
        $this->processStreamReader = $processStreamReader;
        $this->systemConfig = $processingConfig;
        $this->scriptLocation = $scriptLocation;
    }

    /**
     * @param mixed $id
     * @return array|mixed
     */
    public function get($id)
    {
        $processLog = $this->processLogFinder->getLoggedProcess($id);

        if ($processLog === null) {
            return $this->notFoundAction();
        }

        $processLog['events'] = $this->convertToClientProcessEvents(
            $this->processStreamReader->getStreamOfProcess($id)
        );

        $processDefinitions = $this->systemConfig->getProcessDefinitions();

        if (! isset($processDefinitions[$processLog['start_message']])) {
            //@TODO: Provide better error, so that the client can show the user a message that config is missing
            return $this->notFoundAction();
        }

        $processDefinition = $processDefinitions[$processLog['start_message']];


        $processDefinition = ProcessToClientTranslator::translate(
            $processLog['start_message'],
            $processDefinition,
            $this->systemConfig->getAllAvailableProcessingTypes(),
            $this->scriptLocation
        );

        $processLog['tasks'] = $processDefinition['tasks'];

        $this->populateTaskEvents($processLog);

        return ['log' => $processLog];
    }

    /**
     * @return array|\ZF\ApiProblem\ApiProblemResponse
     */
    public function getList()
    {
        $startMessage = $this->getRequest()->getQuery('start_message', null);

        if ($startMessage === null) {
            return $this->apiProblem(400, 'Missing required filter start_message');
        }

        Assertion::string($startMessage);
        Assertion::notEmpty($startMessage);

        $logs = $this->processLogFinder->getLogsTriggeredBy($startMessage);

        return ['logs' => $logs];
    }

    /**
     * @param DomainEvent[] $streamEvents
     * @return array
     */
    private function convertToClientProcessEvents(array $streamEvents)
    {
        $clientEvents = [];

        foreach ($streamEvents as $streamEvent) {
            $clientEvent = [
                'name' => ClassFunctions::short($streamEvent->messageName()),
                'process_id' => $streamEvent->metadata()['aggregate_id'],
                'payload' => $streamEvent->payload(),
                'occurred_on' => $streamEvent->createdAt()->format(\DateTime::ISO8601),
            ];

            $taskListPosition = null;

            if (isset($clientEvent['payload']['taskListPosition'])) {
                $taskListPosition = TaskListPosition::fromString($clientEvent['payload']['taskListPosition']);
            }

            $clientEvent['task_list_id'] = ($taskListPosition)? $taskListPosition->taskListId()->toString() : null;
            $clientEvent['task_list_position'] = ($taskListPosition)? $taskListPosition->position() : null;
            $clientEvent['client_task_id'] = ($taskListPosition)? $taskListPosition->position() - 1 : null;
            $clientEvents[] = $clientEvent;
        }

        return $clientEvents;
    }

    /**
     * Copy each task event to its task
     *
     * @param array $processLog
     */
    private function populateTaskEvents(array &$processLog)
    {
        foreach ($processLog['tasks'] as &$task) {
            $task['events'] = [];
            foreach ($processLog['events'] as $event) {
                if ($event['client_task_id'] == $task['id']) {
                    $task['events'][] = $event;
                }
            }
        }
    }
}