<?php

use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menus = [
            [
                'url' => route('blogs.index'),
                'name' => 'Blog'
            ],
            [
                'url' => route('courses.all'),
                'name' => 'Courses'
            ],
            [
                'url' => route('bundles.all'),
                'name' => 'Bundles'
            ],
            [
                'url' => asset('forums'),
                'name' => 'Forums'
            ],
            [
                'url' => asset('contact'),
                'name' => 'Contact'
            ],
            [
                'url' => asset('about-us'),
                'name' => 'About Us'
            ]
        ];

        $nav_menu = \Harimayco\Menu\Models\Menus::where('name', '=', 'nav-menu')->first();
        if ($nav_menu == "") {
            $nav_menu = new \Harimayco\Menu\Models\Menus();
        }
        $nav_menu->name = 'nav-menu';
        $nav_menu->save();
        foreach ($menus as $key => $item) {
            $key++;
            $menuItem = \Harimayco\Menu\Models\MenuItems::where('link', '=', $item['url'])
                ->where('menu', '=', $nav_menu->id)->first();
            if ($menuItem == "") {
                $menuItem = new \Harimayco\Menu\Models\MenuItems();
                $menuItem->label = $item['name'];
                $menuItem->link = \Illuminate\Support\Arr::last(explode('/', $item['url']));
                $menuItem->parent = 0;
                $menuItem->sort = $key;
                $menuItem->menu = $nav_menu->id;
                $menuItem->depth = 0;
                $menuItem->save();
            }
        }

        $nav_menu_config = \App\Models\Config::firstOrCreate(['key'=>'nav_menu']);
        $nav_menu_config->value = $nav_menu->id;
        $nav_menu_config->save();

    }
}
