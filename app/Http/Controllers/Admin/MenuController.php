<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Language;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\MenuItemTranslation;
use App\Models\Page;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $menus = Menu::with(['allItems.translations', 'allItems.parent'])->get();
        $activeMenu = $request->filled('menu_id')
            ? Menu::with(['allItems.translations', 'allItems.parent'])->find($request->menu_id)
            : $menus->first();

        $pages = Page::where('is_published', true)->with('translations')->get();
        $categories = ProductCategory::where('is_active', true)->with('translations')->get();
        $languages = Language::active()->get();

        return view('admin.menus.index', compact('menus', 'activeMenu', 'pages', 'categories', 'languages'));
    }

    public function storeItem(Request $request, Menu $menu)
    {
        $request->validate([
            'type' => ['required', 'string'],
            'target_url' => ['nullable', 'string'],
            'translations' => ['required', 'array'],
            'parent_id' => ['nullable', 'exists:menu_items,id'],
        ]);

        $item = MenuItem::create([
            'menu_id' => $menu->id,
            'parent_id' => $request->parent_id,
            'type' => $request->type,
            'target_id' => $request->target_id,
            'target_url' => $request->target_url,
            'order' => MenuItem::where('menu_id', $menu->id)->max('order') + 1,
            'is_active' => true,
        ]);

        foreach ($request->translations as $locale => $tData) {
            MenuItemTranslation::create([
                'menu_item_id' => $item->id,
                'locale' => $locale,
                'title' => $tData['title'] ?? 'Link',
                'url' => $tData['url'] ?? $request->target_url,
            ]);
        }

        ActivityLog::log('add_menu_item', "Added menu item to {$menu->name}");

        return redirect()->route('admin.menus.index', ['menu_id' => $menu->id])->with('success', 'Menu item added successfully.');
    }

    public function deleteItem(MenuItem $item)
    {
        $menuId = $item->menu_id;
        $item->delete();

        ActivityLog::log('delete_menu_item', "Deleted menu item #{$item->id}");

        return redirect()->route('admin.menus.index', ['menu_id' => $menuId])->with('success', 'Menu item removed.');
    }
}
