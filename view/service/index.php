<?php include '../../header.php' ?>
<?php include '../../navbar.php' ?>
<body class="container">
    <h3>Service</h3>
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
                <td>Server</td>
                <td>Service</td>
                <td align="center">Warna</td>
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
                <h1 class="modal-title fs-5" id="modal-label">Add Service</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="alert alert-warning display-none mb-3"></div>
                <input type="hidden" name="" id="id"/>
                <label for="" class="mb-1"> Service</label>
                <input type="text" name="" id="service" class="form-control mb-3"> 
                <label for="" class="mb-1"> Server</label>
                <select name="" id="sel-server" class="form-control mb-3"></select>
                <label for="" class="mb-1"> Warna Penanda</label>
                <input type="color" name="" id="color" class="ms-3 mb-3"> 
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
            url : '<?= $baseurl ?>src/service-api.php',
            method : "POST",
            data : {do:"load"},
            success:function(res){
                console.log(res);
                if (res.data.length > 0){
                    let str = '';
                    $.each(res.data, function(i, item){
                        const num = 1 + i;
                        str += '<tr>'+
                            '<td>'+num+'</td>'+
                            '<td>'+item.server_name+'</td>'+
                            '<td>'+item.service+'</td>'+
                            '<td align="center" style="vertical-align:middle">'+formWarna(item.color)+'</td>'+
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

    $("#btn-add").click(function(){
        $("#modal-add").modal("show");
        $("#modal-label").html("Add Service");
        $("#service").val("");
        $("#sel-server").val("");
        $("#color").val("");
        $(".alert-warning").hide();
        $("#btn-save").attr("onclick", "store()");
        loadServer();
    });

    function showEdit(id){
        $("#modal-add").modal("show");
        $("#modal-label").html("Edit Service");
        $("#service").val("");
        $("#sel-server").val();
        $("#color").val("");
        $(".alert-warning").hide();
        $("#btn-save").attr("onclick", "update()");
        loadServer();

        $.ajax({
            url : '<?= $baseurl ?>src/service-api.php',
            method : "POST",
            data : {do:"load", id:id},
            success:function(res){
                if (res.data.length > 0){
                    const item = res.data[0];
                    $("#id").val(item.id);
                    $("#service").val(item.service);
                    $("#sel-server").val(item.server_id);
                    $("#color").val(item.color);
                }
            },error:function(er){
                $(".alert-warning").fadeIn();
                $(".alert-warning").html(er.responseJSON.message);
            }
        })
    }

    function update(){
        const id        = $("#id").val();
        const server    = $("#sel-server option:selected").val();
        const service   = $("#service").val(); 
        const color     = $("#color").val();
        $.ajax({
            url: '<?= $baseurl ?>src/service-api.php',
            method : "POST",
            data : {
                do:"update", 
                service_id:id,
                service : service,
                server_id : server,
                color : color
            },
            success:function(res){
                if (res.code == "200"){
                    $("#modal-add").modal("hide");
                    $(".alert-success").fadeIn();
                    $(".alert-success").html(res.message);
                    load();
                } else {
                    $(".alert-warning").fadeIn();
                    $(".alert-warning").html(res.message);
                }
            },error:function(er){
                $(".alert-warning").fadeIn();
                $(".alert-warning").html(er.responseJSON == undefined ? er.responseText : er.responseJSON.message);
            }
        })
    }

    function loadServer(){
        $.ajax({
            url : '<?= $baseurl ?>src/service-api.php',
            method : "POST",
            data : {do:"load-server"},
            success:function(res){
                if (res.data.length > 0){
                    let str = '<option disabled>Pilih Server</option>';
                    $.each(res.data, function(i, item){
                        str += '<option value="'+item.id+'">'+item.name+'</option>';
                    });
                    $("#sel-server").html(str);
                }
            },error:function(er){
                $(".alert-warning").fadeIn();
                $(".alert-warning").html(er.responseJSON.message);
            }
        })
    }

    function store(){
        const service   = $("#service").val();
        const server    = $("#sel-server option:selected").val();
        const color     = $("#color").val();

        $.ajax({
            url : '<?= $baseurl ?>src/service-api.php',
            method : "POST",
            data : {
                do:"save-add", 
                service : service,
                server : server,
                color : color
            }, success : function(res){
                if (res.code == "200"){
                    $("#modal-add").modal("hide");
                    $(".alert-success").fadeIn();
                    $(".alert-success").html(res.message);
                    load();
                } else {
                    $(".alert-warning").fadeIn();
                    $(".alert-warning").html(res.message);
                }
            }, error : function(er){
                $(".alert-warning").fadeIn();
                $(".alert-warning").html(er.responseJSON.message);
            }
        })
    }

    function formWarna(warna){
        return '<div class="rounded-circle" style="width:20px;height:20px;background-color:'+warna+'"></div>'
    }
</script>