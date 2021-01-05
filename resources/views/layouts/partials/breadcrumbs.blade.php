<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">
                {{ $breadTitle }} </h3>
            <span class="kt-subheader__separator kt-hidden"></span>
            <div class="kt-subheader__breadcrumbs">
                <a href="/app" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
    
                @php $last = end($crumbs); @endphp
                @foreach($crumbs as $crumb)
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="{{ $crumb['url'] }}" class="kt-subheader__breadcrumbs-link @if($crumb == $last) kt-subheader__breadcrumbs-link--active @endif">{{ $crumb['name'] }} </a>
                @endforeach
            </div>
        </div>
    </div>
    
</div>