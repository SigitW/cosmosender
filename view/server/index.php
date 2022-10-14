<?php include '../../header.php' ?>
<?php include '../../navbar.php' ?>
<body class="container">
    <h3>Server</h3>
    <div class="alert alert-danger display-none mb-3"></div>
    <div class="alert alert-success display-none mb-3"></div>
    <div class="mb-1 text-end">
        <button class="btn btn-sm btn-light" id="btn-add"><i class="bi bi-plus-lg"></i> Add</button>
    </div>
    <div>
        <table class="table table-dark table-striped table-hover">
            <thead>
                <tr>
                <td>#</td>
                <td>Nama</td>
                <td>Domain</td>
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
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Server</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="alert alert-warning display-none mb-3"></div>

                <label for="" class="mb-1"> Nama Server</label>
                <input type="text" name="" id="name" class="form-control mb-3">
                <label for="" class="mb-1"> Domain</label>
                <input type="text" name="" id="domain" class="form-control mb-3">  
                <label for="" class="mb-1"> Warna Penanda</label>
                <input type="color" name="" id="color" class="ms-3 mb-3"> 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-success" id="btn-save-add"><i class="bi bi-check-lg"></i> Save</button>
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
            url : '<?= $baseurl ?>/src/server-api.php',
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
                            '<td>'+item.name+'</td>'+
                            '<td>'+item.domain+'</td>'+
                            '<td align="center" style="vertical-align:middle">'+formWarna(item.color)+'</td>'+
                            '<td>'+item.created_at+'</td>'+
                            '<td>'+item.created_who+'</td>'+
                            '<td><span class="btn-edit"><i class="bi bi-pencil-square"></i> Edit</span></td>'+
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
        $(".alert-warning").hide();
    });

    $("#btn-save-add").click(function(){
        
        const name      = $("#name").val();
        const domain    = $("#domain").val();
        const color     = $("#color").val();

        $.ajax({
            url : '<?= $baseurl ?>/src/server-api.php',
            method : "POST",
            data : {
                do:"save-add", 
                name : name,
                domain : domain,
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
    });

    function formWarna(warna){
        return '<div class="rounded-circle" style="width:20px;height:20px;background-color:'+warna+'"></div>'
    }
</script>