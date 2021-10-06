<x-app-layout>
 @section('content')
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
               <div class="card-body py-1 px-4 d-flex justify-content-between flex-wrap d-flex">
                   @if(count($userdata) > 0)
                  <p class="mt-3 text-white font-weight-medium">
                   User Details
                  </p>
                  @else
                  <h6 class="text-white mt-3">No Data Available</h6>
                  @endif
                  <div>
                    <button type="button" id="clear_text" class="btn btn-primary" data-toggle="modal" data-target="#sendmessage"> Send Message to User </button>
                    <button class="btn btn-light bold" id="back-button" onclick="history.go(-1);">Go Back</button>
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
                    <div class="row">
                        @foreach($userdata as $data)
                          <div class="col-md-6 mb-3">
                            <div class="form-group">
                              <label for="firstName">Full Name</label>
                              <input type="text" class="form-control" value="{{ $data->name }}" disabled="disabled">
                            </div>
                          </div>
                          <div class="col-md-6 mb-3">
                            <label for="username">Email</label>
                              <div class="form-group">
                                 <input type="text" class="form-control" value="{{ $data->email }}" disabled="disabled">
                              </div>
                          </div>
                          <div class="col-md-4 mb-3">
                            <div class="form-group">
                              <label for="lastName">Contact No.</label>
                              <input type="text" class="form-control" value="{{ $data->contact }}"  disabled="disabled">
                            </div>
                          </div>
                          <div class="col-md-4 mb-3">
                            <div class="form-group">
                              <label for="lastName">User Role</label>
                              @if($data->user_type == 2)
                              <input type="text" value="Teacher" class="form-control" disabled="disabled">
                              @elseif($data->user_type == 3)
                              <input type="text" value="Student" class="form-control" disabled="disabled">
                              @elseif($data->user_type == 4)
                              <input type="text" value="Parent" class="form-control" disabled="disabled">
                              @else
                              <input type="text" value="Admin" class="form-control" disabled="disabled">
                              @endif
                            </div>
                          </div>
                          @if(isset($data->gender))
                          <div class="col-md-4 mb-3">
                            <div class="form-group">
                              <label for="lastName">Gender</label>
                              <input type="text" value="{{ $data->gender }}" class="form-control" disabled="disabled">
                            </div>
                          </div>
                          @endif
                          @if(isset($data->qualification))
                          <div class="col-md-6 mb-3">
                            <div class="form-group">
                              <label for="lastName">Qualification</label>
                              <input type="text" value="{{ $data->qualification }}" class="form-control" disabled="disabled">
                            </div>
                          </div>
                          @endif
                          @if(isset($data->qualification))
                          <div class="col-md-6 mb-3">
                            <div class="form-group">
                              <label for="lastName">Occupation</label>
                              <input type="text" value="{{ $data->occupation }}" class="form-control" disabled="disabled">
                            </div>
                          </div>
                          @endif
                        @endforeach
                    </div>
                  @if(count($additional_data) > 0)  
                    @if($user_role == 2)
                      <p class="text-info bold">Fields of Expertise</p>
                      <div class="row">
                        @foreach($additional_data as $info)
                          <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <input type="text" class="form-control" value="{{ $info->field_of_expertise }}" disabled="disabled">
                            </div>
                          </div>
                        @endforeach
                      </div>
                    @elseif($user_role == 3)
                      <p class="text-info bold">User Fields of Interest</p>
                      <div class="row">
                        @foreach($additional_data as $info)
                          <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <input type="text" class="form-control" value="{{ $info->field_of_interest }}" disabled="disabled">
                            </div>
                          </div>
                        @endforeach
                      </div>
                    @endif
                  @endif  
                </div>
              </div>
            </div>
          </div>
      </div>
{{--Send Message to User--}}                        
      <div class="modal fade" id="sendmessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form method="post" action="/message-to-user" data-toggle="validator" id="my_form">
              @csrf
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Enter Message</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                <div class="form-group"> 
                  <textarea name="message" id="textarea" rows="4" maxlength="240" minlength="5" placeholder="Enter Something" class="form-control" data-error="Please Enter Message" required></textarea>
                    <span id="rchars">0</span> /240
                    <div class="help-block with-errors"></div>
                </div>
                <input type="hidden" name="sent_to" value="{{$id}}">
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
