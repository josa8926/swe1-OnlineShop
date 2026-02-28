<?php
$email   = $argv[1] ?? '';
$betreff = $argv[2] ?? '';
$inhalt  = $argv[3] ?? '';

$logfile = "/data/workuser/log/simulation.log";

usleep(300000);

$line = date('Y-m-d H:i:s') . ": $email | $betreff | $inhalt\n";
//$line = date . ": $email | $betreff | $inhalt\n";
file_put_contents($logfile, $line, FILE_APPEND);
