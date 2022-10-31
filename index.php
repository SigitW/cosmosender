<?php include 'header.php' ?>
<?php include 'navbar.php' ?>
<body class="container">
    <div id="panel-card-brand"></div> 
</body>
<?php include 'footer.php' ?>
<script>
    $(document).ready(function(){
        loadBrand();
    })
    function loadBrand(){
        $.ajax({
            url : '<?= $baseurl ?>src/blast-rule-api.php',
            method : "POST",
            data : {do:"load"},
            success:function(res){                
                if (res.data.length > 0){
                    let str = '';
                    $.each(res.data, function(i, item){

                        const rules     = formatRules(item.rules);
                        const services  = formatServices(item.services);
                        const servers   = formatServer(item.servers);

                        str += '<div class="card-brand mb-3">'+
                            '<div onclick="showAction(\''+item.id+'\')">' +
                            '<div class="card-brand-title" style="background-color: teal;"><h1>'+item.name+'</h1></div>'+
                            '<div class="card-brand-property" style="background-color: teal;filter: brightness(85%);">'+
                            '<div class="row">'+
                            '<div class="col-md-6 mb-3" style="text-align: left;">'+ rules +
                            '</div>'+
                            '<div class="col-md-3 mb-3" style="text-align: right;">'+
                            '<div class="mb-3">Content Service</div>'+
                            services +
                            '</div>'+
                            '<div class="col-md-3 mb-3" style="text-align: right;">'+
                            '<div class="mb-3">SMTP Server</div>'+ 
                            servers +
                            '</div>'+
                            '</div>'+
                            '</div>'+
                            '</div>'+ appendAction(item.id) +
                            '</div>'; 
                    });
                    $("#panel-card-brand").html(str);
                }

            }, error:function(e){
                console.log(e);
            }
        })
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

    function formatServices(list){
        if (list.length == 0)
            return "";
        
        let str = '';
        $.each(list, function(i,item){
            const bgcolor = item.color == "" ? "white" : item.color;
            str += '<span class="badge" style="background-color:'+bgcolor+';color:black;">'+item.server_name+' / '+item.service+' </span>';
        });
        return str;    
    }

    function formatServer(list){
        if (list.length == 0)
            return "";
        
        let str = '';
        $.each(list, function(i,item){
            const bgcolor = item.color == "" ? "white" : item.color;
            str += '<span class="badge me-1" style="background-color:'+bgcolor+';color:black;">'+item.server_name +' / '+ item.host +'</span>';

        });
        return str;    
    }

    function appendAction(id){
        return '<div class="card-brand-title display-none" id="panel-action-'+id+'" style="background-color: teal;font-size:20px;text-align:center;">'+
                    '<div style="width:100%;text-align:right;"><div class="btn-menu" style="font-size:14px" onclick="hideAction(\''+id+'\')"><i class="bi bi-x-lg"></i> Close</div></div>'+
                    '<span class="me-3 btn-menu" onclick="toMenu(\'view/content/?id='+id+'\')"><i class="bi bi-menu-button-wide me-2"></i> Content Management</span> <div class="vr"></div>'+
                    '<span class="ms-3 btn-menu" onclick="toMenu(\'view/blast/?id='+id+'\')"><i class="bi bi-send-fill me-2"></i> Begin Blast</span>'+
                '</div>';
    }

    function showAction(id){
        $("#panel-action-"+id).slideDown();
    }

    function hideAction(id){
        $("#panel-action-"+id).slideUp();
    }

    function toMenu(url){
        window.location.href = '<?= $baseurl ?>' + url;
    }


</script>