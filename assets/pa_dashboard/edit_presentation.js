var maxTime;
var minTime;
var isFirstTimeVideoLoaded = true;
var activeSegment = null;

$(document).ready(function (e) {

    if (segment_list) {
        updateSegmentListDiv();
        $("#video-info .num-of-segment").html(segment_list.length);
        $("#segment-list .list-group-item:nth-child(1)").click();
    }
    if (video_list) {

        console.log("video: ", video_list)
        video_list.forEach(v => {
            var thisFormData = new FormData($("#secure-frm")[0]);
            $.ajax({
                type: "POST",
                url: base_url + "pa_dashboard/ajax_getVideoHTMLbyId/" + v.id,
                data: thisFormData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function (res) {

                    // console.log("response: ", res);
                    csrfName = res.csrfName;
                    csrfHash = res.csrfHash;
                    $(".csrftoken").attr("name", csrfName);
                    $(".csrftoken").val(csrfHash);
                    $("#slt-video-list").append(res.videoHtml);
                    $("#video-info .num-of-video").html(video_list.length);
                    // activeSegment = 0;
                    $("#top-pl").collapse("show");
                    $("#segment-preview1").attr("src", segment_list[0].path)

                    if (activeSegment !== null) {
                        $("#segment-preview1")[0].pause();
                        $("#segment-preview1")[0].currentTime = segment_list[activeSegment].start;
                    }

                    renderPlayer(0);
                }
            });
        });
    }
})

$(".video-card").click(function () {
    $(this).find(".video-slt").prop('checked', true);
});

$("#add-video-to-queue").click(function (e) {
    $('.video-card input:checked').each(function (e) {

        $("#slt-video-list").html('');
        var thisFormData = new FormData($("#secure-frm")[0]);
        $.ajax({
            type: "POST",
            url: base_url + "pa_dashboard/ajax_getVideoHTMLbyId/" + $(this).attr("data-vid"),
            data: thisFormData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (res) {

                csrfName = res.csrfName;
                csrfHash = res.csrfHash;
                $(".csrftoken").attr("name", csrfName);
                $(".csrftoken").val(csrfHash);
                $("#slt-video-list").append(res.videoHtml);
                video_list.splice(0, 1, res.video);
                // video_list.push(res.video);
                // console.log("videooooooooooooo", video_list)
                $("#video-info .num-of-video").html(video_list.length);

                if ($('#create-segment-from-seletected-video').is(':checked')) {

                    var segment = {
                        "segment_id": res.video.segment_id,
                        "start": 0,
                        "vid_duration": res.video.duration,
                        "end": res.video.duration,
                        "duration": res.video.duration,
                        "duration_in_format": format(res.video.duration),
                        "video_id": res.video.vid,
                        "path": base_url + res.video.path,
                        "description": "",
                        "added": false
                    }

                    var total_duration = 0;
                    segment_list.splice(1, 1, segment);
                    // segment_list.push(segment);
                    for (let i = 0; i < segment_list.length; i++) {
                        const seg = segment_list[i];
                        total_duration += seg.duration;

                        if (!seg.added) {
                            seg.added = true;
                        }
                    }

                    updateSegmentListDiv();

                    $("#video-info .num-of-segment").html(segment_list.length);
                    $("#video-info .total-duration").html(format(total_duration));

                    if (activeSegment == null) {
                        $(".list-group-item:first-child").addClass("active");
                        $(".list-group-item:first-child").click();
                        activeSegment = 0;
                        $("#top-pl").collapse("show");
                    }

                    if (activeSegment !== null) {
                        $("#segment-preview1")[0].pause();
                        $("#segment-preview1")[0].currentTime = segment_list[activeSegment].start;
                    }

                    // var index = $("#segment-list .list-group-item").index($(this));
                    renderPlayer(1);

                }
            }
        });

        $("#add-video-modal").modal("hide");
        $(".video-slt").prop('checked', false);

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
            console.log("success", res);
            window.location.href = base_url + "pa_dashboard/create_segment/" + $('input[name="presentation_id"]').val() + "/" + res;
        }
    });
})

