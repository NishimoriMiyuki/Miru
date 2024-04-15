<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Http\Requests\PageRequest;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $pages = auth()->user()->pages;
        return view('pages.index', compact('pages'));
    }

    public function create()
    {
        $page = auth()->user()->pages()->create();
        $page->title = "てすと";
        
        return view('pages.edit', compact('page'));
    }

    public function store(PageRequest $request)
    {
        $page = Page::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => auth()->id(),
        ]);
        
        session()->flash('message', '新しいページが作成されました');
        
        return redirect()->route('pages.edit', compact('page'));
    }
    
    public function show($page)
    {
        $page = Page::withTrashed()->findOrFail($page);
        
        $this->authorize('view', $page);
        
        $trashedPages = auth()->user()->pages()->onlyTrashed()->get();
        
        return view('pages.show', compact('page', 'trashedPages'));
    }

    public function edit(Page $page)
    {
        $this->authorize('update', $page);
        
        return view('pages.edit', compact('page'));
    }

    public function update(PageRequest $request, Page $page)
    {
        $this->authorize('update', $page);
        
        $page->update($request->validated());
        
        session()->flash('message', 'ページが更新されました');
        return redirect()->route('pages.edit', compact('page'));
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
