function formatGame (game) {
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

function formatGameSelection (game) {
    return game.name || game.text;
}

function formatParticipant (participant) {
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

function formatParticipantSelection (participant) {
    return participant.name || participant.text;
}