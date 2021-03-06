<hr />
<?php
//pd($classes);
?>
<div class="row">
    <div class="col-md-12">

        <!------ CONTROL TABS START ------>
        <ul class="nav nav-tabs bordered">
            <li class="active">
                <a href="#sectionlist" data-toggle="tab"><i class="entypo-menu"></i> 
                    <?php echo get_phrase('section_list'); ?>
                </a></li>
            <li>
                <a href="#sectionadd" data-toggle="tab"><i class="entypo-plus-circled"></i>
                    <?php echo get_phrase('add_section'); ?>
                </a></li>
        </ul>
        <!------ CONTROL TABS END ------>

        <div class="tab-content">
            <br>
            <!----TABLE LISTING STARTS-->
            <div class="tab-pane box active" id="sectionlist">

            <div id="editSectionHolder"></div>
                <div id="sectionLists">

                <table class="table table-bordered datatable" id="table_export">
                    <thead>
                        <tr>
                            <th><div>#</div></th>
                            <th><?php echo get_phrase('class'); ?></th>
                            <th><?php echo get_phrase('section_name'); ?></th>
                            <th><?php echo get_phrase('nick_name'); ?></th>
                            <th><?php echo get_phrase('teacher'); ?></th>
                            <th><?php echo get_phrase('options'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                            $count = 1;
                            $sections = $this->db->get_where('section')->result_array();
                            foreach ($sections as $row):
                                ?>
                            <tr id="section<?php echo $row['section_id'];?>">
                                <td><?php echo $count++; ?></td>
                                <td><?php echo $this->db->get_where('class',['class_id'=>$row['class_id']])->row()->name; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['nick_name']; ?></td>
                                <td>
                                    <?php
                                    if ($row['teacher_id'] != '' || $row['teacher_id'] != 0)
                                        echo $this->db->get_where('teacher', array('teacher_id' => $row['teacher_id']))->row()->name;
                                    ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                            Action <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-default pull-right" role="menu">

                                            <!-- EDITING LINK -->
                                            <li>
                                                <a href="#" onclick="editSection('<?php echo $row['section_id'];?>')">
                                                    <i class="entypo-pencil"></i>
    <?php echo get_phrase('edit'); ?>
                                                </a>
                                            </li>
                                            <li class="divider"></li>

                                            <!-- DELETION LINK -->
                                            <li>
                                                <a href="#" onclick="confDelete('admin','ajax_delete_section','<?php echo $row['section_id'];?>','section<?php echo $row['section_id'];?>')">
                                                    <i class="entypo-trash"></i>
    <?php echo get_phrase('delete'); ?>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
<?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!----TABLE LISTING ENDS--->


            <!----CREATION FORM STARTS---->
            <div class="tab-pane box" id="sectionadd" style="padding: 5px">
                <div class="box-content">

                <form id="createSection" action="<?php echo base_url() .'index.php?admin/ajax_create_section'; ?>" class="form-horizontal form-groups-bordered" method="post">
	
					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('name');?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control" name="name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="" autofocus>
						</div>
					</div>
					
					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('nick_name');?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control" name="nick_name" value="" >
						</div> 
					</div>

					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('class');?></label>
                        
						<div class="col-sm-5">
							<select name="class_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                              <option value=""><?php echo get_phrase('select');?></option>
                              <?php 
									$classes = $this->db->get('class')->result_array();
									foreach($classes as $row):
										?>
                                		<option value="<?php echo $row['class_id'];?>">
												<?php echo $row['name'];?>
                                                </option>
                                    <?php
									endforeach;
								?>
                          </select>
						</div> 
					</div>

					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('teacher');?></label>
                        
						<div class="col-sm-5">
							<select name="teacher_id" class="form-control">
                              <option value=""><?php echo get_phrase('select');?></option>
                              <?php 
									$teachers = $this->db->get('teacher')->result_array();
									foreach($teachers as $row):
										?>
                                		<option value="<?php echo $row['teacher_id'];?>">
												<?php echo $row['name'];?>
                                                </option>
                                    <?php
									endforeach;
								?>
                          </select>
						</div> 
					</div>
                    
                    <div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class="btn btn-info"><?php echo get_phrase('add_section');?></button>
						</div>
					</div>
                <?php echo form_close();?>
                
                </div>                
            </div>
            <!----CREATION FORM ENDS-->
        </div>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function () {

        $('#createSection').ajaxForm({
            beforeSend: function () {
                $('#loading2').show();
                $('#overlayDiv').show();
            },
            success: function (data) {
                var jData = JSON.parse(data);

                if (!jData.type) {
                    toastr.error(jData.msg);
                } else {
                    toastr.success(jData.msg);
                    $("#sectionLists").html(jData.html);
                    $("#table_export").dataTable();
                    $('#createSection').resetForm();
                }
                $('body,html').animate({
                    scrollTop: 0
                }, 800);
                $('#loading2').fadeOut('slow');
                $('#overlayDiv').fadeOut('slow');
            }
        });
        
    });

    function editSection(sectionID) {
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url();?>index.php?admin/ajax_edit_section/' + sectionID,
            beforeSend: function () {
                $('#loading2').show();
                $('#overlayDiv').show();
            },
            success: function (data) {
                var jData = JSON.parse(data);

                toastr.success(jData.msg);
                $("#editSectionHolder").html(jData.html);
                $('body,html').animate({
                    scrollTop: 350
                }, 800);
                $('#loading2').fadeOut('slow');
                $('#overlayDiv').fadeOut('slow');
            }
        });
    }
</script>