<?php


namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Routing\Controller as BaseController;

class MenuController extends BaseController
{
    /*
    Requirements=>
    - the eloquent expressions should result in EXACTLY one SQL query no matter the nesting level or the amount of menu items.
    - it should work for infinite level of depth (children of childrens children of childrens children, ...)
    - verify your solution with `php artisan test`
    - do a `git commit && git push` after you are done or when the time limit is over

    Hints=>
    - open the `app/Http/Controllers/MenuController` file
    - eager loading cannot load deeply nested relationships
    - a recursive function in php is needed to structure the query results
    - partial or not working answers also get graded so make sure you commit what you have


    Sample response on GET /menu=>
    ```json
    
     */

    public function getMenuItems() {
        $menuItems = MenuItem::orderBy('parent_id', 'desc')->get()->toArray();
        $menu = [];
        $children = [];
        foreach ($menuItems as $menuItem) {
            if ($menuItem['parent_id'] == null) {
                $menuItem['children'] = !empty($children[$menuItem['id']]) ? $children[$menuItem['id']] : [];
                $menu[] = $menuItem;
            } else {
                $menuItem['children'] = !empty($children[$menuItem['id']]) ? $children[$menuItem['id']] : [];
                $children[$menuItem['parent_id']][] = $menuItem;
                if (!empty($children[$menuItem['id']])) {
                    unset($children[$menuItem['id']]);
                }
            }
        }
        return response()->json($menu);
    }
}
