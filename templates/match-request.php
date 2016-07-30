<button type="button" class="btn btn-primary" data-animation="blur" data-toggle="modal" data-target="#game-request" data-game-id="1">Open modal </button>
<p><a href="#game-request" rel="modal:open">Open Modal.. yea</a></p>
<div class="modal fade" id="game-request" tabindex="-1" role="dialog" aria-labelledby="gameRequestModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gameRequestModalLabel">New Game Request</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="game" class="control-label">Game:</label>
                        <select style="width: 100%" id="game" tabindex="-1"></select>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Participant:</label>
                        <select style="width: 100%" id="recipient-name" tabindex=""></select>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Send Request</button>
            </div>
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
            minimumInputLength: 2,
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
            minimumInputLength: 2,
            templateResult: formatParticipant, // omitted for brevity, see the source of this page
            templateSelection: formatParticipantSelection // omitted for brevity, see the source of this page
        });
    }, false);
</script>
