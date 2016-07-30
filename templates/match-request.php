<div id="game-req" style="display:none">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <a type="button" class="close" data-dismiss="modal" aria-label="Close" rel="modal:close"><span aria-hidden="true">&times;</span></a>
                <h4 class="modal-title" id="gameRequestModalLabel">Report Game Result</h4>
            </div>
            <form action="/match/create" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="game" class="control-label">Game:</label>
                        <select style="width: 100%" id="game" tabindex="-1" name="game_id"></select>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Opponent:</label>
                        <select style="width: 100%" id="recipient-name" tabindex="" name="opponent_id"></select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Result:</label>
                        <div class="btn-group" data-toggle="buttons">
                            <label class="btn btn-primary active">
                                <input type="radio" name="result" id="option1" autocomplete="off" value="win" checked>WIN
                            </label>
                            <label class="btn btn-primary">
                                <input type="radio" name="result" id="option2" autocomplete="off" value="lose"> LOSE
                            </label>
                            <label class="btn btn-primary">
                                <input type="radio" name="result" id="option3" autocomplete="off" value="draw"> DRAW
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-default" data-dismiss="modal" rel="modal:close">Close</a>
                    <button type="submit" class="btn btn-primary">Send Request</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    window.addEventListener('load', function () {
        $('#game').select2({
            ajax: {
                url: '/v1/games',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 1;

                    return {
                        results: data.items,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            //minimumInputLength: 2,
            templateResult: formatGame, // omitted for brevity, see the source of this page
            templateSelection: formatGameSelection // omitted for brevity, see the source of this page
        });

        $('#recipient-name').select2({
            ajax: {
                url: '/v1/users',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 1;

                    return {
                        results: data.items,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            //minimumInputLength: 2,
            templateResult: formatParticipant, // omitted for brevity, see the source of this page
            templateSelection: formatParticipantSelection // omitted for brevity, see the source of this page
        });
    }, false);
</script>