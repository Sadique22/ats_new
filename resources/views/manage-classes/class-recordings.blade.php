<x-app-layout>
 @section('content')
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
               <div class="card-body py-1 px-4 d-flex justify-content-between flex-wrap d-flex">
                  @if(count($class_recordings) > 0)
                  <p class="mt-3 text-white font-weight-medium">
                   Class Recordings
                  </p>
                  @else
                    <h6 class="text-white mt-3">There is no Class Recordings available for this class yet!</h6>
                  @endif
                  <a href="/class-links/{{$id}}" class="btn btn-light bold" id="back-button" onclick="history.go(-1);">Go Back</a>
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
                  <p class="card-title">Class Recording Details</p>
                  <div class="table-responsive">
                    <table id="example" class="display nowrap" style="width:100%">
                      <thead class="head_color">
                        <tr>
                          <th>Class</th>
                          <th>Class Recording Link</th>
                          <th>Date</th>
                        </tr>
                      </thead>
                      @foreach($class_recordings as $link)
                      <tbody>
                        <tr>
                          <?php $c_id = base64_encode($link->class_id);
                                $t_id = base64_encode($t_id)
                          ?>
                         <td class="text-primary bold">
                         <a href="/class-detail/{{$c_id}}/{{$t_id}}" target="_blank"> {{$class_name}} </a>
                         </td>
                         <td>{{$link->cr_link}}</td>
                          <?php
                          $originalDate = $link->created_at ;
                          $newDate = date("F d, Y", strtotime($originalDate));
                          ?>
                          <td>{{$newDate}}</td>
                        </tr>
                      </tbody>
                      @endforeach
                    </table>
                    {{$class_recordings->links()}}
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>    
@endsection
</x-app-layout>
