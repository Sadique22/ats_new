<x-app-layout>
 @section('content')
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
                <div class="card-body py-1 px-4 d-flex justify-content-between flex-wrap d-flex">
                  <p class="mt-3 text-white font-weight-medium">
                   Search Result for: <span class="text-info bold">@if(isset($data->user_name))Teacher: {{$data->user_name}},@endif @if(isset($data->user_qualification))Qualification: {{$data->user_qualification}},@endif @if(isset($data->user_occupation))Occupation: {{$data->user_occupation}},@endif @if(isset($data->rating))User Rating: {{$data->rating}} Star @endif @if(isset($expertise)) Expertise: {{$expertise}} @endif</span>
                  </p>
                  <div>
                    <button class="btn btn-light bold" id="back-button" onclick="history.go(-1);">Go Back</button>
                    <button type="button" class="btn btn-primary float-right" id="clear_text" data-toggle="modal" data-target="#sendmessage"> Send Message to Users </button>
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
          @if(count($teacher) > 0)
          <div class="row all-classes">
            @foreach($teacher as $data)
              <div class="col-xs-12 col-sm-3 mb-3 pb-2">
                <div class="single_course">
                  <div class="course_head" style="background-color: #759aff">
                      <a href="/user-details/{{$data->teacher_id}}" target="_blank">
                        <img class="img-fluid" src="{{asset ('assets/img/logo-white.png')}}" alt="" />
                      </a>
                  </div>
                  <div class="course_content" style="border: 1px solid #adcbff">
                    <h4 class="mt-2">
                      {{$data->name}}
                    </h4>
                    <div class="author purchase">
                      <h6 class="d-inline-block">Occupation: {{$data->occupation}}</h6>
                    </div>
                    <div class="timings">
                      <span class="mt-1 mr-1"><i class="fa fa-calendar mr-1" aria-hidden="true"></i> Qualification:{{$data->qualification}}</span>
                    </div>
                    <div class="star_ratings">
                      Rating:
                      @for($i = 0; $i < 5; $i++)
                        <span class="ml-1">
                          <i class="fa fa-star text-warning{{ $data->avg_rating <= $i ? '-o' : '' }}"></i>
                        </span>
                      @endfor
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

{{--Send Message to the Search Result Users--}}      
      <div class="modal fade" id="sendmessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form method="post" action="/admin-send" data-toggle="validator" id="my_form">
              @csrf
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Send Message to the Search Result Users:</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <select class="form-control mb-3 @error('sent_to') is-invalid @enderror" name="sent_to[]" data-style="select-with-transition" data-error="Please Select User." multiple="multiple" required>
                    @foreach ($teacher as $user)
                     <?php
                        $user_name = strstr($user->email,"@",true);
                      ?>
                      <option value="{{$user->teacher_id}}">{{$user->name}} || {{$user_name}}</option>
                    @endforeach 
                  </select>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group"> 
                  <textarea name="message" id="textarea" maxlength="240" rows="4" placeholder="Enter Something" class="form-control" data-error="Please Enter Message" required></textarea>
                  <span id="rchars">0</span> /240
                    <div class="help-block with-errors"></div>
                </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  <input type="submit" name="submit" value="Send" class="btn btn-info">
              </div>
            </form>
          </div>
        </div>
      </div>  
@endsection
</x-app-layout>
