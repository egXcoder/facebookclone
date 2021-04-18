<?php

namespace Database\Seeders;

use App\Models\PostActivity;
use Illuminate\Database\Seeder;

class PostActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $activities = [
            [
                'name'=>'Celebrating',
                'icon_url'=>'/images/celebrating.png',
                'children'=>[
                    [
                        "friendship",
                        "/images/54-gJ-D-MAC.png",
                    ],[
                        "a birthday",
                        "/images/h_ZL8plxtHj.png",
                    ],[
                        "your special day",
                        "/images/54-gJ-D-MAC.png",
                    ],[
                        "birthdays",
                        "/images/J4u8-SI_Ax4.png",
                    ],[
                        "Christmas",
                        "/images/54-gJ-D-MAC.png",
                    ],[
                        "New Year's Eve",
                        "/images/54-gJ-D-MAC.png",
                    ],[
                        "this special day",
                        "/images/54-gJ-D-MAC.png",
                    ],[
                        "my brother's birthday",
                        "/images/C6EVMzISFXp.png",
                    ]
                ]
            ],
            [
                'name'=>'Watching',
                'icon_url'=>'/images/watching.png',
            ],
            [
                'name'=>'Eating',
                'icon_url'=>'/images/eating.png',
                'children'=>[
                    [
                        "lunch",
                        "/images/BP0C_-w87mZ.png",
                    ],
                    [
                        "dinner",
                        "/images/u0oQVabWJWO.png",
                    ],
                    [
                        "breakfast",
                        "/images/4ZNnfA3I6y6.png",
                    ],[
                        "dinner with beloved family♥",
                        "/images/4ZNnfA3I6y6.png",
                    ],[
                        "dinner with my love",
                        "/images/u0oQVabWJWO.png",
                    ],[
                        "brunch",
                        "/images/4ZNnfA3I6y6.png",
                    ],[
                        "eating dinner",
                        "/images/y07LdtT_U31.png",
                    ],[
                        "burgers and fries",
                        "/images/4ZNnfA3I6y6.png",
                    ],[
                        "deliciousness",
                        "/images/1j56anHZQbz.png",
                    ],[
                        "dessert",
                        "/images/reXgWQb2lwz.png",
                    ],[
                        "Mexican food",
                        "/images/4ZNnfA3I6y6.png",
                    ],[
                        "lunch with family",
                        "/images/5AF0Uuf4eho.png",
                    ]
                ]
            ],
            [
                'name'=>'Drinking',
                'icon_url'=>'/images/drinking.png',
                'children'=>[
                    [
                        "coffee",
                        "/images/0gYGK7_Enlf.png",
                    ],
                    [
                        "adult beverages",
                        "/images/9smbMVNK2g8.png",
                    ],[
                        "tea",
                        "/images/dzEv8D2tIiM.png",
                    ],[
                        "iced coffee",
                        "/images/dzEv8D2tIiM.png",
                    ],[
                        "frappe",
                        "/images/9smbMVNK2g8.png",
                    ],[
                        "latte",
                        "/images/9smbMVNK2g8.png",
                    ],[
                        "Capuccino",
                        "/images/FEVCh4yA65Z.png",
                    ],[
                        "water",
                        "/images/0gYGK7_Enlf.png",
                    ]
                ]
            ],
            [
                'name'=>'Attending',
                'icon_url'=>'/images/attending.png',
                'children'=>[
                    [
                        "church",
                        "/images/RuGtPUZxWzS.png",
                    ],[
                        "a wedding",
                        "/images/uL3zo5tbvfq.png",
                    ],[
                        "an event",
                        "/images/54-gJ-D-MAC.png",
                    ],[
                        "a birthday party",
                        "/images/J4u8-SI_Ax4.png",
                    ],[
                        "a Christmas party",
                        "/images/U4xnS4Djrf8.png",
                    ],[
                        "work",
                        "/images/tBH4nUzSV5T.png",
                    ],[
                        "a live concert",
                        "/images/lER6FZ1fH9-.png",
                    ],[
                        "a meeting",
                        "/images/QZR0aJ7gvjj.png",
                    ]
                ]
            ],
            [
                'name'=>'Travelling',
                'icon_url'=>'/images/travelling.png',
            ],
            [
                'name'=>'Listening To',
                'icon_url'=>'/images/listening.png',
                'children'=>[
                    [
                        "Music",
                        "/images/Axc7njJO1Gw.png",
                    ],[
                        "YouTube",
                        "/images/Axc7njJO1Gw.png",
                    ],[
                        "Gospel song",
                        "/images/Axc7njJO1Gw.png",
                    ],[
                        "Spotify",
                        "/images/tshouyp_LS1.png",
                    ],[
                        "Pandora",
                        "/images/Z04N9xmkAN3.png",
                    ]

                ]
            ],
            [
                'name'=>'Looking For',
                'icon_url'=>'/images/looking.png',
                'children'=>[
                    [
                        "advice",
                        "/images/YPRmBdmihaP.png",
                    ],[
                        "help",
                        "/images/vRixRpGIYR_.png",
                    ],[
                        "a miracle",
                        "/images/n5bIQfQxFxI.png",
                    ],
                    [
                        "answers",
                        "/images/zAxN_cGh9M1.png",
                    ],[
                        "ideas",
                        "/images/n5bIQfQxFxI.png",
                    ],[
                        "prayers",
                        "/images/n5bIQfQxFxI.png",
                    ],[
                        "perfection",
                        "/images/1j56anHZQbz.png",
                    ],[
                        "birthday gift recommendations",
                        "/images/XAZ0RKpXbnx.png",
                    ],[
                        "Nessie",
                        "/images/n5bIQfQxFxI.png",
                    ]
                ]
            ],
            [
                'name'=>'Reading',
                'icon_url'=>'/images/reading.png',
            ],
        ];

        foreach ($activities as $activity) {
            $created = PostActivity::create([
                'name' => $activity['name'],
                'icon_url' => $activity['icon_url'],
            ]);

            if (array_key_exists('children', $activity)) {
                foreach ($activity['children'] as $child) {
                    PostActivity::create([
                        'name'=>$child[0],
                        'icon_url'=>$child[1],
                        'parent_id'=>$created->id
                    ]);
                }
            }
        }
    }
}
