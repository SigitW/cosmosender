<?php include '../../header.php' ?>
<?php include '../../navbar.php' ?>
<body class="container">
    <h3>Edit Blast Rules</h3>
    <div class="alert alert-danger danger-search display-none mb-3"></div>
    <div class="alert alert-danger danger-blast-config display-none mb-3">
        Anda Belum Menambahkan Pengaturan Untuk Prosess Blast !
        <div class="text-end">
            <button class="btn btn-sm btn-danger" onclick="showAddBlast()"><i class="bi bi-plus-lg"></i>  Tambahkan</button>
        </div>
    </div>
    <div class="alert alert-success display-none mb-3"></div>
    <div class="mb-1 text-end">
        <a href="index.php" class="btn-menu"><i class="bi bi-chevron-left"></i> Kembali</a>
    </div>
    <div style="overflow-x: auto;"> 
        <label for="" class="mb-1"> Brand</label>
        <input type="text" name="" id="brand-name" class="form-control mb-3" disabled> 
        <!-- <label for="" class="mb-1"> Delay Times (Minute)</label>
        <input type="number" name="" id="delaytimes" class="form-control mb-3" disabled>
        <label for="" class="mb-1"> Qty Send/Delay</label>
        <input type="number" name="" id="qtydelay" class="form-control mb-3" disabled> -->
        
        <div class="mt-3 mb-3 text-end">
            <button class="btn btn-sm btn-light" onclick="showAdd()"><i class="bi bi-plus-lg"></i> Add Send Test</button>
            <!-- <button class="btn btn-sm btn-primary" onclick="editConfigure()"><i class="bi bi-pencil-square"></i> Edit Config</button> -->
        </div>
        <hr/>
        <div id="panel-rules" class="mb-3 over-x"></div>

    </div>
</body>

<!-- Modal -->
<div class="modal fade" id="modal-add" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-add" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modal-label">Add Rules / Send Test</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="alert alert-warning display-none mb-3"></div>
                <input type="hidden" name="" id="id"/>
                <label for="" class="mb-1"> Rules Name</label>
                <input type="text" name="" id="name" class="form-control mb-3"> 
                <label for="" class="mb-1"> SMTP Server</label>
                <select name="" id="sel-host" class="form-control">

                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-success" id="btn-save" onclick="storeRule()"><i class="bi bi-check-lg"></i> Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-add-blast" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-add" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modal-label">Add Blast Config</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="alert alert-warning warning-blast-config display-none mb-3"></div>
                <label for="" class="mb-1"> SMTP Server</label>
                <select name="" id="sel-host-blast" class="form-control">

                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-success" id="btn-save" onclick="storeRuleBlast()"><i class="bi bi-check-lg"></i> Save</button>
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
                <div class="alert alert-warning warning-relay display-none mb-3"></div>
                <input type="hidden" name="" id="host-id"/>
                <input type="hidden" name="" id="rule-id"/>
                <label for="" class="mb-1"> Email</label>
                <select name="" id="sel-relay" class="form-control"></select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-success" id="btn-save-relay" onclick="storeRelay()"><i class="bi bi-check-lg"></i> Save</button>
            </div>
        </div>
    </div>
</div>

