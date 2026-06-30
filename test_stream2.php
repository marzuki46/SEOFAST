<?php
$ch = curl_init('https://rifzpbl.abc-tunnel.us/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer sk-ce6a7cf8b061f964-frth1a-26e86b57',
    'Bypass-Tunnel-Reminder: true'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'model' => 'oc/deepseek-v4-flash-free',
    'messages' => [['role'=>'user','content'=>'What is 2+2? Explain your reasoning.']],
    'stream' => true
]));
$res = curl_exec($ch);
file_put_contents('test_stream_2.txt', $res);
