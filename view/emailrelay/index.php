<?php include '../../header.php' ?>
<?php include '../../navbar.php' ?>
<body class="container">
    <h3>Email Relay</h3>
    <div class="alert alert-danger display-none mb-3"></div>
    <div class="alert alert-success display-none mb-3"></div>
    <div class="mb-1 text-end">
        <button class="btn btn-sm btn-light" id="btn-add"><i class="bi bi-plus-lg"></i> Add</button>
    </div>
    <div style="overflow-x: auto;"> 
        <table class="table table-dark table-striped table-hover">
            <thead>
                <tr>
                <td>#</td>
                <td>Nama</td>
                <td>Email</td>
                <td>Port</td>
                <td>Send as</td>
                <td>Alias</td>
                <td>Created Date</td>
                <td>Created Who</td>
                <td></td>
                </tr>
            </thead>
            <tbody id="table-body">

            </tbody>
        </table>
    </div>
</body>

<!-- Modal -->
<div class="modal fade" id="modal-add" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-add" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modal-label">Add Email Relay</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="alert alert-warning display-none mb-3"></div>
                <input type="hidden" name="" id="id"/>
                <label for="" class="mb-1"> Email</label>
                <input type="text" name="" id="email" class="form-control mb-3"> 
                <label for="" class="mb-1"> Password</label>
                <input type="password" name="" id="password" class="form-control mb-3"> 
                <label for="" class="mb-1"> Port</label>
                <input type="number" name="" id="port" class="form-control mb-3"> 
                <label for="" class="mb-1"> Email From</label>
                <input type="text" name="" id="emailfrom" class="form-control mb-3"> 
                <label for="" class="mb-1"> Email Alias</label>
                <input type="text" name="" id="emailalias" class="form-control mb-3"> 
                <label for="" class="mb-1"> Email Host</label>
                <select name="" id="sel-host" class="form-control"></select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-success" id="btn-save" onclick="store()"><i class="bi bi-check-lg"></i> Save</button>
            </div>
        </div>
    </div>
</div>
<?php include '../../footer.php' ?>
<script>

    $(document).ready(function(){
        load();
    });

    function load(){
        $.ajax({
            url : '<?= $baseurl ?>src/email-relay-api.php',
            method : "POST",
            data : {do:"load"},
            success:function(res){
                if (res.data.length > 0){
                    let str = '';
                    $.each(res.data, function(i, item){
                        const num = 1 + i;
                        str += '<tr>'+
                            '<td>'+num+'</td>'+
                            '<td>'+item.email+'</td>'+
                            '<td>'+item.host_name+'</td>'+
                            '<td>'+item.port+ '</td>'+
                            '<td>'+item.email_from+ '</td>'+
                            '<td>'+item.email_alias+ '</td>'+
                            '<td>'+item.created_at+'</td>'+
                            '<td>'+item.created_who+'</td>'+
                            '<td><span class="btn-edit" onclick="showEdit(\''+item.id+'\')"><i class="bi bi-pencil-square"></i> Edit</span></td>'+
                            '</tr>';
                    });
                    $("#table-body").html(str); 
                }
            }, 
            error:function(er){
                console.log(er);
            }
        });
    }

    function loadHost(){
        $.ajax({
            url : '<?= $baseurl ?>src/host-api.php',
            method : "POST",
            data : {do:"load"},
            success:function(res){
                if (res.data.length > 0){
                    let str = '<option disabled> Pilih Host </option>';
                    $.each(res.data, function(i,item){
                        str += '<option value="'+item.id+'">'+item.server_name+' / '+item.host+'</option>';
                    });
                    $("#sel-host").html(str);
                }
            }, 
            error:function(er){
                console.log(er);
            }
        });
    }

    $("#btn-add").click(function(){
        $("#modal-add").modal("show");
        $(".alert-warning").hide();
        $("#modal-label").html("Add Email Relay");

        $("#email").val("");
        $("#password").val("");
        $("#port").val("");
        $("#sel-host").val("");

        loadHost();
        $("#btn-save").attr("onclick", "store()");
    });

    function showEdit(id){
        $("#modal-add").modal("show");
        $(".alert-warning").hide();
        $("#modal-label").html("Edit Email Relay");

        $("#email").val("");
        $("#password").val("");
        $("#port").val("");
        $("#sel-host").val("");

        loadHost();
        $("#btn-save").attr("onclick", "update()");

        $.ajax({
            url : '<?= $baseurl ?>src/email-relay-api.php',
            method : "POST",
            data : {do:"load-by-id", relay_id : id},
            success:function(res){
                if (res.data.length > 0){
                    const item = res.data[0];
                    $("#id").val(item.id);
                    $("#email").val(item.email);
                    $("#pasword").val(item.password);
                    $("#emailfrom").val(item.email_from);
                    $("#emailalias").val(item.email_alias);
                    $("#port").val(item.port);
                    $("#sel-host").val(item.host_id);
                }
            }, error:function(err){
                $(".alert-warning").fadeIn();
                $(".alert-warning").html(err.responseText);
            }
        })
    }

    function update(){
        const id              = $("#id").val();
        const email           = $("#email").val();
        const port            = $("#email").val();
        const password        = $("#password").val();
        const emailfrom       = $("#emailfrom").val();
        const emailalias      = $("#emailalias").val();
        const host            = $("#sel-host option:selected").val();

        $.ajax({
            url : '<?= $baseurl ?>src/email-relay-api.php',
            method : "POST",
            data : {
                do:"update", 
                id : id,
                email : email,
                port : port,
                password : password,
                host : host,
                email_from : emailfrom,
                email_alias : emailalias
            }, success : function(res){
                if (res.code == "200"){
                    $("#modal-add").modal("hide");
                    $(".alert-success").fadeIn();
                    $(".alert-success").html(res.message);
                    load();
                }
            }, error : function(er){
                $(".alert-warning").fadeIn();
                $(".alert-warning").html(er.responseText);
            }
        })
    }

    function store(){
        const email       = $("#email").val();
        const password    = $("#password").val();
        const port        = $("#port").val();
        const host        = $("#sel-host option:selected").val();
        const emailfrom   = $("#emailfrom").val();
        const emailalias  = $("#emailalias").val();

        $.ajax({
            url : '<?= $baseurl ?>src/email-relay-api.php',
            method : "POST",
            data : {
                do:"save-add", 
                email : email,
                password : password,
                port : port,
                host : host,
                email_from : emailfrom,
                email_alias : emailalias
            }, success : function(res){
                if (res.code == "200"){
                    $("#modal-add").modal("hide");
                    $(".alert-success").fadeIn();
                    $(".alert-success").html(res.message);
                    load();
                }
            }, error : function(er){
                $(".alert-warning").fadeIn();
                $(".alert-warning").html(er.responseText);
            }
        })
    }

    function formWarna(warna){
        return '<div class="rounded-circle" style="width:20px;height:20px;background-color:'+warna+'"></div>'
    }
</script>
