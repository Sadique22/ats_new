<x-app-layout>
 @section('content')
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
                <div class="card-body py-1 px-4 d-flex justify-content-between flex-wrap d-flex">
                  <p class="mt-3 text-white font-weight-medium">
                   Search Result for: "{{$searchtext}}"
                  </p>
                  <button class="btn btn-light bold" id="back-button" onclick="history.go(-1);">Go Back</button>
                </div>
              </div>
            </div>
          </div>
          @if(session()->has('message'))
            <div class="alert alert-success" id="show_message">
              <div class="container">
                {{ session()->get('message') }}
              </div>
            </div>
          @endif
          @if ($errors->any())
            <div class="alert alert-danger" id="show_message">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                 @endforeach
              </ul>
            </div>
          @endif
          @if(count($classes) > 0)
          <div class="row all-classes">
            @foreach($classes as $class)
              <div class="col-xs-12 col-sm-3 mb-3 pb-2">
                <div class="single_course">
                  <div class="course_head" style="background-color: {{$class->bg_color}};">
                      <?php $c_id = base64_encode($class->id);
                        $t_id = base64_encode($class->created_by)
                      ?>
                      <a href="/class-detail/{{$c_id}}/{{$t_id}}" target="_blank">
                        <img class="img-fluid" src="/{{$class->category_image}}" alt="" />
                      </a>
                  </div>
                  <div class="course_content">
                    <h4 class="mt-2">
                      <a href="/class-detail/{{$c_id}}/{{$t_id}}" target="_blank"> {{$class->class_title}} </a>
                    </h4>
                    <div class="author purchase">
                      <h6 class="d-inline-block">By: {{$class->name}}</h6>
                    </div>
                    <div class="timings">
                      <span class="mt-1 mr-1"><i class="fa fa-calendar mr-1" aria-hidden="true"></i> NOV 24</span>
                      <span class="mt-2 mr-3"><i class="fa fa-clock-o mr-1" aria-hidden="true"></i>3PM - 5PM  (3 Hrs)</span>
                    </div>
                    <div class="star_ratings">
                      @for($i = 0; $i < 5; $i++)
                        <span class="ml-1">
                          <i class="fa fa-star text-warning{{ $class->avg_rating <= $i ? '-o' : '' }}"></i>
                        </span>
                      @endfor
                      <h6 class="level"><i class="fa fa-bar-chart" aria-hidden="true"></i></i> {{$class->class_level}}</h6>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
            @else
            <img class="img-fluid mx-auto d-block" src="{{asset('assets/img/result.png')}}">
            @endif
      </div>    
@endsection
</x-app-layout>
