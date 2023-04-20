<?php
return [
    'passbolt' => [
        'plugins' => [
            'log' => [
                'enabled' => true,
                'version' => '1.0.1',
                'config' => [
                    // The actions listed in the blacklist will not be logged.
                    'blackList' => [
                        'AuthIsAuthenticated.isAuthenticated',
                        'TransfersView.view',
                        'AuthLogin.loginGet',
                    ],
                ],
            ],
        ],
    ],
];
