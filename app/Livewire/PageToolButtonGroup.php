// <?php

// namespace App\Livewire;

// use App\Models\Page;
// use Livewire\Component;

// class PageToolButtonGroup extends Component
// {
//     public Page $page;
//     public $isFavorite;
//     public $isPublic;
    
//     public function mount(Page $page)
//     {
//         $this->page = $page;
//         $this->isFavorite = $this->page->is_favorite;
//         $this->isPublic = $this->page->is_public;
//     }
    
//     public function toggleFavorite()
//     {
//         $this->isFavorite = !$this->isFavorite;
        
//         $this->authorize('update', $this->page);
//         $this->page->is_favorite = $this->isFavorite;
//         $this->page->save();
//     }
    
//     public function togglePublic()
//     {
//         $this->isPublic = !$this->isPublic;
        
//         $this->authorize('update', $this->page);
//         $this->page->is_public = $this->isPublic;
//         $this->page->save();
//     }
    
//     public function delete()
//     {
//         $this->authorize('delete', $this->page);
//         $this->page->delete();
        
//         $this->close();
//     }
    
//     public function forceDelete()
//     {
//         $this->authorize('forceDelete', $this->page);
//         $this->page->forceDelete();
        
//         $this->close();
//     }
    
//     public function restore()
//     {
//         $this->authorize('restore', $this->page);
//         $this->page->restore();
        
//         $this->close();
//     }
    
//     public function close()
//     {
//         $this->dispatch('close-edit');
//     }
    
//     public function render()
//     {
//         if ($this->page->trashed())
//         {
//             return <<<'HTML'
//             <div class="flex bg-white pt-2 text-gray-500 flex justify-between">
//                 <div class="flex">
//                     <div wire:loading.remove x-data="{ isOpen: false }" style="position: relative;" @mouseover="isOpen = true" @mouseout="isOpen = false">
//                         <button wire:click="restore" class="px-2 py-1">
//                             <span class="material-symbols-outlined">
//                                 restore_from_trash
//                             </span>
//                         </button>
//                         <div x-show="isOpen" style="position: absolute; top: calc(100% + 2px); left: 50%; transform: translateX(-50%); width: auto; background-color: #333; color: #fff; padding: 10px; border-radius: 5px; white-space: nowrap;">
//                             復元する
//                         </div>
//                     </div>
//                     <div wire:loading.remove x-data="{ isOpen: false }" style="position: relative;" @mouseover="isOpen = true" @mouseout="isOpen = false">
//                         <button wire:click="forceDelete" class="px-2 py-1" wire:confirm="完全に削除されます。よろしいですか？">
//                             <span class="material-symbols-outlined">
//                                 delete_forever
//                             </span>
//                         </button>
//                         <div x-show="isOpen" style="position: absolute; top: calc(100% + 2px); left: 50%; transform: translateX(-50%); width: auto; background-color: #333; color: #fff; padding: 10px; border-radius: 5px; white-space: nowrap;">
//                             完全に削除する
//                         </div>
//                     </div>
//                 </div>
//                 <button wire:click="close" class="px-2 py-1">
//                     閉じる
//                 </div>
//             </div>
//             HTML;
//         }
//         else 
//         {
//             return <<<'HTML'
//             <div class="flex bg-white pt-2 text-gray-500 flex justify-between">
//                 <div class="flex">
//                     <div wire:loading.remove x-data="{ isOpen: false }" style="position: relative;" @mouseover="isOpen = true" @mouseout="isOpen = false">
//                         <button wire:click="delete" class="px-2 py-1">
//                             <span class="material-symbols-outlined">
//                                 delete
//                             </span>
//                         </button>
//                         <div x-show="isOpen" style="position: absolute; top: calc(100% + 2px); left: 50%; transform: translateX(-50%); width: auto; background-color: #333; color: #fff; padding: 10px; border-radius: 5px; white-space: nowrap;">
//                             ゴミ箱に入れる
//                         </div>
//                     </div>
//                     <div wire:loading.remove x-data="{ isOpen: false }" style="position: relative;" @mouseover="isOpen = true" @mouseout="isOpen = false">
//                         <button wire:click="toggleFavorite" class="px-2 py-1">
//                             @if($isFavorite)
//                                 <span class="material-symbols-outlined">
//                                     heart_check
//                                 </span>
//                             @else
//                                 <span class="material-symbols-outlined">
//                                     favorite
//                                 </span>
//                             @endif
//                         </button>
//                         <div x-show="isOpen" style="position: absolute; top: calc(100% + 2px); left: 50%; transform: translateX(-50%); width: auto; background-color: #333; color: #fff; padding: 10px; border-radius: 5px; white-space: nowrap;">
//                             @if($isFavorite)
//                                 お気に入り解除
//                             @else
//                                 お気に入り登録
//                             @endif
//                         </div>
//                     </div>
//                     <div wire:loading.remove x-data="{ isOpen: false }" style="position: relative;" @mouseover="isOpen = true" @mouseout="isOpen = false">
//                         <button wire:click="togglePublic" class="px-2 py-1" wire:confirm="{{ $isPublic ? '貴方だけが閲覧できる設定に変更します。非公開にしますか？' : '全てのユーザーがこのメモを閲覧できる設定に変更します。本当に公開しますか？' }}">
//                             @if($isPublic)
//                                 <span class="material-symbols-outlined">
//                                     public
//                                 </span>
//                             @else
//                                 <span class="material-symbols-outlined">
//                                     public_off
//                                 </span>
//                             @endif
//                         </button>
//                         <div x-show="isOpen" style="position: absolute; top: calc(100% + 2px); left: 50%; transform: translateX(-50%); width: auto; background-color: #333; color: #fff; padding: 10px; border-radius: 5px; white-space: nowrap;">
//                             @if($isPublic)
//                                 非公開にする
//                             @else
//                                 公開する
//                             @endif
//                         </div>
//                     </div>
//                 </div>
//                 <button wire:click="close" class="px-2 py-1">
//                     閉じる
//                 </div>
//             </div>
//             HTML;
//         }
//     }
// }
