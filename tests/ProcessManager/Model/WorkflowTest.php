<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/5/15 - 7:50 PM
 */
namespace ProophTest\Link\ProcessManager\Model;

use Prooph\Link\Application\SharedKernel\MessageMetadata;
use Prooph\Link\ProcessManager\Model\MessageHandler;
use Prooph\Link\ProcessManager\Model\ProcessingMetadata;
use Prooph\Link\ProcessManager\Model\Task;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowId;
use Prooph\Link\ProcessManager\Model\Workflow;
use Prooph\Processing\Processor\NodeName;
use ProophTest\Link\ProcessManager\Mock\ProcessingType\ArticleCollection;
use ProophTest\Link\ProcessManager\TestCase;

final class WorkflowTest extends TestCase
{
    /**
     * @test
     */
    function it_is_created_with_a_workflow_id_and_a_name()
    {
        $nodeName = NodeName::defaultName();
        $workflowId = WorkflowId::generate();

        $workflow = Workflow::locatedOn($nodeName, $workflowId, 'Article Export');

        $this->assertTrue($nodeName->equals($workflow->processingNodeName()));
        $this->assertTrue($workflowId->equals($workflow->workflowId()));
        $this->assertEquals('Article Export', $workflow->name());
    }

    /**
     * @test
     * @dataProvider provideStartScenarios
     */
    function it_determines_one_start_task_managed_by_one_process_as_long_as_message_handler_is_located_on_the_same_processing_node(
        Workflow\Message $startMessage,
        MessageHandler $firstMessageHandler,
        ProcessingMetadata $taskMetadata,
        $expectedTaskType,
        $expectedProcessType
    ) {
        $workflow = Workflow::locatedOn(NodeName::defaultName(), WorkflowId::generate(), 'Article Export');

        //Pop the WorkflowWasCreatedEvent
        $this->extractRecordedEvents($workflow);

        $tasks = $workflow->determineFirstTasks($startMessage, $firstMessageHandler, $taskMetadata);

        $this->assertEquals(1, count($tasks));
        $this->assertInstanceOf(Task::class, $tasks[0]);
        $this->assertEquals($expectedTaskType, $tasks[0]->type()->toString());
        $this->assertTrue($firstMessageHandler->messageHandlerId()->equals($tasks[0]->messageHandlerId()));

        $domainEvents = $this->extractRecordedEvents($workflow);

        $this->assertEquals(3, count($domainEvents));
        $this->assertInstanceOf(Workflow\StartMessageWasAssignedToWorkflow::class, $domainEvents[0]);
        $this->assertInstanceOf(Workflow\ProcessWasAddedToWorkflow::class, $domainEvents[1]);
        $this->assertInstanceOf(Workflow\TaskWasAddedToProcess::class, $domainEvents[2]);

        foreach ($domainEvents as $domainEvent) {
            if ($domainEvent instanceof Workflow\ProcessWasAddedToWorkflow) {
                $this->assertEquals($expectedProcessType, $domainEvent->processType()->toString());
            }
        }
    }

    function provideStartScenarios()
    {
        return [
            //Linear collect data
            [
                Workflow\Message::emulateProcessingWorkflowMessage(
                    Workflow\MessageType::collectData(),
                    ArticleCollection::prototype(),
                    ProcessingMetadata::noData()
                ),
                $this->getArticleExporterMessageHandler(),
                ProcessingMetadata::noData(),
                Task\TaskType::TYPE_COLLECT_DATA,
                Workflow\ProcessType::TYPE_LINEAR_MESSAGING
            ],
            //Linear process data
            [
                Workflow\Message::emulateProcessingWorkflowMessage(
                    Workflow\MessageType::dataProcessed(),
                    ArticleCollection::prototype(),
                    ProcessingMetadata::noData()
                ),
                $this->getArticleImporterMessageHandler(),
                ProcessingMetadata::noData(),
                Task\TaskType::TYPE_PROCESS_DATA,
                Workflow\ProcessType::TYPE_LINEAR_MESSAGING
            ],
            //Foreach collect data
            [
                Workflow\Message::emulateProcessingWorkflowMessage(
                    Workflow\MessageType::collectData(),
                    ArticleCollection::prototype(),
                    ProcessingMetadata::noData()
                ),
                $this->getArticleExporterMessageHandler(true),
                ProcessingMetadata::noData(),
                Task\TaskType::TYPE_COLLECT_DATA,
                Workflow\ProcessType::TYPE_PARALLEL_FOREACH
            ],
            //Foreach process data
            [
                Workflow\Message::emulateProcessingWorkflowMessage(
                    Workflow\MessageType::dataProcessed(),
                    ArticleCollection::prototype(),
                    ProcessingMetadata::noData()
                ),
                $this->getArticleImporterMessageHandler(true),
                ProcessingMetadata::noData(),
                Task\TaskType::TYPE_PROCESS_DATA,
                Workflow\ProcessType::TYPE_PARALLEL_FOREACH
            ],
            //Chunk collect data defined by message metadata
            [
                Workflow\Message::emulateProcessingWorkflowMessage(
                    Workflow\MessageType::collectData(),
                    ArticleCollection::prototype(),
                    ProcessingMetadata::fromArray([MessageMetadata::LIMIT => 100])
                ),
                $this->getArticleExporterMessageHandler(),
                ProcessingMetadata::noData(),
                Task\TaskType::TYPE_COLLECT_DATA,
                Workflow\ProcessType::TYPE_PARALLEL_CHUNK
            ],
            //Chunk collect data defined by task metadata
            [
                Workflow\Message::emulateProcessingWorkflowMessage(
                    Workflow\MessageType::collectData(),
                    ArticleCollection::prototype(),
                    ProcessingMetadata::noData()
                ),
                $this->getArticleExporterMessageHandler(),
                ProcessingMetadata::fromArray([MessageMetadata::LIMIT => 100]),
                Task\TaskType::TYPE_COLLECT_DATA,
                Workflow\ProcessType::TYPE_PARALLEL_CHUNK
            ],
            //Chunk process data defined by message metadata
            [
                Workflow\Message::emulateProcessingWorkflowMessage(
                    Workflow\MessageType::dataCollected(),
                    ArticleCollection::prototype(),
                    ProcessingMetadata::fromArray([MessageMetadata::LIMIT => 100])
                ),
                $this->getArticleImporterMessageHandler(),
                ProcessingMetadata::noData(),
                Task\TaskType::TYPE_PROCESS_DATA,
                Workflow\ProcessType::TYPE_PARALLEL_CHUNK
            ],
            //Chunk collect data defined by task metadata
            [
                Workflow\Message::emulateProcessingWorkflowMessage(
                    Workflow\MessageType::dataCollected(),
                    ArticleCollection::prototype(),
                    ProcessingMetadata::noData()
                ),
                $this->getArticleImporterMessageHandler(),
                ProcessingMetadata::fromArray([MessageMetadata::LIMIT => 100]),
                Task\TaskType::TYPE_PROCESS_DATA,
                Workflow\ProcessType::TYPE_PARALLEL_CHUNK
            ],
        ];
    }
} 