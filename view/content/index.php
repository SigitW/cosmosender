<?php include '../../header.php' ?>
<?php include '../../navbar.php' ?>
<?php 
$id = "";
$isId = isset($_GET['id']);
if ($isId)
    $id = $_GET['id']; 
?>
<body class="container">
    <h3 class="mb-3">Content Management</h3>
    <h5 class="mb-3" id="brand-title"></h5>
    <div class="alert alert-danger danger-search display-none mb-3"></div>
    <div class="alert alert-success success-search display-none mb-3"></div>
    <div class="mb-3 text-end">
        <a href="../index.php" class="btn-menu"><i class="bi bi-chevron-left"></i> Kembali</a>
    </div>
    <div class="text-end mb-2">
        <button class="btn btn-sm btn-light" id="btn-add"><i class="bi bi-plus-lg"></i> Add</button>
    </div>
    <div class="over-x">
        <table class="table table-dark table-striped table-hover text-nowrap">
            <thead>
                <tr>
                <td>#</td>
                <td>Materi Name</td>
                <td>Subject</td>
                <td>Date</td>
                <td>Time</td>
                <td></td>
                </tr>
            </thead>
            <tbody id="table-body">

            </tbody>
        </table>
    </div>
</body>

<div class="modal fade" id="modal-add" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-add" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Add Materi</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning warning-add display-none mb-3"></div>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label for="" class="mb-1">Materi</label>
                        <input type="text" name="" id="add-materi" class="form-control mb-3"/>
                        <label for="" class="mb-1">Subject</label>
                        <input type="text" name="" id="add-subject" class="form-control mb-3"/>
                        <label for="" class="mb-1">Date</label>
                        <input type="date" name="" id="add-date" class="form-control mb-3"/>
                        <label for="" class="mb-1">Blast Hour</label>
                        <select name="" id="add-time" class="form-control mb-3">
                            <option value="11:00">11:00</option>
                            <option value="17:00">17:00</option>
                        </select>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-success" id="btn-save-config" onclick="createContent()"><i class="bi bi-check-lg"></i> Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-add" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Edit Materi</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning warning-edit display-none mb-3"></div>
                <input type="hidden" name="" id="edit-id"/>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label for="" class="mb-1">Materi</label>
                        <input type="text" name="" id="edit-materi" class="form-control mb-3"/>
                        <label for="" class="mb-1">Subject</label>
                        <input type="text" name="" id="edit-subject" class="form-control mb-3"/>
                        <!-- <label for="" class="mb-1">Date</label>
                        <input type="date" name="" id="edit-date" class="form-control mb-3"/>
                        <label for="" class="mb-1">Blast Hour</label>
                        <select name="" id="edit-hour" class="form-control mb-3">
                            <option value="11:00">11:00</option>
                            <option value="17:00">17:00</option>
                        </select> -->
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-success" onclick="saveEdit()"><i class="bi bi-check-lg"></i> Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-edit-content" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-add" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Edit Content</h1>
                <button type="button" class="btn-close" id="btn-close-edit-content" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning warning-edit-content display-none mb-3"></div>
                <div class="alert alert-success success-edit-content display-none mb-3"></div>
                <input type="hidden" name="" id="c-date"/>
                <input type="hidden" name="" id="c-time"/>
                <input type="hidden" name="content_id" id="id-content"/>
                <div class="row">
                    <div class="col-md-4 col-xs-12" style="margin-bottom:20px;">
                        <div>
                            <div id="content-brand" style="font-size: 20px;font-weight:bold">
                            </div>
                            <div id="content-tanggal" style="font-size: 12px;" class="mb-3"> - </div>
                            <div id="content-materi"> - </div>
                        </div>
                        <hr/>    
                        <span style="color:gray;font-size: 12px;">* klik url dibawah asset untuk meng-copy url asset tersebut</span>
                        <div class="mt-1" style="width:100%;height:500px;background-color:lightgrey;border-radius:5px;overflow-y:scroll;padding:5px;" id="asset-panel"></div>
                    </div>
                    <div class="col-md-8 col-xs-12">
                        <textarea name="" id="content-editor" style="width:100%;"></textarea>
                    </div>
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-success" id="btn-save-content"><i class="bi bi-check-lg"></i> Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-upload" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-add" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Upload Asset</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning warning-upload display-none mb-2">Tolong pilih file dulu</div>
                <form action="" id="form-upload" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="brand_name" id="upload-brand-name">
                    <input type="hidden" name="content_id" id="upload-content-id">
                    <input type="hidden" name="upload_path" id="upload-path">
                    <input type="hidden" name="upload_date" id="upload-date">
                    <input type="hidden" name="upload_hour" id="upload-hour">
                    <input type="hidden" name="back_url" id="upload-back-url">
                    <input type="hidden" name="update_url" id="update-url">
                    <input type="hidden" name="update_do" id="update-do">
                    <input type="file" name="fileupload[]" id="fileupload" class="form-control" accept="image/*" multiple>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-primary" id="btn-save-upload"><i class="bi upload"></i> Upload</button>
            </div>
        </div>
    </div>
