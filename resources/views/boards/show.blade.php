<x-app-layout>
    <div class="flex">
        <!-- ページ一覧部分 -->
        <x-board-list :boards="$boards" />
        
        <div class="container">
            <!-- タイトルを変更するフォーム -->
            <form action="{{ route('boards.update', $board) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="text" name="name" value="{{ $board->name }}">
                <button type="submit">更新</button>
            </form>
            
            <!-- モーダルを開くボタン 新しいレコードを作る -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newRowModal">新規作成</button>
            
            <!-- レコードをカードで表示 -->
            @foreach ($board->boardRows as $boardRow)
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                {{ $boardRow->title }}
                                <!-- レコード削除ボタン -->
                                <form method="POST" action="{{ route('board_rows.destroy', $boardRow) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500" onclick="event.stopPropagation();">{{ __('削除') }}</button>
                                </form>
                            </div>
                            <div class="card-body cursor-pointer" data-bs-toggle="modal" data-bs-target="#updateRowModal" data-board-row="{{ $boardRow }}">
                                {{ $boardRow->quiz_content }}
                                {{ $boardRow->quiz_answer }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
        
    <!-- モーダルウィンドウ 新しいレコードを作る用 -->
    <div class="modal fade" id="newRowModal" tabindex="-1" aria-labelledby="newRowModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <!-- モーダルヘッダー -->
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="newRowModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <!-- レコード作成フォーム -->
                <form id="modal-form" action="{{ route('board_rows.store')}}" method="POST">
                    @csrf
                    <input type="hidden" name="board_id" value="{{ $board->id }}">
                    <!-- モーダルボディ -->
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="col-form-label">Title:</label>
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                        <div class="mb-3">
                            <label for="quiz-content" class="col-form-label">Quiz Content:</label>
                            <textarea class="form-control" id="quiz-content" name="quiz_content"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="quiz-answer" class="col-form-label">Quiz Answer:</label>
                            <textarea class="form-control" id="quiz-answer" name="quiz_answer"></textarea>
                        </div>
                    </div>

                    <!-- モーダルフッター -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                        <button type="submit" class="btn btn-primary">作成</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- モーダルウィンドウ レコードを修正する用 -->
    <div class="modal fade" id="updateRowModal" tabindex="-1" aria-labelledby="updateRowModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <!-- モーダルヘッダー -->
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="updateRowModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- レコード修正フォーム -->
                <form id="modal-form" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="board_id" value="{{ $board->id }}">
                    <!-- モーダルボディ -->
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="col-form-label">Title:</label>
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                        <div class="mb-3">
                            <label for="quiz-content" class="col-form-label">Quiz Content:</label>
                            <textarea class="form-control" id="quiz-content" name="quiz_content"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="quiz-answer" class="col-form-label">Quiz Answer:</label>
                            <textarea class="form-control" id="quiz-answer" name="quiz_answer"></textarea>
                        </div>
                    </div>

                    <!-- モーダルフッター -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                        <button type="submit" class="btn btn-primary">変更</button>
                    </div>
                </form>
            </div>
        </div>
    
<!-- モーダルに選択したレコードのデータを渡す -->
<script>
    const updateRowModal = document.getElementById('updateRowModal');
    if (updateRowModal) {
        updateRowModal.addEventListener('show.bs.modal', event => {
            // カード要素を取得
            const card = event.relatedTarget;
        
            // カード要素から$boardRowを取得(文字列として渡されてる)
            const boardRowData = card.getAttribute('data-board-row');
            
            // 文字列をJavaScriptのオブジェクトに変換する
            const boardRow = JSON.parse(boardRowData);
        
            // モーダル内のデータ渡したい要素を取得
            const titleInput = updateRowModal.querySelector('#title');
            const quizContentInput = updateRowModal.querySelector('#quiz-content');
            const quizAnswerInput = updateRowModal.querySelector('#quiz-answer');
            const form = updateRowModal.querySelector('#modal-form');
            
            // 入れる
            titleInput.value = boardRow.title;
            quizContentInput.value = boardRow.quiz_content;
            quizAnswerInput.value = boardRow.quiz_answer;
            form.action = '/board_rows/' + boardRow.id;
        });
    }
</script>
</x-app-layout>