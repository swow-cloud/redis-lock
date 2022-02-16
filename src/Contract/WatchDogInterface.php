<?php
/**
 * This file is part of SwowCloud
 * @license  https://github.com/swow-cloud/music-server/blob/main/LICENSE
 */

declare(strict_types=1);

namespace SwowCloud\RedisLock\Contract;

interface WatchDogInterface
{
    /**
     * watchdog sentinel automatic renewal mechanism
     * Return true if the task completed successfully
     *
     * @param \SwowCloud\RedisLock\Contract\LockInterface $lock
     * @throws \Throwable
     */
    public function sentinel(LockInterface $lock, int $time = 60): bool;
}
