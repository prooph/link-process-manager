<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/19/15 - 1:54 PM
 */
namespace Prooph\Link\ProcessManager\Infrastructure;

use Prooph\Link\Application\Command\AddConnectorToConfig;
use Prooph\Link\Application\Command\AddNewProcessToConfig;
use Prooph\Link\Application\Command\ChangeConnectorConfig;
use Prooph\Link\Application\Command\ChangeProcessConfig;
use Prooph\Link\Application\Command\RemoveProcessConfig;
use Prooph\Link\Application\Projection\ProcessingConfig;
use Prooph\Link\Application\SharedKernel\ConfigLocation;
use Prooph\Link\ProcessManager\Model\MessageHandler\Exception\MessageHandlerNotFound;
use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerCollection;
use Prooph\Link\ProcessManager\Model\MessageHandler;
use Prooph\Link\ProcessManager\Model\Task\Exception\TaskNotFound;
use Prooph\Link\ProcessManager\Model\Task\TaskCollection;
use Prooph\Link\ProcessManager\Model\Task\TaskId;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowPublisher;
use Prooph\Link\ProcessManager\Model\Workflow;
use Prooph\Processing\Message\MessageNameUtils;
use Prooph\ServiceBus\CommandBus;

/**
 * WorkflowPublisher WorkflowToProcessingConfigTranslator
 *
 * @package Prooph\Link\ProcessManager\Infrastructure
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class WorkflowToProcessingConfigTranslator implements WorkflowPublisher
{
    /**
     * @var TaskCollection
     */
    private $taskCollection;

    /**
     * @var MessageHandlerCollection
     */
    private $messageHandlerCollection;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var ConfigLocation
     */
    private $processingConfigLocation;

    /**
     * @var ProcessingConfig
     */
    private $processingConfig;

    /**
     * @param TaskCollection $taskCollection
     * @param MessageHandlerCollection $messageHandlerCollection
     * @param ProcessingConfig $processingConfig
     * @param CommandBus $commandBus
     * @param ConfigLocation $processingConfigLocation
     */
    public function __construct(
        TaskCollection $taskCollection,
        MessageHandlerCollection $messageHandlerCollection,
        ProcessingConfig $processingConfig,
        CommandBus $commandBus,
        ConfigLocation $processingConfigLocation)
    {
        $this->taskCollection = $taskCollection;
        $this->messageHandlerCollection = $messageHandlerCollection;
        $this->processingConfig = $processingConfig;
        $this->commandBus = $commandBus;
        $this->processingConfigLocation = $processingConfigLocation;
    }

    /**
     * @param Workflow $workflow
     * @throws \RuntimeException
     * @return void
     */
    public function writeToProcessingConfig(Workflow $workflow)
    {
        $processNum = count($workflow->processList());

        if ($processNum == 0) {
            $this->commandBus->dispatch(
                RemoveProcessConfig::ofProcessTriggeredByMessage($workflow->startMessage()->messageName(), $this->processingConfigLocation)
            );

            return;
        }

        $processDefinitions = $this->processingConfig->getProcessDefinitions();

        if ($processNum == 1) {
            $processConfig = $this->translateToProcessingProcess($workflow->processList()[0]);

            $processConfig['name'] = $workflow->name();

            if (isset($processDefinitions[$workflow->startMessage()->messageName()])) {
                $this->commandBus->dispatch(
                    ChangeProcessConfig::ofProcessTriggeredByMessage(
                        $workflow->startMessage()->messageName(),
                        $processConfig,
                        $this->processingConfigLocation
                    )
                );
            } else {
                $this->commandBus->dispatch(
                    AddNewProcessToConfig::fromDefinition(
                        $processConfig['name'],
                        $processConfig['process_type'],
                        $workflow->startMessage()->messageName(),
                        $processConfig['tasks'],
                        $this->processingConfigLocation
                    )
                );
            }
        } else {
            throw new \RuntimeException("Handling of more than process per workflow is not supported yet!");
        }
    }

    /**
     * @param Workflow\Process $process
     * @return array
     */
    private function translateToProcessingProcess(Workflow\Process $process)
    {
        $tasks = [];

        foreach ($process->tasks() as $taskId) {
            $tasks[] = $this->translateToProcessingTask($taskId);
        }

        return [
            'process_type' => $process->type()->toString(),
            'tasks' => $tasks
        ];
    }

    /**
     * @param TaskId $taskId
     * @return array
     * @throws MessageHandlerNotFound
     * @throws \RuntimeException
     * @throws TaskNotFound
     */
    private function translateToProcessingTask(TaskId $taskId)
    {
        $task = $this->taskCollection->get($taskId);

        if (is_null($task)) {
            throw TaskNotFound::withId($taskId);
        }

        $messageHandler = $this->messageHandlerCollection->get($task->messageHandlerId());

        if (is_null($messageHandler)) {
            throw MessageHandlerNotFound::withId($task->messageHandlerId());
        }

        $this->syncMessageHandler($messageHandler);

        $taskData = [
            'task_type' => $task->type()->toString(),
            'metadata'  => $task->metadata()->toArray(),
        ];

        if ($task->type()->isCollectData()) {
            $taskData['source'] = $messageHandler->processingId()->toString();
            $taskData['processing_type'] = $task->processingType()->of();
        } elseif ($task->type()->isProcessData()) {
            $taskData['target'] = $messageHandler->processingId()->toString();
            $taskData['allowed_types'] = [$task->processingType()->of()];
            $taskData['preferred_type'] = $task->processingType()->of();
        } else {
            throw new \RuntimeException("TaskType {$task->type()->toString()} is not supported yet");
        }

        return $taskData;
    }

    /**
     * @param MessageHandler $messageHandler
     */
    private function syncMessageHandler(MessageHandler $messageHandler)
    {
        $additionalData = $messageHandler->additionalData();

        if ($messageHandler->preferredProcessingType()) {
            $additionalData['preferred_type'] = $messageHandler->preferredProcessingType()->of();
        }

        $additionalData['node_name'] = $messageHandler->processingNodeName()->toString();
        $additionalData['icon'] = $messageHandler->icon();
        $additionalData['icon_type'] = $messageHandler->iconType();
        $additionalData['metadata'] = $messageHandler->processingMetadata()->toArray();
        $additionalData['ui_metadata_riot_tag'] = $messageHandler->metadataRiotTag();

        $allowedTypes = ($messageHandler->supportedProcessingTypes()->areAllTypesSupported())?
            MessageHandler\ProcessingTypes::SUPPORT_ALL : $messageHandler->supportedProcessingTypes()->typeList();


        if ($messageHandler->isKnownInProcessingSystem()) {
            $this->commandBus->dispatch(
                ChangeConnectorConfig::ofConnector(
                    $messageHandler->processingId()->toString(),
                    array_merge([
                        'name' => $messageHandler->name(),
                        'allowed_messages' => $this->determineAllowedMessages($messageHandler),
                        'allowed_types' => $allowedTypes,
                    ], $additionalData),
                    $this->processingConfigLocation
                )
            );
        } else {
            $this->commandBus->dispatch(
                AddConnectorToConfig::fromDefinition(
                    $messageHandler->processingId()->toString(),
                    $messageHandler->name(),
                    $this->determineAllowedMessages($messageHandler),
                    $allowedTypes,
                    $this->processingConfigLocation,
                    $additionalData
                )
            );
        }
    }

    /**
     * @param MessageHandler $messageHandler
     * @return array
     */
    private function determineAllowedMessages(MessageHandler $messageHandler)
    {
        $allowedMessages = [];

        if ($messageHandler->dataDirection()->isSource()) {
            $allowedMessages[] = MessageNameUtils::COLLECT_DATA;
        }

        if ($messageHandler->dataDirection()->isTarget()) {
            $allowedMessages[] = MessageNameUtils::PROCESS_DATA;
        }

        if ($messageHandler->handlerType()->isCallback()) {
            $allowedMessages[] = MessageNameUtils::DATA_COLLECTED;
            $allowedMessages[] = MessageNameUtils::DATA_PROCESSED;
        }

        return $allowedMessages;
    }
}