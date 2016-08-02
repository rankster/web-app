!function ($) {
    "use strict";

    var SweetAlert = function () {
    };

    //examples
    SweetAlert.prototype.init = function () {

        //winning animation
        $('#sa-win').click(function () {
            swal({
                type: "success",
                title: "Good Job !",
                text: "You're getting better and better !.",
                confirmButtonText: "Thanks!",
                timer: 2000,
                showConfirmButton: false
            });
        });

        //lose animation
        $('#sa-lose').click(function () {
            swal({
                type: "error",
                title: "Sorry Buddy!",
                text: "Hopefully you will get better... maybe.",
                timer: 2000,
                showConfirmButton: false
            });
        });

        //draw animation
        $('#sa-draw').click(function () {
            swal({
                type: "warning",
                title: "Fine!",
                text: "Let's rematch ! :)",
                timer: 2000,
                showConfirmButton: false
            });
        });

    },
        //init
        $.SweetAlert = new SweetAlert, $.SweetAlert.Constructor = SweetAlert
}(window.jQuery),

//initializing
    function ($) {
        "use strict";
        $.SweetAlert.init()
    }(window.jQuery);

function sendCreateMatchForm() {

    $.post( "/v1/submit-score",
        {
            'result': $( "input:radio[name=result]:checked" ).val(),
            'opponent_id': $(this).parent().find('input[name=opponent_id]').val(),
            'game_id': $(this).parent().find('input[name=game_id]').val()
        }
    );

    switch($( "input:radio[name=result]:checked" ).val()) {
    case "win":
        $( "#sa-win" ).trigger( "click" );
        break;
    case "lose":
        $( "#sa-lose" ).trigger( "click" );
        break;
    default:
        $( "#sa-draw" ).trigger( "click" );
    }

    $( "#close-me" ).trigger( "click" );

}
