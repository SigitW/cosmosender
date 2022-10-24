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
    <div class="alert alert-danger danger-search mb-3">
    </div>
    <div class="text-end mb-2">
        <button class="btn btn-sm btn-light" id="btn-add"><i class="bi bi-plus-lg"></i> Add</button>
    </div>
    <div class="over-x">
        <table class="table table-dark table-striped table-hover table-responsive-xs">
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
                        <select name="" id="add-hour" class="form-control mb-3">
                            <option value="1100">11:00</option>
                            <option value="1700">17:00</option>
                        </select>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-success" id="btn-save-config"><i class="bi bi-check-lg"></i> Save</button>
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
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <label for="" class="mb-1">Materi</label>
                        <input type="text" name="" id="edit-materi" class="form-control mb-3"/>
                        <label for="" class="mb-1">Subject</label>
                        <input type="text" name="" id="edit-subject" class="form-control mb-3"/>
                        <label for="" class="mb-1">Date</label>
                        <input type="date" name="" id="edit-date" class="form-control mb-3"/>
                        <label for="" class="mb-1">Blast Hour</label>
                        <select name="" id="edit-hour" class="form-control mb-3">
                            <option value="1100">11:00</option>
                            <option value="1700">17:00</option>
                        </select>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-success" id="btn-save-config"><i class="bi bi-check-lg"></i> Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-edit-content" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-add" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Edit Content</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning warning-edit-content display-none mb-3"></div>
                <div class="row">
                    <div class="col-md-4 col-xs-12" style="margin-bottom:20px;">
                        <div>
                            <div id="content-brand" style="font-size: 20px;font-weight:bold">
                            </div>
                            <div id="content-tanggal" style="font-size: 12px;" class="mb-3"> - </div>
                            <div id="content-materi"> - </div>
                        </div>
                        <hr style="color:black">
                        <form action="#" enctype="multipart/form-data">
                            <div style="margin-bottom:7px;">Upload File :</div>
                            <input type="file" class="form-control" name="file" id="files" style="margin-bottom:7px;" multiple>
                            <a href="#" class="btn btn-sm btn-primary" style="width:100%;" id="btn-upload"><i class="bi bi-arrow-bar-up"></i> Upload</a>
                            <hr>
                            <span style="color:gray;font-size: 12px;">* klik url dibawah asset untuk meng-copy url asset tersebut</span>
                            <div class="mt-1" style="width:100%;height:500px;background-color:lightgrey;border-radius:5px;overflow-y:scroll;padding:5px;" id="asset-panel">
                            </div>
                        </form>
                    </div>
                    <div class="col-md-8 col-xs-12">
                        <textarea name="" id="content-editor" style="width:100%;"></textarea>
                    </div>
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-success" id="btn-save-config"><i class="bi bi-check-lg"></i> Save</button>
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
                    theme:"dracula"
                });    
    editor.setSize(null, 800);

    var foundId = '<?= $isId ?>';
    $(document).ready(function(){
        $(".alert-danger").hide();
        if (!foundId){
            callError("Brand Id Tidak Ditemukan.");
        };
        loadContent();
    });

    function callError(data){
        $(".alert-danger").show();
        $(".alert-danger").html(data);
    }

    $("#btn-add").click(function(){
        $("#modal-add").modal("show");
    })

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
                            '<td>'+ replaceNull(item.materi_name)+'</td>'+
                            '<td>'+ replaceNull(item.subject)+'</td>'+
                            '<td>'+item.date_namespace+'</td>'+
                            '<td>'+item.time_namespace+'</td>'+
                            '<td>'+
                            '<div class="hstack gap-3 text-center">'+
                            '<div class="btn-edit ms-3" onclick="showEdit(\''+item.id+'\')"><i class="bi bi-pencil-square"></i> Edit</div><div class="vr"></div>'+
                            '<div class="btn-edit" onclick="showContentById(\''+item.id+'\', \''+item.materi_name+'\',\''+item.date_namespace+'\',\''+item.time_namespace+'\')"><i class="bi bi-grid"></i> Content</div><div class="vr"></div>'+
                            '<div class="btn-edit"><i class="bi bi-search"></i> View</div>'+
                            '</div>'+
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

    function showEdit(id){
        $("#modal-edit").modal('show');
    }

    function showContentById(id, materi, tanggal, jam){
        $("#modal-edit-content").modal('show');
        $("#asset-panel").html("")
        $("#id-content").val(id);
        $("#content-editor").html("");
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

    function replaceNull(str){
        return str == null ? "-" : str;
    }

    let pathPreview = "";


    function showCodeMirror(code){
        editor.getDoc().setValue(code);
    }

    function panelAsset(item){
        return '<div style="border-radius:5px 5px 0px 0px;height:100px;margin-top:10px;text-align:center;cursor:pointer;background-color:grey;">'+
        '<img src="http://'+item+'" style="height:100%;margin:auto 0px">'+
        '<br/>'+
        '<input type="text" class="form-control" style="cursor:pointer;color:grey;border-radius: 0px 0px 5px 5px;width:100%;" value="http://'+item+'" onclick="navigator.clipboard.writeText(this.value);" readonly/>' +
        '<div/>';
    }

</script>