$(document).on('click', '.add-new-video-segment', function (event) {

    var thisVideoID = $(this).attr('data-vid');
    var thisVideo = findVideoInListByID(thisVideoID);

    $("#edit-video-segment-modal .modal-title").html(thisVideo.title);
    $("#all-segments-of-video").empty();
    $("#add-segment-to-presentation").attr("data-vid", thisVideoID);
    if (thisVideo.full_path != null) {
        path = thisVideo.full_path;
    } else {
        path = thisVideo.path;
    }
    $("#segment-preview").attr("src", base_url + path);
    $("#segment-preview").on('loadeddata', function () {

        var videoElement = $(this)[0]
        var duration = videoElement.duration * 1000;
        updateStartTime(0);
        updateEndTime(0);
        $("#duration-segment").html(format(duration / 1000));
        $("#segDur").html(format(duration / 1000));
        $("#vid-duration").val(videoElement.duration);
        $("#ex2").attr("data-slider-max", duration);

        var minTime = 0;
        var maxTime = duration / 1000;
        updateStartTime(minTime);
        updateEndTime(maxTime);
        $("#maxTime-store").val(maxTime);

        $("#ex2").slider({
            min: 0,
            max: duration,
            value: [0, duration],

        }).on('change', function (e) {

            var minVal = e.value.newValue[0] / 1000;
            var maxVal = e.value.newValue[1] / 1000;
            $("#maxTime-store").val(maxVal);
            $("#minTime-store").val(minVal);
            $(".tooltip-inner").html(format(minVal) + " - " + format(maxVal));
            var parentWidth = $("#full-duration-display-div").width();
            $("#full-duration-display-div .progress").css("left", (minVal / (duration / 1000)) * parentWidth + "px");
            $("#full-duration-display-div .progress").css("width", (((maxVal - minVal) / (duration / 1000)) * parentWidth) + "px");

        }).on('slideStop', function (e) {

            var minVal = e.value[0] / 1000;
            var maxVal = e.value[1] / 1000;

            $(".tooltip-inner").html(format(minVal) + " - " + format(maxVal));
            $("#maxTime-store").val(maxVal);
            $("#minTime-store").val(minVal);

            updateStartTime(minVal);
            updateEndTime(maxVal);
            $("#duration-segment").html(format(maxVal - minVal));
            $("#segDur").html(format(maxVal - minVal));
            var parentWidth = $("#full-duration-display-div").width();
            $("#full-duration-display-div .progress").css("left", (minVal / (duration / 1000)) * parentWidth + "px");
            $("#full-duration-display-div .progress").css("width", (((maxVal - minVal) / (duration / 1000)) * parentWidth) + "px");
            videoElement.currentTime = e.value[0] / 1000;

        }).on('slide', function (e) {

            var minVal = e.value[0] / 1000;
            var maxVal = e.value[1] / 1000;

            $(".tooltip-inner").html(format(minVal) + " - " + format(maxVal));
            $("#maxTime-store").val(maxVal);
            $("#minTime-store").val(minVal);

            updateStartTime(minVal);
            updateEndTime(maxVal);
            $("#duration-segment").html(format(maxVal - minVal));
            $("#segDur").html(format(maxVal - minVal));
            var parentWidth = $("#full-duration-display-div").width();
            $("#full-duration-display-div .progress").css("left", (minVal / (duration / 1000)) * parentWidth + "px");
            $("#full-duration-display-div .progress").css("width", (((maxVal - minVal) / (duration / 1000)) * parentWidth) + "px");
            videoElement.currentTime = e.value[0] / 1000;
        });

        $(".tooltip-inner").html(format(0) + " - " + format(duration / 1000));
    });

    $("#segment-preview").on("timeupdate", function () {

        var video = $("#segment-preview")[0];
        var maxTime = $("#maxTime-store").val();
        var minTime = $("#minTime-store").val();
        var progress_value = (video.currentTime - minTime) * 100 / (maxTime - minTime);

        $("#full-duration-display-div .progress .progress-bar").width(progress_value + "%");
        $("#segchronos").html(format(video.currentTime - minTime));
        if (video.currentTime >= maxTime) {
            video.pause();
            $("#preview-play").html("Play");
            $("#preview-play").attr("data-playing", "0");
        }
        if (video.currentTime <= minTime) video.currentTime = minTime;

    });

    $("#segment-preview").on("play", function () {
        var video = $("#segment-preview")[0];
        var maxTime = $("#maxTime-store").val();
        var minTime = $("#minTime-store").val();
        if (video.currentTime > maxTime) video.currentTime = minTime;
    })
    $("#edit-video-segment-modal").modal();
})

$("#add-segment-to-presentation").click(function (e) {

    self = $(this);
    var step = self.attr("data-step");
    var maxTime = $("#maxTime-store").val();
    var minTime = $("#minTime-store").val();
    if (step == 1) {
        $("#step-1").hide();
        $("#step-2").show();
        self.attr("data-step", "2");
        self.html("Add Sgement To Presentation");
    } else {
        $.ajax({
            type: "GET",
            url: base_url + "pa_dashboard/ajax_get_segment_uniqueid",
            dataType: "text",
            success: function (id) {

                var segment = {
                    "segment_id": id,
                    "start": minTime,
                    "end": maxTime,
                    "duration": maxTime - minTime,
                    "duration_in_format": format(maxTime - minTime),
                    "video_id": self.attr('data-vid'),
                    "path": $("#segment-preview").attr("src"),
                    "description": $("#seg-desc").val(),
                    "added": false
                }

                updateSegmentList(segment);
                $("#edit-video-segment-modal").modal("hide");
                $("#segment-preview")[0].pause();
                $("#preview-play").html("Play");
                $("#preview-play").attr("data-playing", "0");
                $("#step-1").show();
                $("#step-2").hide();
                self.attr("data-step", "1");
                self.html("Next");
            }
        });
    }
});

