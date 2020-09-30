<?php
/**
 * ProBIND v3 - Professional DNS management made easy.
 *
 * Copyright (c) 2016 by Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of some open source application.
 *
 * Licensed under GNU General Public License 3.0.
 * Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2016 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/probind
 */

namespace App\Repositories;

use Illuminate\Support\Arr;

class EnvironmentRepository
{
    /**
     * @var string
     */
    private $envPath;
    /**
     * @var string
     */
    private $envExamplePath;
    /**
     * @var array
     */
    private $env;

    /**
     * Create a new EnvironmentRepository instance.
     *
     * @param string $envPath
     * @param string $envExamplePath
     */
    public function __construct($envPath = '.env', $envExamplePath = '.env.example')
    {
        $this->envPath = base_path($envPath);
        $this->envExamplePath = base_path($envExamplePath);
        $this->env = $this->all();
    }

    /**
     * Get the content of the .env file.
     *
     * @return array
     */
    private function all(): array
    {
        if (false === file_exists($this->envPath)) {
            $this->copyEnvExampleToEnv();
        }
        $content = file($this->envPath);

        return (false === $content) ? [] : $content;
    }

    /**
     * Copy the file '.env.example' (if exists) to '.env'.
     */
    private function copyEnvExampleToEnv(): void
    {
        touch($this->envPath);
        if (true === file_exists($this->envExamplePath)) {
            copy($this->envExamplePath, $this->envPath);
        }
    }

    /**
     * Set the database setting of the .env file.
     *
     * $connectionSettings = [
     *  'database'  => 'probind',
     *  'username'  => 'user',
     *  'password'  => 'secret',
     *  'host'      => 'localhost',
     * ];
     *
     * @param array $connectionSettings
     *
     * @return bool
     */
    public function setDatabaseSetting(array $connectionSettings): bool
    {
        if (! Arr::has($connectionSettings, ['database', 'username', 'password', 'host'])) {
            return false;
        }

        $this->set('DB_DATABASE', $connectionSettings['database']);
        $this->set('DB_USERNAME', $connectionSettings['username']);
        $this->set('DB_PASSWORD', $connectionSettings['password']);
        $this->set('DB_HOST', $connectionSettings['host']);

        return $this->saveFile();
    }

    /**
     * Set .env element.
     *
     * @param string $key
     * @param string $value
     */
    private function set(string $key, string $value)
    {
        $this->env = array_map(function ($item) use ($key, $value) {
            if (strpos($item, $key) !== false) {
                $start = strpos($item, '=') + 1;
                $item = substr_replace($item, $value . "\n", $start);
            }

            return $item;
        }, $this->env);
    }

    /**
     * Save the edited content to the .env file.
     * Return false on error.
     *
     * @return bool
     */
    private function saveFile(): bool
    {
        return file_put_contents($this->envPath, implode($this->env)) !== false;
    }
}
