<?php

use mindplay\foobox\config\AppFactory;
use mindplay\foobox\container\Container;
use mindplay\foobox\deps\Database;
use mindplay\foobox\deps\UserRepository;
use mindplay\foobox\provider\ConfigProvider;
use mindplay\foobox\provider\FactoryLoader;
use mindplay\foobox\provider\FactoryProvider;

use function mindplay\testies\{ configure, test, run, ok, eq };

require_once __DIR__ . "/../vendor/autoload.php";

test(
    "can load provider metadata from a factory",
    function () {
        $metadata = iterator_to_array(FactoryLoader::load(AppFactory::class));

        eq(
            $metadata,
            [
                "mindplay\\foobox\\deps\\Database" => [
                    [AppFactory::class, "createDatabase"],
                    ["dsn" => "user-service.dsn"]
                ],
                "user-service.cache" => [
                    [AppFactory::class, "createCache"],
                    []
                ],
                "mindplay\\foobox\\deps\\UserRepository" => [
                    [AppFactory::class, "createUserRepository"],
                    ["db" => Database::class, "cache" => "user-service.cache"]
                ],
                "mindplay\\foobox\\deps\\Logger" => [
                    [AppFactory::class, "createLogger"],
                    []
                ],
            ]
        );
    }
);

test(
    "can load provider metadata from a factory with ID attributes",
    function () {
        $DSN = "mysql:host=localhost;dbname=foobar";

        $container = new Container([
            new FactoryProvider([AppFactory::class]),
            new ConfigProvider(["user-service.dsn" => $DSN])
        ]);

        ok($container->get(Database::class) instanceof Database);
        
        ok($container->get(UserRepository::class) instanceof UserRepository);

        eq($container->get(UserRepository::class)->db, $container->get(Database::class));

        eq($container->get(UserRepository::class)->db->dsn, $DSN);
    }
);

exit(run());
