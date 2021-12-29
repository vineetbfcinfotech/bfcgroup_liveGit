
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
          
        <div class="panel_s">
          <div class="panel-body">
              <script>
                var data = [
                   ['HDFC - Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16'],
                   ['HDFC - Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16'],
                   ['HDFC - Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16'],
                   ['HDFC - Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16']
                ];
 
 
                function download_csv() {
                    var csv = 'Scheme Name,Scheme Category,Credit Rate Lum,Credit Rate SIP,GST,TDS,Effective From\n';
                    data.forEach(function(row) {
                            csv += row.join(',');
                            csv += "\n";
                    });
                 
                    console.log(csv);
                    var hiddenElement = document.createElement('a');
                    hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
                    hiddenElement.target = '_blank';
                    hiddenElement.download = 'MutualFund_Scheme.csv';
                    hiddenElement.click();
                }
                </script>
              <script>
                var data = [
                   ['Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16'],
                   ['Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16'],
                   ['Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16'],
                   ['Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16']
                ];
 
 
                function download_cfdcsv() {
                    var csv = 'CFD,Tenure,Credit,GST,TDS,Effective From\n';
                    data.forEach(function(row) {
                            csv += row.join(',');
                            csv += "\n";
                    });
                 
                    console.log(csv);
                    var hiddenElement = document.createElement('a');
                    hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
                    hiddenElement.target = '_blank';
                    hiddenElement.download = 'CFD_Scheme.csv';
                    hiddenElement.click();
                }
                </script>
              <script>
                var data = [
                   ['Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16'],
                   ['Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16'],
                   ['Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16'],
                   ['Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16']
                ];
 
 
                function download_hicsv() {
                    var csv = 'POLICY NAME,CREDIT RATE_FRESH,CREDIT RATE_RENEWAL,GST,TDS,Effective From\n';
                    data.forEach(function(row) {
                            csv += row.join(',');
                            csv += "\n";
                    });
                 
                    console.log(csv);
                    var hiddenElement = document.createElement('a');
                    hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
                    hiddenElement.target = '_blank';
                    hiddenElement.download = 'HealthInsurance_Scheme.csv';
                    hiddenElement.click();
                }
                </script> 
              <script>
                var data = [
                   ['Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16'],
                   ['Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16'],
                   ['Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16'],
                   ['Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16']
                ];
 
 
                function download_licsv() {
                    var csv = 'POLICY NAME,CREDIT RATE_FRESH,CREDIT RATE_RENEWAL,GST,TDS,Effective From\n';
                    data.forEach(function(row) {
                            csv += row.join(',');
                            csv += "\n";
                    });
                 
                    console.log(csv);
                    var hiddenElement = document.createElement('a');
                    hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
                    hiddenElement.target = '_blank';
                    hiddenElement.download = 'LifeInsurance_Scheme.csv';
                    hiddenElement.click();
                }
                </script> 
              <script>
                var data = [
                   ['Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16'],
                   ['Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16'],
                   ['Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16'],
                   ['Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16']
                ];
 
 
                function download_gicsv() {
                    var csv = 'POLICY NAME,CREDIT RATE_FRESH,CREDIT RATE_RENEWAL,GST,TDS,Effective From\n';
                    data.forEach(function(row) {
                            csv += row.join(',');
                            csv += "\n";
                    });
                 
                    console.log(csv);
                    var hiddenElement = document.createElement('a');
                    hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
                    hiddenElement.target = '_blank';
                    hiddenElement.download = 'GeneralInsurance_Scheme.csv';
                    hiddenElement.click();
                }
                </script>
              <script>
                var data = [
                   ['Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16'],
                   ['Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16'],
                   ['Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16'],
                   ['Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16']
                ];
 
 
                function download_bondcsv() {
                    var csv = 'Product Name,Credit Rate,GST,TDS,Effective From\n';
                    data.forEach(function(row) {
                            csv += row.join(',');
                            csv += "\n";
                    });
                 
                    console.log(csv);
                    var hiddenElement = document.createElement('a');
                    hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
                    hiddenElement.target = '_blank';
                    hiddenElement.download = 'bonds_Scheme.csv';
                    hiddenElement.click();
                }
                </script>
              <script>
                var data = [
                   ['Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16'],
                   ['Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16'],
                   ['Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16'],
                   ['Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16']
                ];
 
 
                function download_membershipcsv() {
                    var csv = 'POLICY NAME,CREDIT RATE_FRESH,CREDIT RATE_RENEWAL,GST,TDS,Effective From\n';
                    data.forEach(function(row) {
                            csv += row.join(',');
                            csv += "\n";
                    });
                 
                    console.log(csv);
                    var hiddenElement = document.createElement('a');
                    hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
                    hiddenElement.target = '_blank';
                    hiddenElement.download = 'Membership_Scheme.csv';
                    hiddenElement.click();
                }
                </script>
              <script>
                var data = [
                   ['Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16'],
                   ['Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16'],
                   ['Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16'],
                   ['Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16']
                ];
 
 
                function download_pmscsv() {
                    var csv = 'Name,Credit Rate,GST,TDS,Effective From\n';
                    data.forEach(function(row) {
                            csv += row.join(',');
                            csv += "\n";
                    });
                 
                    console.log(csv);
                    var hiddenElement = document.createElement('a');
                    hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
                    hiddenElement.target = '_blank';
                    hiddenElement.download = 'pms_Scheme.csv';
                    hiddenElement.click();
                }
                </script>
              <script>
                var data = [
                   ['Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16'],
                   ['Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16'],
                   ['Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16'],
                   ['Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16']
                ];
 
 
                function download_otherscsv() {
                    var csv = 'Scheme,Credit Rate,GST,TDS,Effective From\n';
                    data.forEach(function(row) {
                            csv += row.join(',');
                            csv += "\n";
                    });
                 
                    console.log(csv);
                    var hiddenElement = document.createElement('a');
                    hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
                    hiddenElement.target = '_blank';
                    hiddenElement.download = 'Others_Scheme.csv';
                    hiddenElement.click();
                }
                </script>
              <script>
                var data = [
                   ['Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16'],
                   ['Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16'],
                   ['Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16'],
                   ['Sample Data','Sample Data','Sample Data','Sample Data','Sample Data','2019-05-16']
                ];
 
 
                function download_tbfcsv() {
                    var csv = 'POLICY NAME,CREDIT RATE_FRESH,CREDIT RATE_RENEWAL,GST,TDS,Effective From\n';
                    data.forEach(function(row) {
                            csv += row.join(',');
                            csv += "\n";
                    });
                 
                    console.log(csv);
                    var hiddenElement = document.createElement('a');
                    hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
                    hiddenElement.target = '_blank';
                    hiddenElement.download = 'TBF_Scheme.csv';
                    hiddenElement.click();
                }
                </script>  
     <div class="dropdown">   
     <button class="btn btn-alert"  onclick="window.location='<?php echo base_url(); ?>admin/products';"> Back</button>   <!--<button  class="btn btn-success"  onclick="download_csv()" >Download Sample</button>-->
     
    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">All Category Sample
    <span class="caret"></span></button>
    <ul class="dropdown-menu">
      <li><a onclick="download_csv()" href="#">Mutual Fund</a></li>
      <li><a onclick="download_cfdcsv()" href="#">Corporate Fixed Deposit</a></li>
      <li><a onclick="download_hicsv()" href="#">Health Insurance</a></li>
      <li><a onclick="download_licsv()" href="#">Life Insurance</a></li>
      <li><a onclick="download_gicsv()" href="#">General Insurance</a></li>
      <li><a onclick="download_bondcsv()" href="#">Securities & Bonds </a></li>
      <li><a onclick="download_membershipcsv()" href="#">Membership </a></li>
      <li><a onclick="download_pmscsv()" href="#">Portfolio Management Services  </a></li>
      <li><a onclick="download_otherscsv()" href="#">Others  </a></li>
      <li><a onclick="download_tbfcsv()" href="#">Task Based Fee </a></li>
    </ul>
  </div>
      
