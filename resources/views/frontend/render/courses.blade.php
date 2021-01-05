<section id="courses" class="bg-lighter">
    <div class="container pb-60">
      <div class="section-title mb-10">
        <div class="row">
          <div class="col-md-8">
            <h2
              class="mt-0 text-uppercase font-28 line-bottom line-height-1"
            >
              Our
              <span class="text-theme-color-2 font-weight-400"
                >COURSES</span
              >
            </h2>
          </div>
        </div>
      </div>
      <div class="section-content">
        <div class="row">
          <div class="col-md-12">
            <div class="owl-carousel-4col" data-dots="true" data-nav="true"> 
              @foreach($courses as $course)
              <a href="/login">
                <div class="item custom-item">
                  <div class="service-block custom-service bg-white">
                    <div class="thumb">
                      <img
                        alt="featured project"
                        src="{{ $course["image"] }}"
                        class="img-fullwidth custom-image"
                      />
                      <h4 class="text-white mt-0 mb-0">
                        <span class="price">{{ $course["credit_hours"] }} hours</span>
                      </h4>
                    </div>
                    <div class="content custom-content pb-0 text-left flip p-25 pt-0">
                      <h4 class="line-bottom mb-10">
                        {{ $course["title"] }}
                      </h4>
                      <p>
                        {{ mb_strimwidth($course['learn_what'], 0, 247, "...") }}
                      </p>
                    </div>
                  </div>
                </div>
              </a>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>