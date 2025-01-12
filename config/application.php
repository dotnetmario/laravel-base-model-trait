<?php

return [
    'properties' => [
        'data-chunks' => [ // data chunking for collections
            'slow' => 100,
            'normal' => 1000,
            'fast' => 10000,
        ],
        'exports' => [
            'folder' => 'exports',
            'data-separation' => '|'
        ]
    ],
    'models' => [
        \App\Models\User::class => [
            // 'auto_encryted' => false,
            'encryptable' => ['email', 'name'],

            'validation' => [
                'rules' => [
                    'name' => 'string',
                    'email' => ['email', 'unique:users'],
                    'password' => ['confirmed', 'min:8']
                ],
                'messages' => [
                    'name.required' => 'required name',
                ]

            ],

            'seed' => [
                'count' => 10,
                'attributes' => [
                    'name' => ['data' => 'name'],
                    'email' => ['data' => 'email', 'unique' => true],
                    'password' => 'password'
                ]
            ]
        ],

        \App\Models\Tag::class => [
            'seed' => [
                'count' => 10,
                'attributes' => [
                    'name' => ['data' => 'name'],
                ]
            ]
        ],
        \App\Models\Author::class => [
            'seed' => [
                'count' => 5,
                'attributes' => [
                    'name' => ['data' => 'name'],
                    'joined' => function () {
                        return \Carbon\Carbon::now()->subMonths(rand(10, 20))->format('Y-m-d H:i:s');
                    }
                ],

                'relations' => [
                    'posts' => [
                        'type' => \Illuminate\Database\Eloquent\Relations\HasMany::class,
                        'count' => 5,
                        'attributes' => [
                            'title' => ['data' => 'sentence', 'params' => [1]],
                            'body' => ['data' => 'paragraphs', 'params' => [rand(10, 20), true]]
                        ],

                        'relations' => [
                            'tags' => [
                                'type' => \Illuminate\Database\Eloquent\Relations\BelongsToMany::class,
                                'assign' => [
                                    1 => ['is_main' => true],
                                    2 => ['is_main' => false],
                                    3 => ['is_main' => false]
                                ],
                                // 'assign' => function($post) {
                                //     return [
                                //         \App\Models\Tag::inRandomOrder()->first()->id => ['is_main' => true],
                                //         \App\Models\Tag::inRandomOrder()->first()->id => ['is_main' => false],
                                //         \App\Models\Tag::inRandomOrder()->first()->id => ['is_main' => false]
                                //     ];
                                // },
                                // 'count' => 2,
                                // 'attributes' => [
                                //     'name' => ['data' => 'name']
                                // ],
                                // 'pivot' => function ($post) {
                                //     return [
                                //         'is_main' => $post->tags()->wherePivot('is_main', true)->exists() ? false : true
                                //     ];
                                // }
                            ],
                            'comments' => [
                                'type' => \Illuminate\Database\Eloquent\Relations\HasMany::class,
                                'count' => 5,
                                'attributes' => [
                                    'user_id' => function () {
                                        return \App\Models\User::inRandomOrder()->first()->id;
                                    },
                                    'body' => ['data' => 'paragraphs', 'params' => [rand(1, 2), true]]
                                ],

                                'relations' => [
                                    'replies' => [
                                        'type' => \Illuminate\Database\Eloquent\Relations\HasMany::class,
                                        'count' => 5,
                                        'attributes' => [
                                            'user_id' => function () {
                                                return \App\Models\User::inRandomOrder()->first()->id;
                                            },
                                            'body' => ['data' => 'paragraphs', 'params' => [rand(1, 2), true]]
                                        ],
                                    ]
                                ]
                            ],
                        ]
                    ]
                ]
            ],


            'export' => [

            ]
        ]
    ]
];