<form action="<?php echo site_url();?>admin/products/uploadschemes" method="post" enctype="multipart/form-data" name="form1" id="form1"> 
        
        <hr>
        <ul>
              <li>1. Your CSV data should be in the format below. The first line of your CSV file should be the column headers as in the table example. Also make sure that your file is <b>UTF-8</b> to avoid unnecessary <b>encoding problems</b>.</li>
              <li class="text-danger">2. Duplicate email rows wont be imported</li>
            </ul>
            <!--<div class="table-responsive no-dt">-->
            <!--    <h5>Mutual Fund</h5>-->
            <!--  <table class="table table-hover table-bordered">-->
            <!--    <thead>-->
            <!--      <tr>-->
                                                                  
            <!--                                                      <th class="bold"> Scheme Name</th>-->
            <!--                                                      <th class="bold"> Scheme Category</th>-->
            <!--                                                      <th class="bold"> Credit Rate Lum</th>-->
            <!--                                                      <th class="bold"> Credit Rate SIP</th>-->
            <!--                                                      <th class="bold"> GST</th>-->
            <!--                                                      <th class="bold"> TDS</th>-->
            <!--                                                      <th class="bold"> Effective From</th>-->
            <!--                                                    </tr>-->
            <!--      </thead>-->
            <!--      <tbody>-->
            <!--        <tr><td>Sample Data</td><td>Sample Data</td><td>Sample Data</td><td>Sample Data</td><td>Sample Data</td><td>Sample Data</td><td>2019-05-16</td></tr>                  </tbody>-->
            <!--    </table>-->
            <!--  </div>-->
            <!--<div class="table-responsive no-dt">-->
            <!--    <h5>Corporate Fixed Deposit</h5>-->
            <!--  <table class="table table-hover table-bordered">-->
            <!--    <thead>-->
            <!--      <tr>-->
                                                                  
            <!--                                                      <th class="bold"> CFD</th>-->
            <!--                                                      <th class="bold"> TENURE</th>-->
            <!--                                                      <th class="bold"> CREDIT</th>-->
            <!--                                                      <th class="bold"> GST</th>-->
            <!--                                                      <th class="bold"> TDS</th>-->
            <!--                                                      <th class="bold"> Effective From</th>-->
            <!--                                                    </tr>-->
            <!--      </thead>-->
            <!--      <tbody>-->
            <!--        <tr><td>Sample Data</td><td>Sample Data</td><td>Sample Data</td><td>Sample Data</td><td>Sample Data</td><td>2019-05-16</td></tr>                  </tbody>-->
            <!--    </table>-->
            <!--  </div>-->
                    <div class="row">
                  <div class="col-md-4">
                      <?= render_select('cat_id',$categories,array('id',array('name')),'product_cat_id_add_edit_assigned',@$scheme->cid); ?>
                  <?= render_select('company_id',$companies,array('id',array('name')),'product_company_id_add_edit_assigned',@$scheme->cpid); ?>
                   
                <div class="form-group" app-field-wrapper="file_csv">
                    <label for="file_csv" class="control-label"> <small class="req text-danger">* </small>Choose CSV File</label>
                <input type="file" class="form-control" name="userfile" id="userfile"  align="center"/></div>
                
               
                
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
