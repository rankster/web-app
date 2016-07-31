<div id="game-replay" style="display:none">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal" aria-label="Close" rel="modal:close"><span aria-hidden="true">&times;</span></a>
                <h4 class="modal-title" id="gameRequestModalLabel">Replay Game</h4>
            </div>
            <form action="/match/create" method="post" id="create-match">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label">Game:</label>
                        <input type="hidden" name="game_id" id="reply-game-id">
                        <label id="label-game-name"></label>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Opponent:</label>
                        <input type="hidden" name="opponent_id" value="" id="reply-opponent-id">
                        <label id="label-opponent-name"></label>
                    </div>
                    <div class="form-group" style="text-align:center">
                        <div class="btn-group" data-toggle="buttons">
                            <label class="btn btn-lg btn-success active">
                                <input type="radio" name="result" id="option1" autocomplete="off" value="win" checked>WIN
                            </label>
                            <label class="btn btn-lg btn-danger">
                                <input type="radio" name="result" id="option2" autocomplete="off" value="lose"> LOSE
                            </label>
                            <label class="btn btn-lg btn-warning">
                                <input type="radio" name="result" id="option3" autocomplete="off" value="draw"> DRAW
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-default" id="close-me" data-dismiss="modal" rel="modal:close">Cancel</a>
                    <button type="submit" class="btn btn-success" onClick="sendCreateMatchForm();event.preventDefault();">Submit Result</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function gameReplayDialog(game, opponent) {
    $('#reply-game-id').val(game.id);
    $('#label-game-name').val(game.name);
    $('#reply-opponent-id').val(opponent.id);
    $('#label-opponent-name').val(opponent.name);
    $('#game-replay').show();
}
</script>
