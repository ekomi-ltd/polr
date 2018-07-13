<?php

namespace App\Helpers;

use App\Models\Lock\MySqlLock;
use NinjaMutex\Mutex;

class MutexHelper
{
    private $mutex;

    /**
     * MutexHelper constructor.
     * @param string $key mutex name
     */
    public function __construct(string $key)
    {
        $mysqlLock = new MySqlLock();
        $this->mutex =  new Mutex($key, $mysqlLock);
    }

    /**
     * @param int $timeout milliseconds to keep retrying if the lock is taken by another process
     * @return bool
     */
    public function acquireLock($timeout = 3000)
    {
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
