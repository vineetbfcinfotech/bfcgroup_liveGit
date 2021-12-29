<?php init_head(); ?>
<div id="wrapper"><?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php
if(isset($_POST['export']))
{
 @header("Content-Disposition: attachment; filename=mysql_to_excel.csv");

 
$this->db->select('*');
$this->where('status', 'A');
$result = $this->db->get('tblleads');
print_r($result->result_array());
 while($row=$result->result_array())
 {
  $data.=$row['name'].",";
  $data.=$row['data_source'].",";
  $data.=$row['phone']."\n";
 }

 echo $data;
 exit();

}
?>

<form method="post">
    <input type="submit" name="export" value="export">
</form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>

</body>
</html>
