<?php include '../../header.php' ?>
<?php include '../../navbar.php' ?>
<body class="container">
    <h3>Config Email Sync</h3>
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
                <h5 class="mb-3" id="title"> - </h5>
                <div class="text-end mb-1">
                    <button class="btn btn-sm btn-light" id="btn-sync-membership"><i class="bi bi-arrow-repeat"></i> Sync only membership</button>
                    <button class="btn btn-sm btn-light" id="btn-sync"><i class="bi bi-arrow-repeat"></i> Sync</button>
                </div>
                <input type="hidden" name="" id="id">
                <div class="alert alert-warning warning-sync display-none mb-3"></div>
                <div class="alert alert-success success-sync display-none mb-3"></div>

                <label for="" class=""></label>
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
<?php include '../../footer.php' ?>
<script>

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

                        const urlpath = "http://" + item.actual_path + item.server_domain + item.service;

                        str += '<tr>'+
                            '<td>'+num+'</td>'+
                            '<td>'+item.name+'</td>'+
                            '<td>'+item.email_last_sync+'</td>'+
                            '<td>'+item.jumlah+'</td>'+
                            '<td>'+
                            '<span class="btn-edit" onclick="showDetail(\''+item.id+'\',\''+item.name+'\',\''+item.slug+'\', \''+urlpath+'\')"><i class="bi bi-gear"></i> Config</span>'+
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
        loadEmailData();
    }

    $("#btn-sync").click(function(){
        syncEmail();
    });
    
    function syncEmail(){
        const url = $("#urlpath").val();
        const slug = $("#slug").val();
        const id = $("#id").val();
        $.ajax({
            url : url +'/api/api-sync.php',
            method : "POST",
            data : {
                do:"get-email-sync",
                tbname:"mailblastid",
                slug:slug,
                where:"WHERE subrek = 'subscribed' ORDER BY id ASC"
            },
            success:function(res){
                if (res.code == "200" && res.data.length > 0){
                    let strJson = JSON.stringify(res.data);
                    syncEmailToDb(id, strJson);
                    loadEmailData();
                } else {
                    $(".warning-sync").fadeIn();
                    $(".warning-sync").html("Tidak Ada Email Ter-sinkronisasi");
                }
            }, 
            error:function(er){
                $(".warning-sync").fadeIn();
                $(".warning-sync").html(er.responseJSON == null ? er.responseText : er.responseJSON.message);
            }
        });
    }

    function syncEmailToDb(id, str){
        console.log("sync proses ...");
        $.ajax({
            url : '<?= $baseurl; ?>src/email-sync-api.php',
            method : "POST",
            data : {
                do:"sync-to-db",
                brand_id : id,
                str_email : str 
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
                    '<td colspan="5">List Customer Email</td>'+
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
</script>