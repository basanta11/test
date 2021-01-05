@extends('layouts.main')
@section('title','Behaviours | '. config("app.name"))
@section('content')
	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form id="delform" method="POST">
					@csrf
					@method('DELETE')

					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Delete behaviour</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						Are you sure you want to delete?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Confirm</button>
					</div>
				</form>
			</div>
		</div>
	</div>
  
	@include('layouts.partials.breadcrumbs', [
		'breadTitle' => __('Behaviours'),
		'crumbs' => [
			[
				'name' => __('Behaviours'),
				'url' => '/behaviours'
            ],
            
			[
				'name' => $section->title,
				'url' => url()->current()
			],
		]
	])
	
	<div class="kt-container  kt-grid__item kt-grid__item--fluid">
		@include('layouts.partials.flash-message')
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head kt-portlet__head--lg">
				<div class="kt-portlet__head-label">
					<span class="kt-portlet__head-icon">
						<i class="kt-font-brand flaticon2-list-2"></i>
					</span>
					<h3 class="kt-portlet__head-title">
						{{ __('Behaviours') }}
					</h3>
				</div>
				@if($is_classTeacher)
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
                            <a href="/class-teacher/behaviour-types/assign" class="btn btn-brand btn-elevate btn-icon-sm">
								<i class="la la-file-o"></i>
								{{ __("Assign") }}
							</a>
						</div>
					</div>
				</div>
				@endif
			</div>
			<div class="kt-portlet__body">
				<div class="row">

				@foreach($class_sections as $section)
				<div class="col-sm-2">

					<a href="/behaviours/{{ $section->id }}"><button class="btn btn-primary w-100">{{ $section['classroom']['title'] }} - {{ $section->title }}</button></a>
				</div>
				@endforeach
				</div>
				<!--end: Search Form -->
			</div>
			<div class="kt-portlet__body">

				<!--begin: Datatable -->
				<table class="table table-bordered table-striped px-4" id="html_table" width="100%">
					<thead>
						<tr>
							<th>Id</th>
							<th>User</th>
							@foreach($section_behaviours as $sb)
								<th>{{ $sb['behaviour_type']['title'] }}</th>
							@endforeach
						</tr>
					</thead>
					<tbody>
						@foreach($users as $u)
							<tr>
								<td>{{ $u['id'] }}</td>
								<td>{{ $u['name'] }}</td>
								
								@foreach($section_behaviours as $sb)
									<td class="marking" style="width: 100px; height:51px;"><span class="inline-edit" data-user="{{ $u['id'] }}" data-sb={{ $sb->id }} data-id={{ $u[$sb['behaviour_type']['title']] }}>{{ $u[$sb['behaviour_type']['title']] }}</span></td>
								@endforeach
							</tr>
						@endforeach
					</tbody>
				</div>

				<!--end: Datatable -->
			</table>

            <!--end:: Widgets/Blog-->
        </div>
     
    </div>

@endsection

@push('scripts')
<script>
	function bevdelete(id) {
		$('#exampleModal').modal();
		$('#delform').attr('action', '/behaviours/'+id);
	}
	
	$(document).on('click','.marking',function(e){
		if($(this).data('field')!='User' && $(this).data('field')!='Id'){
			var elem=$(this).find('.inline-edit');
			var old_value=elem.data('id');
			var user=elem.data('user');
			var sb=elem.data('sb');
			var old_render=elem.html();
			elem.removeClass('inline-edit').addClass('inline-edit-progress');
			elem.html(`<input class="inline-number" value="${old_value}" min="1"  data-user="${user}" data-sb="${sb}" data-old="${old_value}" style="width:75px" type="number">`)
			// elem.parent().attr('style','width:100px;')
			elem.children()[0].focus();
		}
	})
	$(document).on('focusout','.inline-number',function(e){
		var new_value=$(this).val();
		var old_value=$(this).data('old');
		
		var user=$(this).data('user');
		var sb=$(this).data('sb');
		var render="";
		$(this).parent().removeClass('inline-edit-progress').addClass('inline-edit');
		if($.isNumeric(new_value) && new_value>0 && new_value!=old_value){
			$.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
			$.ajax({
				url:'/behaviours/update-marks',
                dataType:'JSON',
                type:'POST',
                data:{
                    new_value: new_value,
                    user: user,
                    sb:sb,
                },
                beforeSend: function() {
                    $('#keen-spinner').show();
                    $('#main-body-div').css({ opacity: 0.5 });
                },
                success:function(data){
                    $('#keen-spinner').hide();
                    $('#main-body-div').css({ opacity: 1 });
                }
			})
			render=`<span class="inline-edit" data-id="${new_value}">${new_value}</span>`
			$(this).parent().data('id',new_value)
		}else{
			render=`<span class="inline-edit" data-id="${old_value}">${old_value}</span>`;
		}
		$(this).parent().html(render);
	})
</script>
@endpush