$("#add-segment-to-presentation-back").click(function (e) {
    $("#add-segment-to-presentation").attr("data-step", "1");
    $("#add-segment-to-presentation").html("Next");
    $("#step-1").show();
    $("#step-2").hide();
})

function updateStartTime(time) {
    var timeObj = formatTimeObj(time);
    $("#start-segment input:first-child").val(timeObj.hour);
    $("#start-segment input:nth-child(2)").val(timeObj.min);
    $("#start-segment input:nth-child(3)").val(timeObj.sec);
    $("#start-segment input:nth-child(4)").val(timeObj.msec);
}

function updateEndTime(time) {
    var timeObj = formatTimeObj(time);
    $("#end-segment input:first-child").val(timeObj.hour);
    $("#end-segment input:nth-child(2)").val(timeObj.min);
    $("#end-segment input:nth-child(3)").val(timeObj.sec);
    $("#end-segment input:nth-child(4)").val(timeObj.msec);
}

function renderPlayer(index) {

    var index = index;
    activeSegment = index;
    $("#segment-list .active").removeClass("active");
    $(this).addClass("active");
    var seg = segment_list[index];

    $("#segment-index").val(index);

    console.log("segment_list: ", segment_list)
    updateTopStartTime(seg.start);
    updateTopEndTime(seg.end);

    $("#segment-preview1").attr("src", seg.path);
    $("#segment-preview1").attr("poster", "");
    $("#seg-desc1").val(seg.description);

    var minTime = seg.start;
    var maxTime = seg.end;
    updateTopEndTime(maxTime);
    $("#maxTime-store1").val(maxTime);
    $("#minTime-store1").val(minTime);
    var parentWidth = $("#full-duration-display-div1").width();
    var fduration = seg.vid_duration;
    $("#full-duration-display-div1 .progress").css("left", ((minTime) / fduration) * parentWidth + "px");
    $("#full-duration-display-div1 .progress").css("width", (((maxTime - minTime) / fduration) * parentWidth) + "px");
    $("#ex1").val(minTime + "," + maxTime);
    $("#ex1").attr("data-slider-max", fduration * 1000);
    $("#ex1").parent().find(".tooltip-inner").html(format(minTime) + " - " + format(maxTime));
    $("#segment-preview1").on('loadedmetadata', function (e) {

        $("#top-pl").collapse("show");
        var videoElement = $(this)[0]
        var duration = videoElement.duration * 1000;
        $("#duration-segment1").html(format(duration / 1000));
        $("#segDur1").html(format(duration / 1000));
        $("#ex1").attr("data-slider-max", duration);
        var minTime = seg.start;
        var maxTime = seg.end;
        updateTopEndTime(maxTime);
        $("#maxTime-store1").val(maxTime);
        $("#minTime-store1").val(minTime);
        $("#ex1").parent().find(".tooltip-inner").html(format(seg.start * 1000) + " - " + format(seg.end * 1000));
        var parentWidth = $("#full-duration-display-div1").width();
        $("#full-duration-display-div1 .progress").css("left", ((minTime) / (duration / 1000)) * parentWidth + "px");
        $("#full-duration-display-div1 .progress").css("width", (((maxTime - minTime) / (duration / 1000)) * parentWidth) + "px");
        videoElement.currentTime = seg.start;

        $("#ex1").slider({
            min: 0,
            max: duration,
            value: [minTime * 1000, maxTime * 1000]
        }).on('change', function (e) {

            var minVal = e.value.newValue[0] / 1000;
            var maxVal = e.value.newValue[1] / 1000;
            $("#maxTime-store1").val(maxVal);
            $("#minTime-store1").val(minVal);
            $("#ex1").parent().find(".tooltip-inner").html(format(minVal) + " - " + format(maxVal));
            var parentWidth = $("#full-duration-display-div1").width();
            $("#full-duration-display-div1 .progress").css("left", (minVal / (duration / 1000)) * parentWidth + "px");
            $("#full-duration-display-div1 .progress").css("width", (((maxVal - minVal) / (duration / 1000)) * parentWidth) + "px");

        }).on('slideStop', function (e) {

            var minVal = e.value[0] / 1000;
            var maxVal = e.value[1] / 1000;
            $("#ex1").parent().find(".tooltip-inner").html(format(minVal) + " - " + format(maxVal));
            $("#maxTime-store1").val(maxVal);
            $("#minTime-store1").val(minVal);
            updateTopStartTime(minVal);
            updateTopEndTime(maxVal);
            $("#duration-segment1").html(format(maxVal - minVal));
            $("#segDur1").html(format(maxVal - minVal));
            var parentWidth = $("#full-duration-display-div1").width();
            $("#full-duration-display-div1 .progress").css("left", (minVal / (duration / 1000)) * parentWidth + "px");
            $("#full-duration-display-div1 .progress").css("width", (((maxVal - minVal) / (duration / 1000)) * parentWidth) + "px");
            $("#segment-list .list-group-item:nth-child(" + (activeSegment + 1) + ") .segmentDuration").html(format(maxVal - minVal));
            $("#segment-list .list-group-item:nth-child(" + (activeSegment + 1) + ") .segmentStart").html(format(minVal));
            $("#segment-list .list-group-item:nth-child(" + (activeSegment + 1) + ") .segmentEnd").html(format(maxVal));
            videoElement.currentTime = e.value[0] / 1000;
            var segment_index = $("#segment-index").val();
            segment_list[segment_index].start = minVal;
            segment_list[segment_index].end = maxVal;
            segment_list[segment_index].duration = maxVal - minVal;
            segment_list[segment_index].description = $("#seg-desc1").val();

        }).on('slide', function (e) {

            var minVal = e.value[0] / 1000;
            var maxVal = e.value[1] / 1000;

            $("#ex1").parent().find(".tooltip-inner").html(format(minVal) + " - " + format(maxVal));
            $("#maxTime-store1").val(maxVal);
            $("#minTime-store1").val(minVal);
            updateTopStartTime(minVal);
            updateTopEndTime(maxVal);
            $("#duration-segment1").html(format(maxVal - minVal));
            $("#segDur1").html(format(maxVal - minVal));
            var parentWidth = $("#full-duration-display-div1").width();
            $("#full-duration-display-div1 .progress").css("left", (minVal / (duration / 1000)) * parentWidth + "px");
            $("#full-duration-display-div1 .progress").css("width", (((maxVal - minVal) / (duration / 1000)) * parentWidth) + "px");
            videoElement.currentTime = e.value[0] / 1000;

        });

        if (isFirstTimeVideoLoaded) {
            console.log("firsttime");
            isFirstTimeVideoLoaded = false;
        } else {
            console.log("Secondtime");
            $("#ex1").slider('setValue', [minTime * 1000, maxTime * 1000]);
        }
        $("#ex1").parent().find(".tooltip-inner").html(format(seg.start) + " - " + format(seg.end));

    })

    $("#segment-preview1").on("timeupdate", function () {

        var video = $("#segment-preview1")[0];
        var maxTime = $("#maxTime-store1").val();
        var minTime = $("#minTime-store1").val();
        var progress_value = (video.currentTime - minTime) * 100 / (maxTime - minTime);

        $("#full-duration-display-div1 .progress .progress-bar").width(progress_value + "%");
        $("#segchronos1").html(format(video.currentTime - minTime));
        if (video.currentTime >= maxTime) {
            video.pause();
            $("#preview-play1").html("Play");
            $("#preview-play1").attr("data-playing", "0");
        }
        if (video.currentTime <= minTime) video.currentTime = minTime;

    });

    $("#segment-preview1").on("play", function () {

        var video = $("#segment-preview1")[0];
        var maxTime = $("#maxTime-store1").val();
        var minTime = $("#minTime-store1").val();
        if (video.currentTime > maxTime) video.currentTime = minTime;
    })

    $("#publish-btn").show();
}

