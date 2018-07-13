<?php

namespace App\Models\Lock;

use Illuminate\Support\Facades\DB;
use NinjaMutex\Lock\LockAbstract;
use NinjaMutex\Lock\MySqlLock as BaseMysqlLock;

class MySqlLock extends BaseMysqlLock
{
    public function __construct()
    {
        LockAbstract::__construct();
    }

    protected function setupPDO($name)
    {
        // Reuse the same connection, don't make a new one for each mutex
        $this->pdo[$name] = DB::connection()->getPdo();
        return true;
    }
}