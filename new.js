$(document).ready(function(){
    $('#formdata').validate({
        rules: {
            fullname:"required",
            gender:{
                required: true
            },
            'hobbies[]': {
                required: true
            },
            computer: {
                required: true
            },
            description: {
                required: true
            },
            image: {
                required: true
            }   
        }, 
        messages: {
            fullname: {
                required: "Please enter your fullname"
            },
            gender:{
                required: "Please enter your gender"
            },
            'hobbies[]': {
                required: "Please enter hobbies"
            },
            computer: {
                required: "Please enter computer"
            },
            description: {
                required: "Please enter description"
            },
            image: {
                required: "Please enter an image"
            }
        },
        errorPlacement: function(error, element) {
            if (element.is(":radio")) {
                error.appendTo(element.parents('.lol'));
            } else if (element.is(":checkbox")) {
                error.appendTo(element.parent().parent());
            }
            else {
                error.insertAfter(element);
            }
        }
    });

    $('#tabledata').DataTable({
        destroy: true,
        "pageLength": 4,
        lengthMenu: [
            [4, 8, 12, -1],
            [4, 8, 12, 'All']
        ],
        ajax: {
          url: "action.php",
          type: "POST",
          data: {
            type: "list"
          },
          dataSrc: "data"
        },
        columns: [
          { "data": "fullname" },
          { "data": "gender" },
          { "data": "hobbies" },
          { "data": "computer" },
          { "data": "description" },
        //   { "data": "image", "orderable": false },
        { "data": "image" ,
        "render": function ( data) {
        return '<img src="C:/wamp64/www/new/ajaxoops/image/4.png" width="80px" height="80px">';}
      },
          { "data": "actions", "orderable": false }
        ]
      });


    $("#submit").click(function(){
        let form = new FormData(document.getElementById('formdata'));
        $.ajax({
            type:"POST",
            url:"action.php",
            data:form,
            contentType: false,
            cache: false,
            processData: false,
            success:function (data){
                console.log(data);
                var json = JSON.parse(data);
                if (json.status == "1") {
                alert(json.message);
                } else {
                alert(json.message);
                }
                list();
                $('#modal').modal('hide');
                $('#formdata').trigger("reset");
                $("#id").val("");
            }
        });
    });

    list();
    $("#modal").on("hidden.bs.modal", function() {
        $('#formdata').trigger("reset");
    }); 
});

function list(){
    $('#tabledata').DataTable({
        destroy: true,
        "pageLength": 4,
        lengthMenu: [
            [4, 8, 12, -1],
            [4, 8, 12, 'All']
        ],
        ajax: {
          url: "action.php",
          type: "POST",
          data: {
            type: "list"
          },
          dataSrc: "data"
        },
        columns: [
          { "data": "fullname" },
          { "data": "gender" },
          { "data": "hobbies" },
          { "data": "computer" },
          { "data": "description" },
        { "data": "image" ,
        "render": function ( data) {
        return '<img src="C:/wamp64/www/new/ajaxoops/image/4.png" width="80px" height="80px">';}
      },
          { "data": "actions", "orderable": false }
        ]
      });
}

$(document).on("click","#edit",function(){
    var id = $( this ).data( 'id' );
    $.ajax({
        type:"POST",
        url:"action.php",
        data:{
            type:"edit",
            id:id
        },
        success:function(response){
            console.log(response);
            $('#modal').modal('show');
            var edit = JSON.parse(response)[0];
                let id = edit["id"];
                let fullname = edit["fullname"];
                $('input[type="radio"]').filter('[value=' + edit['gender'] + ']').prop("checked", true);
                let hobbiesArray = edit['hobbies'].split(',');
                $('input[type="checkbox"]').filter(function() {return hobbiesArray.includes($(this).val());}).prop('checked', true);
                let computer = edit["computer"];
                let description = edit["description"];

                $("#id").val(id);
                $("#fullname").val(fullname);
                $("#computer").val(computer);
                $("#description").val(description);
        }
    });
});

$(document).on("click","#delete",function(){
    var id = $( this ).data( 'id' );
    $.ajax({
        type:"POST",
        url:"action.php",
        data:{
            type:'delete',
            id:id
        },
        success:function(data){
            var json = JSON.parse(data);
                if (json.status == "1") {
                alert(json.message);
                } else {
                alert(json.message);
                }
            list();
        }
    });
});