<div>
    <form class="kt-form mt-2" id="paragraph-form" enctype="multipart/form-data">
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
            <input type="number" min="1" id="marks" class="form-control number-type" name="marks" required>
        </div>
        <div class="form-group">
            <label>Paragraph</label>
            <textarea class="form-control" id="formNote" name="note"></textarea>
        </div>
        
    </form>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __("Close") }}</button>
        <button id="paragraph" form="paragraph-form" type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
    </div>
</div>
