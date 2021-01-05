@extends('layouts.main')
@section('title','Exams | '. config("app.name"))
@section('exams','kt-menu__item--open')
@section('exams','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
    'breadTitle' => __('Exams'),
    'crumbs' => [
        [
            'name' => __('Exams'),
            'url' => '/exam-teachers'
        ],
        [
            'name' => __('View Exam'),
            'url' => '/exam-teachers/'.$question->set->exam_id
        ],
        [
            'name' => __('View Set'),
            'url' => '/sets/'.$question->set_id
        ],
        [
            'name' => __('Edit Question'),
            'url' => url()->current()
        ],
    ]
])

<div class="kt-container  kt-grid__item kt-grid__item--fluid">
    @include('layouts.partials.flash-message')
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				<h3 class="kt-portlet__head-title">
					{{ __("Edit Question") }}
				</h3>
			</div>
			
		</div>
        <div class="kt-portlet__body">
            <form class="kt-form mt-2" id="paragraph-form" action="/update-paragraph/{{ $question->id }}" method="post"  enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label>Question</label>
                    <textarea class="form-control" id="editor1" name="question">{{ $question->title }}</textarea>
                </div>
                <div class="form-group">
                    <label>Order</label>
                    <input type="number" id="order" min="1" class="form-control number-type" name="order" value="{{ $question->order }}" required min="1">
                </div>
                <div class="form-group">
                    <label>Marks</label>
                    <input type="number" id="marks" class="form-control number-type" name="marks" value="{{ $question->marks }}" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" id="formNote" name="note">{{ $question->note }}</textarea>
                </div>
                
                
                
                
                <div class="kt-portlet__foot px-0 pt-4">
                    <div class="kt-form__actions">
                        <button i  type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
                        <a href="/sets/{{ $question->set_id }}"><button type="button" class="btn btn-secondary">{{ __("Cancel") }}</button></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
@push('scripts')

<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script>
    $(document).ready(function(){
      
        CKEDITOR.replace( 'editor1' ,{ removeButtons: 'Table' } );
        CKEDITOR.replace( 'formNote' );

       
        $('#paragraph-form').on('submit', function(e) {
            e.preventDefault();
            KTApp.blockPage();
            var form=$(this);
        
            form.validate({
                focusInvalid: true,
                rules: {
                    question: {
                        required: true
                    },
                    marks: {
                        required: true,
                        number: true,
                        min: 1,
                    },
                    note:{
                        required: true,
                    }            
                    
                }
            });
            
            if (!form.valid()) {
                e.preventDefault();
            KTApp.unblockPage();
                return false;
            }
        
            $(this).unbind('submit').submit();
            return true;
        });    
    })
    const question_id={{ $question->id }};
    
    if($('input[name="marks"]').length>0){
        const url = "@php echo url('/api/question/get-mark/'.$question->set_id) @endphp";
        
        $.ajax({
            url:url,
            dataType:'JSON',
            type:'GET',
            data:{
                question_id:question_id,
            },
            success:function(data){
                $('input[name="marks"]').parent().find('label').append(`<span> <abbr title="Reamining out of total marks">${data.remains}/${data.full}</abbr></span>`);
            }
        })
    }
     // order check
     $(document).on('focus','input[name="order"]',function(e){
            var elem=$(this)
            remErrCustom(elem)
        })
        $(document).on('focusout','input[name="order"]',function(e){
            var elem=$(this);
            var order=elem.val();
            var set_id={{ $question->set_id }};
            $.ajax({
                url:'/api/question/order-exists/',
                dataType:'JSON',
                type:'GET',
                data:{
                    order:order,
                    set_id:set_id,
                    question_id:question_id,
                },
                success:function(data){
                    if(data.status){
                        makeErrCustom(elem,'Order already taken')
                    }
                }
            })
            
        })

        // marking 
        $(document).on('focus','input[name="marks"]',function(e){
            var elem=$(this)
            remErrCustom(elem)
        })
        $(document).on('focusout','input[name="marks"]',function(e){
            var elem=$(this);
            var marks=elem.val();
            var set_id={{ $question->set_id }};
            $.ajax({
                url:'/api/question/mark-valid/',
                dataType:'JSON',
                type:'GET',
                data:{
                    marks:marks,
                    set_id:set_id,
                    question_id:question_id,
                },
                success:function(data){
                    if(data.status){
                        makeErrCustom(elem,'Marks given is greater than remaining marks for this set.');
                    }
                }
            })
            
        })

</script>
@endpush