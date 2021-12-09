<?php
/**
 * This file is part of SwowCloud
 * @license  https://github.com/swow-cloud/music-server/blob/main/LICENSE
 */

declare(strict_types=1);

namespace SwowCloud\RedisLock\Contract;

interface LockInterface
{
    /**
     * get lock，This method will return fasle directly after the lock is failed.
     *
     * @param string $key lock unique identifier
     *
     * @throws \Throwable
     */
    public function tryLock(string $key, int $ttl = 3): bool;

    /**
     * get lock，This method will return fasle directly after the lock is failed.
     *
     * @param string $key lock unique identifier
     *
     * @param int $retries number of retries
     *
     * @throws \Throwable
     */
    public function lock(string $key, int $ttl = 3, int $retries = 3, int $usleep = 10000): bool;

    /**
     * release lock
     *
     * @throws \Throwable
     */
    public function unLock(): bool;

    /**
     * get lock life ttl
     */
    public function lockTtl(): int;

    /**
     * Let the lock last for N seconds, the default N is 3
     *
     * @throws \Throwable
     */
    public function keepAlive(int $ttl = 3): bool;

    /**
     * check if the lock is valid
     *
     * @throws \Throwable
     */
    public function isAlive(): bool;
}
