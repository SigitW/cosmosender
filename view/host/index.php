<?php include '../../header.php' ?>
<?php include '../../navbar.php' ?>
<body class="container">
    <h3>Email Host</h3>
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
                <td>Host</td>
                <td>Server</td>
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
                <h1 class="modal-title fs-5" id="modal-label">Add Host</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="alert alert-warning display-none mb-3 over-x"></div>
                <input type="hidden" name="" id="id"/>
                <label for="" class="mb-1"> Host</label>
                <input type="text" name="" id="host" class="form-control mb-3"> 
                <label for="" class="mb-1"> Server</label>
                <select name="" id="sel-server" class="form-control">

                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-success" id="btn-save"><i class="bi bi-check-lg"></i> Save</button>
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
            url : '<?= $baseurl ?>src/host-api.php',
            method : "POST",
            data : {do:"load"},
            success:function(res){
                if (res.data.length > 0){
                    let str = '';
                    $.each(res.data, function(i, item){
                        const num = 1 + i;
                        str += '<tr>'+
                            '<td>'+num+'</td>'+
                            '<td>'+item.host+'</td>'+
                            '<td>'+item.server_name +'</td>'+
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

    function loadServer(){
        $.ajax({
            url : '<?= $baseurl ?>src/server-api.php',
            method : "POST",
            data : {do:"load"},
            success:function(res){
                if (res.data.length > 0){
                    let str = '<option disabled> Pilih Server </option>';
                    $.each(res.data, function(i,item){
                        str += '<option value="'+item.id+'">'+item.name+'</option>';
                    });
                    $("#sel-server").html(str);
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
        $("#modal-label").html("Add Host");
        $("#host").val("");
        $("#sel-server").val("");
        $("#btn-save").attr("onclick", "saveAdd()");

        loadServer();
    });

    function showEdit(id){
        $("#modal-add").modal("show");
        $(".alert-warning").hide();
        $("#host").val("");
        $("#server").val("");
        $("#btn-save").attr("onclick", "saveEdit()");
        $("#modal-label").html("Edit Host");
        loadServer();

        $.ajax({
            url : '<?= $baseurl ?>src/host-api.php',
            method : "POST",
            data : {do:"load-by-id", host_id : id},
            success:function(res){
                if (res.data.length > 0){
                    const item = res.data[0];
                    $("#id").val(item.id);
                    $("#host").val(item.host);
                    $("#sel-server").val(item.server_id);
                }
            }, error:function(er){
                $(".alert-warning").fadeIn();
                $(".alert-warning").html(er.responseJSON == undefined ? er.responseText : er.responseJSON.message);
            }
        })
    }

    function saveEdit(){
        const id        = $("#id").val();
        const host      = $("#host").val();
        const server    = $("#sel-server option:selected").val();

        $.ajax({
            url : '<?= $baseurl ?>src/host-api.php',
            method : "POST",
            data : {
                do:"save-edit", 
                host_id : id,
                host : host,
                server_id : server,
            }, success : function(res){
                if (res.code == "200"){
                    $("#modal-add").modal("hide");
                    $(".alert-success").fadeIn();
                    $(".alert-success").html(res.message);
                    load();
                }
            }, error : function(er){
                $(".alert-warning").fadeIn();
                $(".alert-warning").html(er.responseJSON == undefined ? er.responseText : er.responseJSON.message);
            }
        })
    }

    function saveAdd(){
        const host              = $("#host").val();
        const server            = $("#sel-server option:selected").val();

        $.ajax({
            url : '<?= $baseurl ?>src/host-api.php',
            method : "POST",
            data : {
                do:"save-add", 
                host : host,
                server : server
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