function updateSegmentList(segment) {

    var total_duration = 0;
    segment.vid_duration = $("#vid-duration").val();
    segment_list.push(segment);

    for (let i = 0; i < segment_list.length; i++) {
        const seg = segment_list[i];
        total_duration += seg.duration;

        if (!seg.added) {
            seg.added = true;
        }
    }

    updateSegmentListDiv();

    $("#video-info .num-of-segment").html(segment_list.length);
    $("#video-info .total-duration").html(format(total_duration));
}

function updateSegmentListDiv() {

    $("#segment-list").empty();
    var htmlCode = "";
    var indexTblHTML = "";
    var currentDuration = 0;

    for (let i = 0; i < segment_list.length; i++) {

        const e = segment_list[i];
        htmlCode += '<div class="list-group-item d-flex align-items-center justify-content-between"  data-id="' + i + '" data-segment-id="' + e.segment_id + '" data-array-index="' + i + '" >';
        htmlCode += '<div>';
        htmlCode += '<p class="mb-0 d-inline-flex align-items-center">';
        htmlCode += 'Segment #' + (i + 1).toString() + " - <b>Duration</b> : " + '<span class="segmentDuration">' + e.duration_in_format + '</span>' + ' - <b>Start</b> : <span class="segmentStart">' + format(e.start) + '</span> - <b>End</b> : <span class="segmentEnd">' + format(e.end) + '</span>';
        htmlCode += '</p>';
        htmlCode += '</div>';
        htmlCode += '</div>';
        currentDuration += segment_list[i].duration;
    }

    $("#segment-list").append(htmlCode);
    $(".input-wrapper").append(indexTblHTML);
}

