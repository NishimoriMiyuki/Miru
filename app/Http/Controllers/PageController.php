<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Http\Requests\PageRequest;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(Request $request)
    {
        switch ($request->query('type')) {
            case 'public':
                $type = 'public';
                $pages = auth()->user()->getPublicPages();
                break;
            case 'private':
                $type = 'private';
                $pages = auth()->user()->getPrivatePages();
                break;
            case 'favorite':
                $type = 'favorite';
                $pages = auth()->user()->getFavoritePages();
                break;
            default:
                $type = 'private';
                $pages = auth()->user()->getPrivatePages();
                break;
        }
        
        $request->session()->put('type', $type);
        
        return view('pages.index', compact('pages'));
    }

    public function create()
    {
        $page = auth()->user()->pages()->create();
        $page->title = "無題";
        $page->save();
        
        return redirect()->route('pages.edit', compact('page'));
    }
    
    public function show($page)
    {
        $page = Page::withTrashed()->findOrFail($page);
        
        $this->authorize('view', $page);
        
        $trashedPages = auth()->user()->pages()->onlyTrashed()->get();
        
        return view('pages.show', compact('page', 'trashedPages'));
    }

    public function destroy(Page $page)
    {
        $this->authorize('delete', $page);
        
        $page->delete();
        
        session()->flash('message', 'ページを削除しました');
        return redirect(route('pages.index'));
    }
    
    public function forceDelete($page)
    {
        $page = Page::withTrashed()->findOrFail($page);
        
        $this->authorize('delete', $page);
        
        $page->forceDelete();
        
        session()->flash('message', 'ページを完全に削除しました');
        return redirect(route('pages.trashed'));
    }
    
    public function restore($page)
    {
        $page = Page::withTrashed()->findOrFail($page);
        
        $this->authorize('delete', $page);
        
        $page->restore();
        
        session()->flash('message', 'ページを復元しました');
        return redirect(route('pages.trashed'));
    }
    
    public function trashed()
    {
        $trashedPages = auth()->user()->pages()->onlyTrashed()->get();
            
        return view('pages.trashed', compact('trashedPages'));
    }
}
