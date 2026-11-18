<?php

return [
    'four_xx_per_minute' => (int) env('FOURXX_PER_MINUTE', 30),
    'four_xx_retry_after_seconds' => 60,
    // Scanner probes are rejected after a few attempts without writing every
    // request to the 404 database tracker.
    'scanner_probe_attempts' => (int) env('SCANNER_PROBE_ATTEMPTS', 3),
    'scanner_probe_decay_seconds' => (int) env('SCANNER_PROBE_DECAY_SECONDS', 600),
];
