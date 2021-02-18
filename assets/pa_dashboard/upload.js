var video_file_input,
  video_url_button,
  image_file_input,
  debug;
var video;

function loadFileOrBlob(file, callback) {
  var reader = new FileReader();
  reader.onload = function (event) {
    var result = event.target.result;
    callback(result);
  }
  reader.readAsDataURL(file);
}

function createVideo(url) {
  video = document.createElement("video");
  video.src = url;
  video.autoplay = true;
  video.controls = true;
  video.addEventListener('loadedmetadata', function () {
    var minutes = parseInt(video.duration / 60, 10);
    var seconds = parseInt(video.duration % 60);
    var secondsnew = ("0" + seconds).slice(-2);

    console.log(minutes + ":" + secondsnew);
    document.getElementById("duration").value = video.duration;
    document.getElementById("video-width").value = video.videoWidth;
    document.getElementById("video-height").value = video.videoHeight;

    video.style.width = video.videoWidth + "px";
    video.style.maxWidth = "100%";
    video.style.height = "auto";

    jQuery("#vid-upload-preview").collapse();

  });

  video.addEventListener('canplay', function () {
    let w = video.videoWidth;
    let h = video.videoHeight;
    let canvas = document.createElement('canvas');
    canvas.width = w;
    canvas.height = h;
    let neww = 0;
    let newh = 0;
    console.log("width : " + w);
    console.log("height : " + h)
    let ctx = canvas.getContext('2d');

    //crop thumbnails to 16:9 ratio
    if ((w / h) >= 1.77) {
      newh = h;
      neww = newh * 1.77;
      ctx.drawImage(video, Math.round((w - neww) / 2), 0, neww, newh);
    } else {
      neww = w;
      newh = neww / 1.77;
      ctx.drawImage(video, 0, Math.round((h - newh) / 2), neww, newh);
    }
    console.log(w / h);
    console.log("newwidth : " + neww);
    console.log("newheight : " + newh)

    let dataURI = canvas.toDataURL();

    document.getElementById("thumbnail-base64").value = dataURI;

  });

  debug.appendChild(video);
}

function isSuccessStatusCode(code) {
  var test = code.toString();
  var regex = /^(200|201|202|203|204|205|206)$/;
  return regex.test(test);
}

function init() {
  video_file_input = document.getElementById('videoFileInput');

  debug = document.querySelector('.debug');

  video_file_input.addEventListener('change', function () {
    var file = this.files[0];
    loadFileOrBlob(file, createVideo);
  });

}

document.addEventListener('DOMContentLoaded', init);

jQuery(document).on('click', '.video-card .card-body', function () {

  var vid = jQuery(this).attr('data-vid');
  jQuery.ajax({
    url: "ajax_getVideobyId/" + vid,
    type: "GET",
    dataType: "json",
    success: function (videoData) {

      jQuery(".c-video-title").empty();
      jQuery(".c-video-description").empty();
      jQuery(".c-video-duration").empty();
      jQuery("#player").empty();

      var video = document.createElement("video");
      video.src = base_url + "/" + videoData.full_path;
      video.autoplay = false;
      video.controls = true;
      video.style.width = "100%";
      video.style.height = "auto";
      video.oncanplay = function () {
        jQuery("#player").append(video);
        jQuery("#main-player").collapse();
      }
      jQuery(".c-video-title").html(videoData.title);
      jQuery(".c-video-description").html(videoData.description);
      jQuery(".c-video-duration").html(videoData.uploaded_on);

    }
  })
});

jQuery("#sbmt-btn").click(function (event) {

  event.preventDefault();
  var thisFormData = new FormData(jQuery("#uploadForm")[0]);
  $('#upload-progress-bar').collapse();

  jQuery.ajax({
    type: "POST",
    url: base_url + "pa_dashboard/do_upload",
    data: thisFormData,
    processData: false,
    contentType: false,
    dataType: "text",
    xhr: function () {
      var xhr = $.ajaxSettings.xhr();
      xhr.upload.onprogress = function (e) {
        // For uploads
        if (e.lengthComputable) {
          var percent = Math.round((e.loaded / e.total) * 100);
          $('.progress-bar').css('width', percent + '%').attr('aria-valuenow', percent);
          $('.progress-bar').html("Uploading " + percent + " % complete");
          if (percent == 100) {
            $('.progress-bar').html("Proccessing");
          }
        }
      };
      return xhr;
    },
    success: function (res) {

      jQuery("#video-grid").prepend(jQuery(res)).hide().fadeIn(1500);

      location.reload();
    },
    complete: function () {
      jQuery("#uploadForm")[0].reset();
      $('.progress-bar').removeClass('progress-bar-animated');
      $('#upload-progress-bar').collapse("hide");


      $('#upload-modal').modal('toggle');
      video.pause();
      video.currentTime = 0;
      jQuery("#vid-upload-preview").collapse("hide");
      jQuery("#vid-upload-preview video").empty();

      $("#uploaded-video-alert").fadeTo(10000, 500).slideUp(500, function () {
        $("#uploaded-video-alert").slideUp(500);
      });
    }
  });
});

jQuery(document).on('click', ".delete-vid-btn", function () {
  var vid = jQuery(this).attr("data-vid");
  jQuery("#delete-video-id").val(vid);
  jQuery("#deleteVideoModal").modal();
});

jQuery(".btn-delete-confirm").click(function () {
  var vid = jQuery("#delete-video-id").val();
  jQuery.ajax({
    type: "GET",
    url: base_url + "pa_dashboard/ajax_deleteVideo/" + vid,
    success: function (res) {

      jQuery(".vid[data-vid='" + vid + "']").fadeOut(1000, function () {
        jQuery(this).remove();
      })
    }
  });

  jQuery("#deleteVideoModal").modal("hide");
});

jQuery(document).on('click', '.edit-vid-btn', function () {
  var vid = jQuery(this).attr("data-vid");
  jQuery("#edit-video-id").val(vid);

  jQuery.ajax({
    type: "GET",
    url: base_url + "pa_dashboard/ajax_getVideobyId/" + vid,
    dataType: "json",
    success: function (res) {

      jQuery("#edit-video-title").val(res.title);
      jQuery("#edit-video-description").val(res.description);
      jQuery("#editVideoModal").modal();
    }
  });
});

jQuery(".btn-edit-confirm").click(function () {
  var vid = jQuery("#edit-video-id").val();
  var thisFormData = new FormData(jQuery("#edit-video-form")[0]);
  jQuery.ajax({
    type: "POST",
    url: base_url + "pa_dashboard/ajax_editVideo/" + vid,
    data: thisFormData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (res) {

      //need update the current html text : title , description , token ....
      csrfName = res.csrfName;
      csrfHash = res.csrfHash;
      jQuery(".csrftoken").attr("name", csrfName);
      jQuery(".csrftoken").val(csrfHash);

      jQuery(".vid[data-vid='" + vid + "']").html(jQuery(res.success));
      jQuery("#editVideoModal").modal("hide");
    }
  })
});
