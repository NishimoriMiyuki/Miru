<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::where('user_id', auth()->id())->get();
        return view('pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $page = new Page;
        $page->title = "title";
        $page->content = "content";
        $page->user_id = auth()->id();
        $page->save();
        
        return redirect()->route('pages.show', $page);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        // ページの所有者が現在のユーザーでなければ、403エラーを返す
        if ($page->user_id !== auth()->id()) {
            abort(403);
        }
        
        $pages = Page::where('user_id', auth()->id())->get();
        return view('pages.show', ['select_page' => $page, 'pages' => $pages]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        // ページの所有者が現在のユーザーでなければ、403エラーを返す
        if ($page->user_id !== auth()->id()) {
            abort(403);
        }
        
        $data = $request->only(['title', 'content']);
        $page->update($data);
        return redirect()->route('pages.show', $page);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        // ページの所有者が現在のユーザーでなければ、403エラーを返す
        if ($page->user_id !== auth()->id()) {
            abort(403);
        }
        
        // 論理削除
        $page->delete();
        
        session()->flash('message', '削除しました');
        return redirect()->route('pages.index');
    }
    
    public function trashed()
    {
        $pages = Page::where('user_id', auth()->id())->get();
        
        $trashedPages = Page::onlyTrashed()
            ->where('user_id', auth()->id())
            ->paginate(5);
            
        return view('pages.trashed', compact('trashedPages', 'pages'));
    }
    
    public function deleteAll()
    {
        $trashedPages = Page::onlyTrashed()->where('user_id', auth()->id())->get();
        
        // すべての論理削除されたページを永久に削除
        foreach ($trashedPages as $trashedPage) 
        {
            $trashedPage->forceDelete();
        }
        
        session()->flash('message', 'ゴミ箱の中身を全て削除しました');
        return redirect()->route('pages.index');
    }
    
    public function deleteSelected(Request $request)
    {
        $selectedPagesId = $request->input('selectedPages');
        $pages = Page::onlyTrashed()->where('user_id', auth()->id())->whereIn('id', $selectedPagesId)->get();
        
        foreach ($pages as $page)
        {
            $page->forceDelete();
        }
        
        session()->flash('message', '選択されたページを削除しました。');
        return redirect()->route('pages.trashed');
    }
    
    public function restoreSelected(Request $request)
    {
        $selectedPagesId = $request->input('selectedPages');

        Page::where('user_id', auth()->id())->whereIn('id', $selectedPagesId)->restore();

        session()->flash('message', '選択されたページを復元しました。');
        return redirect()->route('pages.index');
    }
}
