<style>
.extra-menu h2, 
.extra-menu h4{
    color: #fff;
}
.extra-menu {
    padding: 2px 10px !important;
    min-height: 40px;
}
.extra-menu h4 {
    font-size: 13px !important;
}
.customNavManu {
    margin-bottom: 20px;
    border-bottom: 1px solid #EEEEEE;
}
.panel-stat3 .stat-icon {
    top: 0 !important;
}

.panel-stat3 {
    border-radius: 0px !important;
}
</style>
<br>
<br>
<?php 

if($_SESSION['name']=='NihalIT'):
    $links = ['exam','grade','marks_manage','exam_marks_sms','tabulation_sheet','question_paper'];
    $title = ['Exam List','Exam Grades','Manage Marks','Send Mark By SMS','Tabulation Sheet','Question Paper'];
else:
    $links = ['exam','grade','question_paper'];
    $title = ['Exam List','Exam Grades','Question Paper'];
endif;
$color = ['bg-info','bg-primary','bg-sms','bg-today-app','bg-confirm-app','bg-padding-app','input-group-addon'];
 ?>
<div class="row customNavManu" id="examNavManu">

<?php foreach($links as $k=>$each):?>
    <div class="col-sm-3 col-md-2" style="margin-bottom: 10px;">
        <a href="#" onclick="changePage('<?php echo $each?>')">
            <div class="panel-stat3 bg-info extra-menu" id="customNavBg<?php echo $each;?>">
                <!-- <h2 class="m-top-none" id="userCount"><?php echo $k+1;?></h2> -->
                <h4><?php echo $title[$k];?></h4>

                <div class="stat-icon">
                    <i class="customIcon fa" id="customNavIcon<?php echo $each; ?>"></i>
                </div>
            </div>
        </a>
    </div>
    <!-- /.col -->
<?php endforeach;?>
</div>


<div class="row" id="examMainManu">

<div class="menu-navigation-icons">
    <?php foreach($links as $k=>$each):?>
        <div class="col-sm-4 col-md-4" style="margin-bottom: 10px;">
            <a href="#" class="<?php echo manuColor($k);?>" onclick="changePage('<?php echo $each?>')">
                <i class="fa <?php echo fo_icon();?>"></i>
                <span><?php echo $title[$k];?></span>
            </a>
        </div>
        <!-- /.col -->
    <?php endforeach;?>
</div>


</div>



<div id="ajaxPageContainer"></div>


<script>

$('#examNavManu').hide();

function changePage(page)
{
    var selectValue = page;
    /* ACTIVE MANU SECTION */
    $('.extra-menu').addClass('bg-info').removeClass('bg-success');
    $('.customIcon').removeClass('fa-thumb-tack');
    $('#customNavBg'+selectValue).addClass('bg-success').removeClass('bg-info');
    $('#customNavIcon'+selectValue).addClass('fa-thumb-tack');
    /* END ACTIVE MANU SECTION */
    
    $.ajax({
        type: "POST",
        data: {
            pageName : selectValue                
        },
        beforeSend: function() {                
                $('#loading2').show();
                $('#overlayDiv').show();
        },  
        url: '<?php echo base_url(); ?>index.php?admin/ajax_exam_menu_pages',
        success: function (response)
        {   
            var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?admin/' + selectValue;
            window.history.pushState({path:newurl},'',newurl);                
            // var cName = $('#'+selectValue).hasClass('bg-info'); 
            // if(cName){
            //     $("#"+selectValue).removeClass("bg-info");
            //     $("#"+selectValue).addClass("bg-primary");
            // } else {
            //     $("#"+selectValue).addClass("bg-info");
            //     $("#"+selectValue).removeClass("bg-primary");
            // }
            // if(cName.contains("bg-info")){
            //     console.log("String Found");
            // }
            //$("#"+selectValue).removeClass("bg-info");
            //$("#"+selectValue).toggleClass("bg-primary");
            $('#examNavManu').show();
            $('#examMainManu').hide();            
            $('#ajaxPageContainer').html(response);
            $('#loading2').fadeOut('slow');
            $('#overlayDiv').fadeOut('slow');                
        }
    });
}

</script>