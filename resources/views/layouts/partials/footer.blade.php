  <!-- begin:: Footer -->
  <div class="kt-footer  kt-footer--extended  kt-grid__item" id="kt_footer">
    <div class="kt-footer__bottom">
        <div class="kt-container ">
            <div class="kt-footer__wrapper">
                <div class="kt-footer__copyright">
                &copy; {{ date('Y') }} Powered by: <a href="https://learnonline.co.th/" target="_blank">{{  config("app.name") }} ติดต่อเราได้ที่ <a href="tel:+66020077795">020077795</a> 
                </div>
                <span class="float-right">Version: {{env('APP_VERSION', '1.0.0').env('APP_BUILD', '-beta')}}</span>
            </div>
        </div>
    </div>
</div>

<!-- end:: Footer -->