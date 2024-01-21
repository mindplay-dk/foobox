<?php

namespace mindplay\foobox\config;

use mindplay\foobox\deps\Cache;
use mindplay\foobox\deps\Database;
use mindplay\foobox\deps\Logger;
use mindplay\foobox\deps\UserRepository;
use mindplay\foobox\provider\ID;

abstract class AppFactory
{
    public static function createDatabase(#[ID("user-service.dsn")] string $dsn): Database
    {
        return new Database($dsn);
    }

    #[ID("user-service.cache")]
    public static function createCache(): Cache
    {
        return new Cache();
    }

    public static function createUserRepository(Database $db, #[ID("user-service.cache")] Cache $cache): UserRepository
    {
        return new UserRepository($db, $cache);
    }

    public static function createLogger(): Logger
    {
        return new Logger();
    }
}
