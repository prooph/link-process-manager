<?php
/*
* This file is part of prooph/link.
 * (c) prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 06.12.14 - 22:26
 */
return array(
    'router' => [
        'routes' => [
            'prooph.link' => [
                'child_routes' => [
                    'process_config' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/process-config',
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'configurator' => [
                                'type' => 'Literal',
                                'options' => [
                                    'route' => '/process-manager',
                                    'defaults' => [
                                        'controller' => 'Prooph\Link\ProcessManager\Controller\ProcessManager',
                                        'action' => 'start-app'
                                    ]
                                ]
                            ],
                            'configurator-test' => [
                                'type' => 'Literal',
                                'options' => [
                                    'route' => '/process-manager-test',
                                    'defaults' => [
                                        'controller' => 'Prooph\Link\ProcessManager\Controller\ProcessManager',
                                        'action' => 'start-test-app'
                                    ]
                                ]
                            ],
                            'api' => [
                                'type' => 'Literal',
                                'options' => [
                                    'route' => '/api',
                                ],
                                'may_terminate' => true,
                                'child_routes' => [
                                    'workflow' => [
                                        'type' => 'Segment',
                                        'options' => [
                                            'route' => '/workflows[/:id]',
                                            'constraints' => array(
                                                'id' => '.+',
                                            ),
                                            'defaults' => [
                                                'controller' => \Prooph\Link\ProcessManager\Api\Workflow::class,
                                            ]
                                        ]
                                    ],
                                    'message_handler' => [
                                        'type' => 'Segment',
                                        'options' => [
                                            'route' => '/message-handlers[/:id]',
                                            'constraints' => array(
                                                'id' => '.+',
                                            ),
                                            'defaults' => [
                                                'controller' => \Prooph\Link\ProcessManager\Api\MessageHandler::class,
                                            ]
                                        ]
                                    ],
                                    'task' => [
                                        'type' => 'Segment',
                                        'options' => [
                                            'route' => '/tasks[/:id]',
                                            'constraints' => array(
                                                'id' => '.+',
                                            ),
                                            'defaults' => [
                                                'controller' => \Prooph\Link\ProcessManager\Api\Task::class,
                                            ]
                                        ]
                                    ],
                                    'connection' => [
                                        'type' => 'Literal',
                                        'options' => [
                                            'route' => '/connections',
                                            'defaults' => [
                                                'controller' => \Prooph\Link\ProcessManager\Api\Connection::class,
                                            ]
                                        ]
                                    ],
                                    'flowchart' => [
                                        'type' => 'Segment',
                                        'options' => [
                                            'route' => '/flowcharts[/:id]',
                                            'constraints' => array(
                                                'id' => '.+',
                                            ),
                                            'defaults' => [
                                                'controller' => \Prooph\Link\ProcessManager\Api\Flowchart::class,
                                            ]
                                        ]
                                    ],
                                    'workflow_release' => [
                                        'type' => 'Segment',
                                        'options' => [
                                            'route' => '/workflow-releases[/:id]',
                                            'constraints' => array(
                                                'id' => '.+',
                                            ),
                                            'defaults' => [
                                                'controller' => \Prooph\Link\ProcessManager\Api\WorkflowRelease::class,
                                            ]
                                        ]
                                    ],
                                    'process' => [
                                        'type' => 'Segment',
                                        'options' => [
                                            'route' => '/processes[/:id]',
                                            'constraints' => array(
                                                'id' => '.+',
                                            ),
                                            'defaults' => [
                                                'controller' => 'Prooph\Link\ProcessManager\Api\Process',
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],
                    ]
                ],
            ],
        ],
    ],
    'prooph.link.dashboard' => [
        'process_config_widget' => [
            'controller' => 'Prooph\Link\ProcessManager\Controller\DashboardWidget',
            'order' => 100 //100 - 200 config order range
        ]
    ],
    'view_manager' => array(
        'template_map' => [
            'prooph.link.process-manager/dashboard/widget' => __DIR__ . '/../view/process-config/dashboard/widget.phtml',
            'prooph.link.process-manager/process-manager/app' => __DIR__ . '/../view/process-config/process-manager/app.phtml',
            'prooph.link.process-manager/process-manager/app-test' => __DIR__ . '/../view/process-config/process-manager/app-test.phtml',
            //Partials for ProcessManager
            'prooph.link.process-manager/process-manager/partial/sidebar-left'     => __DIR__ . '/../view/process-config/process-manager/partial/sidebar-left.phtml',
            //riot tags
            'prooph.link.process-manager/process-manager/riot-tag/process-manager'    => __DIR__ . '/../view/process-config/process-manager/riot-tag/process-manager.phtml',
            'prooph.link.process-manager/process-manager/riot-tag/process-sidebar'    => __DIR__ . '/../view/process-config/process-manager/riot-tag/process-sidebar.phtml',
            'prooph.link.process-manager/process-manager/riot-tag/process-common-sidebar'     => __DIR__ . '/../view/process-config/process-manager/riot-tag/process-common-sidebar.phtml',
            'prooph.link.process-manager/process-manager/riot-tag/process-flowchart-sidebar'  => __DIR__ . '/../view/process-config/process-manager/riot-tag/process-flowchart-sidebar.phtml',
            'prooph.link.process-manager/process-manager/riot-tag/process-flowchart'  => __DIR__ . '/../view/process-config/process-manager/riot-tag/process-flowchart.phtml',
            'prooph.link.process-manager/process-manager/riot-tag/workflow-list'      => __DIR__ . '/../view/process-config/process-manager/riot-tag/workflow-list.phtml',
            'prooph.link.process-manager/process-manager/riot-tag/process-create'     => __DIR__ . '/../view/process-config/process-manager/riot-tag/process-create.phtml',
            'prooph.link.process-manager/process-manager/riot-tag/process-tasklist'   => __DIR__ . '/../view/process-config/process-manager/riot-tag/process-tasklist.phtml',
            'prooph.link.process-manager/process-manager/riot-tag/task-edit'          => __DIR__ . '/../view/process-config/process-manager/riot-tag/task-edit.phtml',
            'prooph.link.process-manager/process-manager/riot-tag/task-desc'          => __DIR__ . '/../view/process-config/process-manager/riot-tag/task-desc.phtml',
            'prooph.link.process-manager/process-manager/riot-tag/process-name'       => __DIR__ . '/../view/process-config/process-manager/riot-tag/process-name.phtml',
            'prooph.link.process-manager/process-manager/riot-tag/process-play'       => __DIR__ . '/../view/process-config/process-manager/riot-tag/process-play.phtml',
        ],
    ),
    'asset_manager' => array(
        'resolver_configs' => array(
            'map' => array(
                'css/link_pm_flowchart.css' => __DIR__ . '/../public/css/link_pm_flowchart.css',
                'js/jquery.jsPlumb.min.js'  => __DIR__ . '/../public/js/jquery.jsPlumb.min.js',
                'js/jquery-ui.min.js'  => __DIR__ . '/../public/js/jquery-ui.min.js',
                'img/paper_white.jpg'  => __DIR__ . '/../public/img/paper_white.jpg',
                'img/paper_blue.jpg'  => __DIR__ . '/../public/img/paper_blue.jpg',
            ),
            'riot-tags' => [
                'js/prooph/link/process-config/app.js' => [
                    'prooph.link.process-manager/process-manager/riot-tag/process-manager',
                    'prooph.link.process-manager/process-manager/riot-tag/process-sidebar',
                    'prooph.link.process-manager/process-manager/riot-tag/process-common-sidebar',
                    'prooph.link.process-manager/process-manager/riot-tag/process-flowchart-sidebar',
                    'prooph.link.process-manager/process-manager/riot-tag/process-flowchart',
                    'prooph.link.process-manager/process-manager/riot-tag/workflow-list',
                    'prooph.link.process-manager/process-manager/riot-tag/process-create',
                    'prooph.link.process-manager/process-manager/riot-tag/process-tasklist',
                    'prooph.link.process-manager/process-manager/riot-tag/task-edit',
                    'prooph.link.process-manager/process-manager/riot-tag/task-desc',
                    'prooph.link.process-manager/process-manager/riot-tag/process-name',
                    'prooph.link.process-manager/process-manager/riot-tag/process-play',
                ]
            ],
            'paths' => array(
                __DIR__ . '/../public',
            ),
        ),
    ),
    'service_manager' => [
        'factories' => [
            \Prooph\Link\ProcessManager\Model\Workflow\CreateWorkflowWithNameHandler::class => \Prooph\Link\ProcessManager\Infrastructure\Factory\CreateWorkflowWithNameHandlerFactory::class,
            \Prooph\Link\ProcessManager\Model\Workflow\ChangeWorkflowNameHandler::class => \Prooph\Link\ProcessManager\Infrastructure\Factory\ChangeWorkflowNameHandlerFactory::class,
            \Prooph\Link\ProcessManager\Model\Workflow\ScheduleFirstTasksForWorkflowHandler::class => \Prooph\Link\ProcessManager\Infrastructure\Factory\ScheduleFirstTasksForWorkflowHandlerFactory::class,
            \Prooph\Link\ProcessManager\Model\Workflow\ScheduleNextTasksForWorkflowHandler::class => \Prooph\Link\ProcessManager\Infrastructure\Factory\ScheduleNextTasksForWorkflowHandlerFactory::class,
            \Prooph\Link\ProcessManager\Model\MessageHandler\InstallMessageHandlerHandler::class => \Prooph\Link\ProcessManager\Infrastructure\Factory\InstallMessageHandlerHandlerFactory::class,
            \Prooph\Link\ProcessManager\Model\Task\UpdateTaskMetadataHandler::class => \Prooph\Link\ProcessManager\Infrastructure\Factory\UpdateTaskMetadataHandlerFactory::class,
            'prooph.link.pm.workflow_collection' => \Prooph\Link\ProcessManager\Infrastructure\Factory\WorkflowCollectionFactory::class,
            'prooph.link.pm.message_handler_collection' => \Prooph\Link\ProcessManager\Infrastructure\Factory\MessageHandlerCollectionFactory::class,
            'prooph.link.pm.task_collection' => \Prooph\Link\ProcessManager\Infrastructure\Factory\TaskCollectionFactory::class,
            'prooph.link.pm.local_processing_node' => \Prooph\Link\ProcessManager\Infrastructure\Factory\LocalProcessingNodeFactory::class,
        ],
        'invokables' => [
            'prooph.link.pm.processing_node_list' => \Prooph\Link\ProcessManager\Model\ProcessingNodeList::class,
            'prooph.link.pm.flowchart_store' => \Prooph\Link\ProcessManager\Infrastructure\FlowchartStore::class,
            \Prooph\Link\ProcessManager\Projection\Workflow\WorkflowProjector::class => \Prooph\Link\ProcessManager\Projection\Workflow\WorkflowProjector::class,
            \Prooph\Link\ProcessManager\Projection\Workflow\WorkflowFinder::class => \Prooph\Link\ProcessManager\Projection\Workflow\WorkflowFinder::class,
            \Prooph\Link\ProcessManager\Projection\MessageHandler\MessageHandlerProjector::class => \Prooph\Link\ProcessManager\Projection\MessageHandler\MessageHandlerProjector::class,
            \Prooph\Link\ProcessManager\Projection\MessageHandler\MessageHandlerFinder::class => \Prooph\Link\ProcessManager\Projection\MessageHandler\MessageHandlerFinder::class,
            \Prooph\Link\ProcessManager\Projection\Task\TaskProjector::class => \Prooph\Link\ProcessManager\Projection\Task\TaskProjector::class,
            \Prooph\Link\ProcessManager\Projection\Task\TaskFinder::class => \Prooph\Link\ProcessManager\Projection\Task\TaskFinder::class,
            \Prooph\Link\ProcessManager\Projection\Process\ProcessProjector::class => \Prooph\Link\ProcessManager\Projection\Process\ProcessProjector::class,
        ]
    ],
    'controllers' => array(
        'invokables' => [
            \Prooph\Link\ProcessManager\Api\Connection::class => \Prooph\Link\ProcessManager\Api\Connection::class,
        ],
        'factories' => array(
            'Prooph\Link\ProcessManager\Controller\DashboardWidget' => \Prooph\Link\ProcessManager\Controller\Factory\DashboardWidgetControllerFactory::class,
            'Prooph\Link\ProcessManager\Controller\ProcessManager' => \Prooph\Link\ProcessManager\Controller\Factory\ProcessManagerControllerFactory::class,
            'Prooph\Link\ProcessManager\Api\Process' => \Prooph\Link\ProcessManager\Api\Factory\ProcessFactory::class,
            \Prooph\Link\ProcessManager\Api\Workflow::class => \Prooph\Link\ProcessManager\Api\Factory\WorkflowFactory::class,
            \Prooph\Link\ProcessManager\Api\Flowchart::class => \Prooph\Link\ProcessManager\Api\Factory\FlowchartFactory::class,
            \Prooph\Link\ProcessManager\Api\MessageHandler::class => \Prooph\Link\ProcessManager\Api\Factory\MessageHandlerFactory::class,
            \Prooph\Link\ProcessManager\Api\Task::class => \Prooph\Link\ProcessManager\Api\Factory\TaskFactory::class,
            \Prooph\Link\ProcessManager\Api\WorkflowRelease::class => \Prooph\Link\ProcessManager\Api\Factory\WorkflowReleaseFactory::class,
        ),
    ),
    'prooph.psb' => [
        'command_router_map' => [
            \Prooph\Link\ProcessManager\Command\Workflow\CreateWorkflowWithName::class => \Prooph\Link\ProcessManager\Model\Workflow\CreateWorkflowWithNameHandler::class,
            \Prooph\Link\ProcessManager\Command\Workflow\ChangeWorkflowName::class => \Prooph\Link\ProcessManager\Model\Workflow\ChangeWorkflowNameHandler::class,
            \Prooph\Link\ProcessManager\Command\MessageHandler\InstallMessageHandler::class => \Prooph\Link\ProcessManager\Model\MessageHandler\InstallMessageHandlerHandler::class,
            \Prooph\Link\ProcessManager\Command\Workflow\ScheduleFirstTasksForWorkflow::class => \Prooph\Link\ProcessManager\Model\Workflow\ScheduleFirstTasksForWorkflowHandler::class,
            \Prooph\Link\ProcessManager\Command\Workflow\ScheduleNextTasksForWorkflow::class => \Prooph\Link\ProcessManager\Model\Workflow\ScheduleNextTasksForWorkflowHandler::class,
            \Prooph\Link\ProcessManager\Command\Task\UpdateTaskMetadata::class => \Prooph\Link\ProcessManager\Model\Task\UpdateTaskMetadataHandler::class,
        ],
        'event_router_map' => [
            \Prooph\Link\ProcessManager\Model\Workflow\WorkflowWasCreated::class => [
                \Prooph\Link\ProcessManager\Projection\Workflow\WorkflowProjector::class,
            ],
            \Prooph\Link\ProcessManager\Model\Workflow\WorkflowNameWasChanged::class => [
                \Prooph\Link\ProcessManager\Projection\Workflow\WorkflowProjector::class
            ],
            \Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerWasInstalled::class => [
                \Prooph\Link\ProcessManager\Projection\MessageHandler\MessageHandlerProjector::class
            ],
            \Prooph\Link\ProcessManager\Model\Workflow\StartMessageWasAssignedToWorkflow::class => [
                \Prooph\Link\ProcessManager\Projection\Workflow\WorkflowProjector::class,
            ],
            \Prooph\Link\ProcessManager\Model\Task\TaskWasSetUp::class => [
                \Prooph\Link\ProcessManager\Projection\Task\TaskProjector::class
            ],
            \Prooph\Link\ProcessManager\Model\Workflow\TaskWasAddedToProcess::class => [
                \Prooph\Link\ProcessManager\Projection\Task\TaskProjector::class
            ],
            \Prooph\Link\ProcessManager\Model\Workflow\ProcessWasAddedToWorkflow::class => [
                \Prooph\Link\ProcessManager\Projection\Process\ProcessProjector::class
            ],
            \Prooph\Link\ProcessManager\Model\Task\TaskMetadataWasUpdated::class => [
                \Prooph\Link\ProcessManager\Projection\Task\TaskProjector::class,
            ],
            \Prooph\Link\ProcessManager\Model\Workflow\WorkflowWasReleased::class => [
                \Prooph\Link\ProcessManager\Projection\Workflow\WorkflowProjector::class,
            ],
        ],
    ],
    'zf-content-negotiation' => [
        'controllers' => [
            'Prooph\Link\ProcessManager\Api\Process' => 'Json',
            \Prooph\Link\ProcessManager\Api\Workflow::class => 'Json',
            \Prooph\Link\ProcessManager\Api\MessageHandler::class => 'Json',
            \Prooph\Link\ProcessManager\Api\Task::class => 'Json',
            \Prooph\Link\ProcessManager\Api\Flowchart::class => 'Json',
            \Prooph\Link\ProcessManager\Api\WorkflowRelease::class => 'Json',
        ],
        'accept_whitelist' => [
            'Prooph\Link\ProcessManager\Api\Process' => ['application/json'],
            \Prooph\Link\ProcessManager\Api\Workflow::class => ['application/json'],
            \Prooph\Link\ProcessManager\Api\MessageHandler::class => ['application/json'],
            \Prooph\Link\ProcessManager\Api\Task::class => ['application/json'],
            \Prooph\Link\ProcessManager\Api\Flowchart::class => ['application/json'],
            \Prooph\Link\ProcessManager\Api\WorkflowRelease::class => ['application/json'],
        ],
        'content_type_whitelist' => [
            'Prooph\Link\ProcessManager\Api\Process' => ['application/json'],
            \Prooph\Link\ProcessManager\Api\Workflow::class => ['application/json'],
            \Prooph\Link\ProcessManager\Api\MessageHandler::class => ['application/json'],
            \Prooph\Link\ProcessManager\Api\Task::class => ['application/json'],
            \Prooph\Link\ProcessManager\Api\Flowchart::class => ['application/json'],
            \Prooph\Link\ProcessManager\Api\WorkflowRelease::class => ['application/json'],
        ],
    ],
);