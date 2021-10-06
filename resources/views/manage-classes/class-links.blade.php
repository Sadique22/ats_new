<x-app-layout>
 @section('content')
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
               <div class="card-body py-1 px-4 d-flex justify-content-between flex-wrap d-flex">
                  @if(count($class_links) > 0)
                  <p class="mt-3 text-white font-weight-medium">
                   Class Links
                  </p>
                  @else
                    <h6 class="text-white mt-3">There is no Class Links available for this class yet!</h6>
                  @endif
                  <div class="d-flex">
                    <a href="/class-recordings/{{$id}}" class="btn btn-info">Class Recordings</a>
                    <a href="/view-classes" class="btn btn-light bold" id="back-button" onclick="history.go(-1);">Go Back</a>
                  </div>
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
          <div class="row">
            <div class="col-md-12 stretch-card">
              <div class="card">
                <div class="card-body">
                  <p class="card-title">Live Class Links</p>
                  <div class="table-responsive">
                    <table id="example" class="display nowrap" style="width:100%">
                      <thead class="head_color">
                        <tr>
                          <th>Name</th>
                          <th>Class Topic</th>
                          <th>Class Link</th>
                          <th>Class Time</th>
                          <th>Class Date</th>
                          <th>Message</th>
                        </tr>
                      </thead>
                      @foreach($class_links as $link)
                      <tbody>
                        <tr>
                         <td class="text-primary bold">{{$link->class_title}}</td>
                         <td class="bold">{{$link->schedule_desc}}</td>

                         <?php
                            date_default_timezone_set('Asia/Kolkata'); 
                            $current_time = date("H:i");
                            $current_date = date("Y/m/d");
                            $link_generate = date("H:i",strtotime(date($link->class_time)." -5 minutes"));
                            $class_date = date("Y/m/d", strtotime($link->class_date));
                          ?>

                          @if($current_time >= $link_generate && $current_date == $class_date)
                            <td><input type="text" class="form-control" value="{{$link->class_link}}"></td>
                          @elseif($class_date < $current_date)
                            <td class="text-danger">Link Expired</td>
                          @else
                            <td class="text-info">Link will generate soon</td>
                          @endif

                          <?php 
                          $time = $link->class_time; 
                          $newTime = date('h:i A', strtotime($time));
                          ?>
                         <td>{{$newTime}}</td>
                          <?php
                          $originalDate = $link->class_date ;
                          $newDate = date("F d, Y", strtotime($originalDate));
                          ?>
                          <td>{{$newDate}}</td>
                          <td>{{$link->message}}</td>
                        </tr>
                      </tbody>
                      @endforeach
                    </table>
                    {{$class_links->links()}}
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>    
@endsection
</x-app-layout>
