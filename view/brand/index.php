<?php include '../../header.php' ?>
<?php include '../../navbar.php' ?>
<body class="container">
    <h3>Brand</h3>
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
                <td>Newsletter Location</td>
                <td>Service</td>
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
                <h1 class="modal-title fs-5" id="modal-label">Add Brand</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="alert alert-warning display-none mb-3"></div>
                <input type="hidden" name="" id="id"/>
                <label for="" class="mb-1"> Name</label>
                <input type="text" name="" id="name" class="form-control mb-3"> 
                <label for="" class="mb-1"> Domain</label>
                <input type="text" name="" id="domain" class="form-control mb-3"> 
                <label for="" class="mb-1"> Content Domain</label>
                <input type="text" name="" id="content_domain" class="form-control mb-3"> 
                <label for="" class="mb-1"> Actual Path</label>
                <input type="text" name="" id="actual_path" class="form-control mb-3"> 
                <label for="" class="mb-1"> Blast Limit</label>
                <input type="number" name="" id="blast_limit" class="form-control mb-3"> 
                <label for="" class="mb-1"> Blast Minute Interval</label>
                <input type="number" name="" id="blast_hour_interval" class="form-control mb-3"> 
                <label for="" class="mb-1"> Newsletter Folder</label>
                <input type="text" name="" id="aseet_namespace" class="form-control mb-3"> 
                <label for="" class="mb-1"> Service</label>
                <select name="" id="sel-service" class="form-control"></select>
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
        loadService();
        load();
    });

    function load(){
        $.ajax({
            url : '<?= $baseurl ?>src/brand-api.php',
            method : "POST",
            data : {do:"load"},
            success:function(res){
                if (res.data.length > 0){
                    let str = '';
                    $.each(res.data, function(i, item){
                        const num = 1 + i;
                        str += '<tr>'+
                            '<td>'+num+'</td>'+
                            '<td>'+item.name+'</td>'+
                            '<td>'+item.domain+ "/" +item.aseet_namespace+ '</td>'+
                            '<td>'+item.service_name+ '</td>'+
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

    function loadService(){
        $.ajax({
            url : '<?= $baseurl ?>src/service-api.php',
            method : "POST",
            data : {do:"load"},
            success:function(res){
                if (res.data.length > 0){
                    let str = '<option disabled> Pilih Service </option>';
                    $.each(res.data, function(i,item){
                        str += '<option value="'+item.id+'">'+item.server_name+' / '+item.service+'</option>';
                    });
                    $("#sel-service").html(str);
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
        $("#modal-label").html("Add Brand");
        $("#name").val("");
        $("#domain").val("");
        $("#aseet_namespace").val("");
        $("#sel-service").val("");
        $("#btn-save").attr("onclick", "store()");
        loadService();
    });

    function showEdit(id){
        $("#modal-add").modal("show");
        $(".alert-warning").hide();
        $("#modal-label").html("Edit Brand");
        $("#btn-save").attr("onclick", "update()");
        loadService();

        $.ajax({
            url : '<?= $baseurl ?>src/brand-api.php',
            method : "POST",
            data : {do:"load-by-id", brand_id : id},
            success:function(res){
                if (res.data.length > 0){
                    const item = res.data[0];
                    $("#id").val(item.id);
                    $("#name").val(item.name);
                    $("#domain").val(item.domain);
                    $("#aseet_namespace").val(item.aseet_namespace);
                    $("#sel-service").val(item.service_id);
                    $("#actual_path").val(item.actual_path);
                    $("#content_domain").val(item.content_domain);
                    $("#blast_limit").val(item.blast_limit);
                    $("#blast_hour_interval").val(item.blast_hour_interval);
                }
            }, error:function(err){
                $(".alert-warning").fadeIn().delay(2000).fadeOut();
                $(".alert-warning").html(er.responseJSON == null ? er.responseText : er.responseJSON.message);
            }
        })
    }

    function store(){
        const name              = $("#name").val();
        const domain            = $("#domain").val();
        const newsletter        = $("#aseet_namespace").val();
        const service           = $("#sel-service option:selected").val();
        const path              = $("#actual_path").val();
        const contentdomain     = $("#content_domain").val();
        const blastlimit        = $("#blast_limit").val();
        const blasthourinterval = $("#blast_hour_interval").val();

        $.ajax({
            url : '<?= $baseurl ?>src/brand-api.php',
            method : "POST",
            data : {
                do:"save-add", 
                name : name,
                domain : domain,
                newsletter : newsletter,
                service : service,
                path:path,
                content_domain:contentdomain,
                blast_limit:blastlimit,
                blast_hour_interval:blasthourinterval
            }, success : function(res){
                if (res.code == "200"){
                    $("#modal-add").modal("hide");
                    $(".alert-success").fadeIn().delay(2000).fadeOut();
                    $(".alert-success").html(res.message);
                    load();
                }
            }, error : function(er){
                $(".alert-warning").fadeIn().delay(2000).fadeOut();
                $(".alert-warning").html(er.responseJSON == null ? er.responseText : er.responseJSON.message);
            }
        })
    }

    function update(){
        const id                = $("#id").val();
        const name              = $("#name").val();
        const domain            = $("#domain").val();
        const newsletter        = $("#aseet_namespace").val();
        const service           = $("#sel-service option:selected").val();
        const path              = $("#actual_path").val();
        const contentdomain     = $("#content_domain").val();
        const blastlimit        = $("#blast_limit").val();
        const blasthourinterval = $("#blast_hour_interval").val();

        $.ajax({
            url : '<?= $baseurl ?>src/brand-api.php',
            method : "POST",
            data : {
                do:"save-edit", 
                id : id,
                name : name,
                domain : domain,
                newsletter : newsletter,
                service : service,
                path:path,
                content_domain:contentdomain,
                blast_limit:blastlimit,
                blast_hour_interval:blasthourinterval
            }, success : function(res){
                if (res.code == "200"){
                    $("#modal-add").modal("hide");
                    $(".alert-success").fadeIn().delay(2000).fadeOut();
                    $(".alert-success").html(res.message);
                    load();
                }
            }, error : function(er){
                console.log(er);
                $(".alert-warning").fadeIn().delay(2000).fadeOut();
                $(".alert-warning").html(er.responseJSON == null ? er.responseText : er.responseJSON.message);
            }
        })
    }
</script>
