# Queue Runner Trait

Provides a trait for use with the [Drupal Test Traits](https://gitlab.com/weitzman/drupal-test-traits) library.

## Installation

```
composer require --dev drupaltest/queue-runner-trait
```

## Usage

```php
class SampleTestWithQueueRunner extends ExistingSiteBase
{
    use QueueRunnerTrait;

    protected function tearDown()
    {
        // Empty a given queue after the test has finished.
        $this->clearQueue('my_queue_name');
    }

    public function testSomeQueue()
    {
        // Do something that adds an item to a queue.
        
        // Run the queue by name.
        $this->runQueue('my_queue_name');
    }
}
```

See the included [test](./tests/QueueRunnerTraitTest.php) for a detailed example.
