$("#create-presentation-btn").click(function () {

    var thisFormData = new FormData($("#create_presentation_form")[0]);
    $.ajax({
        type: "POST",
        url: base_url + "pa_dashboard/create_presentation",
        data: thisFormData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (res) {
            window.location.href = base_url + "pa_dashboard/create_presentationcontent/" + res;
        }
    });
});

$(".btn-delete-presentation-showmodal").click(function (event) {
    $("#delete-presentation-name").html($(this).attr("data-name"));
    $("#input-delete-pid").val($(this).attr("data-pid"));
    $("#delete-presentation-modal").modal();
});

$(".btn-delete-presentation-comfirm").click(function () {
    var thisFormData = new FormData($("#delete-presentation-form")[0]);
    $.ajax({
        type: "POST",
        url: base_url + "pa_dashboard/delete_presentation/" + $("#input-delete-pid").val(),
        data: thisFormData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (data) {

            if (data.success) {
                csrfName = data.csrfName;
                csrfHash = data.csrfHash;
                $(".csrftoken").attr("name", csrfName);
                $(".csrftoken").val(csrfHash);
                $("#delete-presentation-modal").modal("hide");
                $("a[data-pid='" + data.success + "']").closest("tr").fadeOut(1000, function () {
                    $("a[data-pid='" + data.success + "']").closest("tr").remove();
                });
                $("#deleted-presentation-alert").show();

                setTimeout(function () {
                    $('#deleted-presentation-alert').fadeOut(1000);
                }, 3000);
            }
        }
    });
});

$("input[type='checkbox']").on('click', function () {
    var ckb_id = $(this).attr("id");
    var pid = ckb_id.replace('checkbox_', '');
    var form_data = $(this).closest('Form')[0];

    var thisFormData = new FormData(form_data);

    console.log("checked", form_data)
    $.ajax({
        type: "POST",
        url: base_url + "pa_dashboard/update_publicstatus/" + pid,
        data: thisFormData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (res_pid) {

            console.log("successssss", res_pid)
            location.reload();
        }
    });
})

$(".btn-link").click(function () {
    var clipboard_id = $(this).attr('id')
    var publicURL = $(this).attr('aria-label');

    toastr.options = {

        'closeButton': true,
        'debug': false,
        'newestOnTop': false,
        'progressBar': false,
        'positionClass': 'toast-bottom-left',
        'preventDuplicates': false,
        'showDuration': '2000',
        'hideDuration': '2000',
        'timeOut': '6000',
        'extendedTimeOut': '1000',
        'showEasing': 'swing',
        'hideEasing': 'linear',
        'showMethod': 'fadeIn',
        'hideMethod': 'fadeOut',
    }
    toastr.success('Presentation URL is copied to clipboard');
    let clipboard = new ClipboardJS('[data-clipboard-text]');
    var publicPreURL = 'http://localhost/exhibens/presentation/' + publicURL
    console.log("copying", publicPreURL)

    $('#' + clipboard_id).attr('data-clipboard-text', publicPreURL);
})