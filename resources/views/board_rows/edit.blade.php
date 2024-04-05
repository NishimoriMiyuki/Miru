<x-app-layout>
    <div class="flex">
        
        <!-- サイドバー -->
        <div class="w-1/4 h-screen bg-gray-200 overflow-auto">
            
            <!-- ボタン -->
            <div class="p-4 flex flex-col space-y-2">
                <a href="{{ route('boards.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    新規作成
                </a>
                <a href="{{ route('boards.trashed') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    ゴミ箱を開く
                </a>
            </div>
            
            <!-- 各ページのリンク -->
            <ul class="space-y-2 p-4">
                @foreach ($boards as $linkboard)
                    <li>
                        <div class="flex justify-between">
                            <a href="{{ route('boards.edit', $linkboard) }}" class="text-blue-500 hover:underline">
                                {{ \Illuminate\Support\Str::limit($linkboard->name, 20) }}
                            </a>
                            <!-- ドロップダウンメニュー -->
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <!-- 削除ボタン -->
                                    <form method="POST" action="{{ route('boards.destroy', $linkboard) }}">
                                        @csrf
                                        @method('DELETE')
                                        <x-dropdown-link :href="route('boards.destroy', $linkboard)"
                                                onclick="event.preventDefault();
                                                            this.closest('form').submit();">
                                            {{ __('削除') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        
        <div class="container mx-auto px-4 mt-4">
            <!-- レコード編集フォーム -->   
            <form action="{{ route('board_rows.update', $boardRow)}}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="title" class="block text-sm font-medium text-gray-700">タイトル:</label>
                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="title" name="title" value="{{ $boardRow->title }}">
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>
                <!-- 問題 -->
                <div class="mb-3">
                    <label for="quiz-content" class="block text-sm font-medium text-gray-700">問題:</label>
                    <textarea class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="quiz-content" name="quiz_content" >{{ $boardRow->quiz_content }}</textarea>
                    <x-input-error :messages="$errors->get('quiz_content')" class="mt-2" />
                </div>
                <!-- 答え -->
                <div class="mb-3">
                    <label for="quiz-answer" class="block text-sm font-medium text-gray-700">答え:</label>
                    <textarea class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="quiz-answer" name="quiz_answer">{{ $boardRow->quiz_answer }}</textarea>
                    <x-input-error :messages="$errors->get('quiz_answer')" class="mt-2" />
                </div>
                <!-- 難易度 -->
                <div class="mb-3">
                    <label for="difficulty-level" class="block text-sm font-medium text-gray-700">難易度:</label>
                    <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="difficulty-level" name="difficulty_level_id">
                        @foreach ($difficultyLevels as $difficultyLevel)
                            <option value="{{ $difficultyLevel->id }}" {{ $boardRow->difficulty_level_id == $difficultyLevel->id ? 'selected' : '' }}>{{ $difficultyLevel->type }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('difficulty_level_id')" class="mt-2" />
                </div>
                <!-- タグ -->
                <div class="mb-3">
                    <label for="tagField" class="block text-sm font-medium text-gray-700">タグ:</label>
                    <div id="tagContainer" data-board-row-id={{ $boardRow->id }}>
                        @foreach($boardRow->tags as $boardRowTag)
                            <div class="tagField relative">
                                <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="tag[]" data-tag-id="{{ $boardRowTag->id }}" value="{{ $boardRowTag->name }}" readonly>
                                <button class="absolute top-0 right-0 mt-2 mr-2 tagClear" type="button">×</button>
                            </div>
                        @endforeach
                        <div class="tagField relative">
                            <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="tag[]">
                        </div>
                    </div>
                    <button id="addTag" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">タグを追加</button>
                </div>
                <!-- タグ一覧 -->
                <div class="mb-3">
                    <label for="tagList" class="block text-sm font-medium text-gray-700">タグ一覧:</label>
                    <div id="tagListContainer" data-board-row-id={{ $boardRow->id }}>
                        @foreach($tags as $tag)
                            <div class="tagList relative">
                                <input type="text" class="tag-item cursor-pointer mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="tag[]" data-tag-id="{{ $tag->id }}" value="{{ $tag->name }}" readonly>
                                <button class="absolute top-0 right-0 mt-2 mr-2 tagDelete" type="button">×</button>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- 状態 -->
                <div class="mb-3">
                    <label for="status" class="block text-sm font-medium text-gray-700">状態:</label>
                    <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="status" name="status_id">
                        @foreach ($statuses as $status)
                            <option value="{{ $status->id }}" {{ $boardRow->status_id == $status->id ? 'selected' : '' }}>{{ $status->type }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('status_id')" class="mt-2" />
                </div>
                <!-- 質問とコメント -->
                <div class="flex flex-col space-y-4">
                    <div>
                        <button class="w-full px-4 py-2 text-left bg-gray-200" type="button">
                            質問予想
                        </button>
                        <div class="p-4 bg-white">
                            <input type="text" id="questionInput" class="w-full px-4 py-2 border border-gray-300" placeholder="質問を入力してください">
                            <button id="submitQuestion" data-board-row-id="{{ $boardRow->id }}" class="w-full mt-2 px-4 py-2 text-white bg-blue-500">送信</button>
                            <ul id="questionList" class="mt-2 space-y-2">
                                @foreach($boardRow->questions as $boardRowQuestion)
                                    <li class="list-group-item" data-question-id={{ $boardRowQuestion->id}}>
                                        {{ $boardRowQuestion->content }}
                                        <button type="button" onclick="deleteQuestion({{ $boardRowQuestion->id }})">削除</button>
                                        <div style="float: right;">
                                            <input type="radio" name="{{ 'choice'.$boardRowQuestion->id }}" value="yes" onclick="saveChoice({{ $boardRowQuestion->id }}, this.value)" {{ $boardRowQuestion->answer ? 'checked' : '' }}>
                                            <label>Yes</label>
                                            <input type="radio" name="{{ 'choice'.$boardRowQuestion->id }}" value="no" onclick="saveChoice({{ $boardRowQuestion->id }}, this.value)" {{ !$boardRowQuestion->answer ? 'checked' : '' }}>
                                            <label>No</label>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div>
                        <button class="w-full px-4 py-2 text-left bg-gray-200" type="button">
                            コメントを残す
                        </button>
                        <div class="p-4 bg-white">
                            <input type="text" id="commentInput" class="w-full px-4 py-2 border border-gray-300" placeholder="コメントを入力してください">
                            <button id="submitComment" data-board-row-id="{{ $boardRow->id }}" class="w-full mt-2 px-4 py-2 text-white bg-blue-500">送信</button>
                            <ul id="commentList" class="mt-2 space-y-2">
                                @foreach($boardRow->comments as $boardRowComment)
                                    <li class="list-group-item" data-comment-id={{ $boardRowComment->id }}>
                                        {{ $boardRowComment->content }}
                                        <button type="button" onclick="deleteComment({{ $boardRowComment->id }})">削除</button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700 transition duration-200 ease-in-out">
                    保存
                </button>
                <a href="{{ route('boards.edit', $board) }}" class="px-4 py-2 ml-3 inline-block bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition duration-200 ease-in-out">
                    戻る
                </a>
            </form>
        </div>
        
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    
    // コメントを残す送信ボタン
    $('#submitComment').click(function(e){
        e.preventDefault();
    
        var commentInput = $('#commentInput');
        var boardRowId = $(this).data('boardRowId');
        var commentList = $('#commentList');
    
        $.ajax({
            url: '/comments',
            method: 'POST',
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify({
                content: commentInput.val(),
                board_row_id: boardRowId
            }),
            success: function(data) {
                // リセット
                commentList.html('');
                commentInput.val('');
                
                // コメントの数だけ要素を作る
                $.each(data.success, function(i, comment) {
                    // 各コメント
                    var li = $('<li></li>').text(comment.content).addClass('list-group-item').attr('data-comment-id', comment.id);
                    // 削除ボタン
                    var deleteButton = $('<button></button>').text('削除').attr('type', 'button').click(function() {
                        deleteComment(comment.id);
                    });
                    li.append(deleteButton);
                    commentList.append(li);
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('There has been a problem with your fetch operation:', errorThrown);
            }
        });
    });
    
    // 質問の送信ボタン
    $(document).ready(function() {
        $('#submitQuestion').click(function(e){
            e.preventDefault();
        
            var questionInput = $('#questionInput');
            var boardRowId = $(this).data('boardRowId');
            var questionList = $('#questionList');
        
            $.ajax({
                url: '/questions',
                method: 'POST',
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: JSON.stringify({
                    content: questionInput.val(),
                    board_row_id: boardRowId
                }),
                success: function(data) {
                    // リセット
                    questionList.html('');
                    questionInput.val('');
                    
                    // questionの数だけ要素作る
                    $.each(data.success, function(i, question) {
                        var li = $('<li></li>').text(question.content).addClass('list-group-item').attr('data-question-id', question.id);
                        
                        var radioDiv = $('<div></div>').css('float', 'right');
                        
                        // ラジオボタン
                        var yesRadio = $('<input>').attr({type: 'radio', name: 'choice' + question.id, value: 'yes', checked: question.answer !== null ? question.answer : false});
                        var yesLabel = $('<label></label>').text('Yes');
                        
                        var noRadio = $('<input>').attr({type: 'radio', name: 'choice' + question.id, value: 'no', checked: question.answer !== null ? !question.answer : false});
                        var noLabel = $('<label></label>').text('No');
                        
                        // イベント登録
                        yesRadio.change(function() {
                            saveChoice(question.id, this.value);
                        });
                        noRadio.change(function() {
                            saveChoice(question.id, this.value);
                        });
                        
                        radioDiv.append(yesRadio, yesLabel, noRadio, noLabel);
                        
                        var deleteButton = $('<button></button>').text('削除').attr('type', 'button').click(function() {
                            deleteQuestion(question.id);
                        });
                        
                        li.append(deleteButton, radioDiv);
                        
                        questionList.append(li);
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('There has been a problem with your fetch operation:', errorThrown);
                }
            });
        });
    });
    
    // 選択肢を保存する
    function saveChoice(questionId, choice) {
        $.ajax({
            url: '/questions/' + questionId,
            method: 'PUT',
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify({
                question_id: questionId,
                choice: choice
            }),
            success: function(data) {
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('There has been a problem with your fetch operation:', errorThrown);
            }
        });
    }
    
    // 質問を削除する
    function deleteQuestion(questionId) {
        $.ajax({
            url: '/questions/' + questionId,
            method: 'DELETE',
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify({
                question_id: questionId
            }),
            success: function(data) {
                // 質問のリストから削除
                $('li[data-question-id="' + questionId + '"]').remove();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('There has been a problem with your fetch operation:', errorThrown);
            }
        });
    }
    
    // コメントを削除する
    function deleteComment(commentId) {
        $.ajax({
            url: '/comments/' + commentId,
            method: 'DELETE',
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify({
                comment_id: commentId
            }),
            success: function(data) {
                // コメントのリストから削除
                $('li[data-comment-id="' + commentId + '"]').remove();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // エラーが発生したとき
                console.error('There has been a problem with your fetch operation:', errorThrown);
            }
        });
    }
    
    // タグ追加ボタン
    $(document).ready(function() {
        $('#addTag').click(function(e) {
            // デフォルトのフォーム送信を止めるやつ
            e.preventDefault();
    
            // 最後のinputとってきて前後の空白削除
            var lastTag = $('.tagField input').last().val().trim();
    
            var boardRowId = $('#tagContainer').data('board-row-id');
    
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
    
            // 最後が空じゃなかったら
            if (lastTag !== '') {
                $.ajax({
                    url: '/tags/store',
                    method: 'POST',
                    data: {
                        name: lastTag,
                        board_row_id: boardRowId
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(data) {
                        // 最後を読み取り専用にしてidつけてクリアボタンつける
                        $('.tagField input').last().attr('readonly', true).attr('data-tag-id', data.tag.id).parent().append('<button class="absolute top-0 right-0 mt-2 mr-2 tagClear" type="button">×</button>');
    
                        // 新しいtagFieldを入れる
                        $('#tagContainer').append('<div class="tagField relative"><input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="tag[]"></div>').find('input').last().focus();
    
                        // 追加したタグを一覧にも反映させるために入れる
                        var newTagItem = '<div class="tagList relative"><input type="text" class="tag-item cursor-pointer mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="tag[]" data-tag-id="' + data.tag.id + '" value="' + data.tag.name + '" readonly><button class="absolute top-0 right-0 mt-2 mr-2 tagDelete" type="button">×</button></div>';
                        $('#tagListContainer').append(newTagItem);
                    },
                    error: function(error) {
                        console.log('An error occurred while processing your request.');
                    }
                });
            }
        });
    });

    // 各タグボタン タグをboardRowに紐付ける
    $(document).ready(function() {
        $(document).on('click', '#tagListContainer .tag-item', function() {
            var tagName = $(this).val();
            var tagId = $(this).data('tag-id');
    
            var boardRowId = $('#tagContainer').data('board-row-id');
    
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
    
            $.ajax({
                url: '/tags/attachTagToBoardRow',
                method: 'POST',
                data: {
                    tag_id: tagId,
                    board_row_id: boardRowId
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(data) {
                    // タグ追加があったら
                    if (data.attached) 
                    {
                        // tagField作る読み取り
                        var newTagInput = $('<div class="tagField relative"><input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="tag[]" data-tag-id="' + tagId + '" readonly><button class="absolute top-0 right-0 mt-2 mr-2 tagClear" type="button">×</button></div>');
                        
                        // クリックしたタグの名前にする
                        newTagInput.find('input').val(tagName);
                        
                        // タグコンテナの一番上に追加
                        $('#tagContainer').prepend(newTagInput);
                    }
                },
                error: function(error) {
                    console.log('An error occurred while processing your request.');
                }
            });
        });
    });
    
    // tagClearボタン 紐付いてるタグを外す
    $(document).on('click', '.tagClear', function() {
        var tagId = $(this).siblings('input').data('tag-id');
        var boardRowId = $('#tagContainer').data('board-row-id');
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
    
        $.ajax({
            url: '/tags/detachTagFromBoardRow',
            method: 'POST',
            data: {
                _method: 'DELETE',
                tag_id: tagId,
                board_row_id: boardRowId
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(data) {
                var targetElement = $('input[data-tag-id="' + tagId + '"]').closest('.tagField').remove();
                // console.log('targetElement:', targetElement);
            },
            error: function(error) {
                console.log('An error occurred while processing your request.');
            }
        });
    });
    
    // tagDeleteボタン タグを削除
    $(document).on('click', '.tagDelete', function() {
        var tagId = $(this).siblings('input').data('tag-id');
        var boardRowId = $('#tagContainer').data('board-row-id');
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
    
        $.ajax({
            url: '/tags/delete',
            method: 'POST',
            data: {
                _method: 'DELETE',
                tag_id: tagId,
                board_row_id: boardRowId
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(data) {
                var targetElement1 = $('input[data-tag-id="' + tagId + '"]').closest('.tagList').remove();
                var targetElement2 = $('input[data-tag-id="' + tagId + '"]').closest('.tagField').remove();
                console.log('targetElement1:', targetElement1);
                console.log('targetElement2:', targetElement2);
            },
            error: function(error) {
                console.log('An error occurred while processing your request.');
            }
        });
    });
    
    // テキストエリアの高さオート
    $(document).ready(function() {
        $('textarea').on('input', function () {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });
</script>

</x-app-layout>