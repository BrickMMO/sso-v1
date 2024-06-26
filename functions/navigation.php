<?php

function navigation_array($selected = false)
{

    $navigation = [
        [
            'title' => 'City Portal',
            'protected' => false,
            'sections' => [
                [
                    'title' => 'Control',
                    'id' => 'control',
                    'pages' => [
                        [
                            'icon' => 'control-panel',
                            'url' => '/control-panel',
                            'title' => 'Control Panel',
                        ],[
                            'icon' => 'control-brix',
                            'url' => '/clock/dashboard',
                            'title' => 'Clock',
                        ]
                    ],
                ],[
                    'title' => 'Transportation',
                    'id' => 'transportation',
                    'pages' => [
                        [
                            'icon' => 'navigation',
                            'url' => '/navigation/dashboard',
                            'title' => 'Navigation',
                        ],[
                            'icon' => 'roadview',
                            'url' => '/roadview/dashboard',
                            'title' => 'Roadview',
                        ],[
                            'icon' => 'tracks',
                            'url' => '/tracks/dashboard',
                            'title' => 'Tracks',
                        ],[
                            'icon' => 'train',
                            'url' => '/train/dashboard',
                            'title' => 'Train',
                        ],
                    ],
                ],[
                    'title' => 'Students',
                    'id' => 'students',
                    'pages' => [
                        [
                            'icon' => 'brick-overflow',
                            'url' => '/brick-overflow/dashboard',
                            'title' => 'Brick Overflow',
                        ],[
                            'icon' => 'flow',
                            'url' => '/flow/dashboard',
                            'title' => 'Flow',
                        ],[
                            'icon' => 'timesheets',
                            'url' => '/timesheets/dashboard',
                            'title' => 'Timesheets',
                        ],
                    ],
                ],[
                    'title' => 'Community',
                    'id' => 'community',
                    'pages' => [
                        [
                            'icon' => 'radio',
                            'url' => '/radio/dashboard',
                            'title' => 'Radio',
                        ],[
                            'icon' => 'events',
                            'url' => '/events/dashboard',
                            'title' => 'Events',
                        ],[
                            'icon' => 'qr-codes',
                            'url' => '/qr-codes/dashboard',
                            'title' => 'Qr Codes',
                        ],
                    ],
                ],[
                    'title' => 'Social',
                    'id' => 'social',
                    'pages' => [
                        [
                            'icon' => 'brix',
                            'url' => '/brix/dashboard',
                            'title' => 'Brix',
                        ],[
                            'icon' => 'timeline',
                            'url' => '/timeline/dashboard',
                            'title' => 'Timeline',
                        ],
                    ],
                ],[
                    'title' => 'Finances',
                    'id' => 'finances',
                    'pages' => [
                        [
                            'icon' => 'crypto',
                            'url' => '/crypto/dashboard',
                            'title' => 'Crypto',
                        ],
                    ],
                ],
            ],
        ],[
            'title' => 'Administration',
            'protected' => false,
            'sections' => [
                [
                    'title' => 'Content',
                    'id' => 'admin-content',
                    'pages' => [
                        [
                            'icon' => 'bricksum',
                            'url' => '/admin/bricksum/dashboard',
                            'title' => 'Bricksum',
                            'sub-pages' => [
                                [
                                    'title' => 'Dashboard',
                                    'url' => '/admin/bricksum/dashboard',
                                    'colour' => 'red'
                                ],[
                                    'title' => 'Modify Word List',
                                    'url' => '/admin/bricksum/wordlist',
                                    'colour' => 'red'
                                ],[
                                    'br' => '---'
                                ],[
                                    'title' => 'Visit Bricksum App',
                                    'url' => 'https://bricksum.brickmmo.com',
                                    'colour' => 'orange'
                                ],[
                                    'br' => '---'
                                ],[
                                    'title' => 'Uptime Report',
                                    'url' => '/admin/uptime/bricksum',
                                    'colour' => 'orange'
                                ],[
                                    'title' => 'Stats Report',
                                    'url' => '/admin/uptime/stats',
                                    'colour' => 'orange'
                                ]
                            ]
                        ],[
                            'icon' => 'colours',
                            'url' => '/admin/colours/dashboard',
                            'title' => 'Colours',
                        ],[
                            'icon' => 'parts',
                            'url' => '/admin/parts/dashboard',
                            'title' => 'Parts',
                        ],[
                            'icon' => 'stores',
                            'url' => '/admin/stores/dashboard',
                            'title' => 'Stores',
                        ],[
                            'icon' => 'media',
                            'url' => '/admin/media/dashboard',
                            'title' => 'Media',
                        ]
                    ],
                ],[
                    'title' => 'Finances',
                    'id' => 'admin-finances',
                    'pages' => [
                        [
                            'icon' => 'crypto',
                            'url' => '/admin/crypto/dashboard',
                            'title' => 'Crypto',
                        ],
                    ],
                ],[
                    'title' => 'Tools',
                    'id' => 'admin-tools',
                    'pages' => [
                        [
                            'icon' => 'github',
                            'url' => '/admin/github/dashboard',
                            'title' => 'GitHub Scanner', 
                            'sub-pages' => [
                                [
                                    'title' => 'Dashboard',
                                    'url' => '/admin/github/dashboard',
                                    'colour' => 'red'
                                ],[
                                    'title' => 'Scan Results',
                                    'url' => '/admin/github/results',
                                    'colour' => 'red'
                                ],[
                                    'title' => 'Rescan GitHub',
                                    'url' => '/admin/github/rescan',
                                    'colour' => 'red'
                                ],[
                                    'br' => '---'
                                ],[
                                    'title' => 'Stats Report',
                                    'url' => '/admin/stats/github',
                                    'colour' => 'orange'
                                ]
                            ],[
                                'icon' => 'uptime',
                                'url' => '/admin/uptime/dashboard',
                                'title' => 'Up Time',
                            ],[
                                'icon' => 'stats',
                                'url' => '/admin/stats/dashboard',
                                'title' => 'Stats',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ];

    if($selected)
    {
        $selected = '/'.$selected;
        $selected = str_replace('.php', '', $selected);
        $selected = str_replace('.', '/', $selected);
        $selected = substr($selected, 0, strrpos($selected, '/'));

        foreach($navigation as $levels)
        {
            foreach($levels['sections'] as $section)
            {
                foreach($section['pages'] as $page)
                {

                    if(strpos($page['url'], $selected) === 0)
                    {
                        return $page;
                    }

                }
            }

        }
        die();
    }

    return $navigation;

}