function findVideoInListByID(vid) {

    for (let i = 0; i < video_list.length; i++) {
        const e = video_list[i];
        if (e.vid == (vid + "")) {
            return e;
        }
    }
}

function format(time) {
    // Hours, minutes and seconds
    var hrs = ~~(time / 3600);
    var mins = ~~((time % 3600) / 60);
    var secs = ~~time % 60;

    // Output like "1:01" or "4:03:59" or "123:03:59"
    var ret = "";
    if (hrs > 0) {
        ret += "" + hrs + ":" + (mins < 10 ? "0" : "");
    }
    if (mins < 10) {
        ret += "0" + mins + ":" + (secs < 10 ? "0" : "");
    } else {
        ret += mins + ":" + (secs < 10 ? "0" : "");
    }

    ret += "" + secs;
    return ret;
}

function formatTimeObj(time) {
    var hrs = ~~(time / 3600);
    var mins = ~~((time % 3600) / 60);
    var secs = ~~time % 60;

    return {
        'hour': (hrs < 10 ? "0" : "") + hrs,
        'min': (mins < 10 ? "0" : "") + mins,
        'sec': (secs < 10 ? "0" : "") + secs,
        'msec': Math.round((time % 1) * 1000)
    }
}

function getTimefromInput(h, m, s, ms) {
    return h * 3600 + m * 60 + s + ms / 1000;
}

$("#edit-video-segment-modal .time-input").change(function (e) {

    var els = $(this).parent().find("input");
    var lbl = $(this).parent().attr("data-lbl");
    var h = parseInt(els[0].value);
    var m = parseInt(els[1].value);
    var s = parseInt(els[2].value);
    var ms = parseInt(els[3].value);
    var time = getTimefromInput(h, m, s, ms);
    var duration = $("#segment-preview")[0].duration;

    if (lbl == "start") {

        //kiem tra gia tri min
        if (time < 0) time = 0;
        if (time > duration) time = duration;

        //can update o day
        var maxTime = $("#maxTime-store").val();
        $("#ex2").slider('setValue', [time * 1000, maxTime * 1000]);
        minTime = time;
        $("#minTime-store").val(minTime);
        $("#duration-segment").html(format(maxTime - minTime));
        $("#segDur").html(format(maxTime - minTime));
        var parentWidth = $("#full-duration-display-div").width();
        $("#segment-preview")[0].currentTime = minTime;
        $("#full-duration-display-div .progress").css("left", (minTime / (duration)) * parentWidth + "px");
        $("#full-duration-display-div .progress").css("width", (((maxTime - minTime) / (duration)) * parentWidth) + "px");
        $(".tooltip-inner").html(format(minTime) + " - " + format(maxTime));

    } else {

        //kiem tra gia tri max
        if (time < 0) time = 0;
        if (time > duration) time = duration;
        var minTime = $("#minTime-store").val();
        $("#ex2").slider('setValue', [minTime * 1000, time * 1000]);
        maxTime = time;
        $("#maxTime-store").val(maxTime);
        $("#duration-segment").html(format(maxTime - minTime));
        $("#segDur").html(format(maxTime - minTime));
        var parentWidth = $("#full-duration-display-div").width();
        $("#full-duration-display-div .progress").css("left", (minTime / (duration)) * parentWidth + "px");
        $("#full-duration-display-div .progress").css("width", (((maxTime - minTime) / (duration)) * parentWidth) + "px");
        $(".tooltip-inner").html(format(minTime) + " - " + format(maxTime));
        $("#full-duration-display-div .progress .progress-bar").width("0%");
    }
    $(".tooltip-inner").html(format(minTime) + " - " + format(maxTime));

});

