<div class="container-fluid">
    <div class="row">
        <h3 class="col-md-12">Add New Video</h3>
    </div>
  <div class="row">
    <?php if(isset($error)) echo $error;?>

    <?php 
    $attributes = ['class' => 'col-md-4'];
    echo form_open_multipart('Pa_dashboard/do_upload',$attributes);?>
    <div class="form-group">
      <label>Title</label>
      <input type="text" name="title" class="form-control" placeholder="Video title">
    </div>
    <div class="form-group">
      <label for="exampleFormControlInput1">Description</label>
      <textarea name="description" class="form-control" rows="5" ></textarea>
    </div>
    <div class="form-group">
      <label for="exampleFormControlInput1">Select file</label>
      <input type="file" name="userfile" id="videoFileInput" class="form-control">
    </div>
    <div class="form-group">
      <input type="submit" value="Upload" class="form-control btn btn-primary"/>
      <input type="hidden" name="duration" id="duration">
      <input type="hidden" name="thumbnail-base64" id="thumbnail-base64">
      <input type="hidden" name="width" id="video-width">
      <input type="hidden" name="height" id="video-height">
    </div>
    </form>
    <div class="col-md-4">
      <label>Previews</label>
      <div class="debug" style="width:100%;"></div>      
      <div class="debug2" style="width:100%;"></div>
    </div>
  </div>
</div>