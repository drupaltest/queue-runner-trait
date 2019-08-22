<?php

namespace DrupalTest\QueueRunnerTrait;

/**
 * Methods to process queues during test runs.
 *
 * @property \Drupal\Core\DependencyInjection\Container $container
 * @used-by \weitzman\DrupalTestTraits\ExistingSiteBase
 */
trait QueueRunnerTrait
{

    /**
     * Empties a given queue.
     *
     * This should be run in setUp to ensure the test only processes items
     * that are part of the test, not left-over items from the database.
     *
     * @param string $queue_name
     *   The queue to empty.
     */
    protected function clearQueue($queue_name)
    {
        $this->container->get('database')
            ->delete('queue')
            ->condition('name', $queue_name)
            ->execute();
    }

    /**
     * Runs a given queue until all items are processed.
     *
     * @param string $queue_name
     *   The queue to process.
     *
     * @see \Drush\Drupal\Commands\core\QueueCommands::run
     */
    protected function runQueue($queue_name)
    {
        $queue = $this->container->get('queue')->get($queue_name);
        $worker = $this->container->get('plugin.manager.queue_worker')->createInstance($queue_name);
        while ($item = $queue->claimItem()) {
            $worker->processItem($item->data);
            $queue->deleteItem($item);
        }
    }
}
