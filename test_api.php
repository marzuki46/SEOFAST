<?php
$data = ['model' => 'marzuki', 'messages' => [['role' => 'user', 'content' => 'Halo, tes.']]];
$ch = curl_init('https://rifzpbl.abc-tunnel.us/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: Bearer sk-ce6a7cf8b061f964-frth1a-26e86b57']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
$res = curl_exec($ch);
echo "RESPONSE:\n" . $res . "\n";
