<?php

namespace App\Helpers;

use App\Models\Lock\MySqlLock;
use NinjaMutex\Mutex;

class MutexHelper
{
    /**
     * @var Mutex
     */
    private $mutex;

    /**
     * @var int
     */
    private $defaultTimeout;

    /**
     * MutexHelper constructor.
     * @param string $key mutex name
     */
    public function __construct(string $key)
    {
        $mysqlLock = new MySqlLock();
        $this->mutex =  new Mutex($key, $mysqlLock);
        $this->defaultTimeout = env('MUTEX_TIMEOUT', 3000);
    }

    /**
     * @param int $timeout milliseconds to keep retrying if the lock is taken by another process.
     *                     Defaults to env variable MUTEX_TIMEOUT with fallback 3000 milliseconds
     * @return bool
     */
    public function acquireLock(int $timeout = null)
    {
        if ($timeout === null) {
            $timeout = $this->defaultTimeout;
        }
        return $this->mutex->acquireLock($timeout);
    }

    /**
     * @return bool
     */
    public function releaseLock()
    {
        return $this->mutex->releaseLock();
    }

    /**
     * @return bool
     */
    public function isAcquired()
    {
        return $this->mutex->isAcquired();
    }
}
