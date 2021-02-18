</main>

</div>

<footer class="bg-white sticky-footer">
  <div class="container my-auto">
    <div class="text-center my-auto copyright"><span>Copyright © Exhibens 2020</span></div>
  </div>
</footer>
</div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a></div>
<script src="<?= base_url() ?>assets/dashboard/js/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/dashboard/bootstrap/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>assets/dashboard/js/theme.js"></script>

<script type="text/javascript">
  let current_url = window.location.origin + window.location.pathname;
  var base_url = "<?= base_url() ?>";

  function get_base_url(m_url) {
    return base_url + m_url;
  }

  jQuery(function($) {

    $(".sidebar-dropdown > a").click(function() {
      $(".sidebar-submenu").slideUp(200);
      if (
        $(this)
        .parent()
        .hasClass("active")
      ) {
        $(".sidebar-dropdown").removeClass("active");
        $(this)
          .parent()
          .removeClass("active");
      } else {
        $(".sidebar-dropdown").removeClass("active");
        $(this)
          .next(".sidebar-submenu")
          .slideDown(200);
        $(this)
          .parent()
          .addClass("active");
      }
    });

    $("#close-sidebar").click(function() {
      $(".page-wrapper").removeClass("toggled");
    });
    $("#show-sidebar").click(function() {
      $(".page-wrapper").addClass("toggled");
    });

    $(".sidebar-menu a").each((index, el) => {
      let el_href = $(el).attr('href');
      if (el_href == current_url) {
        if ($(el).parents(".sidebar-submenu").length) {
          $(el).parents('.sidebar-dropdown').addClass('active');
          $(el).parents(".sidebar-submenu").show();
        }
        $(el).parents('li').addClass('active');
      }
    })

  });
</script>

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