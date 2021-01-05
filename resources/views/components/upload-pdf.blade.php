<div>
    <form class="kt-form" id="upload-pdf-form" enctype="multipart/form-data">
        @csrf
        <div class="kt-form__control">
            <label>Pdf file:</label>
            <div class="custom-file">
                <input data-type="attachments" accept="application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document" type="file" name="pdf" class="custom-file-input custom-file-rem" id="customFileImage">
                <label class="custom-file-label" for="customFileImage">{{ __("Choose file") }}</label>
            </div>
            <div class="progress mt-3" style="display: none">
                <input type="hidden" name="option_file_name">
                <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
        </div>
    </form>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __("Close") }}</button>
        <button id="upload-pdf-submit" form="upload-pdf-form" type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
    </div>
</div>
