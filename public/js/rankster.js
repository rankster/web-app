(function(){
    function formatGame(game) {
        if (game.loading) return game.text;

        var markup = "<div class='select2-result-list clearfix'>" +
            "<div class='select2-result-list__avatar'><img src='" + (game.picture || '/i/unknown-game.jpg') + "' /></div>" +
            "<div class='select2-result-list__meta'>" +
            "<div class='select2-result-list__title'>" + game.name + "</div>";

        if (game.description) {
            markup += "<div class='select2-result-list__description'>" + game.description + "</div>";
        }

//        markup += "<div class='select2-result-list__statistics'>" +
//            "<div class='select2-result-list__forks'><i class='fa fa-flash'></i> " + game.forks_count + " Forks</div>" +
//            "<div class='select2-result-list__stargazers'><i class='fa fa-star'></i> " + game.stargazers_count + " Stars</div>" +
//            "<div class='select2-result-list__watchers'><i class='fa fa-eye'></i> " + game.watchers_count + " Watchers</div>" +
//            "</div>" +
//            "</div></div>";

        return markup;
    }

    function formatGameSelection(game) {
        return game.name || game.text;
    }

    function formatParticipant(participant) {
        if (participant.loading) return participant.text;

        var markup = "<div class='select2-result-list clearfix'>" +
            "<div class='select2-result-list__avatar'><img src='" + (participant.picture || '/i/unknown-participant.jpg') + "' /></div>" +
            "<div class='select2-result-list__meta'>" +
            "<div class='select2-result-list__title'>" + participant.name + "</div>";

        if (participant.description) {
            markup += "<div class='select2-result-list__description'>" + participant.description + "</div>";
        }

//        markup += "<div class='select2-result-list__statistics'>" +
//            "<div class='select2-result-list__forks'><i class='fa fa-flash'></i> " + participant.forks_count + " Forks</div>" +
//            "<div class='select2-result-list__stargazers'><i class='fa fa-star'></i> " + participant.stargazers_count + " Stars</div>" +
//            "<div class='select2-result-list__watchers'><i class='fa fa-eye'></i> " + participant.watchers_count + " Watchers</div>" +
//            "</div>" +
//            "</div></div>";

        return markup;
    }

    function formatParticipantSelection(participant) {
        return participant.name || participant.text;
    }

    function gameReplayDialog(gameId, opponentId) {
        $('#reply-game-id').val(gameId);
        $('#label-game-name').html(games[gameId].name);
        $('#reply-opponent-id').val(opponentId);
        $('#label-opponent-name').html(users[opponentId].name);
        $("#game-replay").modal();
    }

    function newGameDialog() {
        $("#game-req").modal();
    }

    function decorateSpins() {
        $('[data-type="spin"]').each(function (index, element) {
            var parentElement = $(element);
            $('.btn-number', parentElement).click(function (e) {
                e.preventDefault();

                var button = e.currentTarget || e.delegateTarget;
                var type = $(button).attr('data-type');
                var input = $('input[data-type="spin-input"]', this);
                var minVal = parseInt(input.attr('min'));
                var maxVal = parseInt(input.attr('max'));
                var currentVal = parseInt(input.val());
                if (isNaN(currentVal)) {
                    input.val(1);
                    return;
                }

                if (type == 'minus') {
                    if (currentVal > minVal) {
                        input.val(currentVal - 1).change();
                    }
                    if (parseInt(input.val()) <= minVal) {
                        $(button).attr('disabled', true);
                    }
                } else if (type == 'plus') {
                    if (currentVal < maxVal) {
                        input.val(currentVal + 1).change();
                    }

                    if (parseInt(input.val()) >= maxVal) {
                        $(button).attr('disabled', true);
                    }
                }
            }.bind(parentElement));

            $('input[data-type="spin-input"]', parentElement).focusin(function(){
                $(this).data('oldValue', $(this).val());
            });
            $('input[data-type="spin-input"]', parentElement).change(function() {

                var input = $('input[data-type="spin-input"]', this);
                var minValue =  parseInt(input.attr('min'));
                var maxValue =  parseInt(input.attr('max'));
                var valueCurrent = parseInt(input.val());

                if (valueCurrent >= minValue) {
                    $(".btn-number[data-type='minus']", this).removeAttr('disabled');
                } else {
                    input.val(input.data('oldValue'));
                }
                if(valueCurrent <= maxValue) {
                    $(".btn-number[data-type='plus']", this).removeAttr('disabled');
                } else {
                    input.val(input.data('oldValue'));
                }
            }.bind(parentElement));

            $('input[data-type="spin-input"]', parentElement).keydown(function (e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                    // Allow: Ctrl+A
                    (e.keyCode == 65 && e.ctrlKey === true) ||
                    // Allow: home, end, left, right
                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
        });
    }

    function eventHandler () {
        decorateSpins();
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
            escapeMarkup: function (markup) {
                return markup;
            }, // let our custom formatter work
            //minimumInputLength: 2,
            templateResult: Rankster.formatGame, // omitted for brevity, see the source of this page
            templateSelection: Rankster.formatGameSelection // omitted for brevity, see the source of this page
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
            escapeMarkup: function (markup) {
                return markup;
            }, // let our custom formatter work
            //minimumInputLength: 2,
            templateResult: Rankster.formatParticipant, // omitted for brevity, see the source of this page
            templateSelection: Rankster.formatParticipantSelection // omitted for brevity, see the source of this page
        });
    }

    var games = {}, users = {};



    window.Rankster = {
        formatGame: formatGame,
        formatGameSelection: formatGameSelection,
        formatParticipant: formatParticipant,
        formatParticipantSelection: formatParticipantSelection,
        gameReplayDialog: gameReplayDialog,
        newGameDialog: newGameDialog,
        eventHandler: eventHandler,
        setUserGameInfo: function setUserGameInfo(u, g) {
            users = u;
            games = g;
        }
    };

    if (document.cookie.indexOf("tz") >= 0) {
    }
    else {
        document.cookie = "tz=" + jstz.determine().name();
    }
})();