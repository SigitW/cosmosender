<?php include '../../header.php' ?>
<?php include '../../navbar.php' ?>
<body class="container">
    <h3>Customers Email Data</h3>
    <div class="alert alert-danger display-none mb-3"></div>
    <div class="alert alert-success success-search display-none mb-3"></div>
    <div style="overflow-x: auto;"> 
        <table class="table table-dark table-striped table-hover">
            <thead>
                <tr>
                <td>#</td>
                <td>Brand</td>
                <td>Last Sync</td>
                <td>Number of Emails</td>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modal-label">Detail</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5 class="mb-3" id="title">Pandan Garden</h5>
                <div class="text-end mb-1">
                    <button class="btn btn-sm btn-light" id="btn-sync"><i class="bi bi-arrow-repeat"></i> Sync</button>
                </div>
                <input type="hidden" name="" id="id">
                <input type="hidden" name="" id="slug">
                <input type="hidden" name="" id="urlpath">

                <input type="hidden" name="" id="c-table">
                <input type="hidden" name="" id="c-email-column">
                <input type="hidden" name="" id="c-name-column">
                <input type="hidden" name="" id="c-where-clause">
                <input type="hidden" name="" id="c-where-value">

                <div class="alert alert-warning warning-sync display-none mb-3"></div>
                <div class="alert alert-success success-sync display-none mb-3"></div>

                <div class="over-x">
                    <table class="table table-dark table-striped table-borderless table-hover">
                        <tbody id="body-table">
                        </tbody>
                    </table>
                </div>
                <div class="text-end">
                    <span class="btn-edit noselect me-2" onclick="backPage()"><i class="bi bi-chevron-left"></i> Back </span> 
                    <select name="" id="sel-page" onchange="comboPage()" style="width:50px">
                        <option value="1">1</option>
                        <option value="2">2</option>
                    </select>
                    <span class="btn-edit noselect ms-2" onclick="nextPage()"> Next <i class="bi bi-chevron-right"></i></span> 
                </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-sm btn-success" id="btn-save"><i class="bi bi-check-lg"></i> Save</button> -->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-add-config" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-add" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modal-label">Config</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5 id="config-title">Config</h5>
                <div style="font-size: 12px;" class="mb-4">
                * untuk mencocokan table dan column pada database masing - masing brand. 
                sebagai acuan untuk pengambilan data email customer 
                </div>
                <input type="hidden" name="" id="id-config">
                <div class="alert alert-warning warning-config display-none mb-3"></div>
                <div class="alert alert-success success-config display-none mb-3"></div>

                <label for="" class="mb-1">Target Table Name</label>
                <input type="text" name="" id="table-config" class="form-control mb-3"/>
                <hr>
                <label for="" class="mb-1">Target Email Column</label>
                <input type="text" name="" id="email-config" class="form-control mb-3"/>
                <label for="" class="mb-1">Target Name Column</label>
                <input type="text" name="" id="name-config" class="form-control mb-3"/>
                <hr>
                <label for="" class="mb-1">Where Clause</label>
                <input type="text" name="" id="where-config" class="form-control mb-3"/>
                <label for="" class="mb-1">Where value</label>
                <input type="text" name="" id="where-value-config" class="form-control mb-3"/>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-success" id="btn-save-config"><i class="bi bi-check-lg"></i> Save</button>
            </div>
        </div>
    </div>
</div>

