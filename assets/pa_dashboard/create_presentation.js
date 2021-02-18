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