<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/16/15 - 11:36 PM
 */
namespace Prooph\Link\ProcessManager\Api;

use Prooph\Link\Application\Service\AbstractRestController;
use Prooph\Link\Application\Service\ActionController;
use Prooph\Link\ProcessManager\Command\Workflow\ScheduleFirstTasksForWorkflow;
use Prooph\Link\ProcessManager\Command\Workflow\ScheduleNextTasksForWorkflow;
use Prooph\ServiceBus\CommandBus;

final class Connection extends AbstractRestController implements ActionController
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function create($connection)
    {
        if (! array_key_exists('type', $connection)) return $this->apiProblem(422, "No type given for the connection");
        if (! array_key_exists('message_handler', $connection)) return $this->apiProblem(422, "No message_handler given for the connection");
        if (! array_key_exists('workflow_id', $connection)) return $this->apiProblem(422, "No workflow_id given for the connection");

        if ($connection['type'] == "start_connection") {
            if (! array_key_exists('start_message', $connection)) return $this->apiProblem(422, "No start_message given for the connection");

            $this->commandBus->dispatch(ScheduleFirstTasksForWorkflow::withData(
                $connection['workflow_id'],
                $connection['start_message'],
                $connection['message_handler']
            ));

            return $this->accepted();
        } elseif ($connection['type'] == "source_target_connection") {
            if (! array_key_exists('previous_task', $connection)) return $this->apiProblem(422, "No previous_task given for the connection");

            $this->commandBus->dispatch(ScheduleNextTasksForWorkflow::withData(
                $connection['workflow_id'],
                $connection['previous_task'],
                $connection['message_handler']
            ));

            return $this->accepted();
        } else {
            return $this->apiProblem(422, "Unknown connection type");
        }

    }

    /**
     * @param CommandBus $commandBus
     * @return void
     */
    public function setCommandBus(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }
}