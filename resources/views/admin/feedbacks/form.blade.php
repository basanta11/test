<div class="row">
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <div class="tab-content">

                    <div class="active tab-pane" id="Description">

                        <div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
                            <label for="title" class="control-label">{{ __('Title') }}</label>
                            <input class="form-control" name="title" type="text" id="title" value="{{ isset($feedback->title) ? $feedback->title : ''}}" required>
                            {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
                        </div>
                        <div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
                            <label for="description" class="control-label">{{ __('Description') }}</label>
                            <textarea class="form-control" rows="5" name="description" type="textarea" id="newa" >{{ isset($feedback->description) ? $feedback->description : ''}}</textarea>
                            {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
                        </div>
                        <div class="form-group {{ $errors->has('image') ? 'has-error' : ''}}">
                            <label for="image" class="control-label">{{ __('Image') }}</label>
                            {{-- <input class="form-control" name="photo" type="file" id="image"> --}}
                            <div class="custom-file">
                                <input accept="image/*" type="file" name="photo" class="custom-file-input" id="customFile">
                                <label class="custom-file-label" for="customFile">{{ __("Choose file") }}</label>
                            </div>
                            {!! $errors->first('image', '<p class="help-block">:message</p>') !!}

                            @if(isset($feedback->image))
                                @if ($feedback->image)
                                <div class="mt-3">
                                    <img class="img-fluid" src="{{ Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/feedbacks/'.$feedback->image) }}" alt="">
                                </div>
                                @endif
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-primary card-outline">
            <div class="card-footer">
                <button type="submit" class="btn btn-primary btn-lg btn-block">{{ __('Submit') }}</button>
                <br>
                <a href="/feedbacks"><button type="button" class="btn btn-secondary btn-lg btn-block">{{ __("Cancel") }}</button></a>
            </div>
        </div>
    </div>

</div>