<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function refresh()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

        // Get all table names
        $tables = DB::select('SHOW TABLES');

        // Drop each table
        foreach ($tables as $table) {
            $tableName = $table->{'Tables_in_' . env('DB_DATABASE')}; // For MySQL
            Schema::dropIfExists($tableName);
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

        Artisan::call('migrate');

        dd('done');
    }

    public function home()
    {
        // $users = User::all();
        // dump($users->toArray());

        // encrypt users and update values => OK
        // foreach($users as $user){
        //     $user->encryptAttributes();
        //     $user->updateRecord($user->toArray());
        // }

        // $user = User::create([
        //     'name' => 'blabla',
        //     'email' => 'blabla'.rand(),
        //     'password' => 'passwords'
        // ]);
        // $id = $user->id;
        // $user->deleteRecord();
        // User::forceDeleteRecord($id);
        // dd($user);


        // $params = [
        //     'pagination_name' => 'posts',
        //     // 'page' => 2,
        //     // 'where' => ['id' => 1],
        //     'with' => [
        //         'author' => [
        //             // 'where' => ['id' => 3]
        //         ],
        //         'comments' => [
        //             // 'limit' => 5,
        //             // 'page' => 2,
        //             // 'pagination_name' => 'page2',
        //             'with' => [
        //                 'replies' => [
        //                     // 'limit' => 5,
        //                     // 'page' => 1,
        //                     // 'pagination_name' => 'page3',
        //                 ]
        //             ],
        //             'with_count' => [
        //                 'replies' => 'replies',
        //                 'replies as replies_custom_query' => [
        //                     'where' => ['body' => ['like', '%Dolores%']]
        //                 ],

        //             ],

        //             'having' => [
        //                 'replies_custom_query' => ['>', 15]
        //             ]
        //         ]
        //     ],


        //     'with_count' => [
        //         'comments' => 'comments',
        //         'comments as comments_custom_query' => [
        //             'where' => ['body' => ['like', '%Dolores%']]
        //         ]

        //     ],

        //     'having' => [
        //         'comments_custom_query' => ['>', 10]
        //     ]
        // ];

        // $posts = Post::listRecords($params);

        // dd($posts->toArray());

        // User::seedData();
        // Tag::seedData();
        // Author::seedData();


        // $user_data = [
        //     'name' => 'test',
        //     'email' => 'kub.adriana@yahoo.com',
        //     'password' => '1234'
        // ];

        // if(session()->has('errors')){
        //     dd(session('errors'));
        // }

        // $validation = (new User)->validateAttributes($user_data, true, true);

        // dump($validation);

        // $array = [
        //     ['id' => 1, 'name' => 'John'],
        //     ['id' => 2, 'name' => 'Jane'],
        //     ['id' => 3, 'name' => 'Jack']
        // ];
        // $names = array_column($array, 'name');
        // dump($names);

        $params = [
            'limit' => 10,
            'with' => [
                'author' => [],
                'comments' => [
                    'with' => [
                        'replies' => []
                    ]
                ],
            ]
        ];

        $posts = Post::listRecords($params);
        // dd($posts->toArray());

        $columns = [
            'title' => [
                'label' => trans('label.to.title')
            ],
            'created_at' => [
                'label' => trans('label.to.created'),
                'callback' => function ($item) {
                    return \Carbon\Carbon::parse($item)->format('Y-m-d');
                }
            ],
            'author.name' => [ // accessing the name key inside the author array
                'label' => trans('label.to.name')
            ],
            'author.joined' => [ // accessing the joined key inside the author array
                'label' => trans('label.to.joined'),
                'callback' => function ($item) {
                    return \Carbon\Carbon::parse($item)->format('Y-m-d H:i:s');
                }
            ],
            'comments.body' => [
                'label' => trans('label.to.body'),
                'take' => 'first',
            ],
            'comments.replies.body' => [ // accessing the the body key inside the replies inside comments
                'label' => trans('label.to.body'),
                'callback' => function ($item) {
                    return substr($item, 0, 10);
                }
            ]
        ];


        return (new Post)->exportData($posts, $columns, 'csv', true);




        dd('done');
    }
}
