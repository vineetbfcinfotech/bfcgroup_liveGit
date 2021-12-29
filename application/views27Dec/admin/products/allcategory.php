<?php init_head(); ?>
<div id="wrapper">
    <?php init_clockinout(); ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <!--<a href="<?= admin_url('products/schemes') ?>" class="btn mright5 btn-info pull-left display-block">-->
                            <!--    <?php echo _l('add_new_product'); ?>-->
                            <!--</a>-->
                            <a href="<?= admin_url('products/import_schemes') ?>"  class="btn mright5  pull-left display-block btn-success">
                                <?php echo _l('import_schm'); ?>
                            </a>

                            <div class="clearfix"></div>
                            <hr class="hr-panel-heading" />
                            <?php if(!empty($allcategory) > 0){ ?>
                            <?php foreach ($allcategory as $procat) { ?>
                                    <ul style="width: 20%;margin-right: 6px;display: inline-block;text-align: center">
                                        <a href="<?= sprintf(base_url('admin/products/view_cat_schemes/%s'), $procat->id); ?>"
                                           style="cursor:pointer;">
                                            <i class="fa fa-list-alt fa-5x"
                                               aria-hidden="true"></i><br/><?= $procat->name ?>
                                            <br/><?= $procat->short_name ?><br/>
                                        </a>

                                    </ul>
                                    <?php
                                } ?>

                            <?php } else { ?>
                                <p class="no-margin text-center"><?php echo _l('no_product_schemes_found'); ?></p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php init_tail(); ?>
    
    </body>
    </html>