$("#top-pl .time-input").change(function (e) {

    var els = $(this).parent().find("input");
    var lbl = $(this).parent().attr("data-lbl");
    var segment_index = $("#segment-index").val();
    var h = parseInt(els[0].value);
    var m = parseInt(els[1].value);
    var s = parseInt(els[2].value);
    var ms = parseInt(els[3].value);
    var time = getTimefromInput(h, m, s, ms);
    var duration = $("#segment-preview")[0].duration;

    if (lbl == "start") {

        //kiem tra gia tri min
        if (time < 0) time = 0;
        if (time > duration) time = duration;
        //can update o day
        var maxTime = $("#maxTime-store1").val();

        $("#ex1").slider('setValue', [time * 1000, maxTime * 1000]);
        minTime = time;

        //$("#maxTime-store1").val(maxTime);
        $("#minTime-store1").val(minTime);
        $("#duration-segment1").html(format(maxTime - minTime));
        $("#segDur1").html(format(maxTime - minTime));
        var parentWidth = $("#full-duration-display-div1").width();
        $("#segment-preview1")[0].currentTime = minTime;
        $("#full-duration-display-div1 .progress").css("left", (minTime / (duration)) * parentWidth + "px");
        $("#full-duration-display-div1 .progress").css("width", (((maxTime - minTime) / (duration)) * parentWidth) + "px");
        $("#ex1").parent().find(".tooltip-inner").html(format(minTime) + " - " + format(maxTime));
        segment_list[segment_index].start = minTime;
        //segment_list[segment_index].end = maxTime;
        segment_list[segment_index].description = $("#seg-desc1").val();
        segment_list[segment_index].duration = maxTime - minTime;

        $("#segment-list .list-group-item:nth-child(" + (activeSegment + 1) + ") .segmentDuration").html(format(maxTime - minTime));
        $("#segment-list .list-group-item:nth-child(" + (activeSegment + 1) + ") .segmentStart").html(format(minTime));
        $("#segment-list .list-group-item:nth-child(" + (activeSegment + 1) + ") .segmentEnd").html(format(maxTime));

    } else {
        //kiem tra gia tri max
        if (time < 0) time = 0;
        if (time > duration) time = duration;

        //can update o day
        var minTime = $("#minTime-store1").val();
        $("#ex1").slider('setValue', [minTime * 1000, time * 1000]);
        maxTime = time;
        $("#maxTime-store1").val(maxTime);
        $("#duration-segment1").html(format(maxTime - minTime));
        $("#segDur1").html(format(maxTime - minTime));
        var parentWidth = $("#full-duration-display-div1").width();
        $("#full-duration-display-div1 .progress").css("left", (minTime / (duration)) * parentWidth + "px");
        $("#full-duration-display-div1 .progress").css("width", (((maxTime - minTime) / (duration)) * parentWidth) + "px");
        $("#ex1").parent().find(".tooltip-inner").html(format(minTime) + " - " + format(maxTime));
        $("#full-duration-display-div1 .progress .progress-bar").width("0%");

        segment_list[segment_index].end = maxTime;
        segment_list[segment_index].description = $("#seg-desc1").val();
        segment_list[segment_index].duration = maxTime - minTime;
        $("#segment-list .list-group-item:nth-child(" + (activeSegment + 1) + ") .segmentDuration").html(format(maxTime - minTime));
        $("#segment-list .list-group-item:nth-child(" + (activeSegment + 1) + ") .segmentStart").html(format(minTime));
        $("#segment-list .list-group-item:nth-child(" + (activeSegment + 1) + ") .segmentEnd").html(format(maxTime));
    }
    $("#ex1").parent().find(".tooltip-inner").html(format(minTime) + " - " + format(maxTime));

});

