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
use Prooph\Link\Application\Service\AbstractRestController;
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
     * @param ProcessLogFinder $processLogFinder
     * @param ProcessStreamReader $processStreamReader
     */
    public function __construct(ProcessLogFinder $processLogFinder, ProcessStreamReader $processStreamReader)
    {
        $this->processLogFinder = $processLogFinder;
        $this->processStreamReader = $processStreamReader;
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

            $clientEvents[] = $clientEvent;
        }

        return $clientEvents;
    }
}