<?php

namespace App\Tests\Acceptance;

use Behat\Behat\Context\Context;
use PHPUnit\Framework\Assert;
use Exception;
use Symfony\Component\Process\Process;

class CliContext implements Context
{
    /**
     * @When I run the command :command
     */
    public function iRunTheCommand($command)
    {
        $command = sprintf('%s/../../bin/console --env=test %s',
            __DIR__,
            $command
        );
        $process = new Process($command);
        $process->run();

        if ($process->getExitCode() !== 0) {
            throw new Exception(sprintf(
                'Command returned non-zero exit code "%s": %s / %s',
                $process->getExitCode(),
                $process->getOutput(),
                $process->getErrorOutput()
            ));
        }
        sleep(1);
    }
}
