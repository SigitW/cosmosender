<?php include '../../header.php' ?>
<?php include '../../navbar.php' ?>
<?php 
$id = "";
$isId = isset($_GET['id']);
if ($isId)
    $id = $_GET['id']; 
?>
<body class="container">
    <h3>Content Management</h3>
    <div class="alert alert-danger">
    </div>
    <div>
        <table class="table table-dark table-striped table-hover">
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
                            '<td align="center"><span class="btn-edit"><i class="bi bi-pencil-square"></i> Edit</span></td>'+
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

    function replaceNull(str){
        return str == null ? "-" : str;
    }
</script>
