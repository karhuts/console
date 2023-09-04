<?php
declare(strict_types=1);

namespace karthus\console\Command;

use Closure;
use karthus\route\Router;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class RouteListCommand extends Command
{
    protected static $defaultName = 'route:list';
    protected static $defaultDescription = 'Route list';

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $headers = ['uri', 'method', 'callback', 'middleware', 'name'];
        $rows = [];
        foreach (Router::getRoutes() as $route) {
            foreach ($route->getMethods() as $method) {
                $cb = $route->getCallback();
                $cb = $cb instanceof Closure ? 'Closure' : (is_array($cb) ? json_encode($cb) : var_export($cb, true));
                $rows[] = [$route->getPath(), $method, $cb, json_encode($route->getMiddleware() ?: null), $route->getName()];
            }
        }

        $table = new Table($output);
        $table->setHeaders($headers);
        $table->setRows($rows);
        $table->render();
        return self::SUCCESS;
    }
}
