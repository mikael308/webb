<?php
namespace Tools\Klaatu;

use function Web\Framework\Request\getSettings;

require_once '/vagrant/src/pages/reference.php';
require_once '/vagrant/tools/klaatu/helper.php';
require_once '/vagrant/src/framework/session/main.php';


$command = $argv[1] ?? '';

$args = readArguments($argv);
$flags = $args['flags'];
$subParams = $args['parameters'];

switch($command)
{
    case 'database':
        database($subParams[0], $flags);
        break;

    case 'opcache:reset':
        opcache_reset();
        break;
    case 'settings:reset':
        getSettings()->reset();
        echo "TODO: resetting settings\n";
        break;

    case 'list:command':
        echo "list of klaatu commands\n";
        echo commandList();
        echo "-\n\n";
        break;
    default:
        echo helpMessage();
        echo "\n";
        break;
}
