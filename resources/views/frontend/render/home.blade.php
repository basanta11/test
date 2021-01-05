<section id="home">
    <div class="container-fluid p-0">
      <!-- Slider Revolution Start -->
      <div class="rev_slider_wrapper">
        <div class="rev_slider" data-version="5.0">
          <ul>
            @if($data['banners'])
            <!-- SLIDE 1 -->
              @foreach($data['banners'] as $key=>$banner)
              <li
            data-index="rs-{{ $key }}"
                data-transition="slidingoverlayhorizontal"
                data-slotamount="default"
                data-easein="default"
                data-easeout="default"
                data-masterspeed="default"
                data-thumb="{{ $banner['image'] }}"
                data-rotate="0"
                data-saveperformance="off"
                data-title="Web Show"
                data-description=""
              >
                <!-- MAIN IMAGE -->
                <img
                  src="{{ $banner['image'] }}"
                  alt=""
                  data-bgposition="center 10%"
                  data-bgfit="cover"
                  data-bgrepeat="no-repeat"
                  class="rev-slidebg"
                  data-bgparallax="6"
                  data-no-retina
                />
                <!-- LAYERS -->

                <!-- LAYER NR. 1 -->
                <div
                  class="tp-caption tp-resizeme text-uppercase text-white font-raleway"
                  id="rs-{{ $key }}-layer-1"
                  data-x="['left']"
                  data-hoffset="['30']"
                  data-y="['middle']"
                  data-voffset="['-110']"
                  data-fontsize="['100']"
                  data-lineheight="['110']"
                  data-width="none"
                  data-height="none"
                  data-whitespace="nowrap"
                  data-transform_idle="o:1;s:500"
                  data-transform_in="y:100;scaleX:1;scaleY:1;opacity:0;"
                  data-transform_out="x:left(R);s:1000;e:Power3.easeIn;s:1000;e:Power3.easeIn;"
                  data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                  data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                  data-start="1000"
                  data-splitin="none"
                  data-splitout="none"
                  data-responsive_offset="on"
                  style="z-index: 7; white-space: nowrap; font-weight: 700;"
                >
                  {{ $banner['title'] }}
                </div>

                

                <!-- LAYER NR. 3 -->
                <div
                  class="tp-caption custom-description tp-resizeme text-white"
                  id="rs-{{ $key }}-layer-3"
                  data-x="['left']"
                  data-hoffset="['35']"
                  data-y="['middle']"
                  data-voffset="['35']"
                  data-fontsize="['16']"
                  data-lineheight="['28']"
                  data-width="none"
                  data-height="none"
                  data-whitespace="nowrap"
                  data-transform_idle="o:1;s:500"
                  data-transform_in="y:100;scaleX:1;scaleY:1;opacity:0;"
                  data-transform_out="x:left(R);s:1000;e:Power3.easeIn;s:1000;e:Power3.easeIn;"
                  data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                  data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                  data-start="1400"
                  data-splitin="none"
                  data-splitout="none"
                  data-responsive_offset="on"
                  style="
                    z-index: 5;
                    letter-spacing: 0px;
                    font-weight: 400;
                    
                  "
                >
                  <p style="white-space:pre-wrap; width:400px;" >{{ $banner['description'] }}</p>
                </div>

              
              </li>

              @endforeach
            @else
            <li
            data-index="rs-1"
                data-transition="slidingoverlayhorizontal"
                data-slotamount="default"
                data-easein="default"
                data-easeout="default"
                data-masterspeed="default"
                data-thumb="{{ global_asset('assets/media/default-image.jpg') }}"
                data-rotate="0"
                data-saveperformance="off"
                data-title="Web Show"
                data-description=""
              >
                <!-- MAIN IMAGE -->
                <img
                  src="{{ global_asset('assets/media/default-image.jpg') }}"
                  alt=""
                  data-bgposition="center 10%"
                  data-bgfit="cover"
                  data-bgrepeat="no-repeat"
                  class="rev-slidebg"
                  data-bgparallax="6"
                  data-no-retina
                />
                <!-- LAYERS -->

                <!-- LAYER NR. 1 -->
                <div
                  class="tp-caption tp-resizeme text-uppercase text-white font-raleway"
                  id="rs-1-layer-1"
                  data-x="['left']"
                  data-hoffset="['30']"
                  data-y="['middle']"
                  data-voffset="['-110']"
                  data-fontsize="['100']"
                  data-lineheight="['110']"
                  data-width="none"
                  data-height="none"
                  data-whitespace="nowrap"
                  data-transform_idle="o:1;s:500"
                  data-transform_in="y:100;scaleX:1;scaleY:1;opacity:0;"
                  data-transform_out="x:left(R);s:1000;e:Power3.easeIn;s:1000;e:Power3.easeIn;"
                  data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                  data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                  data-start="1000"
                  data-splitin="none"
                  data-splitout="none"
                  data-responsive_offset="on"
                  style="z-index: 7; white-space: nowrap; font-weight: 700;"
                >
                Add Title
                </div>

                

                <!-- LAYER NR. 3 -->
                <div
                  class="tp-caption custom-description tp-resizeme text-white"
                  id="rs-1-layer-3"
                  data-x="['left']"
                  data-hoffset="['35']"
                  data-y="['middle']"
                  data-voffset="['35']"
                  data-fontsize="['16']"
                  data-lineheight="['28']"
                  data-width="none"
                  data-height="none"
                  data-whitespace="nowrap"
                  data-transform_idle="o:1;s:500"
                  data-transform_in="y:100;scaleX:1;scaleY:1;opacity:0;"
                  data-transform_out="x:left(R);s:1000;e:Power3.easeIn;s:1000;e:Power3.easeIn;"
                  data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                  data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                  data-start="1400"
                  data-splitin="none"
                  data-splitout="none"
                  data-responsive_offset="on"
                  style="
                    z-index: 5;
                    letter-spacing: 0px;
                    font-weight: 400;
                  "
                >
                Add description
                </div>

              
              </li>

            @endif
            
          </ul>
        </div>
        <!-- end .rev_slider -->
      </div>
      <!-- end .rev_slider_wrapper -->
      <script>
        $(document).ready(function (e) {
          var revapi = $(".rev_slider").revolution({
            sliderType: "standard",
            jsFileLocation: "{{ global_asset('assets/frontend/js/revolution-slider/js/') }}",
            sliderLayout: "auto",
            dottedOverlay: "none",
            delay: 5000,
            navigation: {
              keyboardNavigation: "off",
              keyboard_direction: "horizontal",
              mouseScrollNavigation: "off",
              onHoverStop: "off",
              touch: {
                touchenabled: "on",
                swipe_threshold: 75,
                swipe_min_touches: 1,
                swipe_direction: "horizontal",
                drag_block_vertical: false,
              },
              arrows: {
                style: "gyges",
                enable: true,
                hide_onmobile: false,
                hide_onleave: true,
                hide_delay: 200,
                hide_delay_mobile: 1200,
                tmp: "",
                left: {
                  h_align: "left",
                  v_align: "center",
                  h_offset: 0,
                  v_offset: 0,
                },
                right: {
                  h_align: "right",
                  v_align: "center",
                  h_offset: 0,
                  v_offset: 0,
                },
              },
              bullets: {
                enable: true,
                hide_onmobile: true,
                hide_under: 800,
                style: "hebe",
                hide_onleave: false,
                direction: "horizontal",
                h_align: "center",
                v_align: "bottom",
                h_offset: 0,
                v_offset: 30,
                space: 5,
                tmp:
                  '<span class="tp-bullet-image"></span><span class="tp-bullet-imageoverlay"></span><span class="tp-bullet-title"></span>',
              },
            },
            responsiveLevels: [1240, 1024, 778],
            visibilityLevels: [1240, 1024, 778],
            gridwidth: [1170, 1024, 778, 480],
            gridheight: [620, 768, 960, 720],
            lazyType: "none",
            parallax: "mouse",
            parallaxBgFreeze: "off",
            parallaxLevels: [2, 3, 4, 5, 6, 7, 8, 9, 10, 1],
            shadow: 0,
            spinner: "off",
            stopLoop: "on",
            stopAfterLoops: 0,
            stopAtSlide: -1,
            shuffle: "off",
            autoHeight: "off",
            fullScreenAutoWidth: "off",
            fullScreenAlignForce: "off",
            fullScreenOffsetContainer: "",
            fullScreenOffset: "0",
            hideThumbsOnMobile: "off",
            hideSliderAtLimit: 0,
            hideCaptionAtLimit: 0,
            hideAllCaptionAtLilmit: 0,
            debugMode: false,
            fallbacks: {
              simplifyAll: "off",
              nextSlideOnWindowFocus: "off",
              disableFocusListener: false,
            },
          });
        });
      </script>
      <!-- Slider Revolution Ends -->
    </div>
  </section>