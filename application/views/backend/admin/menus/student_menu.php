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

<?php 
if($_SESSION['name']=='NihalIT'):
    $links = ['student_add','student_bulk_add','total_student_page','student_promotion','update_student_image'];
    $title = ['Admit New Student','Admit Bulk Student','Total Student','Student Promotion','Update Student Image'];
else:
    $links = ['student_add','total_student_page','student_promotion','update_student_image'];
    $title = ['Admit New Student','Total Student','Student Promotion','Update Student Image'];
endif;

$color = ['bg-info','bg-primary','bg-sms','bg-today-app','bg-confirm-app','bg-padding-app','input-group-addon'];
 ?>

<div class="row customNavManu" id="studentNavManu">

    <?php foreach($links as $k=>$each):?>
        <div class="col-sm-3 col-md-2" style="margin-bottom: 10px;">
            <a href="#" onclick="changePage('<?php echo $each?>')">
                <div class="panel-stat3 bg-info extra-menu" id="customNavBg<?php echo $each;?>">
                    <h4><?php echo $title[$k];?></h4>

                    <div class="stat-icon">
                        <i class="customIcon fa" id="customNavIcon<?php echo $each; ?>"></i>
                    </div>
                </div>
            </a>
        </div>
        <!-- /.col -->
    <?php endforeach;?>

    <div class="col-sm-3 col-md-2" style="margin-bottom: 10px;">
        <div class="panel-stat3 bg-info extra-menu" id="customNavClass">
            <h4>Student Information</h4>
            <select name="" id="navMenuSelect" onchange="changePage()" style="color: #000;">
            <option value="">Please Select</option>
            <?php
                $classes = $this->db->get('class')->result_array();
                foreach ($classes as $row):
                    $groupName = $this->db->get_where('group', array('class_id' => $row['class_id']))->result_array();
                    if(!empty($groupName)):
                ?>
                <optgroup label="<?php echo get_phrase('class').' '.$row['name']; ?>">
                    <?php foreach($groupName as $each): ?>
                        <option value="student_information/<?php echo $row['class_id'].'/'.$each['group_id'];?>"><?php echo get_phrase($each['name']); ?></option>
                    <?php endforeach; ?>
                </optgroup>
                <?php else: ?>
                    <option value="student_information/<?php echo $row['class_id']; ?>"><?php echo get_phrase('class').' '.$row['name']; ?></option>
                <?php endif; ?>
                <?php endforeach; ?>
            </select>

            <div class="stat-icon">
                <i class="customIcon fa" id="customNavIcon"></i>
            </div>
        </div>
    </div>

</div>

<div id="ajaxPageContainer"></div>

<br>
<br>

<div class="row" id="studentMainManu">

    <div class="menu-navigation-icons">
    <?php foreach($links as $k=>$each):?>
        <div class="col-sm-6 col-md-3" style="margin-bottom: 10px;">
            <a href="#" class="<?php echo manuColor($k);?>" onclick="changePage('<?php echo $each?>')">
                <i class="fa <?php echo fo_icon();?>"></i>
                <span><?php echo $title[$k];?></span>
            </a>
        </div>
        <!-- /.col -->
    <?php endforeach;?>
    

    <div class="col-sm-6 col-md-3" style="margin-bottom: 10px;">
        <div id="iconBG" class="<?php echo manuColor(1);?>">
            
            <select name="" onchange="changePage()" id="mainMenuSelect" style="color: #000;">
                <option value="">Please Select</option>
            <?php
                $classes = $this->db->get('class')->result_array();
                foreach ($classes as $row):
                    $groupName = $this->db->get_where('group', array('class_id' => $row['class_id']))->result_array();
                    if(!empty($groupName)):
                ?>
                <optgroup label="<?php echo get_phrase('class').' '.$row['name']; ?>">
                    <?php foreach($groupName as $each): ?>
                        <option value="student_information/<?php echo $row['class_id'].'/'.$each['group_id'];?>"><?php echo get_phrase($each['name']); ?></option>
                    <?php endforeach; ?>
                </optgroup>
                <?php else: ?>
                    <option value="student_information/<?php echo $row['class_id']; ?>"><?php echo get_phrase('class').' '.$row['name']; ?></option>
                <?php endif; ?>
                <?php endforeach; ?>
            </select>
            <span>Student Information</span>

        </div>
    </div>
</div>

                    </div>
<script>
$('#studentNavManu').hide();
// studentNavManu
function changePage(page)
{
    var selectValueOne = $('#mainMenuSelect').val();
    var selectValueTwo = $('#navMenuSelect').val();
    var selectValue;
    if(page) {
        selectValue = page;
        /* ACTIVE MANU SECTION */
        $('.extra-menu').addClass('bg-info').removeClass('bg-success');
        $('.customIcon').removeClass('fa-thumb-tack');
        $('#customNavBg'+selectValue).addClass('bg-success').removeClass('bg-info');
        $('#customNavIcon'+selectValue).addClass('fa-thumb-tack');
        /* END ACTIVE MANU SECTION */
    } else if(selectValueTwo) {
        selectValue = selectValueTwo;  
        $('.extra-menu').addClass('bg-info').removeClass('bg-success');
        $('.customIcon').removeClass('fa-thumb-tack');
        $('#customNavClass').addClass('bg-success').removeClass('bg-info');
        $('#customNavIcon').addClass('fa-thumb-tack');     
    } else if(selectValueOne) {
        selectValue = selectValueOne;
        $('.extra-menu').addClass('bg-info').removeClass('bg-success');
        $('.customIcon').removeClass('fa-thumb-tack');
        $('#customNavClass').addClass('bg-success').removeClass('bg-info');
        $('#customNavIcon').addClass('fa-thumb-tack');  
    }
    
    $.ajax({
        type: "POST",
        data: {
            pageName : selectValue                
        },
        beforeSend: function() {                
                $('#loading2').show();
                $('#overlayDiv').show();
        },  
        url: '<?php echo base_url(); ?>index.php?admin/ajax_page_load',
        success: function (response)
        {   
            // var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?admin/' + selectValue;
            // window.history.pushState({path:newurl},'',newurl);                
            
            $('#studentNavManu').show();
            $('#studentMainManu').hide();
            // Dynamic Content Recive
            $('#ajaxPageContainer').html(response);
            // Form Validation Initialize
            $.validate({
                modules: 'security, toggleDisabled'
            });
            // Bangla Initialize
            $(".bangla").bnKb({
                'switchkey': 'y',
                'driver': unijoy
            });
            // Datepicker Initialize
            $('.datepicker').datepicker({
            	format: 'dd-mm-yyyy',
            });
            $('#loading2').fadeOut('slow');
            $('#overlayDiv').fadeOut('slow');                
        }
    });
}

</script>