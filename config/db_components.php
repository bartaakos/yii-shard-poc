<?php

class DbComponents
{
    const MainDb = 'db';
    const SharedDb1 = 'lpdbtest_shared_1';
    const SharedDb2 = 'lpdbtest_shared_2';

    static $componentIds = array(self:: MainDb, self::SharedDb1, self::SharedDb2);
}