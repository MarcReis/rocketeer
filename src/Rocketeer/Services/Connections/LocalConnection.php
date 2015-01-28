<?php
/*
 * This file is part of Rocketeer
 *
 * (c) Maxime Fabre <ehtnam6@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rocketeer\Services\Connections;

use Closure;
use Rocketeer\Interfaces\ConnectionInterface;
use Rocketeer\Interfaces\HasRolesInterface;
use Rocketeer\Traits\HasLocator;
use Rocketeer\Traits\HasRoles;

/**
 * Stub of local connections to make Rocketeer work
 * locally when necessary
 *
 * @author Maxime Fabre <ehtnam6@gmail.com>
 */
class LocalConnection implements ConnectionInterface, HasRolesInterface
{
    use HasLocator;
    use HasRoles;

    /**
     * Return status of the last command
     *
     * @type integer
     */
    protected $previousStatus;

    /**
     * Define a set of commands as a task.
     *
     * @param string       $task
     * @param string|array $commands
     *
     * @codeCoverageIgnore
     */
    public function define($task, $commands)
    {
        // ...
    }

    /**
     * Run a task against the connection.
     *
     * @param string       $task
     * @param Closure|null $callback
     *
     * @codeCoverageIgnore
     */
    public function task($task, Closure $callback = null)
    {
        // ...
    }

    /**
     * Run a set of commands against the connection.
     *
     * @param string|array $commands
     * @param Closure|null $callback
     */
    public function run($commands, Closure $callback = null)
    {
        $commands = (array) $commands;
        $command  = implode(' && ', $commands);

        exec($command, $output, $status);

        $this->previousStatus = $status;
        if ($callback) {
            $output = (array) $output;
            foreach ($output as $line) {
                $callback($line.PHP_EOL);
            }
        }
    }

    /**
     * Get the exit status of the last command.
     *
     * @return integer
     */
    public function status()
    {
        return $this->previousStatus;
    }

    /**
     * Upload a local file to the server.
     *
     * @param string $local
     * @param string $remote
     *
     * @codeCoverageIgnore
     * @return integer
     */
    public function put($local, $remote)
    {
        $local = $this->files->get($local);

        return $this->putString($local, $remote);
    }

    /**
     * Get the contents of a remote file.
     *
     * @param string $remote
     *
     * @codeCoverageIgnore
     * @return string|null
     */
    public function getString($remote)
    {
        return $this->files->exists($remote) ? $this->files->get($remote) : null;
    }

    /**
     * Upload a string to to the given file on the server.
     *
     * @param string $remote
     * @param string $contents
     *
     * @codeCoverageIgnore
     * @return integer
     */
    public function putString($remote, $contents)
    {
        return $this->files->put($remote, $contents);
    }
}
