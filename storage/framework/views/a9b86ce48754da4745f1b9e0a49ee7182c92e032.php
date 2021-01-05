<?php $__env->startSection('title','Behaviours | '. config("app.name")); ?>
<?php $__env->startSection('content'); ?>
	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form id="delform" method="POST">
					<?php echo csrf_field(); ?>
					<?php echo method_field('DELETE'); ?>

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
  
	<?php echo $__env->make('layouts.partials.breadcrumbs', [
		'breadTitle' => __('Behaviour Types'),
		'crumbs' => [
			[
				'name' => __('Behaviour Types'),
				'url' => '/behaviour-types'
			],
		]
	], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	
	<div class="kt-container  kt-grid__item kt-grid__item--fluid">
		<?php echo $__env->make('layouts.partials.flash-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head kt-portlet__head--lg">
				<div class="kt-portlet__head-label">
					<span class="kt-portlet__head-icon">
						<i class="kt-font-brand flaticon2-list-2"></i>
					</span>
					<h3 class="kt-portlet__head-title">
						<?php echo e(__('Behaviours')); ?>

					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
							
							<a href="/behaviour-types/create" class="btn btn-brand btn-elevate btn-icon-sm">
								<i class="la la-plus"></i>
								<?php echo e(__("Add")); ?>

                            </a>
                            <a href="/behaviour-types/assign" class="btn btn-brand btn-elevate btn-icon-sm">
								<i class="la la-file-o"></i>
								<?php echo e(__("Assign")); ?>

							</a>
						</div>
					</div>
				</div>
			</div>
			<div class="kt-portlet__body">

				<!--begin: Search Form -->
				<div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-b-10">
					<div class="row align-items-center">
						<div class="col-xl-12 order-2 order-xl-1">
							<div class="row align-items-center">
								<div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
									<div class="kt-input-icon kt-input-icon--left">
										<input type="text" class="form-control" placeholder="<?php echo e(__('Search')); ?>..." id="generalSearch">
										<span class="kt-input-icon__icon kt-input-icon__icon--left">
											<span><i class="la la-search"></i></span>
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!--end: Search Form -->
			</div>
			<div class="kt-portlet__body kt-portlet__body--fit">

				<!--begin: Datatable -->
				<div class="kt-datatable" >
					

				<!--end: Datatable -->
                </div>

            <!--end:: Widgets/Blog-->
        </div>
     
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
	function bevdelete(id) {
		$('#exampleModal').modal();
		$('#delform').attr('action', '/behaviours/'+id);
	}
	
	$(document).ready(function() {

		var dataJSONArray = <?php echo json_encode($behaviourTypes, 15, 512) ?>;
		
	// 	var column=`
	// 		[
	// 			{
	// 				field: 'student',
	// 				title: '<?php echo e(__("Student")); ?>'
	// 			},
	// 			{
	// 				field: 'classroom',
	// 				title: '<?php echo e(__("Classroom")); ?>',
	// 				template:function(data){
	// 					return '<span class="inline-edit" data-id='"+data.classroom+"'>'"+data.classroom+"'</span>'
	// 				}
	// 			},
	// 			{
	// 				field: 'behaviour',
	// 				title: '<?php echo e(__("Behaviour")); ?>'
	// 			},
	// 			{
	// 				field: 'marks',
	// 				title: '<?php echo e(__("Marks")); ?>'
	// 			},
	// 			{
	// 				field:'id',title:'ID',width:0,
	// 				visible:false,
	// 			}
	// 		]`
		var datatable = $('.kt-datatable').KTDatatable({
			// datasource definition
			data: { 
				type: 'local',
				source: dataJSONArray,
				pageSize: 10,
			},

			// layout definition
			layout: {
				scroll: false, // enable/disable datatable scroll both horizontal and vertical when needed.
				footer: false // display/hide footer
			},

			// column sorting
			sortable: true,

			pagination: true,

			search: {
				input: $('#generalSearch'),
			},

			// columns definition
			columns: [
				{
					field: 'id',
					title: '<?php echo e(__("Id")); ?>'
				},
				{
					field: 'title',
					title: '<?php echo e(__("Title")); ?>',
                },
                {
                    field: 'Actions',
                    title: '<?php echo e(__("Actions")); ?>',
                    sortable: false,
                    width: 110,
                    overflow: 'visible',
                    autoHide: false,
                    template: function(data) {
                        return '\
                        <div class="dropdown">\
                            <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">\
                                <i class="la la-ellipsis-h"></i>\
                            </a>\
                            <div class="dropdown-menu dropdown-menu-right">\
                                <a class="dropdown-item" href="/behaviour-types/'+data.id+'/edit"><i class="la la-edit"></i> <?php echo e(__("Edit Details")); ?></a>\
                                <a data-id="'+data.id+'" class="dropdown-item item-delete" href=""><i class="la la-trash"></i> Delete</a>\
                            </div>\
                        </div>\
                    ';
                    },
                }
			],
		});
	});

    $(document).on('click','.item-delete',function(e){
		e.preventDefault();
		var id=$(this).data('id');	
		link=`/behaviour-types/${id}`;
		message="Do you really want to delete this behaviour type?";
		header="Delete behaviour type";
		makeModal(link,message,header,"DELETE");

	})
	// $(document).on('click','.kt-datatable__cell',function(e){
	// 	if($(this).data('field')!='title'){
	// 		var elem=$(this).find('.inline-edit');
	// 		var old_value=elem.data('id');
	// 		var old_render=elem.html();
	// 		elem.removeClass('inline-edit').addClass('inline-edit-progress');
	// 		elem.html(`<input class="inline-number" value="${old_value}" data-old="${old_value}"" style="width:100%" type="number">`)
	// 		elem.children()[0].focus();
	// 	}
	// })
	// $(document).on('focusout','.inline-number',function(e){
	// 	var new_value=$(this).val();
	// 	var old_value=$(this).data('old');
		
	// 	var render="";
	// 	$(this).parent().removeClass('inline-edit-progress').addClass('inline-edit');
	// 	if($.isNumeric(new_value)){
	// 		render=`<span class="inline-edit" data-id="${new_value}">${new_value}</span>`
	// 		$(this).parent().data('id',new_value)
	// 	}else{
	// 		render=`<span class="inline-edit" data-id="${old_value}">${old_value}</span>`;
	// 	}
	// 	$(this).parent().html(render);
	// })
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\_CODING\_develop\tinel\resources\views/admin/behaviour-types/index.blade.php ENDPATH**/ ?>