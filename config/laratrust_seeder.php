<?php

return [
    'role_structure' => [
        'super' => [
            'users'      => 'c,r,u,d',
            'roles'      => 'c,r,u,d',
            'categories' => 'c,r,u,d',
            'employees' => 'c,r,u,d',
            'employers' => 'c,r,u,d',
            'jobs' => 'c,r,u,d',
            'employeejobs' => 'c,r,u,d',
            'countries'=> 'c,r,u,d',
            'cities'=> 'c,r,u,d',
            'ads'=> 'c,r,u,d',




            
        ],
    ],
    // 'permission_structure' => [
    //     'cru_user' => [
    //         'profile' => 'c,r,u'
    //     ],
    // ],
    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
