<div>
    <input id="title" type="text" wire:model.debounce.500ms="title" placeholder="タイトルを入力して下さい" value="{{ $page->title }}" class="shadow appearance-none border border-transparent w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-3xl font-semibold">

    <textarea id="content" wire:model.debounce.500ms="content" rows="50" placeholder="内容を入力して下さい" class="shadow appearance-none border border-transparent w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ $page->content }}</textarea>
</div>