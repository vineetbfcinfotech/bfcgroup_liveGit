<?php init_head(); ?> <div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <form action="
							<?php echo site_url(); ?>admin/leads/upload_ask_q" method="post" enctype="multipart/form-data" name="form1" id="form1">
              <hr>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group" app-field-wrapper="file_csv">
                    <label for="file_csv" class="control-label">Ask Question</label>
                    <input type="text" id="ask_question" name="ask_question" class="form-control" value="" required>
                  </div>
                  <button type="submit" name="submit" class="btn btn-info import btn-import-submit">Submit </button>
                </div>
                </div>
            </form>
          </div>
          <br>
          <div class="panel_s">
            <div class="panel-body">
              <h2 class="faq-heading">FAQ's</h2>
              <div class="row">
                <div class="col-md-6">
                  <p> <?php echo $links; ?> </p>
                  <span> <?php echo $pagination_number; ?> </span>
                </div>
                <div class="col-md-4">
                  <form method='post' action="<?= base_url() ?>admin/leads/ask_faq">
                    <input type='text' name='search_global' value='<?= $search ?>'>
                    <input class="btn btn-primary" type='submit' name='submit' value='Search'>
                  </form>
                </div>
                <div class="col-md-2">
                  <form method='post' action="<?= base_url() ?>admin/leads/clear_filter_faq">
                    <input class="btn btn-primary" type='submit' name='submit' value='Clear Filter'>
                  </form>
                </div>
              </div>
              <hr>
              <br>
              <section class="faq-container"> <?php foreach ($project_data as $value) { ?> <div class="faq-one">
                  <div class="">
                    <h4 class="faq-page">Question: <?= $value->question ?> </h4> <?php if ($_SESSION['staff_user_id'] == 55) {
                                                                                                            if ($value->answer) { ?> <div>
                      <a href="#" style="cursor: pointer;" class="answer_data" data-question="	<?= $value->question ?>" data-answer="<?= $value->answer ?>" data-id="<?= $value->id ?>">Edit Answer </a>
                    </div> <?php } else { ?> <div>
                      <a href="#" style="cursor: pointer;" class="answer_data" data-question="<?= $value->question ?>" data-answer="<?= $value->answer ?>" data-id="<?= $value->id ?>">Give Answer </a>
                    </div> <?php } ?> <?php } ?>
                  </div>
                  <div class="faq-body">
                    <h4>Answer: <?= $value->answer ?> </h4> <?php if ($value->file) { ?> <a href="	<?= base_url() ?>assets/faq_file/<?= $value->file ?>" download="answer_file" class="btn">
                      <i class="fa fa-download"></i> Download </a> <?php }
                      if ($_SESSION['staff_user_id'] == 55) { ?> By: <?= $value->firstname ?> <?php } ?>
                  </div>
                </div>
                <hr class="hr-line"> <?php } ?>
              </section>
              <p> <?php echo $links; ?> </p>
            </div>
          </div>
          <div id="description_full_data" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                <form action="<?php echo site_url() ?>admin/leads/save_answer" method="post" enctype="multipart/form-data">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">FAQs:</h4>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" id="hidden_id" name="hidden_id" value="">
                    <h5>Question</h5>
                    <textarea cols="90" rows="5" id="ask_question_data" readonly></textarea>
                    <!-- <input type="text" id="ask_question_data" name="ask_question" class="form-control" value="" readonly> -->
                    <br>
                    <textarea cols="90" rows="10" id="ask_answer_data" name="answer" style="border:hidden;" placeholder="Write Answer here.."></textarea>
                    <input type="file" name="file" class="form-control">
                    <br>
                    <input type="submit" class="btn btn-info" value="submit">
                    <!-- <input type="hidden" name="remarks_id" id="remarks_id" class="remarks_ids" value=""/> -->
                  </div>
                  <div class="modal-footer">
                    <!-- <a href="#" class="btn btn-default" id="submit" name="submit">add</a> -->
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="modelClose">Close</button>
                  </div>
                  <span id="alert-msg"></span>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> <?php init_tail(); ?> <script>
      $(document).on('click', '.answer_data', function() {
        var question = $(this).attr("data-question");
        var id = $(this).attr("data-id");
        var answer = $(this).attr("data-answer");
        $("#ask_question_data").val(question);
        $("#ask_answer_data").val(answer);
        $("#hidden_id").val(id);
        $("#description_full_data").modal('show');
      });

      function myFunction() {
        //alert();
        var x = document.getElementById("userfile").value;
        y = x.split(" ").splice(-1);
        document.getElementById("file_name").value = y;
      }
    </script>
    </body>
    </html>