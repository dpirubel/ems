<div id="innerEditClassHoder">
	<?php 
$edit_data = $this->db->get_where('class' , array('class_id' => $class_id) )->result_array();
foreach ( $edit_data as $row):
?>

	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
			<div class="panel-heading">
				<div class="panel-title">
					<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('edit_class');?>
				</div>
			</div>
			<div class="panel-body">

				<form id="updateClass" action="<?php echo base_url() .'index.php?admin/ajax_update_class/'.$row['class_id']; ?>" class="form-horizontal form-groups-bordered" method="post">

					<div class="padded">
						<div class="form-group">
							<label class="col-sm-3 control-label">
								<?php echo get_phrase('name'); ?>
							</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="name" value="<?php echo $row['name'];?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">
								<?php echo get_phrase('name_numeric'); ?>
							</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="name_numeric" value="<?php echo $row['name_numeric'];?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">
								<?php echo get_phrase('teacher'); ?>
							</label>
							<div class="col-sm-5">
								<select name="teacher_id" class="form-control" style="width:100%;">
									<option value="">
										<?php echo get_phrase('select_teacher'); ?>
									</option>
									<?php
				$teachers = $this->db->get('teacher')->result_array();
				foreach ($teachers as $row2):
					?>
										<option value="<?php echo $row2['teacher_id']; ?>" <?php if($row[ 'teacher_id'] == $row2[ 'teacher_id'])echo 'selected';?>>
											<?php echo $row2['name']; ?>
										</option>
										<?php
				endforeach;
				?>
								</select>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class="btn btn-info">
								<?php echo get_phrase('edit_class'); ?>
							</button>
						</div>
					</div>
				</form>

			</div>
		</div>
	</div>

	<?php
endforeach;
?>
</div>

<script>
	$(document).ready(function () {

		$('#updateClass').ajaxForm({
			beforeSend: function () {
				$('#loading').show();
				$('#overlayDiv').show();
			},
			success: function (data) {
				var jData = JSON.parse(data);

				if (!jData.type) {
					toastr.error(jData.msg);
				} else {
					toastr.success(jData.msg);
					$("#classList").html(jData.html);
					$("#table_export").dataTable();
					$("#innerEditClassHoder").html('');
				}
				$('body,html').animate({
					scrollTop: 0
				}, 800);
				$('#loading').fadeOut('slow');
				$('#overlayDiv').fadeOut('slow');
			}
		});




	});
</script>