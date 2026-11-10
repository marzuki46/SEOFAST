<?php

return [
    'four_xx_per_minute' => (int) env('FOURXX_PER_MINUTE', 30),
    'four_xx_retry_after_seconds' => 60,
];
