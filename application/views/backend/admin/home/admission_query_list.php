<?php 
$nihalit = $_SESSION['name'] == 'NihalIT'?'Found':'';

$arr = ['admission_exam_date','admission_exam_time',
        'admission_link_status','admission_sms_title',
        'admission_sms_description','admission_session','admission_exam_mark'];

$this->db->where_in('type',$arr);
$result1 = $this->db->get('settings')->result_array();
$session = $result1[5]['description'];


$getClass = $this->db->get_where('class',['class_id'=>$class_id])->result_array();

$panddingStd = $this->db->get_where('admit_std',
    ['status'=>0,'session'=>$session])
            ->result_array();

$confirmStd = $this->db->get_where('admit_std',
    ['status'=>1,'session'=>$session])
            ->result_array();


$paddingCount = array_count_values(array_column($panddingStd,'class'));
$confirmCount = array_count_values(array_column($confirmStd,'class'));
// pd($panddingStd);

?>
<hr />	
<div class="row">
    <div class="col-md-12">

        <!------CONTROL TABS START------>
        <ul class="nav nav-tabs bordered">
            <li class="active">
                <a href="#list" data-toggle="tab"><i class="entypo-menu"></i> 
                    <?php echo get_phrase('aplicant_list').' <b>('.count($panddingStd).')</b>'; ?>
                </a>
            </li>
            <li>
                <a href="#list2" data-toggle="tab"><i class="entypo-menu"></i> 
                    <?php echo get_phrase('confirm_aplicant_list').' <b>('.count($confirmStd).')</b>'; ?>
                </a>
            </li>
        </ul>
        <!------CONTROL TABS END------>

        <div class="tab-content">
            <br>
            <!----TABLE LISTING STARTS-->
            <div class="tab-pane box active" id="list">
			<ul class="nav nav-tabs bordered">
			<?php foreach($getClass as $k=>$list):
					
			?>
				<li class="<?php echo $k==0?'active':'';?>">
					<a href="#cls<?php echo $list['class_id']?>" data-toggle="tab"><i class="entypo-menu"></i> 
						<?php 
						if(!empty($paddingCount[$list['name_numeric']])){
							$finalPadding = ' <b>('.$paddingCount[$list['name_numeric']].')</b>';
						}else{
							$finalPadding = ' <b>(0)</b>';
						}
						echo $list['name'].$finalPadding; 
						
						?>
					</a>
				</li>
			<?php endforeach; 
					
			?>
			</ul>
			<div class="tab-content">
			<?php foreach($getClass as $k=>$list1):?>
			<div class="tab-pane box <?php echo $k==0?'active':'';?>" id="cls<?php echo $list1['class_id']?>">
                <table class="table table-bordered datatable admissionTable">
                    <thead>
                        <tr>
                            <th><div>#</div></th>
                            <th><div>ID/Roll</div></th>                            
                            <!-- <th width="70px"><div><?php echo get_phrase('image'); ?></div></th> -->
                            <th><div><?php echo get_phrase('name'); ?></div></th>
                            <th><div><?php echo get_phrase('father_name'); ?></div></th>
                            <th width="115px"><div><?php echo get_phrase('want_to_admit'); ?></div></th>
                            <th width="120px"><div><?php echo get_phrase('submission_date'); ?></div></th>
                            <th><div><?php echo get_phrase('mobile'); ?></div></th>
                            <th width="80px"><div><?php echo get_phrase('status'); ?></div></th>
                            <th width="80px"><div><?php echo get_phrase('actions'); ?></div></th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <?php foreach($panddingStd as $key=>$list):
								if($list1['name_numeric']==$list['class']):
                            ?>
                            <tr id="admitStd<?php echo $list['id'];?>">
                                <td><?php echo $key+1;?></td>
                                <td><?php echo substr($list['uniq_id'], -4);?></td>                                
                                <!-- <td>
                                    <img src="assets/<?php //echo $list['img'];?>" width="50px" height="50px"/>
                                </td> -->
                                <td><?php echo $list['name'];?></td>
                                <td><?php echo $list['fname'];?></td>
                                <td>
                                <?php if($list['class']==9){
                                	echo $list['class'].' ('.ucfirst($list['group']).')';
                                }elseif($list['class']==91){
                                	echo '9 Voc'.' ('.ucfirst($list['group']).')';                                
                                }else{
                                	echo $list['class'];
                                }
                                ?>
                                
                                </td>
                                <td><?php 
                                	$minutes_to_add = 720;

				$time = new DateTime($list['datetime']);
				$time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
				
				$stamp = $time->format('d-m-Y H:i');
				echo $stamp ;
                                ?></td>
                                <td><?php echo $list['mobile'];?></td>
                                <td>
                                    <?php if($list['status']==0):?>
                                    <a class="btn btn-warning btn-xs">Pandding</a>
                                    <?php else:?>
                                    <a class="btn btn-success btn-xs">Confirm</a>
                                    <?php endif;?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                            Action <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                            
                                            <?php if($list['status']==0):?>
                                            <!-- CONFIRM LINK -->
                                            <li>
                                                <a href="#" onclick="confirm_std('<?php echo base('Homemanage', 'confirm_std/'.$list['id'].'/'.$list['mobile']) ?>');">
                                                    <i class="entypo-user"></i>
    <?php echo get_phrase('confirm_student'); ?>
                                                </a>
                                            </li>
                                            <li class="divider"></li>
                                            <?php endif;?>
                                            
                                            <!-- EDIT LINK -->
                                            <li>
                                                <a href="#" onclick="showAjaxModal('<?php echo base_url(); ?>index.php?modal/popup/modal_admit_std_edit/<?php echo $list['id']; ?>');">
                                                    <i class="entypo-pencil"></i>
    <?php echo get_phrase('edit_view'); ?>
                                                </a>
                                            </li>
                                            <li class="divider"></li>

                                            <!-- DETAILS LINK -->
                                            <li>
                                                <a href="#" onclick="showAjaxModal('<?php echo base_url(); ?>index.php?modal/popup/modal_admit_std_view/<?php echo $list['id']; ?>');">
                                                    <i class="entypo-plus"></i>
    <?php echo get_phrase('details_view'); ?>
                                                </a>
                                            </li>
                                            <li class="divider"></li>
                                            
                                            <!-- DOWNLOAD FORM LINK -->
                                            <li>
                                                <a href="<?php echo base('Home','check_token/'.encryptor('encrypt', $list['id'])); ?>" target="_blank">
                                                    <i class="fa fa-download"></i>
    <?php echo get_phrase(' download_form'); ?>
                                                </a>
                                            </li>
                                            <li class="divider"></li>

                                            <!-- DELETION LINK -->
                                            <li>
                                                <a href="#" onclick="confDelete('homemanage','ajax_delete_admit_std','<?php echo $list['id'].'-'.$list['img'];?>','admitStd<?php echo $list['id'];?>')">
                                                    <i class="entypo-trash"></i>
    <?php echo get_phrase('delete'); ?>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                            endif;
                            endforeach;?>
                    </tbody>
                </table>
				
				</div>
				<?php endforeach; ?>
				</div>
            </div>
            <!----TABLE LISTING STARTS-->
            <div class="tab-pane box" id="list2">
				<ul class="nav nav-tabs bordered">
				<?php foreach($getClass as $k=>$list):
					
			?>
				<li class="<?php echo $k==0?'active':'';?>">
					<a href="#cls1<?php echo $list['class_id']?>" data-toggle="tab"><i class="entypo-menu"></i> 
						<?php 
						if(!empty($confirmCount[$list['name_numeric']])){
							$finalConfirm = ' <b>('.$confirmCount[$list['name_numeric']].')</b>';
						}else{
							$finalConfirm = ' <b>(0)</b>';
						}
						echo $list['name'].$finalConfirm; 
						
						?>
					</a>
				</li>
			<?php endforeach; 
					
			?>
				</ul>
				<div class="tab-content">
				<?php foreach($getClass as $k=>$list1):?>
				<div class="tab-pane box <?php echo $k==0?'active':'';?>" id="cls1<?php echo $list1['class_id']?>">
                <table class="table table-bordered datatable admissionTable">
                    <thead>
                        <tr>
                            <th><div>#</div></th>
							<th><div>ID/Roll</div></th>  
                            <!-- <th width="70px"><div><?php echo get_phrase('image'); ?></div></th> -->
                            <th><div><?php echo get_phrase('name'); ?></div></th>
                            <th><div><?php echo get_phrase('father_name'); ?></div></th>
                            <th width="115px"><div><?php echo get_phrase('want_to_admit'); ?></div></th>
                            <th width="120px"><div><?php echo get_phrase('submission_date'); ?></div></th>
                            <th><div><?php echo get_phrase('mobile'); ?></div></th>
                            <th width="80px"><div><?php echo get_phrase('status'); ?></div></th>
                            <th width="80px"><div><?php echo get_phrase('actions'); ?></div></th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <?php foreach($confirmStd as $key=>$list):
								if($list1['name_numeric']==$list['class']):
                            ?>
                            <tr id="admitStd<?php echo $list['id'];?>">
                                <td><?php echo $key+1;?></td>
                                <td><?php echo substr($list['uniq_id'], -4);?></td>  
                               <!--  <td>
                                    <img src="assets/<?php //echo $list['img'];?>" width="50px" height="50px"/>
                                </td> -->
                                <td><?php echo $list['name'];?></td>
                                <td><?php echo $list['fname'];?></td>
                                <td><?php echo $list['class'];?></td>
                                <td><?php 
                                	$minutes_to_add = 720;

				$time = new DateTime($list['datetime']);
				$time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
				
				$stamp = $time->format('d-m-Y H:i');
				echo $stamp ;
                                ?></td>
                                <td><?php echo $list['mobile'];?></td>
                                <td>
                                    <?php if($list['status']==0):?>
                                    <a class="btn btn-warning btn-xs">Pandding</a>
                                    <?php else:?>
                                    <a class="btn btn-success btn-xs">Confirm</a>
                                    <?php endif;?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                            Action <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                            
                                            
                                            <!-- EDIT LINK -->
                                            <li>
                                                <a href="#" onclick="showAjaxModal('<?php echo base_url(); ?>index.php?modal/popup/modal_admit_std_edit/<?php echo $list['id']; ?>');">
                                                    <i class="entypo-pencil"></i>
    <?php echo get_phrase('edit_view'); ?>
                                                </a>
                                            </li>
                                            <li class="divider"></li>
                                            <!-- DETAILS LINK -->
                                            <li>
                                                <a href="#" onclick="showAjaxModal('<?php echo base_url(); ?>index.php?modal/popup/modal_admit_std_view/<?php echo $list['id']; ?>');">
                                                    <i class="entypo-plus"></i>
    <?php echo get_phrase('details_view'); ?>
                                                </a>
                                            </li>
                                            <li class="divider"></li>
											<!-- DOWNLOAD FORM LINK -->
                                            <li>
                                                <a href="<?php echo base('Home','check_token/'.encryptor('encrypt', $list['id'])); ?>" target="_blank">
                                                    <i class="fa fa-download"></i>
    <?php echo get_phrase(' download_form'); ?>
                                                </a>
                                            </li>
											<li class="divider"></li>
                                            <!-- DELETION LINK -->
                                            <li>
                                                <a href="#" onclick="confDelete('homemanage','ajax_delete_admit_std','<?php echo $list['id'].'-'.$list['img'];?>','admitStd<?php echo $list['id'];?>')">
                                                    <i class="entypo-trash"></i>
    <?php echo get_phrase('delete'); ?>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                            endif;
                            endforeach; 
                            ?>
                    </tbody>
                </table>
				</div>
				<?php endforeach;?>
				</div>
            </div>
            
            

        </div>
        
            
    </div>
</div>



<!-----  DATA TABLE EXPORT CONFIGURATIONS ---->                      
<script type="text/javascript">

jQuery(document).ready(function ($)
{
    var datatable = $(".admissionTable").dataTable();

    $(".dataTables_wrapper select").select2({
        minimumResultsForSearch: -1
    });
});

</script>