$(document).on('click', "#segment-list .list-group-item", function (e) {
    //stop the video player

    // if (activeSegment !== null) {
    //     $("#segment-preview1")[0].pause();
    //     $("#segment-preview1")[0].currentTime = segment_list[activeSegment].start;
    // }

    // var index = $("#segment-list .list-group-item").index($(this));
    // activeSegment = index;
    // $("#segment-list .active").removeClass("active");
    // $(this).addClass("active");
    // var seg = segment_list[index + 1];

    // $("#segment-index").val(index);

    // console.log("aaaaaaazzzzz", index)
    // updateTopStartTime(seg.start);

    // updateTopEndTime(seg.end);

    // $("#segment-preview1").attr("src", seg.path);
    // $("#segment-preview1").attr("poster", "");
    // $("#seg-desc1").val(seg.description);

    // var minTime = seg.start;
    // var maxTime = seg.end;
    // updateTopEndTime(maxTime);
    // $("#maxTime-store1").val(maxTime);
    // $("#minTime-store1").val(minTime);
    // var parentWidth = $("#full-duration-display-div1").width();
    // var fduration = seg.vid_duration;
    // $("#full-duration-display-div1 .progress").css("left", ((minTime) / fduration) * parentWidth + "px");
    // $("#full-duration-display-div1 .progress").css("width", (((maxTime - minTime) / fduration) * parentWidth) + "px");
    // $("#ex1").val(minTime + "," + maxTime);
    // $("#ex1").attr("data-slider-max", fduration * 1000);
    // $("#ex1").parent().find(".tooltip-inner").html(format(minTime) + " - " + format(maxTime));
    // $("#segment-preview1").on('loadedmetadata', function (e) {

    //     $(".jumbotron").hide();
    //     $("#top-pl").collapse("show");
    //     var videoElement = $(this)[0]
    //     var duration = videoElement.duration * 1000;
    //     $("#duration-segment1").html(format(duration / 1000));
    //     $("#segDur1").html(format(duration / 1000));
    //     $("#ex1").attr("data-slider-max", duration);
    //     var minTime = seg.start;
    //     var maxTime = seg.end;
    //     updateTopEndTime(maxTime);
    //     $("#maxTime-store1").val(maxTime);
    //     $("#minTime-store1").val(minTime);
    //     $("#ex1").parent().find(".tooltip-inner").html(format(seg.start * 1000) + " - " + format(seg.end * 1000));
    //     var parentWidth = $("#full-duration-display-div1").width();
    //     $("#full-duration-display-div1 .progress").css("left", ((minTime) / (duration / 1000)) * parentWidth + "px");
    //     $("#full-duration-display-div1 .progress").css("width", (((maxTime - minTime) / (duration / 1000)) * parentWidth) + "px");
    //     videoElement.currentTime = seg.start;

    //     $("#ex1").slider({
    //         min: 0,
    //         max: duration,
    //         value: [minTime * 1000, maxTime * 1000]
    //     }).on('change', function (e) {

    //         var minVal = e.value.newValue[0] / 1000;
    //         var maxVal = e.value.newValue[1] / 1000;
    //         $("#maxTime-store1").val(maxVal);
    //         $("#minTime-store1").val(minVal);
    //         $("#ex1").parent().find(".tooltip-inner").html(format(minVal) + " - " + format(maxVal));
    //         var parentWidth = $("#full-duration-display-div1").width();
    //         $("#full-duration-display-div1 .progress").css("left", (minVal / (duration / 1000)) * parentWidth + "px");
    //         $("#full-duration-display-div1 .progress").css("width", (((maxVal - minVal) / (duration / 1000)) * parentWidth) + "px");

    //     }).on('slideStop', function (e) {

    //         var minVal = e.value[0] / 1000;
    //         var maxVal = e.value[1] / 1000;
    //         $("#ex1").parent().find(".tooltip-inner").html(format(minVal) + " - " + format(maxVal));
    //         $("#maxTime-store1").val(maxVal);
    //         $("#minTime-store1").val(minVal);
    //         updateTopStartTime(minVal);
    //         updateTopEndTime(maxVal);
    //         $("#duration-segment1").html(format(maxVal - minVal));
    //         $("#segDur1").html(format(maxVal - minVal));
    //         var parentWidth = $("#full-duration-display-div1").width();
    //         $("#full-duration-display-div1 .progress").css("left", (minVal / (duration / 1000)) * parentWidth + "px");
    //         $("#full-duration-display-div1 .progress").css("width", (((maxVal - minVal) / (duration / 1000)) * parentWidth) + "px");
    //         $("#segment-list .list-group-item:nth-child(" + (activeSegment + 1) + ") .segmentDuration").html(format(maxVal - minVal));
    //         $("#segment-list .list-group-item:nth-child(" + (activeSegment + 1) + ") .segmentStart").html(format(minVal));
    //         $("#segment-list .list-group-item:nth-child(" + (activeSegment + 1) + ") .segmentEnd").html(format(maxVal));
    //         videoElement.currentTime = e.value[0] / 1000;
    //         var segment_index = $("#segment-index").val();
    //         segment_list[segment_index].start = minVal;
    //         segment_list[segment_index].end = maxVal;
    //         segment_list[segment_index].duration = maxVal - minVal;
    //         segment_list[segment_index].description = $("#seg-desc1").val();

    //     }).on('slide', function (e) {

    //         var minVal = e.value[0] / 1000;
    //         var maxVal = e.value[1] / 1000;

    //         $("#ex1").parent().find(".tooltip-inner").html(format(minVal) + " - " + format(maxVal));
    //         $("#maxTime-store1").val(maxVal);
    //         $("#minTime-store1").val(minVal);
    //         updateTopStartTime(minVal);
    //         updateTopEndTime(maxVal);
    //         $("#duration-segment1").html(format(maxVal - minVal));
    //         $("#segDur1").html(format(maxVal - minVal));
    //         var parentWidth = $("#full-duration-display-div1").width();
    //         $("#full-duration-display-div1 .progress").css("left", (minVal / (duration / 1000)) * parentWidth + "px");
    //         $("#full-duration-display-div1 .progress").css("width", (((maxVal - minVal) / (duration / 1000)) * parentWidth) + "px");
    //         videoElement.currentTime = e.value[0] / 1000;

    //     });

    //     if (isFirstTimeVideoLoaded) {
    //         console.log("firsttime");
    //         isFirstTimeVideoLoaded = false;
    //     } else {
    //         console.log("Secondtime");
    //         $("#ex1").slider('setValue', [minTime * 1000, maxTime * 1000]);
    //     }
    //     $("#ex1").parent().find(".tooltip-inner").html(format(seg.start) + " - " + format(seg.end));

    // })

    // $("#segment-preview1").on("timeupdate", function () {

    //     var video = $("#segment-preview1")[0];
    //     var maxTime = $("#maxTime-store1").val();
    //     var minTime = $("#minTime-store1").val();
    //     var progress_value = (video.currentTime - minTime) * 100 / (maxTime - minTime);

    //     $("#full-duration-display-div1 .progress .progress-bar").width(progress_value + "%");
    //     $("#segchronos1").html(format(video.currentTime - minTime));
    //     if (video.currentTime >= maxTime) {
    //         video.pause();
    //         $("#preview-play1").html("Play");
    //         $("#preview-play1").attr("data-playing", "0");
    //     }
    //     if (video.currentTime <= minTime) video.currentTime = minTime;

    // });

    // $("#segment-preview1").on("play", function () {

    //     var video = $("#segment-preview1")[0];
    //     var maxTime = $("#maxTime-store1").val();
    //     var minTime = $("#minTime-store1").val();
    //     if (video.currentTime > maxTime) video.currentTime = minTime;
    // })
})

