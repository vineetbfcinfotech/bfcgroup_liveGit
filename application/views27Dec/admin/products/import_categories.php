
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
<form action="<?php echo site_url();?>admin/products/uploadData" method="post" enctype="multipart/form-data" name="form1" id="form1"> 
    <div class="col-md-4">
        <button type="submit" class="btn btn-success">Download Sample</button>
        <hr>
                   
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
