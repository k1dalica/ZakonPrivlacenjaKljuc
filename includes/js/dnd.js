(function() {
    var dropzone = document.getElementById('dropzone');
    $("#imageform").on('submit',(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            type:'POST',
            url: "/inputupload.php",
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(data){
                $('#dndimg').val(data.responseText);
                $('#uploads').css("background-image","url(/"+data.responseText+")");
                $('#uploads').css("background-size","cover");
                
                $('#newpostimg').removeClass('hidden');
                $('#newpostimg').slideDown(300);
                $('#newpostimg').html("<img src='/"+data.responseText+"' width='100%'>");
                $('#hidenewpostimg').show();
            },
            error: function(data){
                $('#dndimg').val(data.responseText);
                $('#uploads').css("background-image","url(/"+data.responseText+")");
                $('#uploads').css("background-size","cover");
                
                $('#newpostimg').removeClass('hidden');
                $('#newpostimg').slideDown(300);
                $('#newpostimg').html("<img src='/"+data.responseText+"' width='100%'>");
                $('#hidenewpostimg').show();
            }
        });
    }));
    $('#openfile').change(function(event) {
        $("#imageform").submit();
        
        if(this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#uploads').show();
                $('#newpostimg').show();
                $('#dropzone').hide();
                
                
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
    
   $('#dropzone').click(function() {
        $('#openfile').click();
    });
    
    
    $('#hidenewpostimg').click(function() {
        var dndimg = $('#dndimg').val();
        $.post('/core/funcs/jfuncs.php', { action: "obrisidndsliku", value: dndimg });
        $('#dndimg').val("");
        $('#newpostimg').val();
        $('#newpostimg').hide();
        $(this).hide();
    });
    
    $('#uploads span').click(function() {
        var dndimg = $('#dndimg').val();
        $.post('/core/funcs/jfuncs.php', { action: "obrisidndsliku", value: dndimg });
        $('#uploads').css("background-image","");
        $('#uploads').hide();
        $('#dropzone').show();
        $('#dndimg').val("");
        $('#dndorfile').val("0");
    });
    var setloading = function() {
        $('#uploads').css("background-image","url(/includes/images/load.gif)");
        $('#uploads').css("background-size","auto");
        $('#uploads span').hide();
        $('#uploads').show();
        $('#dropzone').hide();
    }
    var upload = function(files) {
        var formData = new FormData(),
            xhr = new XMLHttpRequest(),
            x;
        for(x = 0; x < files.length; x = x + 1) {
            formData.append('file[]', files[x]);
        }
        
        xhr.onload = function() {
            var data = this.responseText;
            if (data == "/") {
                $('#uploads').css("background-image","");
                $('#uploads').hide();
                $('#dropzone').show();
                $('#dndimg').val("");
                $('#dndorfile').val("0");
                alert("Unesite sliku u formtu JPG, JPEG, PNG ili BMP!");
            } else {
                $('#uploads').css("background-image","url(/"+data+")");
                $('#uploads').css("background-size","cover");
                $('#uploads span').show();
                $('#dndorfile').val("1");
                $('#dndimg').val(data);
            }
        }
        
        xhr.open('post', '/uploaddnd.php');
        xhr.send(formData);
    }
    
    dropzone.ondrop = function (e) {
        e.preventDefault();
        this.className = 'dropzone';
        upload(e.dataTransfer.files);
        setloading();
    };
    
    dropzone.ondragover = function () {
        this.className = 'dropzone dragover';
        return false;
    };
    
    dropzone.ondragleave = function () {
        this.className = 'dropzone';
        return false;
    };
}());