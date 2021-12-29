<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h3><center>Custom Login / Logout Request</center></h3>
                        <hr/>
                        <table id="timetable" class="table dt-table scroll-responsive">
                                <thead>
                                <tr>
                                    <th><?php echo _l('id'); ?></th>
                                    <th>WP Name</th>
                                    <th>Meeting Slot</th>
                                    <th>Meeting With</th>
                                    <th>Purpose</th>
                                    <th>Location</th>
                                    <th>Scheduled Time</th>
                                    <th>Status</th>
                                    <th>Applied On</th>
                                     <?php if(is_admin()) { ?>
                                    <th>Force Change</th>
                                    <?php } ?>
                                </tr>
                                </thead>
                                <tbody class="ajax-data">
                                    
                                    <?php $i=0; foreach($timehistory as $time ) { ?>
                                    <tr>
                                        <td><?= @++$i; ?></td>
                                        <td><?= $time->wpname ?></td>
                                        <td><?= $time->period ?></td>
                                        <td><?= $time->meeting_with ?></td>
                                        <td><?= $time->purpose ?></td>
                                        <td><?= $time->location ?></td>
                                        <td><?= $time->scheduled_time ?></td>
                                        <?php if(is_admin() || is_sub_admin()) { ?>
                                        <td id="status_<?= $i; ?>">
                                        <?php if($time->status == 0) { ?>
                                        <select name="status" data-rowid="<?= $i; ?>" data-reqid="<?= $time->id; ?>"  id="reqstatus_<?= $i; ?>"   class="selectpicker reqstatus" >
                                            <option></option>
                                            <option value="1">Approve</option>
                                            <option value="2">Reject</option>
                                        </select>
                                        <?php } else { ?>
                                        <?php  if($time->status == 1) { ?>
                                        <button style="cursor: none;" class="btn btn-success appstatus btn-icon _delete">Approved</button>
                                        <?php } elseif($time->status == 2) { ?>
                                        
                                        <button style="cursor: none;" class="btn btn-danger appstatus btn-icon _delete">Rejected</button>
                                        <?php } ?>
                                        <?php } ?>
                                        </td>
                                        <?php } else { ?>
                                        <td>
                                        <?php if($time->status == 0) { ?> 
                                        <button style="cursor: none;" class="btn btn-warning appstatus btn-icon _delete">Pending</button>
                                        <?php } elseif($time->status == 1) { ?>
                                        <button style="cursor: none;" class="btn btn-success appstatus btn-icon _delete">Approved</button>
                                        <?php } elseif($time->status == 2) { ?>
                                        
                                        <button style="cursor: none;" class="btn btn-danger appstatus btn-icon _delete">Rejected</button>
                                        <?php } ?>
                                        
                                        
                                        </td>
                                        <?php } ?>
                                        <td><?= $time->applied_on ?></td>
                                        <?php if(is_admin()) { ?>
                                        <td id="status_<?= $i; ?>">
                                        <select name="status" data-rowid="<?= $i; ?>" data-reqid="<?= $time->id; ?>"  id="reqstatus_<?= $i; ?>"   class="selectpicker reqstatus" >
                                            <option></option>
                                            <option value="1">Approve</option>
                                            <option value="2">Reject</option>
                                        </select>
                                        <?php  } ?>
                                        </td>
                                        
                                    </tr>
                                    
                                    <?php $i++; } ?>
                                    
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(document).on('change', '.reqstatus', function () {
    let rowid = $(this).data('rowid');
    let id = $(this).data('reqid');
    let status = $("#reqstatus_" + rowid).children("option:selected").val();
    if(rowid != null)
    {
       {
      $.ajax({
        url:"updatetimechange",
        data:{rowid:rowid,id:id,status:status},
        type:'POST',
        success:function(response) {
            $("#status_" + rowid).html(response);
            if(status == 1)
            {
                 alert_float('success', 'Approved Successfully');
            }
            else
            {
                 alert_float('success', 'Rejected Successfully');
            }
           
        }
      });
    } 
    }
    
    
  });
</script>
</body>
</html>
