<div>
    <form class="kt-form" id="multi-choice-form" enctype="multipart/form-data">
        @csrf
    
        <input type="hidden" name="type" value="2">
        <div class="kt-portlet__body p-0 pb-4">
            <div class="form-group">
                <label>Question</label>
                <textarea class="form-control" id="editor1" name="question"></textarea>
            </div>
            <div class="form-group">
                <label>Order</label>
                <input type="number" id="order" class="form-control number-type" name="order" min="1" required>
            </div>
            <div class="form-group">
                <label>Marks</label>
                <input type="number" id="marks" class="form-control number-type" name="marks" min="1" required>
            </div>
            <div class="form-group">
                <label>Type of options</label>
                <select name="option_type" class="form-control" required>
                    <option selected disabled>Select options type...</option>
                    <option value="text">Text</option>
                    <option value="image">Image</option>
                </select>
            </div>
    
            <div class="form-group" id="option-text" style="display: none;">
                <div class="kt-separator kt-separator--border-dashed kt-separator--space-md m-0"></div>
                <div id="kt_repeater_1" class="mb-4">
                    <div class="form-group form-group-last row">
                        <label class="col-lg-12 col-form-label">{{ __("Option") }}:</label>
                        <div data-repeater-list="options" class="col-lg-12">
                            
                        <div data-repeater-item="" class="form-group row align-items-center" style="">
                                <div class="col-md-10">
                                    <div class="kt-form__group--inline">
                                        
                                        <div class="kt-form__control">
                                            <input type="text" name="option" maxlength="150" class="form-control" placeholder="Enter option...">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <a href="javascript:;" data-repeater-delete="" class="w-100 btn-sm btn btn-label-danger btn-bold">
                                        <i class="la la-trash-o"></i>
                                        {{ __("Delete") }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-last row">
                        {{-- <label class="col-lg-2 col-form-label"></label> --}}
                        <div class="col-lg-4">
                            <a href="javascript:;" data-repeater-create="" class="btn btn-bold btn-sm btn-label-brand">
                                <i class="la la-plus"></i> {{ __("Add") }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group" id="option-image" style="display: none;">
                <div class="kt-separator kt-separator--border-dashed kt-separator--space-md m-0"></div>
                <div id="kt_repeater_2" class="mb-4">
                    <div class="form-group form-group-last row">
                        <label class="col-lg-12 col-form-label">{{ __("Option") }}:</label>
                        <div data-repeater-list="options" class="col-lg-12">
                            
                            <div data-repeater-item="" class="form-group row align-items-center" style="">
                                <div class="col-md-10">
                                    <div class="kt-form__group--inline">
                                        
                                        <div class="kt-form__control">
                                            <div class="custom-file">
                                                <input data-type="question_options" accept="image/*" type="file" name="option" class="custom-file-input custom-file-rem" id="customFileImage">
                                                <label class="custom-file-label" for="customFileImage">{{ __("Choose file") }}</label>
                                            </div>

                                            <div class="progress mt-3" style="display: none">
                                                <input type="hidden" name="option_file_name">
                                                <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <a href="javascript:;" data-repeater-delete="" class="w-100 btn-sm btn btn-label-danger btn-bold">
                                        <i class="la la-trash-o"></i>
                                        {{ __("Delete") }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-last row">
                        {{-- <label class="col-lg-2 col-form-label"></label> --}}
                        <div class="col-lg-4">
                            <a href="javascript:;" data-repeater-create="" class="btn btn-bold btn-sm btn-label-brand">
                                <i class="la la-plus"></i> {{ __("Add") }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __("Close") }}</button>
        <button id="multi-choice-submit" form="multi-choice-form" type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
    </div>
</div>