<?php include '../../footer.php' ?>
<script>

    const base = '<?= $base; ?>';
    $(document).ready(function(){
        load();
    });

    function load(){
        $.ajax({
            url : '<?= $baseurl ?>src/email-sync-api.php',
            method : "POST",
            data : {do:"load-brand"},
            success:function(res){
                console.log(res);
                if (res.code == "200" && res.data.length > 0){
                    let str = '';
                    $.each(res.data, function(i, item){
                        const num = 1 + i;

                        const urlpath = base + item.server_domain + item.service;

                        str += '<tr>'+
                            '<td>'+num+'</td>'+
                            '<td>'+item.name+'</td>'+
                            '<td>'+item.email_last_sync+'</td>'+
                            '<td>'+item.jumlah+'</td>'+
                            '<td>'+
                            '<span class="btn-edit" onclick="showDetail(\''+item.id+'\',\''+item.name+'\',\''+item.slug+'\', \''+urlpath+'\')"><i class="bi bi-card-list"></i> Detail</span>'+
                            '<div class="vr ms-2 me-2"></div>'+
                            '<span class="btn-edit" onclick="showConfig(\''+item.id+'\',\''+item.name+'\')"><i class="bi bi-gear"></i> Config</span>'+
                            '</td>'+
                            '</tr>';
                    });
                    $("#table-body").html(str);    
                }
            }, 
            error:function(er){
                $(".alert-danger").fadeIn();
                $(".alert-danger").html(er.responseJSON == null ? er.responseText : er.responseJSON.message);
            }
        });
    }

    function showDetail(id, name, slug, urlpath){
        $("#modal-add").modal("show");
        $("#modal-label").html("Detail");
        $("#title").html(name);
        $(".alert-warning").hide();
        $(".alert-warning").html("");
        $("#id").val(id);
        $("#slug").val(slug);
        $(".warning-sync").hide();
        $(".warning-sync").html("");
        $(".success-sync").hide();
        $(".success-sync").html("");
        $("#urlpath").val(urlpath); 
        $("#body-table").html("");
        loadSyncConfigData(id);       
        loadEmailData();
    }

    function showConfig(id, name){
        $("#modal-add-config").modal("show");
        $("#config-title").html(name);
        $(".warning-config").hide();
        $(".success-config").hide();
        $("#id-config").val(id);

        $.ajax({
            url:'<?= $baseurl ?>src/email-sync-api.php',
            method:"POST",
            data:{
                do:"load-config",
                brand_id:id
            }, success:function(res){
                if (res.code == "200" && res.data.length > 0){
                    const item = res.data[0];
                    $("#table-config").val(item.table_name);
                    $("#email-config").val(item.email_column);
                    $("#name-config").val(item.name_column);
                    $("#where-config").val(item.where_clause);
                    $("#where-value-config").val(item.where_value);
                } else {
                    $("#table-config").val("");
                    $("#email-config").val("");
                    $("#name-config").val("");
                    $("#where-config").val("");
                    $("#where-value-config").val("");
                }
            }, error:function(er){
                $(".warning-config").fadeIn();
                $(".warning-config").html(er.responseJSON == null ? er.responseText : er.responseJSON.message);
            }

        })
    }

    $("#btn-sync").click(function(){
        syncEmail();
    });
    
    function syncEmail(){
        const url   = $("#urlpath").val();
        const slug  = $("#slug").val();
        const id    = $("#id").val();
        const tbname         = $("#c-table").val();
        const emailColumn    = $("#c-email-column").val();
        const nameColumn     = $("#c-name-column").val();
        const whereClause    = $("#c-where-clause").val();
        const whereValue     = $("#c-where-value").val();
        $.ajax({
            url : url +'/api/api-sync.php',
            method : "POST",
            data : {
                do:"get-email-sync",
                tbname: tbname,
                email_column:emailColumn,
                name_column:emailColumn,
                where_clause : whereClause,
                where_value : whereValue,
                slug:slug
            },
            success:function(res){
                if (res.code == "200" && res.data.length > 0){
                    let strJson = JSON.stringify(res.data);
                    syncEmailToDb(id, strJson, emailColumn, nameColumn);
                    loadEmailData();
                } else {
                    $(".warning-sync").fadeIn();
                    $(".warning-sync").html("Tidak Ada Email Ter-sinkronisasi");
                }
            }, 
            error:function(er){
                console.log(er);
                $(".warning-sync").fadeIn();
                $(".warning-sync").html(er.responseJSON == null ? er.responseText : er.responseJSON.message);
            }
        });
    }

    function syncEmailToDb(id, str, emailColumn, nameColumn){
        console.log("sync proses ...");
        $.ajax({
            url : '<?= $baseurl; ?>src/email-sync-api.php',
            method : "POST",
            data : {
                do:"sync-to-db",
                brand_id : id,
                str_email : str, 
                email_column : emailColumn, 
                name_column : nameColumn
            }, success:function(res){
                if (res.code == "200"){
                    console.log("true");
                    $(".success-sync").fadeIn();
                    $(".success-sync").html(res.message);
                    return true;
                } else {
                    console.log(res);
                    $(".warning-sync").fadeIn();
                    $(".warning-sync").html(res);
                    return false;
                }
            }, error:function(er){
                console.log("false");
                $(".warning-sync").fadeIn();
                $(".warning-sync").html(er.responseJSON == null ? er.responseText : er.responseJSON.message);
                return false;
            }
        })
    }

    var emailData = [];
    var maxPage = 0;
    function loadEmailData(){
        emailData = [];
        const id = $("#id").val();
        $.ajax({
            url : '<?= $baseurl; ?>src/email-sync-api.php',
            method : "POST",
            data : {
                do:"load-by-brand",
                brand_id : id            
            }, success:function(res){
                if (res.code == "200" && res.data.length > 0){
                   emailData = res.data;
                   maxPage = res.page;
                   loadDataPerPage('1', res.data);
                   renderComboPage(res.page);
                } else {
                    console.log(res);
                }
            }, error:function(er){
                console.log(er);
                $(".warning-sync").fadeIn();
                $(".warning-sync").html(er.responseJSON == null ? er.responseText : er.responseJSON.message);
            }
        })
        return false;
    }

    function comboPage(){
        const nopage = $("#sel-page option:selected").val();
        loadDataPerPage(nopage, emailData);
    }

    function backPage(){
        const nopage = $("#sel-page option:selected").val();
        const page = nopage == 1 ? nopage : parseInt(nopage) - 1;
        $("#sel-page").val(page);
        loadDataPerPage(page, emailData);
    }

    function nextPage(){
        const nopage = $("#sel-page option:selected").val();
        const page = maxPage == nopage ? nopage : parseInt(nopage) + 1;
        $("#sel-page").val(page);
        loadDataPerPage(page, emailData);
    }

    function renderComboPage(page){
        let str = '';
        for (let i = 1; i <= page ; i++){ 
            str += '<option value=\''+i+'\'>'+i+'</option>';
        }
        $("#sel-page").html(str);
    }

    function loadDataPerPage(nopage, list){
        // console.log("loadDataPerPage " + list.length);
        // console.log(nopage);
        // console.log(list);
        const listEmail = list.filter(p => p.page == nopage);
        let str = '<tr>'+
                    '<td colspan="4">List Customer Email</td>'+
                  '</tr>';
        $.each(listEmail, function(i, item){
            str += '<tr>'+
                        '<td>'+item.num+'</td>'+
                        '<td>'+item.email+'</td>'+
                        '<td>'+item.nama+'</td>'+
                        '<td>'+item.created_at+'</td>'+
                    '</tr>';
        });
        $("#body-table").html(str);
    }

    $("#btn-save-config").click(function(){
        const tbname = $("#table-config").val();
        const targetEmailColumn = $("#email-config").val();
        const targetNameColumn = $("#name-config").val();
        const whereClause = $("#where-config").val();
        const brandConfig = $("#id-config").val();
        const whereValue = $("#where-value-config").val();
        $.ajax({
            url:'<?= $baseurl ?>src/email-sync-api.php',
            method:"POST",
            data:{
                do:"save-config",
                brand_id:brandConfig,
                tbname:tbname,
                email_column:targetEmailColumn,
                name_column:targetNameColumn,
                where_clause:whereClause,
                where_value:whereValue,
            }, success:function(res){
                if (res.code == "200"){
                    $("#modal-add-config").modal('hide');
                    $(".success-search").fadeIn();
                    $(".success-search").html(res.message);
                }
            }, error:function(er){
                $(".warning-config").fadeIn();
                $(".warning-config").html(er.responseJSON == null ? er.responseText : er.responseJSON.message);
            }
        })
    });

    function loadSyncConfigData(id){
        $.ajax({
            url:'<?= $baseurl ?>src/email-sync-api.php',
            method:"POST",
            data:{
                do:"load-config",
                brand_id:id
            },
            success:function(res){
                if (res.code == "200" && res.data.length > 0){
                    const item = res.data[0];
                    $("#c-table").val(item.table_name);
                    $("#c-email-column").val(item.email_column);
                    $("#c-name-column").val(item.name_column);
                    $("#c-where-clause").val(item.where_clause);
                    $("#c-where-value").val(item.where_value);

                    if (item.table_name == "" || item.email_column == ""){
                        $(".warning-config").fadeIn();
                        $(".warning-config").html("Lengakapi Nama Tabel dan Kolom Email pada Konfigurasi Terlebih Dahulu");   
                    }

                } else {
                    $("#c-table").val("");
                    $("#c-email-column").val("");
                    $("#c-name-column").val("");
                    $("#c-where-clause").val("");
                    $("#c-where-value").val("");
                }
            }, error:function(er){
                $(".warning-config").fadeIn();
                $(".warning-config").html(er.responseJSON == null ? er.responseText : er.responseJSON.message);
            }
        })
    }
</script>