<?php include '../../footer.php' ?>
<script>

    var brandId = '<?= $_GET['id'] ?>';

    $(document).ready(function(){
        load();
    });

    function load(){
        $.ajax({
            url : '<?= $baseurl ?>src/blast-rule-api.php',
            method : "POST",
            data : {do:"load", brand_id : brandId},
            success:function(res){
                if (res.data.length > 0){
                    const data = res.data[0];
                    $("#brand-name").val(data.name);

                    // load rules 
                    loadRules(data.rules);

                    // check apakah ada config blast
                    const blastData = data.rules.filter(p => p.type === 'blast');
                    if (blastData.length == 0)
                        $(".danger-blast-config").fadeIn();
                    else 
                        $(".danger-blast-config").hide();
                }
            }, 
            error:function(er){
                console.log(er);
                $(".danger-search").fadeIn();
                $(".danger-search").html(er.responseText);
            }
        });
    }

    function loadRules(list){
        // looping rules
        if(list.length == 0)
            return false;
        
        let str = '';    
        $.each(list, function(i, item){

            if (item.details.length > 0)
                console.log("ada details");

            const stDetails = buildDetail(item.details);                
            const btnAdd    = 'onclick="showAddRelay(\''+item.id+'\', \''+item.host_id+'\')"';

            str += '<h4 class="mt-3">'+item.name+' : </h4>'+
                    '<div class="text-end mb-2">'+
                        '<button class="btn btn-sm btn-light me-1"><i class="bi bi-pencil-square"></i> Edit Rules Name</button>'+
                        '<button class="btn btn-sm btn-light" '+btnAdd+'><i class="bi bi-plus-lg"></i> Add Relay</button>'+
                    '</div>'+
                    '<div class="over-x mb-3">'+
                        '<table class="table table-dark table-borderless table-striped table-hover">'+
                            '<tbody>'+ stDetails +
                                // '<tr>'+
                                //     '<td>1</td>'+
                                //     '<td>noreplay@gmail.com</td>'+
                                //     '<td>mail.indraco.com</td>'+
                                //     '<td class="text-center"><a href="" class="btn-menu"><i class="bi bi-x-lg red bold"></i></a></td>'+
                                // '</tr>'+
                            '</tbody>'+
                        '</table>'+
                    '</div><hr/>';
        }); 
        $("#panel-rules").html(str);
    }

    function buildDetail(list){
        if (list.length == 0)
            return "";
        
        let str = '<table class="table table-dark table-borderless table-striped table-hover">';
        $.each(list, function(i, item){
            const num = 1 + i;
            str += '<tr>'+
                        '<td>'+num+'</td>'+
                        '<td>'+item.email+'</td>'+
                        '<td>'+item.host_name+'</td>'+
                        '<td class="text-center"><a href="" class="btn-menu"><i class="bi bi-x-lg red bold"></i></a></td>'+
                    '</tr>';
        });
        str += '</tbody>'+
            '</table>';
            
        return str;    
    }

    function showAddRelay(ruleId, hostId){
        $(".warning-relay").html("");
        $(".warning-relay").hide();
        $("#modal-add-relay").modal('show');
        $("#host-id").val(hostId);
        $("#rule-id").val(ruleId);
        loadRelay(hostId, "sel-relay");
    }

    function storeRelay(){

        const ruleId = $("#rule-id").val();
        const hostId = $("#host-id").val();
        const relayId = $("#sel-relay option:selected").val();

        $.ajax({
            url : '<?= $baseurl ?>src/blast-rule-api.php',
            method : "POST",
            data : {
                do:"store-relay",
                rule_id:ruleId,
                host_id:hostId,
                relay_id:relayId
            }, success:function(res){
                if (res.code == "200"){
                    $("#modal-add-relay").modal("hide");
                    $(".alert-success").fadeIn();
                    $(".alert-success").html(res.message);
                    load();
                }
            }, error:function(er){
                $(".warning-relay").fadeIn();
                $(".warning-relay").html(er.responseText);
            }
        })

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
                    $("#sel-host-blast").html(str);
                }
            }, 
            error:function(er){
                console.log(er);
            }
        });
    }

    function showAdd(){
        $("#modal-add").modal('show');
        $(".alert-warning").hide();
        $(".alert-warning").val("");
        loadHost();
        $("#name").val("");
        $("#sel-host").val("");
    }

    function showAddBlast(){
        $("#modal-add-blast").modal('show');
        $(".warning-blast-config").hide();
        $(".warning-blast-config").val("");
        loadHost();
        $("#sel-host-blast").val("");
    }
    

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

    function storeRule(){
        const name        = $("#name").val();
        const host        = $("#sel-host option:selected").val();

        $.ajax({
            url : '<?= $baseurl ?>src/blast-rule-api.php',
            method : "POST",
            data : {
                do:"save-add", 
                name : name,
                brand : brandId,
                host : host,
                type : "test"
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

    function storeRuleBlast(){
        const name        = "Blast";
        const host        = $("#sel-host-blast option:selected").val();

        $.ajax({
            url : '<?= $baseurl ?>src/blast-rule-api.php',
            method : "POST",
            data : {
                do:"save-add", 
                name : name,
                brand : brandId,
                host : host,
                type : "blast"
            }, success : function(res){
                if (res.code == "200"){
                    $("#modal-add-blast").modal("hide");
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

    function loadRelay(hostId, target){
        $.ajax({
            url : '<?= $baseurl ?>src/blast-rule-api.php',
            method : "POST",
            data : {do:"load-relay-by-host", host_id : hostId},
            success:function(res){
                if (res.data.length > 0){
                    let str = '';
                    $.each(res.data, function(i, item){
                        str += '<option value="'+item.id+'">' + item.email + '</option>';
                    });
                    $("#sel-relay").html(str);
                }
            }, error:function(er){
                $(".warning-relay").fadeIn();
                $(".warning-relay").html(er.responseText);
            }
        });
    }

    function formWarna(warna){
        return '<div class="rounded-circle" style="width:20px;height:20px;background-color:'+warna+'"></div>'
    }
</script>
