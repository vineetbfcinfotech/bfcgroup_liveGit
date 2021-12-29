
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
          
        <div class="panel_s">
          <div class="panel-body">
              <script>
var data = [
   ['Leave 1','single_day','2019-04-18',''],
   ['Leave 2','multiple_days','2019-04-18','2019-04-28'],
   ['Leave 3','single_day','2019-04-18',''],
   ['Leave 4','multiple_days','2019-03-01','2019-03-01']
];
 
 
function download_csv() {
    var csv = 'Name,Leave Type,	Start Date,End Date\n';
    data.forEach(function(row) {
            csv += row.join(',');
            csv += "\n";
    });
 
    console.log(csv);
    var hiddenElement = document.createElement('a');
    hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
    hiddenElement.target = '_blank';
    hiddenElement.download = 'Holiday_Sample.csv';
    hiddenElement.click();
}
</script>
              
  <button class="btn btn-alert"  onclick="window.location='allleaveManagement';"> Back</button>  <button  class="btn btn-success"  onclick="download_csv()" >Download Sample</button>
        
<form action="<?php echo site_url();?>admin/leave/upload_holidays" method="post" enctype="multipart/form-data" name="form1" id="form1"> 
        
        <hr>
        <ul>
              <li>1. Your CSV data should be in the format below. The first line of your CSV file should be the column headers as in the table example. Also make sure that your file is <b>UTF-8</b> to avoid unnecessary <b>encoding problems</b>.</li>
            <!--  <li class="text-danger">2. Duplicate email rows wont be imported</li>-->
            </ul>
            <div class="table-responsive no-dt">
              <table class="table table-hover table-bordered">
                <thead>
                  <tr>
                                                                  
                                                                  <th class="bold">Name</th>
                                                                  <th class="bold">Leave Type</th>
                                                                  <th class="bold">Start Date</th>
                                                                  <th class="bold">End Date</th>
                                                                </tr>
                  </thead>
                  <tbody>
                    <tr><td>Leave 1</td><td>single_day</td><td>2019-04-18</td><td> </td></tr>   
                    <tr><td>Leave 2</td><td>multiple_days</td><td>2019-04-18</td><td> 2019-04-22</td></tr>   
                    </tbody>
                </table>
              </div>
                    <div class="row">
                  <div class="col-md-4">
                 <?php echo render_input('userfile','choose_csv_file','','file'); ?>
                   
                      
               
                
               
                
            <button  type="submit" name="submit" class="btn btn-info import btn-import-submit">Import</button>    
            </div>
</form>
        </div>
        </div>
        
      </div>
    </div>
    <?php init_tail(); ?>
   
 </body>
 </html>
