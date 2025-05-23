<?php include '../../header.php' ?>
<?php include '../../navbar.php' ?>
<body class="container">
    <h3>Blast Rules</h3>
    <div class="alert alert-danger display-none mb-3"></div>
    <div class="alert alert-success display-none mb-3"></div>
    <div style="overflow-x: auto;"> 
        <table class="table table-dark table-striped table-hover">
            <thead>
                <tr>
                <td>#</td>
                <td>Brand</td>
                <td>Rules</td>
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
                <label for="" class="mb-1"> Email Host</label>
                <select name="" id="sel-host" class="form-control">

                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-success" id="btn-save" onclick="store()"><i class="bi bi-check-lg"></i> Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-add-relay" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-add" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modal-label">Add Email Relay</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning display-none mb-3"></div>
                <label for="" class="mb-1"> Email Relay</label>
                <select name="" id="sel-relay" class="form-control">

                </select>
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
            url : '<?= $baseurl ?>src/blast-rule-api.php',
            method : "POST",
            data : {do:"load"},
            success:function(res){
                if (res.code == "200"){
                    if (res.data.length > 0){
                        let str = '';
                        $.each(res.data, function(i, item){
                            const num = 1 + i;
                            str += '<tr>'+
                                '<td>'+num+'</td>'+
                                '<td>'+item.name+'</td>'+
                                '<td>'+formatRules(item.rules)+'</td>'+
                                '<td>'+item.created_at+'</td>'+
                                '<td>'+item.created_who+'</td>'+
                                '<td><span class="btn-edit" onclick="showEdit(\''+item.id+'\')"><i class="bi bi-pencil-square"></i> Edit</span></td>'+
                                '</tr>';
                        });
                        $("#table-body").html(str); 
                    }
                } else {
                    $(".alert-danger").fadeIn();
                    $(".alert-danger").html(res);
                }
                
            }, 
            error:function(er){
                $(".alert-danger").fadeIn();
                $(".alert-danger").html(er.responseText);
            }
        });
    }

    function formatRules(rules){
        if (rules.length == 0)
            return "-";
        
        let str = '';
        $.each(rules, function(i, item){
            const bgbadge = item.type == "blast" ? "text-bg-danger" : "text-bg-light";
            const bgdot   = item.color == "" ? "grey" : item.color; 
            str += '<span class="badge '+bgbadge+'">'+item.name+'</span>';
            str += '<div style="background-color:'+bgdot+';" class="rounded-circle dot-indicator"></div>';
        });
        return str;    
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
        location.href = 'add.php';
    });

    function showEdit(id){
        location.href = "edit.php?id="+id;
    }

    function update(){
        const id              = $("#id").val();
        const email           = $("#email").val();
        const port            = $("#email").val();
        const password        = $("#password").val();
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
                host : host
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

        $.ajax({
            url : '<?= $baseurl ?>src/email-relay-api.php',
            method : "POST",
            data : {
                do:"save-add", 
                email : email,
                password : password,
                port : port,
                host : host
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
