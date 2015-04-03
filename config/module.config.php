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
            'prooph.link.process-manager/process-manager/riot-tag/process-list'       => __DIR__ . '/../view/process-config/process-manager/riot-tag/process-list.phtml',
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
                    'prooph.link.process-manager/process-manager/riot-tag/process-list',
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
    'controllers' => array(
        'factories' => array(
            'Prooph\Link\ProcessManager\Controller\DashboardWidget' => \Prooph\Link\ProcessManager\Controller\Factory\DashboardWidgetControllerFactory::class,
            'Prooph\Link\ProcessManager\Controller\ProcessManager' => \Prooph\Link\ProcessManager\Controller\Factory\ProcessManagerControllerFactory::class,
            'Prooph\Link\ProcessManager\Api\Process' => \Prooph\Link\ProcessManager\Api\Factory\ProcessFactory::class,
        ),
    ),
    'zf-content-negotiation' => [
        'controllers' => [
            'Prooph\Link\ProcessManager\Api\Process' => 'Json',
        ],
        'accept_whitelist' => [
            'Prooph\Link\ProcessManager\Api\Process' => ['application/json'],
        ],
        'content_type_whitelist' => [
            'Prooph\Link\ProcessManager\Api\Process' => ['application/json'],
        ],
    ],
);