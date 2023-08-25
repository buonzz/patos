<?php


use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

use Buonzz\Patos\Commands\ConfigureCommand;

class ConfigureCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $application = new Application();
        $application->add(new ConfigureCommand());

        $command = $application->find('configure');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $this->assertEquals($commandTester->getDisplay(), "Hello\n");
    }
}