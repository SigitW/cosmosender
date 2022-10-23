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
                <h1 class="modal-title fs-5" id="modal-label">Add Content</h1>
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
                <h1 class="modal-title fs-5" id="modal-label">Edit Content</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning warning-add-content display-none mb-3"></div>
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

<?php include '../../footer.php' ?>
<script>
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
                            '<div class="btn-edit"><i class="bi bi-grid"></i> Content</div><div class="vr"></div>'+
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

    function replaceNull(str){
        return str == null ? "-" : str;
    }
</script>
