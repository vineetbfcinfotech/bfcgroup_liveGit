<html lang="en">
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/i18n/defaults-*.min.js"></script>
</head>

<body>
<h5>typeOfGlass</h5>
<select id="typeOfGlass" class="selectpicker">
  <option value="15">Standard/Annealed Glass Width</option>
    <option value="20">Tempered Glass Width</option>
</select><br>

Glass Width<br>
    <select id="glassWidth" class="selectpicker">
        <option value="19">Select type of Glass</option>
    </select>
    <script>$('#typeOfGlass').on('change', function(){
   console.log($('#typeOfGlass').val());
    $('#glassWidth').html('');
    if($('#typeOfGlass').val()==15){
        $('#glassWidth').append('<option value="19">Standard</option>');
        $('#glassWidth').append('<option value="20">Standard 2</option>');

    }else{
        $('#glassWidth').append('<option value="6">Tempered</option>');
        $('#glassWidth').append('<option value="7">Tempered 2</option>');

    }
     $('#glassWidth').selectpicker('refresh');
    
});

</script>

</body></html>