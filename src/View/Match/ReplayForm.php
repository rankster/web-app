<?php

namespace Rankster\View\Match;


use Yaoi\View\Hardcoded;

class ReplayForm extends Hardcoded
{
    public function render()
    {
        echo <<<'HTML'
<div id="game-replay" style="display:none">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal" aria-label="Close" rel="modal:close"><span aria-hidden="true">&times;</span></a>
                <h4 class="modal-title" id="gameRequestModalLabel">Report Game Result</h4>
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
                    <button type="submit" class="btn btn-success">Submit Result</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
window.addEventListener('load', function () {
    $('#game-replay [rel="modal:close"]').click(function () {
        $('#game-replay').hide();
    })
}, false);
</script>

HTML;

    }


}