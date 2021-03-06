<?php 
$schoolInfo = $this->db->get_where('settings',['type'=>'school_information'])->row()->description;
list($schoolName,$schoolAddress,$eiin,$email,$phone) = explode('+', $schoolInfo);

!empty($group_id)?$group_id=$group_id:$group_id='';
if($group_id !== ''):
    $group_name       = $this->db->get_where('group' , array('group_id' => $group_id))->row()->name;
endif;
	$class_name		= $this->db->get_where('class' , array('class_id' => $class_id))->row()->name;
    $section_name       = $this->db->get_where('section' , array('section_id' => $section_id))->row()->name;
	$shift_name  		= $this->db->get_where('shift' , array('shift_id' => $shift_id))->row()->name;
    $running_year       =	$this->db->get_where('settings' , array('type'=>'running_year'))->row()->description;
        if($month == 1) $m = 'January';
        else if($month == 2) $m='February';
        else if($month == 3) $m='March';
        else if($month == 4) $m='April';
        else if($month == 5) $m='May';
        else if($month == 6) $m='June';
        else if($month == 7) $m='July';
        else if($month == 8) $m='August';
        else if($month == 9) $m='Sepetember';
        else if($month == 10) $m='October';
        else if($month == 11) $m='November';
        else if($month == 12) $m='December';

?>
<div id="print">
	<script src="assets/js/jquery-1.11.0.min.js"></script>
	<style type="text/css">
		td {
			padding: 5px;
		}
	</style>

	<center>
		<img src="uploads/school_logo.png" style="max-height : 60px;"><br>
		<h3 style="font-weight: 100;"><?php echo $schoolName;?></h3>
		<?php echo get_phrase('attendance_sheet');?><br>
		<?php echo '<b>'.get_phrase('class') . ':</b> ' . $class_name;?><br>
        <?php echo '<b>'.get_phrase('section').':</b> '.$section_name;?><br>
        <?php echo '<b>'.get_phrase('shift').':</b> '.$shift_name;?><br>
		<?php echo !empty($group_name)?'<b>Group:</b> '.ucwords($group_name).'<br>':'';?>
                <?php echo '<b>'.get_phrase('month').':</b> '.$m;?>
		
	</center>
        
    <table border="1" style="width:100%; border-collapse:collapse;border: 1px solid #ccc; margin-top: 10px;">
        <thead>
            <tr>
                <td style="text-align: center;">
                    <?php echo get_phrase('students'); ?> <i class="entypo-down-thin"></i> 
                    | <?php echo get_phrase('date'); ?> 
                    <i class="entypo-right-thin"></i>
                </td>
                <?php
                $year = explode('-', $running_year);
                $days = cal_days_in_month(CAL_GREGORIAN, $month, $year[0]);
                for ($i = 1; $i <= $days; $i++) {
                    ?>
                    <td style="text-align: center;"><?php echo $i; ?></td>
                <?php } ?>

            </tr>
        </thead>
        <tbody>
                    <?php
                    $data = array();
                    $students = $this->db->get_where('enroll', array('class_id' => $class_id,
                    'shift_id' => $shift_id, 
                    'group_id' =>  $group_id,
                    'year' => $running_year, 
                    'section_id' => $section_id))->result_array();
                    foreach ($students as $row):
                        ?>
                <tr>
                    <td style="text-align: center;">
                    <?php echo $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->name; ?>
                    </td>
                    <?php
                    $status = 0;
                    for ($i = 1; $i <= $days; $i++) {
                        $timestamp = strtotime($i . '-' . $month . '-' . date('Y'));
                        $this->db->group_by('timestamp');
                        // $attendance = $this->db->get_where('attendance', array('section_id' => $section_id, 'group_id' => $group_id,'class_id' => $class_id,'shift_id' => $shift_id, 'year' => $running_year, 'timestamp' => $timestamp, 'student_id' => $row['student_id']))->result_array();
                        $attendance = $this->db->get_where('attendance', array('year' => $running_year, 'timestamp' => $timestamp, 'student_id' => $row['student_id']))->result_array();                               


                        foreach ($attendance as $row1):
                            $month_dummy = date('m', $row1['timestamp']);
                            if ($i == $month_dummy)
                                ;
                            $status = $row1['status'];
                        endforeach;
                        ?>
                        <td style="text-align: center;" data-class="">
                            <?php if ($status == 1) { ?>
                                <div style="color: green; font-weight: bold;">P</div>
                            <?php } else if ($status == 2) { ?>
                            <div style="color: red; font-weight: bold;">A</div>
                            <?php } else if ($status == 3) { ?>
                                <div style="color: orange; font-weight: bold;">E</div>
                            <?php }$status=0; ?>
                        </td>

                    <?php } ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>



<script type="text/javascript">

	jQuery(document).ready(function($)
	{
		var elem = $('#print');
		PrintElem(elem);
		Popup(data);

	});

    function PrintElem(elem)
    {
        Popup($(elem).html());
    }

    function Popup(data) 
    {
        var mywindow = window.open('', 'my div', 'height=400,width=600');
        mywindow.document.write('<html><head><title></title>');
        //mywindow.document.write('<link rel="stylesheet" href="assets/css/print.css" type="text/css" />');
        mywindow.document.write('</head><body >');
        //mywindow.document.write('<style>.print{border : 1px;}</style>');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10

        mywindow.print();
        mywindow.close();

        return true;
    }
</script>