<?php
/*
 * Copyright (c) 2016-2022 Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of ProBIND v3.
 *
 * ProBIND v3 is free software: you can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation, either version 3 of the
 * License, or any later version.
 *
 * ProBIND v3 is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See
 * the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with ProBIND v3. If not,
 * see <https://www.gnu.org/licenses/>.
 *
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
     * @param  string  $envPath
     * @param  string  $envExamplePath
     */
    public function __construct($envPath = '.env', $envExamplePath = '.env.example')
    {
        $this->envPath = base_path($envPath);
        $this->envExamplePath = base_path($envExamplePath);
        $this->env = $this->all();
    }

    /**
     * Get the content of the .env file.
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
     */
    private function set(string $key, string $value): void
    {
        $this->env = array_map(function ($item) use ($key, $value) {
            if (strpos($item, $key) !== false) {
                $start = strpos($item, '=') + 1;
                $item = substr_replace($item, $value."\n", $start);
            }

            return $item;
        }, $this->env);
    }

    /**
     * Save the edited content to the .env file.
     * Return false on error.
     */
    private function saveFile(): bool
    {
        return file_put_contents($this->envPath, implode($this->env)) !== false;
    }
}