</div>

<?php include '../../footer.php' ?>
<script>

    var textArea = document.getElementById("content-editor");
    var editor = CodeMirror.fromTextArea(textArea, {
                    lineNumbers: true,
                    mode: "xml",
                    htmlMode:true,
                    matchBrackets: true,
                    theme:"dracula",
                });    
    editor.setSize(null, 800);

    var foundId = '<?= $isId ?>';
    $(document).ready(function(){
        $(".alert-danger").hide();
        if (!foundId){
            callError("Brand Id Tidak Ditemukan.");
        };
        loadContent();
        loadBrand();
    });

    function callError(data){
        $(".alert-danger").show();
        $(".alert-danger").html(data);
    }

    $("#btn-add").click(function(){
        $("#modal-add").modal("show");
    })

    var base        = '<?= $base; ?>';
    var objBrand    = {};
    var apipath     = "";
    var contentpath = "";
    var uploadpath  = "";
    function loadBrand(){
        $.ajax({
            url:'<?= $baseurl ?>src/brand-api.php',
            method : "POST",
            data : {do:"load-by-id", brand_id:'<?= $id ?>'},
            success:function(res){
                console.log(res);
                if (res.code == "200" && res.data.length > 0){
                    objBrand    = res.data[0];
                    apipath     = base + objBrand.service_path;
                    contentpath = objBrand.content_path;
                    uploadpath  = objBrand.upload_path;
                    $("#brand-title").html(objBrand.name);
                }
                console.log(apipath);
                console.log(contentpath);
                console.log(uploadpath);
            }, error:function(er){
                $(".danger-search").fadeIn();
                $(".danger-search").html(er.responseJSON == null ? er.responseText : er.responseJSON.message);
            }
        })
    }

    function loadContent(){
        $.ajax({
            url : '<?= $baseurl ?>/src/content-api.php',
            method : "POST",
            data : {
                do : "load",
                brand_id : '<?= $id ?>'
            }, 
            success : function (res) {
                if (res.data.length > 0){
                    let str = '';
                    $.each(res.data, function(i, item){

                        const num = 1 + i;
                        str += '<tr>'+
                            '<td>'+num+'</td>'+
                            '<td width="500px;">'+ replaceNull(item.materi_name)+'</td>'+
                            '<td>'+ replaceNull(item.subject)+'</td>'+
                            '<td>'+item.date_namespace+'</td>'+
                            '<td>'+item.time_namespace+'</td>'+
                            '<td>'+
                            '<div class="btn-edit d-inline ms-4 me-4" onclick="showEdit(\''+item.id+'\',\''+item.materi_name+'\', \''+item.subject+'\')"><i class="bi bi-pencil-square"></i> Edit</div>'+
                            '<div class="btn-edit d-inline me-4" onclick="showUpload(\''+item.id+'\', \''+item.materi_name+'\',\''+item.date_namespace+'\',\''+item.time_namespace+'\')"><i class="bi bi-upload"></i> Upload Asset</div>'+
                            '<div class="btn-edit d-inline me-4" onclick="showContentById(\''+item.id+'\', \''+item.materi_name+'\',\''+item.date_namespace+'\',\''+item.time_namespace+'\')"><i class="bi bi-grid"></i> Content</div>'+
                            '<div class="btn-edit d-inline me-4" onclick="showPreview(\''+item.date_namespace+'\',\''+item.time_namespace+'\')"><i class="bi bi-search"></i> View</div>'+
                            '</td>'+
                            '</tr>';
                    });
                    $("#table-body").html(str);
                }
            }, 
            error : function (e) {
                console.log(e);
            }
        })
    }

    function showUpload(id, materi, tgl, jam){
        $("#modal-upload").modal("show");
        $("#upload-brand-name").val(objBrand.name);
        $("#upload-path").val(uploadpath);
        $("#upload-content-id").val(id);
        $("#upload-date").val(tgl);
        $("#upload-hour").val(jam);
        $("#update-url").val('<?= $baseurl ?>src/content-api.php');
        $("#update-do").val('insert-name-asset');
        $("#form-upload").attr("action", apipath + 'upload.php');
        $("#upload-back-url").val(window.location.href);
    }

    $("#btn-save-upload").on("click", function(){

        if ($("#fileupload").val() == ''){
            $(".warning-upload").fadeIn().delay(2000).fadeOut();
            return false;
        }
        
        $("#form-upload").submit();
    });

    function showEdit(id, materi, subject){
        $(".warning-edit").hide();
        $(".warning-edit").html("");
        $("#edit-id").val(id);
        $("#edit-materi").val(materi);
        $("#edit-subject").val(subject);
        $("#modal-edit").modal('show');
    }

    function saveEdit(){

        const id      = $("#edit-id").val();
        const materi  = $("#edit-materi").val();
        const subject = $("#edit-subject").val();

        $.ajax({
            url:'<?= $baseurl ?>src/content-api.php',
            method:"POST",
            data:{
                do:"update-materi",
                content_id:id, 
                materi:materi,
                subject:subject
            }, success:function(res){
                if (res.code == "200"){
                    $(".success-search").fadeIn().delay(2000).fadeOut();
                    $(".success-search").html(res.message);
                    $("#modal-edit").modal('hide');
                    loadContent();
                } 
            }, error:function(er){
                console.log(er);
                $(".warning-edit").fadeIn();
                $(".warning-edit").html(er.responseJSON == null ? er.responseText : er.responseJSON.message);
            }
        })
    }

    function showContentById(id, materi, tanggal, jam){
        $("#modal-edit-content").modal('show');
        $("#asset-panel").html("")
        $("#id-content").val(id);
        $("#content-editor").html("");
        $(".success-edit-content").hide();
        $(".warning-edit-content").hide();

        pathPreview = "";
        
        $.ajax({
            url: '<?= $baseurl ?>src/content-api.php',
            method: "POST",
            data : {
                content_id : id,
                do : 'load-content-by-id'
            },
            success: function (res){

                console.log(res);
                let str = "";

                if (res.data.content != null){
                    showCodeMirror(res.data.content);
                } else {
                    showCodeMirror("");
                }

                setTimeout(() => {
                    editor.refresh();
                }, 500);
                
                if (res.data.asset.length > 0){
                    $.each(res.data.asset, function(i,item){
                        str += panelAsset(item);
                    });
                }

                // set to input content modal
                $("#c-date").val(tanggal);
                $("#c-time").val(jam);

                pathPreview = res.data.path;
                $("#asset-panel").html(str);
                $("#content-tanggal").html(tanggal + " Jam " + jam);
                $("#content-materi").html(materi == "null" ? "" : materi);

            },
            error: function(er){
                console.log(er);
                $(".warning-edit-content").fadeIn();
                $(".warning-edit-content").html(er.responseJSON == null ? er.responseText : er.responseJSON.message);
            }
        });
    }

    $("#btn-save-content").click(function(){
        updateContent();
    });

    function updateContent(){
        const contentbody   = editor.getDoc().getValue();
        const id            = $("#id-content").val();
        const dateNamespace = $("#c-date").val();
        const timeNamespace = $("#c-time").val();
        
        $.ajax({
            url: '<?= $baseurl ?>src/content-api.php',
            method: "POST",
            data : {
                do : 'update-content',
                content_id : id,
                content : contentbody
            },
            success: function(res){
                if (res.code == "200"){
                    sendContentToServer(contentbody, dateNamespace, timeNamespace);
                }
            },
            error: function(er){
                console.log(er);
                $(".warning-edit-content").fadeIn();
                $(".warning-edit-content").html(er.responseJSON == null ? er.responseText : er.responseJSON.message);
            }
        });
    };

    function sendContentToServer(content, datenamespace, timenamespace){
        const path = uploadpath + datenamespace + "/" + timenamespace + "/";
        console.log(path);
        $.ajax({
            url:apipath + "api/api-content.php",
            method:"POST",
            data:{
                do:"upload-content", 
                id:"1",
                token:"1",
                content:content, 
                path:path
            }, success:function(res){
                if (res.code == "200"){
                    $(".success-edit-content").html(res.message);
                    $(".success-edit-content").fadeIn().delay(2000).fadeOut();
                }
            }, error:function(er){
                console.log(er);
                $(".warning-edit-content").fadeIn();
                $(".warning-edit-content").html(er.responseJSON == null ? er.responseText : er.responseJSON.message);
            }
        })
    }

    function replaceNull(str){
        return str == null ? "-" : str;
    }

    let pathPreview = "";


    function showCodeMirror(code){
        editor.getDoc().setValue(code);
    }

    function panelAsset(item){
        return '<div style="border-radius:5px 5px 0px 0px;height:100px;margin-top:10px;text-align:center;cursor:pointer;background-color:grey;">'+
        '<img src="'+ base + item +'" style="height:100%;margin:auto 0px">'+
        '<br/>'+
        '<input type="text" class="form-control" style="cursor:pointer;color:grey;border-radius: 0px 0px 5px 5px;width:100%;" value="'+ base + item + '" onclick="navigator.clipboard.writeText(this.value);" readonly/>' +
        '<div/>';
    }

    function showPreview(tgl, jam){
        const pathPreview = base + contentpath + tgl + "/" + jam + "/";
        window.open(pathPreview);
    }

    $("#btn-close-edit-content").click(function(){
        if (confirm("Pastikan Menyimpan Content Editor Dahulu Sebelum Keluar ?")){
            $("#modal-edit-content").modal("hide");
        }
    });

    function uploadAsset(){

        const id            = $("#id-content").val();
        const filePath      = $("#files").val();
        const filename      = getFileName(filePath);
        const type          = filename.split('.').pop();
        const ltype         = type.toLowerCase();
        const isValidType   = ltype == "png" || ltype == "jpg" || ltype == "gif" || ltype == "jpeg";

        if (!isValidType){
            alert(filename + ' - Not Valid Type');
            return;
        }

        $.ajax({
            url: '<?= $baseurl ?>src/content-api.php',
            method: 'POST',
            data : {
                token : "1",
                id : "1",
                content_id : id,
                strimg : base64Files,
                type : type,
                do : 'upload-asset'
            },
            success: function(res){
                if (res.code == "200"){
                    uploadAssetToServer(type, res.data.filename);
                }
            },
            error: function(er){
                console.log(er);
                $(".warning-edit-content").fadeIn();
                $(".warning-edit-content").html(er.responseJSON == null ? er.responseText : er.responseJSON.message);
            }
        });
    }

    let base64Files = '';
    $("#files").on('change', function(e){
        // Get a reference to the file
        const file = e.target.files[0];

        // Encode the file using the FileReader API
        const reader = new FileReader();
        reader.onloadend = () => {
            // console.log(reader.result);
            base64Files = reader.result;
            // Logs data:<type>;base64,wL2dvYWwgbW9yZ...
        };
        reader.readAsDataURL(file);
    })

    function uploadAssetToServer(type, filename){

        const dateNamespace = $("#c-date").val();
        const timeNamespace = $("#c-time").val();
        const path          = uploadpath + dateNamespace + "/" + timeNamespace + "/" + "img/";

        $.ajax({
            url: apipath + 'api/api-content.php',
            method: 'POST',
            data : {
                token : "1",
                id : "1",
                strimg : base64Files,
                type : type,
                path : path,
                filename : filename,
                do : 'upload-asset'
            },
            success: function(res){
                if (res.code == "200"){
                    loadPanelAsset();
                    $("#files").val("");
                    $(".success-edit-content").html(res.message);
                    $(".success-edit-content").fadeIn().delay(2000).fadeOut();
                }
            },
            error: function(er){
                console.log(er);
                $(".warning-edit-content").fadeIn();
                $(".warning-edit-content").html(er.responseJSON == null ? er.responseText : er.responseJSON.message);
            }
        });
    }

    function loadPanelAsset(){
        const id = $("#id-content").val();
        $.ajax({
            url: '<?= $baseurl ?>src/content-api.php',
            type: 'POST',
            headers: {
            },
            data : {
                token : "1",
                id : "1",
                content_id : id,
                do : 'load-content-by-id'
            },
            success: function (res){
                let str = '';
                if (res.data.asset.length > 0){
                    $.each(res.data.asset, function(i,item){
                        str += panelAsset(item);
                    });
                }
                $("#asset-panel").html(str);
            },
            error: function(er){
                console.log(er);
                $(".warning-edit-content").fadeIn();
                $(".warning-edit-content").html(er.responseJSON == null ? er.responseText : er.responseJSON.message);
            }
        });
    }

    function getFileName(fullPath){
        var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
        var filename = fullPath.substring(startIndex);
        if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
            return filename = filename.substring(1);
        }
        return "";
    }

    function createContent(){
        const materi    = $("#add-materi").val();
        const subject   = $("#add-subject").val();
        const date      = $("#add-date").val();
        const time      = $("#add-time").val();

        if (date == ""){
            alert("Tolong isikan Content Blast Date");
            return;
        }
        
        if (time == ""){
            alert("Tolong isikan Content Blast Time");
            return;
        }

        const arrDate = date.split('-');
        const arrTime = time.split(':');

        let mappedDate = "";
        $.each(arrDate, function(i, item){            
            mappedDate += arrDate[i];
            if (i == 0)
                mappedDate = mappedDate.substring(2,4);
        });

        let mappedTime = "";
        $.each(arrTime, function(i, item){
            mappedTime += arrTime[i];
        });

        $.ajax({
            url: '<?= $baseurl ?>src/content-api.php',
            method: 'POST',
            data: {
                token : "1",
                id : "1",
                brandid : '<?= $id ?>',
                materi : materi,
                subject : subject,
                date : mappedDate,
                time : mappedTime,
                do : 'create-content'
            },
            success: function (res) {
                if (res.code == "200") {
                    createDirToServer(mappedDate, mappedTime);
                } else {
                    console.log(res);
                    $(".warning-add").fadeIn();
                    $(".warning-add").html(res.responseJSON == null ? res.responseText : res.responseJSON.message);
                }
            },
            error: function (er) {
                console.log(er);
                $(".warning-add").fadeIn();
                $(".warning-add").html(er.responseJSON == null ? er.responseText : er.responseJSON.message);
            }
        });
    }

    function createDirToServer(mappedDate, mappedTime){

        const strBrandData = JSON.stringify(objBrand);

        $.ajax({
            url: apipath + 'api/api-content.php',
            method: 'POST',
            data: {
                token : "1",
                id : "1",
                brand : strBrandData,
                date : mappedDate,
                time : mappedTime,
                do : 'create-content'
            },
            success: function (res) {
                if (res.code == "200"){
                    $(".success-search").html(res.message);
                    $(".success-search").fadeIn().delay(2000).fadeOut();
                    $("#modal-add").modal("hide");
                    loadContent();
                } else {
                    console.log(res);
                    $(".warning-add").fadeIn();
                    $(".warning-add").html(res.responseJSON == null ? res.responseText : res.responseJSON.message);
                }
            },
            error: function (er) {
                console.log(er);
                $(".warning-add").fadeIn();
                $(".warning-add").html(er.responseJSON == null ? er.responseText : er.responseJSON.message);
            }
        });
    }

</script>
