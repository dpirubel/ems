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
    $links = ['student_payment','income','income_category','daily_expense','expense_category','monthly_expense_sheet','monthly_balance_sheet','total_balance_sheet','manage_bank_ac','bank_transaction','tution_pendding','stationary'];
    $title = ['Create Student Payment','Student Payment','Payment Category', 'Daily Expense','Expense Category','Monthly Expense Sheet','Monthly balance Sheet','Total balance Sheet','Manage Account','Bank Transaction','Tution Pendding','Stationarys'];
else:
    $links = ['student_payment','income','income_category','daily_expense','expense_category','monthly_expense_sheet','monthly_balance_sheet','total_balance_sheet','manage_bank_ac','bank_transaction'];
    $title = ['Create Student Payment','Student Payment','Payment Category', 'Daily Expense','Expense Category','Monthly Expense Sheet','Monthly balance Sheet','Total balance Sheet','Manage Account','Bank Transaction'];
endif;
$color = ['bg-info','bg-primary','bg-sms','bg-today-app','bg-confirm-app','bg-padding-app','input-group-addon'];
 ?>
<div class="row customNavManu" id="accountingNavManu">

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


<div class="row" id="accountingMainManu">

<div class="menu-navigation-icons">
    <?php foreach($links as $k=>$each):?>
        <div class="col-sm-4 col-md-3" style="margin-bottom: 10px;">
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

$('#accountingNavManu').hide();
function ajaxDataTable(id, url){
    $('#'+id).dataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo base_url(); ?>index.php?"+url,
            "type": "POST"
        },
        //Set column definition initialisation properties.
        "columnDefs": [
        {
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
        "bDestroy": true

    });
}

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
        url: '<?php echo base_url(); ?>index.php?a/accounting/ajax_accounting_menu_pages',
        success: function (response)
        {   
            // var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?a/accounting/' + selectValue;
            // window.history.pushState({path:newurl},'',newurl);                
           
            $('#accountingNavManu').show();
            $('#accountingMainManu').hide();
            $('#ajaxPageContainer').html(response);            
            
            if(selectValue == 'student_payment') {
                $("#acc_date").datepicker({
                    format: 'dd-mm-yyyy',
                    startView: 1
                }).datepicker("setDate", new Date()).on('changeDate', function (e) {
                    $(this).datepicker('hide');
                });


                
            }
            
            ajaxDataTable('stationary_item', 'admin/ajaxStationaryItemList');
            ajaxDataTable('stationary_category', 'admin/ajaxStationaryCategoryList');
            $("#table_export").dataTable();
            
            $('.datepicker').datepicker({
            	format: 'dd-mm-yyyy',
            });
            $('#toggleButton').bootstrapToggle();
            $('#loading2').fadeOut('slow');
            $('#overlayDiv').fadeOut('slow');                
        }
    });
}



</script>