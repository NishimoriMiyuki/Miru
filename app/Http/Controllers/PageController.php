<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Http\Requests\PageRequest;
use Illuminate\Http\Request;

class PageController extends Controller
{
    // public function index(Request $request)
    // {
    //     switch ($request->query('type')) {
    //         case 'public':
    //             $type = 'public';
    //             $title = 'メモ（パブリック）';
    //             $pages = auth()->user()->getPublicPages();
    //             break;
    //         case 'private':
    //             $type = 'private';
    //             $title = 'メモ（プライベート）';
    //             $pages = auth()->user()->getPrivatePages();
    //             break;
    //         case 'favorite':
    //             $type = 'favorite';
    //             $title = 'メモ（お気に入り）';
    //             $pages = auth()->user()->getFavoritePages();
    //             break;
    //         case 'all':
    //             $type = 'all';
    //             $title = 'メモ(全て)';
    //             $pages = auth()->user()->getAllPages();
    //             break;
    //         case 'trashed':
    //             $type = 'trashed';
    //             $title = 'メモ(ゴミ箱)';
    //             $pages = auth()->user()->getTrashedPages();
    //             break;
    //         default:
    //             $type = 'all';
    //             $title = 'メモ(全て)';
    //             $pages = auth()->user()->getAllPages();
    //             break;
    //     }
        
    //     $request->session()->put('type', $type);
        
    //     return view('pages.index', compact('pages', 'title'));
    // }
}
