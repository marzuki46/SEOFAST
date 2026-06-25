<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('items')->get();
        // If no menus exist, create a default Primary Menu
        if ($menus->isEmpty()) {
            $menu = Menu::create(['name' => 'Primary Menu', 'location' => 'primary']);
            $menus->push($menu);
        }

        $pages = Page::all();

        return view('admin.menus.index', compact('menus', 'pages'));
    }

    public function storeItems(Request $request, Menu $menu)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.title' => 'required|string',
            'items.*.url' => 'nullable|string',
        ]);

        // Delete existing items for simplicity
        $menu->items()->delete();

        $createdItems = [];
        foreach ($request->items as $index => $itemData) {
            $createdItems[$index] = $menu->items()->create([
                'title' => $itemData['title'],
                'url' => $itemData['url'] ?? '#',
                'order' => $index,
            ]);
        }

        foreach ($request->items as $index => $itemData) {
            if (isset($itemData['parent_index']) && $itemData['parent_index'] !== '' && isset($createdItems[$itemData['parent_index']])) {
                $createdItems[$index]->update(['parent_id' => $createdItems[$itemData['parent_index']]->id]);
            }
        }

        return redirect()->back()->with('success', 'Menu updated successfully.');
    }
}
