<script src="<?= base_url() ?>assets/dashboard/js/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/dashboard/bootstrap/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
<script type="text/javascript">
    let current_url = window.location.origin + window.location.pathname;
    var base_url = "<?= base_url() ?>";

    function get_base_url(m_url) {
        return base_url + m_url;
    }
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

<script>
    class segmentVideoPlayer {
        duration = 0;
        segment_list = [];
        videos = [];
        divID = "";
        curentIndex = 0;
        currentTime = 0;
        passDuration = 0;
        inTransit = false;
        playMode = 1;

        constructor(divID) {
            this.divID = divID;
            this.curentIndex = 0;
            var selfClass = this;

            document.querySelectorAll(".btn-play-media")[0].addEventListener("click", function() {
                selfClass.play();
            });

        }

        addSegment(seg) {

            this.segment_list.push(seg);
            this.duration += seg.duration;
            var video = document.createElement('video');
            video.src = seg.path;
            video.autoplay = false;
            video.controls = false;
            video.style.height = "auto";
            video.style.width = "100%";
            video.classList.add("mpl");
            video.style.margin = "auto";
            video.onloadeddata = function() {
                video.currentTime = seg.start;
            }
            var selfClass = this;

            video.dataset.start = seg.start;
            video.dataset.end = seg.end;
            if (this.segment_list.length == 1) {
                video.style.display = "block";
            } else {
                video.style.display = "none";
            }

            video.onplay = function() {

            }

            video.ontimeupdate = function(e) {
                var ctime = e.target.currentTime;
                console.log(ctime);
                console.log(selfClass.inTransit);
                
                if (ctime + 0.200 >= selfClass.segment_list[selfClass.curentIndex].end) {
                    if (selfClass.curentIndex < selfClass.videos.length - 1) {
                        e.target.pause();
                        e.target.currentTime = selfClass.segment_list[selfClass.curentIndex].start;
                        if(selfClass.playMode) selfClass.playNext();
                        selfClass.inTransit = true;

                        setTimeout(function() {
                            selfClass.inTransit = false;
                        }, 200)

                    } else {
                        e.target.pause();
                        selfClass.currentTime = selfClass.duration;
                        selfClass.updateChronos();
                        selfClass.updateProgress();
                    }
                } else {
                    selfClass.updateCurrentTime(e.target.currentTime - selfClass.segment_list[selfClass.curentIndex].start);
                    if (!selfClass.inTransit) {
                        selfClass.updateChronos();
                        selfClass.updateProgress();
                    }

                }
            }

            video.onended = function(e) {
                if (selfClass.curentIndex < selfClass.videos.length - 1) {
                    selfClass.playNext();
                }
            }

            this.videos.push(video);
            this.updateDuration();
            this.updateDurationText();

            document.getElementById(this.divID).appendChild(video);
        }

        play() {

            if (this.currentTime == this.duration) {
                this.currentTime = 0;
                this.passDuration = 0;
                this.curentIndex = 0;
                this.inTransit = false;
                this.updateChronos();
                this.updateProgress();
                this.play_index(this.curentIndex);
            } else {
                this.play_index(this.curentIndex);
            }
        }

        jump_to(sec) {
            var mIndex = this.get_video_index_from_sec(sec);
            this.passDuration = this.getSegmentTimePosition(mIndex);
            this.curentIndex = mIndex;
            this.currentTime = sec;
            this.inTransit = false;
            console.log(mIndex);
            for (let i = 0; i < this.videos.length; i++) {
                var v = this.videos[i];
                if(i != mIndex) {
                    v.style.display = "none";
                }
            }
            this.videos[this.curentIndex].style.display = "block";
            this.videos[this.curentIndex].currentTime = sec - this.passDuration + this.segment_list[this.curentIndex].start;
            this.videos[this.curentIndex].play();

            console.log(sec - this.passDuration);
            return true;
        }

        get_video_index_from_sec(sec) {
            var index = 0;

            for (let i = 0; i < this.segment_list.length; i++) {
                const seg = segment_list[i];
                var mPassDuration = this.getSegmentTimePosition(i);
                if (mPassDuration <= sec & sec <= mPassDuration + seg.end) {
                    index = i;
                }
            }
            return index;
        }
        continue (sec) {

        }
        play_index(index) {
            this.curentIndex = index;
            this.setPassDuration(this.getSegmentTimePosition(this.curentIndex));
            for (let i = 0; i < this.videos.length; i++) {
                var v = this.videos[i];
                if(i != index) {
                    v.style.display = "none";
                }
            }
            this.videos[this.curentIndex].style.display = "block";
            this.videos[this.curentIndex].currentTime = this.segment_list[this.curentIndex].start;
            this.videos[this.curentIndex].play();
        }
        playNext() {
            this.videos[this.curentIndex].style.display = "none";
            this.curentIndex = this.curentIndex + 1;
            this.play_index(this.curentIndex);
            this.setPassDuration(this.getSegmentTimePosition(this.curentIndex));
        }
        startPlay() {
            this.curentIndex = 0;
            this.play_index(0);
        }
        getSegmentTimePosition(index) {
            var mDuration = 0;
            for (let j = 0; j < index; j++) {
                const s = segment_list[j];
                mDuration += segment_list[j].duration;
            }
            return mDuration
        }

        updateChronos() {
            var el = document.getElementsByClassName("video-chronos")[0];
            el.innerText = this.format(this.currentTime);
        }
        updateDuration() {
            var d = 0;
            for (let i = 0; i < segment_list.length; i++) {
                const s = segment_list[i];
                d += s.duration;
            }
            this.duration = d;
        }
        updateDurationText() {
            document.getElementsByClassName("toal-duration")[0].innerHTML = this.format(this.duration);
        }
        setPassDuration(time) {
            this.passDuration = time;
        }
        updateCurrentTime(time) {
            console.log("ct" + time)
            this.currentTime = this.passDuration + time;
        }
        updateProgress() {
            document.getElementsByClassName("progress-bar")[0].style.width = ((this.currentTime / this.duration) * 100) + "%";
        }


        format(time) {
            
            var hrs = ~~(time / 3600);
            var mins = ~~((time % 3600) / 60);
            var secs = ~~time % 60;
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

    }
    let mSegmentPlayer = new segmentVideoPlayer("player-wrapper");
</script>
<script>
    jQuery(document).ready(function() {
        console.log(segment_list);
        for (let i = 0; i < segment_list.length; i++) {
            var seg = segment_list[i];
            seg.start = parseFloat(seg.start);
            seg.end = parseFloat(seg.end);
            seg.duration = parseFloat(seg.duration);
            mSegmentPlayer.addSegment(seg);
        }

        jQuery("#player-wrapper").show();

    });
    jQuery(".btn-play-media").click(function() {
        mSegmentPlayer.play();
    });
    jQuery(".segment-link").click(function() {
        
        var start = parseFloat(jQuery(this).attr("data-start"));
        mSegmentPlayer.playMode = 0;
        jQuery("#playMode").prop("checked",true);
        mSegmentPlayer.jump_to(start);
    });
    jQuery("#playMode").change(function(){
        if (jQuery(this).is(':checked')) {
            mSegmentPlayer.playMode = 0;
        } else {
            mSegmentPlayer.playMode = 1;
        }
    });
</script>
</body>

</html>