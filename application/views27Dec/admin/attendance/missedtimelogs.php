<?php init_head(); ?>
<div id="wrapper">
<div class="content">
<div class="row">
<div class="col-md-12">
<div class="panel_s">
<div class="panel-body">
    <?php
	$date = Date("Y-m-d");//2020-02-11
	  $timestamp = strtotime($date);
	  $day = date("d", $timestamp);
	  $month = date("m", $timestamp);
	  if(preg_match("~^0\d+$~", $month))
	  {
		  $month = str_replace("0", "", $month);
	  }
	  $year = date("Y", $timestamp );
	  
	  
	  //$table = "deviceLogs_".$month."_".$year;
	  $table = "deviceLogs_"."2"."_2020";//.$year;
	  //echo $year;exit;
		/* $totalday = $this->db->query("SELECT `LogDate`, `DeviceLogId`, `UserId`, count(UserId) as cnt FROM $table WHERE month(LogDate) = $month GROUP BY `UserId`, date(LogDate) HAVING cnt = 1");
		
		 
		 $totallogsmonth = $totalday->result(); */
		 
		 $this->db->select('LogDate, DeviceLogId, UserId, COUNT(UserId) as cnt');
		 $this->db->from($table);
		 $this->db->group_by('UserId'); 
		 $this->db->order_by('cnt', 'desc'); 
		 $totallogsmonth = $this->db->get();
		 
		 //$totallogsmonth = $this->db->result();
		 echo $totallogsmonth."--".$this->db->last_query();
		 
		 /* echo $this->db->last_query();
		  print_r($totallogsmonth);exit; */
		  ?>

    
      <hr class="hr-panel-heading" />
                      <?php if(!empty($totallogsmonth) > 0){ ?>
                        <table class="table dt-table scroll-responsive">
    <thead>
		<th><?php echo _l('id'); ?></th>
		<th>Staff Name</th>
		<th>Clock-in</th>
		<th>Clock-out</th>
		<th>Action</th>
    </thead>
    <tbody>
    <?php foreach ($totallogsmonth as $timelog) { ?>
        <tr id='lead_id_<?= $timelog->DeviceLogId; ?>'>
            <td><?= @++$i; ?></td>
            <td>
                <?php
                $this->db->select('CONCAT(firstname, " ", lastname) AS name, staffid, bio_id');
                $this->db->where('bio_id', $timelog->UserId);
                $staff = $this->db->get('tblstaff')->row();
                ?>
                
                <?= $staff->name; ?>
            </td>
             <td>
                 <?php 
                 $time = date("H:i:s",strtotime($timelog->LogDate));
                 
                 if($time < '12:00:00') { ?>
                 <?= $timelog->LogDate; ?>
                 <input  id="in_<?= $timelog->DeviceLogId; ?>" type="hidden" value="<?= $timelog->LogDate; ?>" />
              <? }    ?>
            </td>
             <td >
                 
                 <?php 
                 $time = date("H:i:s",strtotime($timelog->LogDate));
                 if($time > '12:00:00') { ?>
                 <?= $timelog->LogDate; ?>
                  <input  id="out_<?= $timelog->DeviceLogId; ?>" type="hidden" value="<?= $timelog->LogDate; ?>" />
              <? }    ?>
            </td>
            <td  >
               <div class="_buttons" >
                   <a  data-rowid="<?= $timelog->DeviceLogId; ?>" data-bio_id="<?= $staff->bio_id; ?>"  class="btn mright5 btn-success display-block approvebtn">
                               Approve                          </a>
               </div>
                
            </td>
            
        </tr>
    <?php } ?>

    </tbody>
</table>
<?php }else{
	echo "<h5> No Missed TimeLogs in this month. </h5>";
} ?>
</div>
</div>
</div>
</div>
</div>
</div>
<?php init_tail(); ?>
<script>
$(document).ready(function(){
    $(".approvebtn").on('click', function postinput(){
        var rowid = $(this).data('rowid');
        var invalue = $('#in_' +rowid).val();
        var outvalue = $('#out_' +rowid).val();
        var bioid = $(this).data('bio_id');
        
        if(invalue != null)
        {
          date = invalue.split(' ')[0];
          logouttime = '18:30:59';
          logdate = date + ' ' + logouttime; 
          
        }
        else
        {
             date = outvalue.split(' ')[0];
          logintime = '09:30:00';
          logdate = date + ' ' + logintime; 
        }
        $.ajax({ 
            url: 'updatemissedtime',
            data: { date: date, logdate: logdate, bioid: bioid },
            type: 'post'
        }).done(function(responseData) {
             $('#lead_id_'+rowid).remove();
                                alert_float('success', 'TimeLog Updated Successfully');
        }).fail(function() {
                                alert_float('success', 'Timelog Updatation failed');
        });
    });
}); 
</script>
</body>
</html>