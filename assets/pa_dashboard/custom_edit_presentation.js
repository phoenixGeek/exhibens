var publicCompId = '';

$(".btn-delete-segment-showmodal").click(function (event) {
    $("#delete-segment-name").html($(this).attr("data-name"));
    $("#input-delete-pid").val($(this).attr("data-pid"));
    $('#input-delete-segid').val($(this).attr("data-segid"));
    $("#delete-segment-modal").modal();
});

$(".btn-delete-segment-comfirm").click(function () {

    var thisFormData = new FormData($("#delete-segment-form")[0]);
    $.ajax({
        type: "POST",
        url: base_url + "pa_dashboard/delete_segment/" + $("#input-delete-pid").val() + "/" + $("#input-delete-segid").val(),
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

                $("#delete-segment-modal").modal("hide");

                $("a[data-segid='" + data.success + "']").closest("tr").fadeOut(1000, function () {
                    $("a[data-segid='" + data.success + "']").closest("tr").remove();
                });

                $("#deleted-segment-alert").show();

                setTimeout(function () {
                    $('#deleted-segment-alert').fadeOut(1000);
                }, 3000);
            }
        }
    });
});

$("#updatePre-btn").click(function () {
    var data = {
        name: $("input[name='presentation-name']").val(),
        description: $("textarea[name='presentation-description']").val(),
        banner: $("textarea[name='presentation-banner']").val(),
    };
    data[$("#csrf").attr("name")] = $("#csrf").val();

    var pid = $(this).attr("data-id");

    $.ajax({
        type: "POST",
        url: base_url + "pa_dashboard/update_presentation/" + pid,
        processData: true,
        dataType: "json",
        data: data,
        success: function (res) {
            console.log("res", res);
            if (res.success) {
                $("#updated-presentation-alert").show();
                setTimeout(function () {
                    $('#updated-presentation-alert').fadeOut(1000);
                }, 3000);
            }
        }
    });
});

$('#create-segment-btn').click(function () {

    var thisFormData = new FormData($("#create_segment_form")[0]);

    $.ajax({
        type: "POST",
        url: base_url + "pa_dashboard/ajax_addsegment",
        data: thisFormData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (res) {
            if (res.type === 'success' && res.segment_type === 'video') {
                window.location.href = base_url + "pa_dashboard/create_segment/" + $('input[name="presentation_id"]').val() + "/" + res.segment_id;
            } else if (res.type = 'success' && res.segment_type === 'url') {
                window.location.href = base_url + "pa_dashboard/edit_presentation/" + $('input[name="presentation_id"]').val()
            }
        }
    });
})

$('#segment-type').on('change', function () {

    var segType = $('#segment-type').val();
    if (segType === 'url') {
        $('#segment-url-container').css('display', 'block')
    } else {
        $('#segment-url-container').css('display', 'none')
    }
})

let sortable = Sortable.create(document.getElementById('segments-lists'), {

    animation: 150,
    handle: '.drag',
    onUpdate: (event) => {

        let segments = [];
        $('#segments-lists > .segments-list').each((i, elm) => {
            let segment = {
                segment_id: $(elm).data('segid'),
                order: i
            };
            segments.push(segment);
        });

        var data = {
            segments: segments
        };
        // data[$("#csrf").attr("name")] = $("#csrf").val();
        var pid = $('#updatePre-btn').attr("data-id");

        $.ajax({
            type: "POST",
            url: base_url + "pa_dashboard/update_order_segment/" + pid,
            processData: true,
            dataType: "json",
            data: data,
            success: function (res) {
                console.log("res", res);
                if (res.success) {
                    $("#updated-segment-order-alert").show();
                    setTimeout(function () {
                        $('#updated-segment-order-alert').fadeOut(1000);
                    }, 3000);
                    // location.reload();
                }
            }
        });
    }
});

let sortableComp = Sortable.create(document.getElementById('segments-for-composite-list'), {

    filter: '.segDiabled',
    animation: 150,
    handle: '.drag',
    onUpdate: (event) => {

        segmentsForComp = [];
        $('#segments-for-composite-list > .segForCompList').each((i, elm) => {

            let segForComp = {
                segment_id: $(elm).data('segid'),
                order: i
            };
            segmentsForComp.push(segForComp);
        });

        var data = {
            segmentsForComp: segmentsForComp,
            tab_type: "composite"
        };
        var pid = $('#updatePre-btn').attr("data-id");

        $.ajax({
            type: "POST",
            url: base_url + "pa_dashboard/update_composite_order_segment/" + pid,
            processData: true,
            dataType: "json",
            data: data,
            success: function (res) {
                if (res.success) {

                    window.location.href = base_url + "pa_dashboard/edit_presentation/" + pid + '?tab=' + res.tab_type
                }
            }
        });
    }
});

$('.ckb-seg-index').on('change', function () {

    var existIndex = $(this)[0].checked;
    var segment_id = $(this).closest('.segments-list').data('segid');
    var data = {
        existIndex: existIndex,
        segment_id: segment_id,
        tab_type: "selection"
    };

    var pid = $('#updatePre-btn').attr("data-id");

    $.ajax({
        type: "POST",
        url: base_url + "pa_dashboard/update_seg_index_exist/" + pid,
        processData: true,
        dataType: "json",
        data: data,
        success: function (res) {
            if (res.success) {

                window.location.href = base_url + "pa_dashboard/edit_presentation/" + pid + '?tab=' + res.tab_type
            }
        }
    });
})


$('.ckb-seg-comp').on('change', function () {

    var existComposite = $(this)[0].checked;
    var segment_id = $(this).closest('.segments-list').data('segid');
    var data = {
        existComposite: existComposite,
        segment_id: segment_id,
        tab_type: "selection"
    };

    var pid = $('#updatePre-btn').attr("data-id");

    $.ajax({
        type: "POST",
        url: base_url + "pa_dashboard/update_seg_composite_exist/" + pid,
        processData: true,
        dataType: "json",
        data: data,
        success: function (res) {
            if (res.success) {

                window.location.href = base_url + "pa_dashboard/edit_presentation/" + pid + '?tab=' + res.tab_type
            }
        }
    });
})

$('#preview-composite-btn').on('click', function () {

    $('#preview-composite').hide();
    var segmentsForComp = [];
    $('#segments-for-composite-list > .segForCompList').each((i, elm) => {

        let segForComp = {
            segment_id: $(elm).data('segid'),
            segment_path: $(elm).data('path'),
            segment_start: $(elm).data('start'),
            segment_duration: $(elm).data('duration'),
            order: i
        };
        segmentsForComp.push(segForComp);
    });
    var data = {
        segmentsForComp: segmentsForComp
    };

    console.log("data: ", data)
    var pid = $('#updatePre-btn').attr("data-id");

    $.ajax({
        type: "POST",
        url: base_url + "pa_dashboard/preview_composite/" + pid,
        processData: true,
        dataType: "json",
        data: data,
        success: function (res) {
            if (res.success) {
                publicCompId = res.success;
                $('#preview-composite').show();
                $('#preview-composite').attr('src', res.path)
            }
        }
    });
})

$('#create-composite-btn').on('click', function () {

    var pid = $('#updatePre-btn').attr("data-id");
    if (publicCompId) {
        var data = {
            publicCompId: publicCompId
        }
        $.ajax({
            type: "POST",
            url: base_url + "pa_dashboard/public_composite/" + pid,
            processData: true,
            dataType: "json",
            data: data,
            success: function (res) {
                if (res.success) {
                    location.reload();
                }
            }
        });
    }
})