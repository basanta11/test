<section id="teachers">

    <div class="container">
      <div class="section-title mb-10">
        <div class="row">
          <div class="col-md-8">
            <h2
              class="mt-0 text-uppercase font-28 line-bottom line-height-1"
            >
              Our
              <span class="text-theme-color-2 font-weight-400"
                >Teachers</span
              >
            </h2>
          </div>
        </div>
      </div>
        
      <div class="section-content">
        <div class="row">
          <div class="col-md-12">
            <div class="owl-carousel-4col" data-dots="true" data-nav="true">
              @foreach($teachers as $teacher)
              <div class="item">
                <div class="service-block bg-white">
                  <div class="thumb">
                    <img
                      alt="featured project"
                      src="{{ $teacher['image'] }}"
                      class="img-fullwidth custom-image"
                    />
                  </div>
                  <div class="content text-left flip p-25 pt-0">
                    <h4 class="line-bottom mb-10">
                      {{ $teacher['name'] }}
                    </h4>
                    <p>
                      {{ $teacher['email'] }}
                    </p>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    
  </section>