<?php

class DbComponents
{
    const MainDb = 'db';
    const MainDbUS = 'db_us';
    const ShardDb1 = 'lpdbtest_shard_1';
    const ShardDb1US = 'lpdbtest_shard_1_us';
    const ShardDb2 = 'lpdbtest_shard_2';

    static $componentIds = array(self:: MainDb, self::MainDbUS, self::ShardDb1, self::ShardDb1US, self::ShardDb2);
}