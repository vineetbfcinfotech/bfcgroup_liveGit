<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h3>Upload File</h3>  
                            </div>
                        </div>
                     
                        <!-- <form action="https://bfcgroup.in/admin/leads/save_answer" method="post" enctype="multipart/form-data" autocomplete="no"> -->
                    <form action="<?php echo base_url();?>admin/ProjectCordinatorDashboard/upload_printer_link" method="post" enctype="multipart/form-data" autocomplete="no">
                        <div class="row">
                            <div class="col-md-8">
                                   <div class="form-group" app-field-wrapper="file_csv">
                                        <input type="file" id="userfile" name="file" class="form-control" required>
                                        <!-- <input type="text" id="book_title" name="book_title" class="form-control" value="" required> -->
                                    </div>
                                </div>
                              
                                  <div class="col-md-4">
                                <div class="form-group" app-field-wrapper="file_csv">
                                     <label for="file_csv" class="control-label"></label>
                                     <input type="submit" name="submit" value="Upload" class="btn btn-info import btn-import-submit">
                                    <!--<button type="submit" name="submit" id="button_disable" class="btn btn-info import btn-import-submit" value="button_disable"> Save
                                    </button>-->
                                </div>
                            </div>
                        </div>
                        <div class="row">
                          
                        </div>
                        </form>
                        <div id="loading-image" style="display: none; text-align: center;">
					        <img src="<?php echo base_url('/assets/images/task_loader.gif'); ?>">
				        </div>
				        
                    </div>
                </div>
            </div>
        </div>
         <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h3>List Uploaded File</h3>  
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div style="overflow-x:auto;">
                                    <table class="table dt-table scroll-responsive tablebusie dt-no-serverside dataTable no-footer" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_info" style="width: 1577px; margin-left: 0px;" id="example33">
                                    <!--<table class="table dt-table scroll-responsive">-->
                                        <thead>
                                        <tr>
                                            <th>Sr. No.</th>
                                            <th>Link</th>
                                            <th>Delete</th>
                                        </tr>
                                        </thead>
                                        <!-- lagao bhaiya code -->
                                        <tbody>
                                        <?php $i=1; foreach($genrate_printer_link as $getdata){ ?>
                                        <tr>
                                            <td><?= $i ?></td>
                                            <td><?= base_url(); ?>assets/printer_genrate_link/<?= $getdata->file?></td>
                                            <td><a href="<?= base_url();?>admin/ProjectCordinatorDashboard/delete_link/<?=$getdata->id?>" class="btn btn-info">Delete link</a></td>
                                             </tr>
                                        <?php $i++;}?>
                                        </tbody>
                                </table>
                                </div> 
                            </div>
                        </div>
                        <div id="loading-image" style="display: none; text-align: center;">
					        <img src="<?php echo base_url('/assets/images/task_loader.gif'); ?>">
				        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

</script>   
<?php init_tail(); ?>
</body>
</html>