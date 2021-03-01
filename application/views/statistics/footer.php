</main>
<!-- page-content -->
</div>

<footer class="bg-white sticky-footer">
    <div class="container my-auto">
    </div>
</footer>
</div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a></div>
<script src="<?= base_url() ?>assets/dashboard/js/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/dashboard/bootstrap/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/bootstrap-slider.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>assets/dashboard/js/theme.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

  
<!-- Load footer scripts -->
<?php
if (isset($footer_script) && count($footer_script) > 0) {
  foreach ($footer_script as $key => $script) {
    echo "\n";
    echo '<script type="text/javascript" src="' . base_url() . 'assets/' . $script . '?ver=' . time() . '"></script>';
    echo "\n";
  }
}
?>

</body>
</html>