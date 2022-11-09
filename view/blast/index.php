<?php include '../../header.php' ?>
<?php include '../../navbar.php' ?>
<body class="container">
    <h3 class="mb-2">Begin Blast</h3>
    <h4 class="mb-3" id="brand-name"> Brand</h4>
    <div class="alert alert-danger danger-search display-none mb-3"></div>
    <div class="alert alert-danger danger-blast display-none mb-3"></div>
    <div class="alert alert-success display-none mb-3"></div>
    <div class="mb-3 text-end">
        <a href="../index.php" class="btn-menu"><i class="bi bi-chevron-left"></i> Kembali</a>
    </div>
    <div class="row mb-3">
        <div class="col-md-6 col-xs-12 mb-2">
            <div id="panel-list-content mb-2">
                <button class="btn btn-sm btn-light" id="pilih-content"><i class="bi bi-menu-button-wide me-1"></i> Pilih Content</button>
            </div>
        </div>
        <div class="col-md-6 col-xs-12 mb-2">
            <div id="panel-rules" class="text-end">

            </div>
        </div>
    </div>
   
    <div class="card-rules mb-3" id="panel-content">
        <div class="caption mb-2">Content Disiapkan</div>
        <div class="over-x">
            <table class="table table-dark table-striped table-hover table-borderles table-responsive">
                <thead>
                    <td>Meteri</td>
                    <td>Subject</td>
                    <td>Date</td>
                    <td>Time</td>
                </thead>
                <tbody id="table-body-content">
                    
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-rules mb-3" id="panel-rules-detail">
        <h4 id="title-test"></h4>
        <div class="over-x" style="max-height: 500px;">
            <table class="table table-dark table-stripped table-hover table-borderles table-responsive">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Email</td>
                        <td>Name</td>
                        <td>Status</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody id="table-body">

                </tbody>
            </table>
        </div>
    </div>
    <div class="text-end mb-3">
        <button class="btn btn-sm btn-success" id="btn-blast"><i class="bi bi-send-fill"></i> Blast</button>
    </div>
</body>

<div class="modal fade" id="modal-add-content" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-add" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modal-label">Pilih Content</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning warning-add-content display-none mb-2"></div>
                <div class="over-x">
                    <table class="table table-dark table-striped table-hover table-borderles table-responsive">
                        <thead>
                            <tr>
                                <td>#</td>
                                <td>Materi</td>
                                <td>Subject</td>
                                <td>Date</td>
                                <td>Time</td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody id="table-body-pilih-content">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-loading" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-loading" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modal-label"></h1>
                <button type="button" class="btn-close" id="btn-close-loading" onclick="closeModalLoading()" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="d-flex justify-content-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <br>
            <div class="text-center">
                Sedang melakukan prosess blasting...
                Tolong jangan tutup halaman ini
            </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>


