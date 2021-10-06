<x-app-layout>
@section('content')
  <div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
                <div class="card-body py-1 px-4 d-flex align-items-center justify-content-between flex-wrap">
                  <p class="mb-0 text-white font-weight-medium">Update Additional Information</p>
                  <div>
                    @if($UserRole == 2)
                      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#fieldsofexpertise"> Add Fields of Expertise </button>
                    @elseif($UserRole == 3)
                      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#fieldsofinterest"> Add Fields of Interests </button>
                    @endif
                    <a href="/user/profile" class="btn btn-light bold" id="back-button" onclick="history.go(-1);">Go Back</a>
                  </div>
                </div>
              </div>
            </div>
        </div>    
        <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  @if(session()->has('message'))
                    <div class="alert alert-success" id="show_message">
                      <div class="container">
                        {{ session()->get('message') }}
                      </div>
                    </div>
                  @elseif(session()->has('fault'))
                    <div class="alert alert-danger" id="show_message">
                      <div class="container">
                        {{ session()->get('fault') }}
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
                <form method="POST" action="{{ route('update.additionalinfo') }}" enctype="multipart/form-data" role="form" data-toggle="validator" id="my_form">
                   @csrf
                    <p class="card-description">
                      Update Information <i class="fa fa-arrow-down" aria-hidden="true"></i> 
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
	                        <label>Qualification<span class="required-star">*</span></label>
	                      <input type="text" value="{{ $data[0]->qualification }}" id="name" class="form-control @error('qualification') is-invalid @enderror"  name="qualification" placeholder="e.g.: BE/MBA with Computer Science..." data-error="Please Enter Your Qualification" required>
                        <div class="help-block with-errors"></div>
	                      </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Occupation<span class="required-star">*</span></label>
                        <input type="text" value="{{ $data[0]->occupation }}" id="name" class="form-control @error('occupation') is-invalid @enderror"  name="occupation" placeholder="Professor/Student" data-error="Please Enter Your Occupation." required>
                        <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Gender<span class="required-star">*</span></label>
                          <select class="form-control @error('gender') is-invalid @enderror" id="c_level" name="gender" data-style="select-with-transition" data-error="Select Class Difficulty Level" required>
                            <option value="{{$data[0]->gender}}">{{$data[0]->gender}}</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                          </select>
                        <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      @if($UserRole == 3)
                        @foreach($interest as $data)
                        <div class="col-md-3">
                          <div class="form-group d-flex">
                            <input type="text" value="{{ $data->field_of_interest }}" class="form-control" disabled="disabled" style="background-color: #d9d9d9;">
                            <a href="/delete-userfield/{{ $data->uai_id }}" class="ml-2 mt-1 text-danger bold" data-toggle="tooltip" data-placement="bottom" title="Delete Field"><i class="fa fa-times" aria-hidden="true"></i></a>
                          </div>
                        </div>
                        @endforeach
                      @elseif($UserRole == 2)
                        @foreach($expertise as $data)
                        <div class="col-md-3">
                          <div class="form-group d-flex">
                            <input type="text" value="{{ $data->field_of_expertise }}" class="form-control" disabled="disabled" style="background-color: #d9d9d9;">
                            <a href="/delete-userfield/{{ $data->uai_id }}" class="ml-2 mt-1 text-danger bold" data-toggle="tooltip" data-placement="bottom" title="Delete Field"><i class="fa fa-times" aria-hidden="true"></i></a>
                          </div>
                        </div>
                        @endforeach
                      @endif  
                    </div>
                    <button type="submit" class="btn btn-primary mt-4" name="action" value="submit">Update Information<i class="fa fa-check" aria-hidden="true"></i></button>
                    <a href="/redirect" class="btn btn-light mt-4">Cancel <i class="fa fa-times" aria-hidden="true"></i></a>
                </form>
                </div>
              </div>
            </div>
        </div>
      </div>
{{--Add Fields of Expertise : Teacher--}}  
  @if($UserRole == 2)                      
      <div class="modal fade" id="fieldsofexpertise" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form method="post" action="{{ route('add.moreuserfields') }}" data-toggle="validator" id="my_form">
              @csrf
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Fields of Expertise</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                <div class="multi-field-wrapper">
                  <div class="multi-fields">
                    <div class="multi-field d-flex form-group">
                      <input type="text" value="{{ old('expertise') }}" class="form-control @error('expertise') is-invalid @enderror mr-1 mt-2" name="expertise[]" placeholder="Please Enter Your Fields Of Expertise" data-error="Please Enter Fields of Expertise" required>
                      <button type="button" class="remove-field btn btn-danger">Remove</button>
                    </div>
                  </div>
                      <button type="button" class="add-field btn btn-primary">Add More</button>
                </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  <input type="submit" name="submit" value="Submit" class="btn btn-info">
              </div>
            </form>
          </div>
        </div>
      </div>
  @elseif($UserRole == 3)
      <div class="modal fade" id="fieldsofinterest" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form method="post" action="{{ route('add.moreuserfields') }}" data-toggle="validator" id="my_form">
              @csrf
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Fields of Interests</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                <div class="multi-field-wrapper">
                  <div class="multi-fields">
                    <div class="multi-field d-flex form-group">
                      <input type="text" value="{{ old('interest') }}" class="form-control @error('interest') is-invalid @enderror mr-1 mt-2" name="interest[]" placeholder="Please Enter Your Fields Of Interests" data-error="Please Enter Fields of Expertise" required>
                      <button type="button" class="remove-field btn btn-danger">Remove</button>
                    </div>
                  </div>
                      <button type="button" class="add-field btn btn-primary">Add More</button>
                </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  <input type="submit" name="submit" value="Submit" class="btn btn-info">
              </div>
            </form>
          </div>
        </div>
      </div>
  @endif

 @endsection
</x-app-layout>