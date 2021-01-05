<div>
    <form class="kt-form mt-2" id="image-upload-form" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Question</label>
            <textarea class="form-control" id="editor1" name="question"></textarea>
        </div>
        <div class="form-group">
            <label>Order</label>
            <input type="number" id="order" min="1" class="form-control number-type" name="order" required>
        </div>
        <div class="form-group">
            <label>Marks</label>
            <input type="number" id="marks" min="1" class="form-control number-type" name="marks" required>
        </div>
        <div class="form-group">
            <label>Note:</label>
            <textarea class="form-control" id="uploadImageNote" name="note"></textarea>
        </div>
        
    </form>

    <label>Image files</label>
    <form action="" class="dropzone dropzone-default dz-clickable" method="POST" enctype="multipart/form-data" id="kt_dropzone">
        @csrf
        
        <div class="dropzone-msg dz-message needsclick">
            <h3 class="dropzone-msg-title">Drop files here or click to upload.</h3>
            <span class="dropzone-msg-desc">Only image</span>
        </div>
    </form>
   
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __("Close") }}</button>
        <button id="image-upload" form="image-upload-form" type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
    </div>
</div>
