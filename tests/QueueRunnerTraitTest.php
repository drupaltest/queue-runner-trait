<?php

namespace weitzman\DrupalTestTraits\Tests;

use weitzman\DrupalTestTraits\ExistingSiteBase;
use DrupalTest\QueueRunnerTrait\QueueRunnerTrait;

/**
 * @coversDefaultClass \DrupalTest\QueueRunnerTrait\QueueRunnerTrait
 */
class QueueRunnerTraitTest extends ExistingSiteBase
{

    use QueueRunnerTrait;

    /**
     * The queue to test with.
     *
     * @var \Drupal\Core\Queue\QueueInterface
     */
    protected $queue;

    /**
     * The queue to test with.
     *
     * @var string
     */
    protected $queueName = 'media_entity_thumbnail';

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        // The core media module has a queue to test with.
        $this->container->get('module_installer')->install(['media']);

        /** @var \Drupal\Core\Queue\QueueInterface $queue */
        $this->queue = $this->container->get('queue')->get($this->queueName);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->clearQueue($this->queueName);
        parent::tearDown();
    }

    /**
     * @covers ::clearQueue
     */
    public function testClearQueue()
    {
        $this->addQueueItem();
        $this->clearQueue($this->queueName);
        $this->assertEquals(0, $this->queue->numberOfItems());
    }

    /**
     * @covers ::runQueue
     */
    public function testRunQueue()
    {
        $this->addQueueItem();
        $this->runQueue($this->queueName);
        $this->assertEquals(0, $this->queue->numberOfItems());
    }

    /**
     * Adds an item to the queue.
     */
    protected function addQueueItem()
    {
        // Add an item to a queue.
        $item = [
            'id' => 'dummy_id',
        ];
        $this->queue->createItem($item);

        // Verify the item is in the queue.
        $this->assertEquals(1, $this->queue->numberOfItems());
    }
}
