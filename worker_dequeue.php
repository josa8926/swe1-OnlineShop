<?php
$queue_dir = "/data/workuser/mailqueue";
$head_file = "$queue_dir/head";
$tail_file = "$queue_dir/tail";

if (!file_exists($head_file) || filesize($head_file) == 0) exit(0);

$thehead = trim(file_get_contents($head_file));
$node_file = "$queue_dir/$thehead";
if (!file_exists($node_file)) {
    file_put_contents($head_file, "");
    file_put_contents($tail_file, "");
    exit(0);
}

$lines = file($node_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$first_line = $lines[0] ?? '';
$email = trim(str_replace("Auftrag: Sende eine E-Mail an", "", $first_line));

$betreff = "Willkommen bei Jenny Afro & Asia Shop";
$inhalt = "Hallo $email,\n\nWillkommen bei Jenny Afro & Asia Shop in Bremerhaven!\nEntdecken Sie exklusive Produkte aus Afrika und Asien.\n\nIhr Jenny Afro & Asia Shop Team";

exec("php /data/workuser/simulate_mail.php " . escapeshellarg($email) . " " . escapeshellarg($betreff) . " " . escapeshellarg($inhalt));

$next_node = $lines[count($lines)-1] ?? '';
if (!empty($next_node)) {
    file_put_contents($head_file, $next_node);
} else {
    @unlink($head_file);
    @unlink($tail_file);
}

@unlink($node_file);
