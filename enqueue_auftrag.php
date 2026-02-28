<?php
function enqueue($email) {
    $queue_dir = "/data/workuser/mailqueue";
    $log_dir   = "/data/workuser/log";
    $logfile   = "$log_dir/enqueue_auftrag.log";

    if (!is_dir($queue_dir)) mkdir($queue_dir, 0777, true);
    if (!is_dir($log_dir)) mkdir($log_dir, 0777, true);

    $node_id = "node-" . microtime(true);
    $node_file = "$queue_dir/$node_id";

    file_put_contents($node_file, "Auftrag: Sende eine E-Mail an $email\n");

    $head_file = "$queue_dir/head";
    $tail_file = "$queue_dir/tail";

    if (!file_exists($head_file) || filesize($head_file) == 0) {
        file_put_contents($head_file, $node_id);
        file_put_contents($tail_file, $node_id);
        chmod($head_file, 0777);
        chmod($tail_file, 0777);
    } else {
        $alt_tail = file_get_contents($tail_file);
        $lines = file("$queue_dir/$alt_tail", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $first_line = $lines[0] ?? '';
        file_put_contents("$queue_dir/$alt_tail", $first_line . "\n" . $node_id . "\n");
        file_put_contents($tail_file, $node_id);
        //chmod($tail_file, 0777);
    }

    file_put_contents($logfile, "Auftrag erstellt: $node_id\n", FILE_APPEND);
}

enqueue($argv[1]);
