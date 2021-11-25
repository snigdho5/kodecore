<!-- All Required js -->
<!-- ============================================================== -->
<script src="<?php echo base_url().'common/assets/libs/jquery/dist/jquery.min.js';?>"></script>
<script src="<?php echo base_url().'common/assets/libs/jquery-validation/dist/jquery.validate.min.js';?>"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="<?php echo base_url().'common/assets/libs/popper.js/dist/umd/popper.min.js';?>"></script>
<script src="<?php echo base_url().'common/assets/libs/bootstrap/dist/js/bootstrap.min.js';?>"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="<?php echo base_url().'common/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js';?>"></script>
<script src="<?php echo base_url().'common/assets/extra-libs/sparkline/sparkline.js';?>"></script>
<!--Wave Effects -->
<script src="<?php echo base_url().'common/dist/js/waves.js';?>"></script>
<!--Menu sidebar -->
<script src="<?php echo base_url().'common/dist/js/sidebarmenu.js';?>"></script>
<!--Custom JavaScript -->
<script src="<?php echo base_url().'common/dist/js/custom.min.js';?>"></script>
<script src="<?php echo base_url().'common/node_modules/sweetalert2/dist/sweetalert2.all.min.js';?>"></script>
<script src="<?php echo base_url().'common/ckeditor/ckeditor.js';?>"></script>
<script src="<?php echo base_url() . 'common/assets/libs/select2/dist/js/select2.full.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'common/assets/libs/select2/dist/js/select2.min.js'; ?>"></script>
<script type="text/javascript">
    var BASE_URL = "<?php echo base_url();?>";

    var max_chars = 2;
    
    $(document).on('keydown', '.num-upto2', function () {
        if ($(this).val().length >= max_chars) { 
            $(this).val($(this).val().substr(0, max_chars));
        }
    });

        
    $(document).on('keyup', '.num-upto2', function () {
        if ($(this).val().length >= max_chars) { 
            $(this).val($(this).val().substr(0, max_chars));
        }
    });
</script>
