<?php
/**
 * This file is part of SwowCloud
 *
 * @license  https://github.com/swow-cloud/websocket-server/blob/main/LICENSE
 */

declare(strict_types=1);

namespace SwowCloud\RedisLock;

use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Engine\Coroutine as HyperfCoroutine;
use Hyperf\Utils\ApplicationContext;
use Swow\Coroutine;
use SwowCloud\RedisLock\Contract\LockInterface;
use SwowCloud\RedisLock\Contract\WatchDogInterface;
use Throwable;

class WatchDog implements WatchDogInterface
{
    public function sentinel(LockInterface $lock, int $watchDogTime = 60): bool
    {
        $logger = ApplicationContext::getContainer()->get(StdoutLoggerInterface::class);
        $cid = Coroutine::getCurrent()->getId();
        $logger->debug(sprintf('coroutine[%s] successfully initialize the watchdog task', $cid));
        $ttl = $lock->lockTtl();
        $sleepTime = (int)(($ttl > 1 ? $ttl - 1 : 0.5) * 1000);
        usleep($sleepTime);
        $startTime = microtime(true);
        while ($lock->isAlive()) {
            if ((microtime(true) - $startTime) * 1000 > $watchDogTime * 1000) {
                $logger->debug(
                    sprintf('coroutine[%s] cleanup watch dog watch dog has exceeded the maximum watch time', $cid)
                );

                return false;
            }
            try {
                HyperfCoroutine::getContextFor();
            } catch (Throwable $e) {
                $logger->debug(sprintf('coroutine[%s] cleanup watch dog after request completed', $cid));

                return true;
            }

            if (!$lock->keepAlive($ttl)) {
                $logger->debug(sprintf('coroutine[%s] cleanup watch dog when renewal failure', $cid));

                return true;
            }

            $logger->debug(sprintf('coroutine[%s] watch dog successful renewal %s s', $cid, $ttl));
            usleep($sleepTime);
        }

        $logger->debug(sprintf('coroutine[%s] cleanup watch dog  when the lock has expired', $cid));

        return true;
    }
}
