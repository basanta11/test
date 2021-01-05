@extends('layouts.main')
@section('title','Tests | '. config("app.name"))
@section('assigned-courses','kt-menu__item--open')
@section('assigned-courses','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
	'breadTitle' => __("Tests"),
        'crumbs' => [
            [
                'name' => !auth()->user()->hasRole('Teacher') ? __("Courses") : __("Assigned Courses"),
                'url' => !auth()->user()->hasRole('Teacher') ? '/courses' : '/assigned-courses'
            ],
            [
                'name' => $lesson['course']['title'],
                'url' => '/assigned-courses/'.$lesson['course']['id'],
			],
    
            [
                'name' => $lesson['title'],
                'url' => '/lessons/'.$lesson['id'],
            ],
            [
                'name' => 'Tests',
                'url' => '/tests/'.$lesson['id'],
            ],

            [
                'name' => 'Edit',
                'url' => url()->current(),
            ],
        ]
    ])
    <div class="modal fade" id="set_del_modal" tabindex="-1" role="dialog" aria-labelledby="set_del_modal_header" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="set_del_modal_header">Delete Set</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this set?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="set_del_modal_submit" type="button" data-set-id="" class="btn btn-primary">Proceed</button>
                </div>
            </div>
        </div>
    </div>
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
    @include('layouts.partials.flash-message')
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				<h3 class="kt-portlet__head-title">
					Edit Test
				</h3>
			</div>
			
		</div>
        <div class="kt-portlet__body">
            <form class="kt-form" method="POST" action="/tests/{{ $test->id }}">
                @csrf
                @method('PATCH')
                <div class="kt-portlet__body p-0 pb-4">
                    <div class="form-group">
                        <label>{{ __("Title") }}</label>
                        <input type="text" name="title" class="form-control" value="{{ $test->title }}" placeholder="Enter name">
                    </div>
                    <div class="form-group">
                        <label>{{ __("Type") }}</label>
                        <select name="type" class="form-control">
                            <option selected disabled>Select type...</option>
                            <option {{ $test->type==0 ? 'selected' : '' }} value="0">Pre Test</option>
                            <option {{ $test->type==1 ? 'selected' : '' }} value="1">Post Test</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __("Test Start") }}</label>
                        <input type="text" name="test_start" class="form-control" id="kt_datepicker_1" readonly="" value="{{ $test->test_start }}" placeholder="Select date">
                    </div>
                    <div class="form-group">
                        <label>{{ __("Duration") }} <span class="text-warning">Please enter in minutes</span></label>
                    <input type="number" name="duration" min="1" class="form-control" placeholder="Enter test duration in minutes" value="{{ $test->duration }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __("Full Marks") }}</label>
                    <input type="number" name="full_marks" min="1" class="form-control" placeholder="Enter full marks" value="{{ $test->full_marks }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __("Pass Marks") }}</label>
                    <input type="number" min="1" id="pmarks" name="pass_marks" class="form-control" placeholder="Enter pass marks" value="{{ $test->pass_marks }}">
                    </div>
                   
                    <div id="kt_repeater_1" class="mb-4">
                        <div class="form-group form-group-last row">
                            <label class="col-lg-12 col-form-label">{{ __("Sets") }}:</label>
                            @foreach($sets as $key=>$set)
                                <div class="col-lg-12" id="set-div-{{ $set->id }}" data-id="{{ $set->id }}">
                                    <div class="form-group row align-items-center">
                                        <div class="col-md-10">
                                            <div class="kt-form__group--inline">
                                                <div class="kt-form__control">
                                                    <input type="text" name="oldset[{{$set->id}}]" maxlength="150" class="form-control" value="{{ $set->title }}">
                                                </div>
                                            </div>
                                        </div>
                                        @if($key!=0)
                                        <div class="col-md-2">
                                            <a href="javascript:;" class="w-100 btn-sm btn btn-label-danger btn-bold del-btn" data-parent="set-div-{{ $set->id }}" data-toggle="modal" data-target="#set_del_modal">
                                                <i class="la la-trash-o"></i>
                                                {{ __("Delete") }}
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            <div data-repeater-list="sets" class="col-lg-12">
                                <div data-repeater-item="" class="form-group row align-items-center" style="">
                                    <div class="col-md-10">
                                        <div class="kt-form__group--inline">
                                            
                                            <div class="kt-form__control">
                                                <input type="text" name="sets" maxlength="150" class="form-control" placeholder="Enter new set name..." >
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
                            <div class="col-lg-4">
                                <a href="javascript:;" data-repeater-create="" class="btn btn-bold btn-sm btn-label-brand">
                                    <i class="la la-plus"></i> {{ __("Add") }}
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="kt-portlet__foot px-0 pt-4">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
                    <a href="/tests/{{ $lesson->id }}"><button type="button" class="btn btn-secondary">{{ __("Cancel") }}</button></a>
                    </div>
                </div>
            </form>
        </div>
     
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.js"></script>

<script>
    $('#kt_datepicker_1').datetimepicker({
        format: 'yyyy-mm-dd hh:ii',
        autoclose: true,
        startDate: '+0d'
    });

    $('.del-btn').on('click', function() {
        const parent = $(this).attr('data-parent');
        const setId = $('#'+parent).attr('data-id');

        $('#set_del_modal_submit').attr('data-set-id', setId);
    });

    $('#set_del_modal_submit').on('click', function() {
        const setId = $(this).attr('data-set-id');

        $.ajax({
            url: '/api/test-sets/' + setId,
            type: 'get',
            dataType: 'json',
            beforeSend: function() {
                $('#keen-spinner').show();
                $('#main-body-div').css({ opacity: 0.5 });
            },
            success: function(res) {
                $('#set-div-'+setId).remove();
                $('#set_del_modal').modal('hide');
                $('#keen-spinner').hide();
                $('#main-body-div').css({ opacity: 1 });
            }
        });
    });
    $('select[name="type"]').selectpicker();
    $('#kt_repeater_1').repeater({
        initEmpty: false,
        // isFirstItemUndeletable: true,
        defaultValues: {
            'text-input': 'foo',
        },
        show: function () {
            $(this).slideDown();
        },
        hide: function (deleteElement) {
            $(this).slideUp(deleteElement);
        }   
    });
  

    $('.kt-form').on('submit',function(e){
        var form=$(this);
        
        KTApp.blockPage();
        $.validator.addMethod("greaterThan",
            function (value, element, param) {
                var $otherElement = $(param);
                return parseInt(value, 10) > parseInt($otherElement.val(), 10);
            });
        
        form.validate({
            focusInvalid: true,
            rules: {
                title: {
                    required: true
                },
                full_marks: {
                    required: true,
                    number: true,
                    min: 1,
                    greaterThan: "#pmarks"
                },
                pass_marks: {
                    required: true,
                    number: true,
                    min: 1
                },
                test_start: {
                    required: true
                },
                duration: {
                    required: true,
                    digits: true
                },
                type: {
                    required: true
                },
                sets: {
                    required: true
                },
            }
        });
        
        if (!form.valid()) {
            KTApp.unblockPage();
            e.preventDefault();
            return false;
        }

        return true;

    })
</script>
@endpush