function updateTopStartTime(time) {
    var timeObj = formatTimeObj(time);
    $("#start-segment1 input:first-child").val(timeObj.hour);
    $("#start-segment1 input:nth-child(2)").val(timeObj.min);
    $("#start-segment1 input:nth-child(3)").val(timeObj.sec);
    $("#start-segment1 input:nth-child(4)").val(timeObj.msec);
}

function updateTopEndTime(time) {
    var timeObj = formatTimeObj(time);
    $("#end-segment1 input:first-child").val(timeObj.hour);
    $("#end-segment1 input:nth-child(2)").val(timeObj.min);
    $("#end-segment1 input:nth-child(3)").val(timeObj.sec);
    $("#end-segment1 input:nth-child(4)").val(timeObj.msec);
}

$("#preview-play1").click(function () {

    var values = $("#ex1").slider('getValue');
    var min = values[0];
    var max = values[1];
    var duration = parseInt($("#ex1").attr("data-slider-max"));
    var isPlaying = $(this).attr("data-playing");
    var video = $("#segment-preview1")[0];

    if (isPlaying != 1) {
        console.log(video.currentTime);
        video.play();
        video.currentTime = video.currentTime;
        isPlaying = 1;
        $(this).attr("data-playing", "1");
        $(this).html("Pause");
    } else {
        video.pause();
        $(this).html("Play");
        $(this).attr("data-playing", "0");
    }
});

$("#full-duration-display-div .progress").click(function (e) {
    var posX = $(this).offset().left, posY = $(this).offset().top;
    var x = e.pageX - posX;
    var y = e.pageY - posY;
    var width = $(this).width();
    var values = $("#ex2").slider('getValue');
    var min = values[0] / 1000;
    var max = values[1] / 1000;
    var duration = parseInt($("#ex2").attr("data-slider-max"));
    currentTimeClick = min + (x / width) * (max - min);
    $("#segment-preview")[0].currentTime = currentTimeClick;
    $("#full-duration-display-div .progress .progress-bar").width(x + "px");
});

$("#full-duration-display-div1 .progress").click(function (e) {

    var posX = $(this).offset().left, posY = $(this).offset().top;
    var x = e.pageX - posX;
    var y = e.pageY - posY;
    var width = $(this).width();
    var values = $("#ex1").slider('getValue');
    var min = values[0] / 1000;
    var max = values[1] / 1000;
    var duration = parseInt($("#ex1").attr("data-slider-max"));
    currentTimeClick = min + (x / width) * (max - min);
    $("#segment-preview1")[0].currentTime = currentTimeClick;
    $("#full-duration-display-div1 .progress .progress-bar").width(x + "px");
});

$("#seg-desc1").change(function (e) {
    var maxTime = $("#maxTime-store1").val();
    var minTime = $("#minTime-store1").val();
    var segment_index = $("#segment-index").val();
    segment_list[segment_index].start = minTime;
    segment_list[segment_index].end = maxTime;
    segment_list[segment_index].duration = maxTime - minTime;
    segment_list[segment_index].description = $("#seg-desc1").val();
});

$("#publish-btn").click(function (e) {

    var pid = $(this).attr("data-id");
    var data = {
        title: $("input[name='presentation-name']").val(),
        description: $("textarea[name='presentation-description']").val(),
        videos: video_list,
        segments: segment_list
    };

    data[$("#csrf").attr("name")] = $("#csrf").val();

    $.ajax({
        type: "POST",
        url: base_url + "pa_dashboard/ajax_save_segment/" + pid,
        processData: true,
        dataType: "json",
        data: data,
        success: function (pres_id) {
            console.log("success", pres_id);
            window.location.href = base_url + "pa_dashboard/edit_presentation/" + pid;
        }
    });
})