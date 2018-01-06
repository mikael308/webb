<?php

namespace Tools\Klaatu;


$rootpath = '/vagrant/';

$commandNames = [
    'database'          => 'database commands',
    'mode:set'          => 'sets mode',
    'mode:show'         => 'shows current mode',
    'opcache:reset'     => 'resets opcache',
    'settings:reset'    => 'resets settings',
    'log:error'         => 'show errorlog',
    'help'              => 'prints helpmessage',
    'list:command'      => 'prints list of commands'
];

function readArguments($argv)
{
    $args = [
        'parameters'  => [],
        'flags'     => []
    ];
    for ($i = 2; $i < sizeof($argv); $i++) {
        $arg = $argv[$i];
        if ($arg[0] == '-') {
            for ($j = 1; $j < strlen($arg); $j++) {
                $args['flags'][] = $arg[$j];
            }
        } else {
            $args['parameters'][] = $arg;
        }
    }
    return $args;
}

function confirm($message = '')
{
    $max_tries = 3;
    $in = '';
    for ($n_tries = 0; $n_tries < $max_tries; $n_tries++) {
        echo "confirm $message (y/n):\t";
        $in = trim(fgets(STDIN));
        if ($in === 'y' || $in === 'yes') {
            return true;
        } else if ($in === 'n' || $in === 'no') {
            break;
        }
        echo "invalid input\n";
    }

    echo "abort command\n";
    return false;
}

function database($param, $flags)
{
    if ($param == null) {
        echo "did not provide database argument\n";
        return false;
    }

    if (in_array('y', $flags) || confirm($param)) {
        global $rootpath;
        $db = $rootpath . 'setup/database.sh';
        $cmd = "sh $db $param";
        echo "executing\t$cmd\n";
        shell_exec($cmd);
    }
}

function setMode($mode)
{
    $validModes = ['debug', 'production'];
    if (in_array(
        $mode,
        $validModes)
    ) {
        $_SESSION['mode'] = $mode;
        echo "mode: '$mode' set successfully\n";
    } else {
        echo "invalid mode '$mode'\n";
        $bulletin = ' - ';
        echo "use: \n $bulletin" . implode("\n $bulletin", $validModes) . "\n";
    }
}

function showMode()
{
    echo $_SESSION['mode'];
}

function helpMessage()
{
    return "this is klaatu tool\n"
        . "to print commandlist use \tklaatu list:command\n";
}

function commandList()
{
    global $commandNames;
    $s='';
    foreach($commandNames as $commandname => $description) {
        $s .= sprintf(
            " %' -20s %s \n",
            $commandname,
            $description
        );
    }
    return $s;
}
