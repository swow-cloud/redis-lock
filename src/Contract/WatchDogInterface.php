<?php
/**
 * This file is part of SwowCloud
 * @license  https://github.com/swow-cloud/websocket-server/blob/main/LICENSE
 */

declare(strict_types=1);

namespace SwowCloud\RedisLock\Contract;

interface WatchDogInterface
{
    /**
     * watchdog sentinel automatic renewal mechanism
     * Return true if the task completed successfully
     *
     * @param LockInterface $lock
     * @throws \Throwable
     */
    public function sentinel(LockInterface $lock, int $time = 60): bool;
}
