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
        $pages = auth()->user()->pages;
        return view('pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $page = new Page;
        $page->title = '無題';
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
        
        $pages = auth()->user()->pages;
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
        $pages = auth()->user()->pages;
        
        $trashedPages = auth()->user()->pages()->onlyTrashed()->paginate(5);
            
        return view('pages.trashed', compact('trashedPages', 'pages'));
    }
    
    public function deleteAll()
    {
        // すべての論理削除されたページを永久に削除
        auth()->user()->pages()->onlyTrashed()->forceDelete();
        
        session()->flash('message', 'ゴミ箱の中身を全て削除しました');
        return redirect()->route('pages.index');
    }
    
    public function deleteSelected(Request $request)
    {
        $selectedPagesId = $request->input('selectedPages');
        
        // idが一致するページを永久に削除
        auth()->user()->pages()->onlyTrashed()->whereIn('id', $selectedPagesId)->forceDelete();
        
        session()->flash('message', '選択されたページを削除しました。');
        return redirect()->route('pages.trashed');
    }
    
    public function restoreSelected(Request $request)
    {
        $selectedPagesId = $request->input('selectedPages');

        auth()->user()->pages()->whereIn('id', $selectedPagesId)->restore();

        session()->flash('message', '選択されたページを復元しました。');
        return redirect()->route('pages.index');
    }
}
