$(document).on('click', '.like', function(){

    //$art_id = ("#test").val()
    $art_id = $(".like").attr("data-id");
    $.ajax({
        url: '/ajax/like',
        type: 'POST',
        data: {
            'art_id' : $art_id,
        },
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        success: function(data){

            if (!data.status) {
                alert('请勿重复投票!');
            }
            $(".count").text(data.vote_num);

        },

        error: function(xhr, type){

            //alert('Ajax error!')
        }

    })
});

$(document).on('click', '.hate', function(){

    //$art_id = ("#test").val()
    $art_id = $(".like").attr("data-id");
    $.ajax({
        url: '/ajax/hate',
        type: 'POST',
        data: {
            'art_id' : $art_id,
        },
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        success: function(data){
            if (!data.status) {
                alert('请勿重复投票!');
            }
            $(".count").text(data.vote_num);
        },

        error: function(xhr, type){
            alert('Ajax error!')
        }

    })
});

$("#submit").click(function(){

    var token = $('#token').val();

    var oldpassword = $('#oldpassword').val();

    var newpassword = $('#newpassword').val();

    var newpassword2 = $('#newpassword2').val();

    //alert(token);

    //alert(oldpassword);

    //alert(newpassword);

    //alert(newpassword2);

    //alert('submit');

    $.post(
        "{{ action('UserController@resetpasswordHandle') }}",

         { '_token': token ,
             'oldpassword': oldpassword,
              'newpassword': newpassword,
             'newpassword2':newpassword2
         },

        function(data){

            alert("Data Loaded: " + data);

        });

});