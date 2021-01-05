<?php $__env->startSection('title','Dashboard | '. config("app.name")); ?>
<?php $__env->startSection('dashboard','kt-menu__item--open'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.partials.breadcrumbs', [
        'breadTitle' => __('Dashboard'),
        'crumbs' => [
            [
                'name' => __('Dashboard'),
                'url' => '/app'
            ],
        ]
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="modal fade" id="home_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form id="modal_form" action="" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <div id="modal_form_div">
                    </div>
                <form>
                <p></p>
            </div>
            <div class="modal-footer">
                
            </div>
        </div>
    </div>
</div>
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
    
	<?php echo $__env->make('layouts.partials.flash-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!--Begin::Dashboard 3-->

    <!--Begin::Row-->
    <div class="row">
        
        <div class="col-xl-8">
            <div class="kt-portlet kt-portlet--height-fluid kt-portlet--mobile ">
                <div class="kt-portlet__head kt-portlet__head--lg kt-portlet__head--noborder kt-portlet__head--break-sm">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            <?php echo e(__("Teachers")); ?>

                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body kt-portlet__body--fit">

                    <!--begin: Datatable -->
                    <div class="kt-datatable" id="kt_datatable_teachers"></div>
                    <!--end: Datatable -->
                </div>
            </div>
        </div>

        <div class="col-xl-4">

            <!--begin:: Packages-->
            <div class="kt-portlet kt-portlet--skin-solid kt-portlet--solid-warning kt-portlet--head-lg kt-portlet--head-overlay kt-portlet--height-fluid">
                <div class="kt-portlet__head kt-portlet__head--noborder">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title kt-font-light">
                            <?php echo e(__("Latest Courses")); ?>

                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body kt-margin-t-0 kt-padding-t-0">

                    <!--begin::Widget 29-->
                    <div class="kt-widget29">

                        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="kt-widget29__content">
                                <h3 class="kt-widget29__title"><?php echo e($course['title']); ?></h3>
                                <div class="kt-widget29__item">
                                    <div class="kt-widget29__info">
                                        <span class="kt-widget29__subtitle"><?php echo e(__("Class")); ?></span>
                                        <span class="kt-widget29__stats kt-font-success"><?php echo e($course['classroom']); ?></span>
                                    </div>
                                    <div class="kt-widget29__info">
                                        <span class="kt-widget29__subtitle"><?php echo e(__("Sections")); ?></span>
                                        <span class="kt-widget29__stats kt-font-brand"><?php echo e($course['sections']); ?></span>
                                    </div>
                                    <div class="kt-widget29__info">
                                        <span class="kt-widget29__subtitle"><?php echo e(__("Credit Hrs")); ?></span>
                                        <span class="kt-widget29__stats kt-font-danger"><?php echo e($course['credit_hours']); ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <div class="kt-widget29__actions kt-align-right">
                            <a href="/courses" class="btn btn-brand"><?php echo e(__("View all courses")); ?></a>
                        </div>
                    </div>
                        
                </div>
              
            </div>

            <!--end:: Packages-->
        </div>

        <?php if( tenant()->plan == 'large' ): ?>    
            <div class="col-xl-4">

                <!--begin:: Widgets/Authors Profit-->
                <div class="kt-portlet kt-portlet--bordered-semi kt-portlet--height-fluid">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title kt-font-success">
                                <?php echo e(__("Upcoming Events")); ?>

                            </h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-widget4">
                            
                            <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="kt-widget4__item">
                                    <div class="kt-widget4__pic kt-widget4__pic--logo">
                                        <i class="flaticon-event-calendar-symbol"></i>
                                    </div>
                                    <div class="kt-widget4__info">
                                        <a class="kt-widget4__title disabled-link">
                                            <?php echo e($event->title); ?>

                                        </a>
                                    </div>
                                    <span class="kt-widget4__number kt-font-brand"><?php echo e(date('jS M, Y g:i a', strtotime($event->event_date))); ?></span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            
                        </div>
                    </div>
                </div>

                <!--end:: Widgets/Authors Profit-->
            </div>
        <?php endif; ?>
        
        <?php if( auth()->user()->hasRole('Principal') ): ?>
            <div class="col-xl-8">
                <div class="kt-portlet kt-portlet--height-fluid kt-portlet--mobile ">
                    <div class="kt-portlet__head kt-portlet__head--lg kt-portlet__head--noborder kt-portlet__head--break-sm">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                <?php echo e(__("Administrators")); ?>

                            </h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body kt-portlet__body--fit">

                        <!--begin: Datatable -->
                        <div class="kt-datatable" id="kt_datatable_admins"></div>
                        <!--end: Datatable -->
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!--End::Row-->

    <!--End::Dashboard 3-->
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
 <!--begin::Page Vendors(used by this page) -->
 <script src="<?php echo e(global_asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js')); ?>" type="text/javascript"></script>
 <script src="//maps.google.com/maps/api/js?key=AIzaSyBTGnKT7dt597vo9QgeQ7BFhvSRP4eiMSM" type="text/javascript"></script>
 <script src="<?php echo e(global_asset('assets/plugins/custom/gmaps/gmaps.js')); ?>" type="text/javascript"></script>

 <!--end::Page Vendors -->

 <!--begin::Page Scripts(used by this page) -->
 <script src="<?php echo e(global_asset('assets/js/pages/dashboard.js')); ?>" type="text/javascript"></script>


 <script src="<?php echo e(global_asset('assets/plugins/colorPicker/dist/vanilla-picker.min.js')); ?>" type="text/javascript"></script>
 
 <!--end::Page Scripts -->
 <script>
    $(document).ready(function(){
        <?php if(!auth()->user()->status): ?>
            link=`/change-password`;
            message=`<?php echo e(__("It looks like you haven't updated your password after first login.")); ?><br> <?php echo e(__("Please click on proceed to update your password.")); ?>`;
            header=`<?php echo e(__('Update Password')); ?>`;
        

            makeModal(link,message,header,"GET");
        <?php endif; ?>

        <?php if(auth()->user()->hasRole('Principal')): ?>
            let dataJSONArrayA = <?php echo json_encode($administrators, 15, 512) ?>;
            var optionsA = {
                // datasource definition
                data: {
                    type: 'local',
                    source: dataJSONArrayA,

                    pageSize: 5, // display 20 records per page
                    // serverPaging: true,
                    // serverFiltering: true,
                    // serverSorting: true,
                },

                // layout definition
                layout: {
                    scroll: true, // enable/disable datatable scroll both horizontal and vertical when needed.
                    height: 550, // datatable's body's fixed height
                    footer: false, // display/hide footer
                },

                // column sorting
                sortable: true,

                pagination: true,

                search: {
                    input: $('#generalSearch'),
                },


                rows:{
                    beforeTemplate: function (row, data, index) {
                        if(data.status==10){
                            row.addClass('cutsom-bg-warning-light')
                            row.data('status',data.status)
                        }
                        if(data.status==12){
                            row.addClass('cutsom-bg-danger-light')
                            row.data('status',data.status)
                        }
                    }
                },
            
                // columns definition
                columns: [
                    {field:'id',title:'ID',visible:false,width:0},
                    {
                        field:'image',
                        title: ' ',

                        width: 60,
                        template:function(data){
                            return `<a href="/administrators/${data.id}"><img class="custom-image" src="${data.image}" > </a>`;
                        }
                    },
                    {
                        field: 'name',
                        title: '<?php echo e(__("Name")); ?>',
                        template:function(data){
                            return `<a href="/administrators/${data.id}"><span>${data.name}</span></a>`
                        }
                    }, 

                    {
                        field: 'email',
                        title: '<?php echo e(__("Email")); ?>',
                        width: 350,
                        
                    }, 

                    {
                        field: 'phone',
                        title: '<?php echo e(__("Phone Number")); ?>',
                    }, 
                    {
                        field: 'status',width:0,
                        title: '<?php echo e(__("Status")); ?>',
                        // callback function support for column rendering
                        template: function(row) {
                            var status = {
                                10: {'title': '<?php echo e(__("Invited")); ?>', 'class': 'kt-badge--brand'},
                                11: {'title': '<?php echo e(__("Active")); ?>', 'class': ' kt-badge--success'},
                                12: {'title': '<?php echo e(__("Inactive")); ?>', 'class': ' kt-badge--danger'},
                            };
                            return '<span class="kt-badge ' + status[row.status].class + ' kt-badge--inline kt-badge--pill">' + status[row.status].title + '</span>';
                        },
                    }],

            };
            var datatable = $('#kt_datatable_admins').KTDatatable(optionsA);
        <?php endif; ?>

        let dataJSONArray = <?php echo json_encode($teachers, 15, 512) ?>;
        var options = {
            // datasource definition
            data: {
                type: 'local',
                source: dataJSONArray,

                pageSize: 5, // display 20 records per page
                // serverPaging: true,
                // serverFiltering: true,
                // serverSorting: true,
            },

            layout: {
                scroll: true,
                footer: false,
                spinner:false,
            },

            // column sorting
            sortable: true,

            pagination: true,

            search: {
                input: $('#generalSearch'),
            },

            rows:{
                beforeTemplate: function (row, data, index) {
                    if(data.status==10){
                        row.addClass('cutsom-bg-warning-light')
                    }
                    if(data.status==12){
                        row.addClass('cutsom-bg-danger-light')
                    }
                    console.log(row);
                }
            },

            // columns definition
            columns: [
                {field:'id',title:'ID',visible:false,width:0},
                
                {
                    field:'image',
                    title: ' ',
                    width: 60,
                    template:function(data){
                        return `<a href="/teachers/${data.id}"><img class="custom-image" src="${data.image}" > </a>`;
                    }
                },
                {
                    field: 'name',
                    title: '<?php echo e(__("Name")); ?>',
                    template:function(data){
                        return `<a href="/teachers/${data.id}"><span>${data.name}</span></a>`
                    }
                }, 

                {
                    field: 'email',
                    title: '<?php echo e(__("Email")); ?>',
                    width: 350,
                    
                }, 

                {
                    field: 'phone',
                    title: '<?php echo e(__("Phone Number")); ?>',
                }, 
                {
                    field: 'status',width:0,visible:false,
                    title: '<?php echo e(__("Status")); ?>',
                    // callback function support for column rendering
                    template: function(row) {
                        var status = {
                            10: {'title': '<?php echo e(__("Invited")); ?>', 'class': 'kt-badge--brand'},
                            11: {'title': '<?php echo e(__("Active")); ?>', 'class': ' kt-badge--success'},
                            12: {'title': '<?php echo e(__("Inactive")); ?>', 'class': ' kt-badge--danger'},
                        };
                        return '<span class="kt-badge ' + status[row.status].class + ' kt-badge--inline kt-badge--pill">' + status[row.status].title + '</span>';
                    },
                }
            ],
        };

		var datatable = $('#kt_datatable_teachers').KTDatatable(options);
    });


 </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\_CODING\_develop\tinel\resources\views/home.blade.php ENDPATH**/ ?>