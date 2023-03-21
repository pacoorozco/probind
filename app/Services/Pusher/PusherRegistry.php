<?php
/*
 * Copyright (c) 2016-2023 Paco Orozco <paco@pacoorozco.info>
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

namespace App\Services\Pusher;

use InvalidArgumentException;

class PusherRegistry
{
    /**
     * List of all pushers in the registry (by named indexes)
     */
    private static array $pushers = [];

    /**
     * Adds new pusher to the registry
     *
     * @param PusherInterface $pusher Instance of the pusher
     * @param string|null $name Name of the pusher channel ($pusher->getName() by default)
     * @param bool $overwrite Overwrite instance in the registry if the given name already exists?
     * @throws InvalidArgumentException    If $overwrite set to false and named Pusher instance already exists
     */
    public static function addPusher(PusherInterface $pusher, ?string $name = null, bool $overwrite = false): void
    {
        $name = $name ?? $pusher->getName();

        if (isset(self::$pushers[$name]) && !$overwrite) {
            throw new InvalidArgumentException('Pusher with the given name already exists');
        }

        self::$pushers[$name] = $pusher;
    }

    /**
     * Checks if such pusher exists by name or instance
     *
     * @param string|PusherInterface $pusher Name or pusher instance
     */
    public static function hasPusher(PusherInterface|string $pusher): bool
    {
        if ($pusher instanceof PusherInterface) {
            $index = in_array($pusher, self::$pushers, true);

            return false !== $index;
        }

        return isset(self::$pushers[$pusher]);
    }

    /**
     * Removes instance from registry by name or instance
     *
     * @param string|PusherInterface $pusher Name or pusher instance
     */
    public static function removePusher(string|PusherInterface $pusher): void
    {
        if ($pusher instanceof PusherInterface) {
            if (false !== ($idx = in_array($pusher, self::$pushers, true))) {
                unset(self::$pushers[$idx]);
            }
        } else {
            unset(self::$pushers[$pusher]);
        }
    }

    /**
     * Clears the registry
     */
    public static function clear(): void
    {
        self::$pushers = [];
    }

    /**
     * Gets Pusher instance from the registry
     *
     * @param  string                    $name Name of the requested Pusher instance
     * @throws InvalidArgumentException If named Pusher instance is not in the registry
     */
    public static function getInstance(string $name): PusherInterface
    {
        if (!isset(self::$pushers[$name])) {
            throw new InvalidArgumentException(sprintf('Requested "%s" pusher instance is not in the registry', $name));
        }

        return self::$pushers[$name];
    }
}
