<?php
$res = file_get_contents('https://rifzpbl.abc-tunnel.us/v1/models', false, stream_context_create(['http' => ['header' => 'Authorization: Bearer sk-ce6a7cf8b061f964-frth1a-26e86b57']]));
$json = json_decode($res, true);
$models = [];
foreach ($json['data'] ?? [] as $m) {
    if (is_array($m) && isset($m['id'])) {
        $models[] = $m['id'];
    } elseif (is_string($m)) {
        $models[] = $m;
    }
}
echo implode("\n", $models);
