<?php

return [
    'domain' => env('JITSI_DOMAIN', 'atendimento.timeplus.com.br'),
    'app_id' => env('JITSI_APP_ID', 'timeplusmeet'),
    'secret' => env('JITSI_SECRET', ''),
    'ttl'    => (int) env('JITSI_TOKEN_TTL', 3600),
];