<?php include '../../footer.php' ?>
<script>

    var brandId = '<?= $_GET['id'] ?>';
    $(document).ready(function(){
        load();
        checkReady();
    });

    var objbrand        = {};
    var listemail       = [];
    var contentselected = {};
    var relays          = [];
    var sentlength      = 0;
    var isBlast         = false;
    var isReady         = true;

    function load(){
        $.ajax({
            url:'<?= $baseurl ?>src/blast-rule-api.php',
            method:"POST",
            data:{
                do:"load",
                brand_id:brandId
            }, success:function(res){
                if (res.code == "200" && res.data.length > 0){
                    
                    const item = res.data[0];
                    objbrand = item;

                    $("#brand-name").html(item.name);    
                    loadRules(item.rules);

                    // console.log(objbrand);
                }
            }, error:function(er){
                console.log(er);
                $(".danger-search").fadeIn();
                $(".danger-search").html(er.responseJSON == null ? er.responseText : er.responseJSON.message);
            }
        })
    }

    function checkReady(){
        $.ajax({
            url:'<?= $baseurl ?>src/blast-api.php',
            method:"POST",
            data:{
                do:"check-ready",
                brand_id:brandId
            }, success:function(res){
                if (res.code == "200"){
                   isReady = res.data;
                   console.log(res.data);
                } else {
                   console.log(res);
                }
            }, error:function(er){
                console.log(er);
                $(".danger-search").fadeIn();
                $(".danger-search").html(er.responseJSON == null ? er.responseText : er.responseJSON.message);
            }
        })
    }

    function loadRules(list){
        let str = '<button class="btn btn-sm text-bg-info me-1 mb-2" onclick="resetRecipient()"><i class="bi bi-arrow-clockwise"></i> Reset</button>';
        $.each(list, function(i, item){
            if (item.type == "blast"){
                str += '<button class="btn btn-sm text-bg-danger me-1 mb-2" onclick="listRecipient(\''+item.id+'\',\''+item.name+'\',\''+item.type+'\')"><i class="bi bi-journal-check"></i> Set '+item.name+'</button>';
            } else {
                str += '<button class="btn btn-sm text-bg-light me-1 mb-2" onclick="listRecipient(\''+item.id+'\',\''+item.name+'\',\''+item.type+'\')"><i class="bi bi-journal-check"></i> Set '+item.name+'</button>';
            }
        });
        $("#panel-rules").html(str);
    }

    function listRecipient(id, name, type){

        isBlast = type == "blast";
        $("#title-test").html(name);
        listemail = [];
        
        const rules = objbrand.rules.filter(p=>p.id === id);
        relays = rules[0].details;
  
        // console.log("----------------------- relay : ");
        // console.log(relays);
        // console.log("--------------------------------");

        console.log(type);

        if (type == "test"){
            
            let str = '';
            $.each(rules[0].recipients, function (i, item){

                let num = 1 + i;
                listemail.push({id:item.id, email:item.email, name:item.name, flag:"Y"});

                str += '<tr>' +
                    '<td>' + num + '</td>' + 
                    '<td>' + item.email + '</td>' + 
                    '<td>' + item.name + '</td>' + 
                    '<td id="status-'+item.id+'"></td>' + 
                    '<td></td>' + 
                    '</tr>';

                // console.log(item);
            });
            $("#table-body").html(str);
        } else {
            
            // jika blast menampilkan customer email
            loadCustomerBlast();
        }
        // console.log(listemail);
    }

    function loadCustomerBlast(){
        checkReady();
        if (!isReady){
            $(".danger-search").fadeIn().delay(2000).fadeOut();
            $(".danger-search").html("Belum Memenuhi Waktu Tunggu Setelah Waktu Blast Terakhir");
            return false;
        }
        $.ajax({
            url:'<?= $baseurl ?>src/blast-api.php',
            method:"POST",
            data:{do:"load-by-brand", brand_id:objbrand.id},
            success:function(res){
                if (res.code == "200" && res.data.length > 0){
                    // console.log(res.data);
                    let str = '';
                    $.each(res.data, function(i, item){
                        
                        let num = 1 + i;
                        listemail.push({id:item.id, email:item.email, name:item.name, flag:"Y"});

                        str += '<tr>' +
                            '<td>' + num + '</td>' + 
                            '<td>' + item.email + '</td>' + 
                            '<td>' + replaceNull(item.name) + '</td>' + 
                            '<td id="status-'+item.id+'"></td>' + 
                            '<td></td>' + 
                            '</tr>';
                    });
                    $("#table-body").html(str);
                }
            }, error:function(er){
                console.log(er);
                $(".danger-search").fadeIn();
                $(".danger-search").html(er.responseJSON == null ? er.responseText : er.responseJSON.message);
            }
        })
    }

    $("#pilih-content").click(function(){
        $("#modal-add-content").modal('show');
        loadContent();
    });

    function loadContent(){
        $.ajax({
            url:'<?= $baseurl ?>src/content-api.php',
            method:"POST",
            data:{do:"load", brand_id : brandId},
            success:function(res){
                if (res.code == "200" && res.data.length > 0){

                    let str = '';
                    $.each(res.data, function(i, item){

                        const num = 1 + i;

                        str += '<tr>' +
                                '<td>' + num + '</td>'+
                                '<td>' + replaceNull(item.materi_name) + '</td>'+
                                '<td>' + replaceNull(item.subject) + '</td>'+
                                '<td>' + item.date_namespace + '</td>'+
                                '<td>' + item.time_namespace + '</td>'+
                                '<td><span class="btn-edit" onclick="pilihContent(\''+item.id+'\',\''+item.materi_name+'\', \''+item.subject+'\', \''+item.date_namespace+'\', \''+item.time_namespace+'\')"><i class="bi bi-check"></i> Pilih</span></td>'+
                                '</tr>'
                    });
                    $("#table-body-pilih-content").html(str);
                }
            }, error:function(er){
                console.log(er);
                $(".warning-add-content").fadeIn();
                $(".warning-add-content").html(er.responseJSON == null ? er.responseText : er.responseJSON.message);
            }
        })
    }

    function pilihContent(id, materi, subject, tgl, hour){
        contentselected = {id:id, materi:replaceNull(materi), subject:replaceNull(subject), tgl:tgl, hour:hour};
        str = '<tr>'+
            '<td>'+ replaceNull(materi) +'</td>'+
            '<td>'+ replaceNull(subject) +'</td>'+
            '<td>'+ tgl +'</td>'+
            '<td>'+ hour +'</td>'+
            '</tr>';
        $("#table-body-content").html(str);
        $("#modal-add-content").modal('hide');
    }

    function replaceNull(str){
        return str == null || str == 'null' ? "-" : str;
    }

    function resetRecipient(){
        listemail = [];
        $("#table-body").html("");
    }

    $("#btn-blast").click(function() {    
        // console.log(contentselected);
        if (confirm("Apakah Anda Akan Melakukan Blast ?"))
        {    
            if (relays.length == 0){
                $(".danger-search").html("Tidak ditemukan pengaturan relay pada rules ini. buka menu blast rules untuk konfigurasi");
                $(".danger-search").fadeIn().delay(2000).fadeOut();
                return false;
            }    

            if (listemail.length == 0){
                $(".danger-search").html("Tidak ditemukan daftar email penerima");
                $(".danger-search").fadeIn().delay(2000).fadeOut();
                return false;
            }
               

            // console.log(contentselected == "");
            // if (contentselected == {}){
            //     console.log("----------");
            //     console.log(contentselected);
            // }
            if (jQuery.isEmptyObject(contentselected)){
                $(".danger-search").html("Tidak Content yang Dipilih !");
                $(".danger-search").fadeIn().delay(2000).fadeOut();
                return false;
            }

            $("#modal-loading").modal("show");
            $('#modal-loading').on('shown.bs.modal', function () {
                beginSend();
            })
        }
    });

    function beginSend() {
        sentlength = 0;
        const content   = contentselected;
        const subject   = !isBlast ? '\'[TEST]\' ' + content.subject : content.subject;
        
        let inRelay = 0;
        const relayLength = relays.length;
        let actionRelayId = relays[0].relay_id;
        const nilaiMod = 5;

        $.each(listemail, function(i, item){

            // set email relay secara bergantian;
            if (inRelay < relayLength){
                actionRelayId = relays[inRelay].relay_id;
                setTimeout(function() { sendEmail(item.id, item.email, content.id, subject, actionRelayId); }, 5000);
                inRelay ++
            } else {
                inRelay = 0;
                setTimeout(function() { sendEmail(item.id, item.email, content.id, subject, actionRelayId); }, 5000);
            }            
        });
    }

    function updateCustomerOkStatus(idemail){
        $("#status-"+idemail).html("Success");
    }

    function updateCustomerFailStatus(idemail){
        $("#status-"+idemail).html("Fail");
    }

    function updateSentLength(emailid){
        sentlength++;
        if(sentlength == listemail.length){
            // jika semua sudah terkirim
            if (isBlast){
                updateLastEmailId(emailid);                
            }
            
            $("#modal-loading").modal("hide");
            $("#btn-blast").attr("disabled", "true");
            // end 
            
            // check status blast untuk yg berlimit
            checkReady();
            // end 
        }
    }

    function updateLastEmailId(lastEmailId){
        $.ajax({
            url:'<?= $baseurl ?>src/blast-api.php',
            method:"POST",
            data:{
                do:"update-last-email",
                brand_id:objbrand.id,
                last_email_id:lastEmailId,
            }, success:function(res){
                // console.log(res);
            }, error:function(er){
                console.log(er);
                $(".danger-search").fadeIn();
                $(".danger-search").html(er.responseJSON == null ? er.responseText : er.responseJSON.message);
            }
        })
    }

    function sendEmail(idEmailUser, recipient, contentId, subject, relayid){
        $.ajax({
            url:'<?= $baseurl ?>src/blast-api.php',
            method:"POST",
            data:{
                do:"send-test",
                recipient:recipient,
                content_id : contentId,
                subject:subject,
                brand_id : brandId,
                relay_id : relayid
            }, success:function(res){
                if (res.code == "200"){
                    updateCustomerOkStatus(idEmailUser);
                } else {
                    updateCustomerFailStatus(idEmailUser);
                }
                // console.log(res.message);
                updateSentLength(idEmailUser);
            }, error:function(er){
                console.log(er);
                updateCustomerFailStatus(idEmailUser);
            }
        })
    }

</script>
