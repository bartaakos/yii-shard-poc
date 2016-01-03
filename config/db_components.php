<?php

class DbComponents
{
    const MainDb = 'db';
    const ShardDb1 = 'lpdbtest_shard_1';
    const ShardDb2 = 'lpdbtest_shard_2';

    static $componentIds = array(self:: MainDb, self::ShardDb1, self::ShardDb2);
}