<x-app-layout>
@section('content')
  <div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
                <div class="card-body py-1 px-4 d-flex align-items-center justify-content-between flex-wrap">
                  <p class="mb-0 text-white font-weight-medium">Add/Update Additional Information</p>
                  <a href="/redirect" class="btn btn-light bold" id="back-button">Go Back</a>
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
                <form method="POST" action="{{ route('user.postdata') }}" enctype="multipart/form-data" role="form" data-toggle="validator" id="my_form">
                   @csrf
                    <p class="card-description">
                      User Information <i class="fa fa-arrow-down" aria-hidden="true"></i> 
                    </p><h6 class="text-primary bold">Please Enter all the Required Details (<span class="required-star">*</span>)</h6>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
	                        <label>Please Enter Your Qualification<span class="required-star">*</span></label>
	                      <input type="text" value="{{ old('qualification') }}" id="name" class="form-control @error('qualification') is-invalid @enderror"  name="qualification" placeholder="e.g.: BE/MBA with Computer Science..." data-error="Please Enter Your Qualification" required>
                        <div class="help-block with-errors"></div>
	                      </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Please Enter Your Occupation<span class="required-star">*</span></label>
                        <input type="text" value="{{ old('occupation') }}" id="name" class="form-control @error('occupation') is-invalid @enderror"  name="occupation" placeholder="Professor/Student" data-error="Please Enter Your Occupation." required>
                        <div class="help-block with-errors"></div>
                        </div>
                      </div>
                    </div>
{{--Information--}}
                @if($UserRole == 2)
                  <p class="card-description">
                    Please Enter Your Fields of Expertise<span class="required-star">*</span><i class="fa fa-arrow-down" aria-hidden="true"></i> 
                  </p>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <div class="multi-field-wrapper">
                          <div class="multi-fields">
                            <div class="multi-field d-flex">
                              <input type="text" value="{{ old('expertise') }}" class="form-control @error('expertise') is-invalid @enderror mr-1 mt-2" name="expertise[]" placeholder="Please Enter your Expertise" data-error="Please Enter Your Fileds of Expertise" required >
                              <button type="button" class="remove-field btn btn-danger">Remove</button>
                            </div>
                          </div>
                          <div class="help-block with-errors"></div>
                          <button type="button" class="add-field btn btn-primary">Add field</button>
                        </div>
                      </div>
                    </div>
                  </div>
                @endif

                @if($UserRole == 3)
                  <p class="card-description">
                    Please Enter Your Fields of Interest<span class="required-star">*</span><i class="fa fa-arrow-down" aria-hidden="true"></i> 
                  </p>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <div class="multi-field-wrapper">
                          <div class="multi-fields">
                            <div class="multi-field d-flex">
                              <input type="text" value="{{ old('interests') }}" class="form-control @error('interests') is-invalid @enderror mr-1 mt-2" name="interest[]" placeholder="Please Enter your fields of Interest" data-error="Please Enter your fields of Interest" required >
                              <button type="button" class="remove-field btn btn-danger">Remove</button>
                            </div>
                          </div>
                          <div class="help-block with-errors"></div>
                          <button type="button" class="add-field btn btn-primary">Add field</button>
                        </div>
                      </div>
                    </div>
                  </div>
                @endif
 
<hr class="schedule-end">

                    <button type="submit" class="btn btn-primary mt-4" name="action" value="submit">Submit Information<i class="fa fa-check" aria-hidden="true"></i></button>
                    <button class="btn btn-light mt-4" onclick="history.go(-1);">Cancel <i class="fa fa-times" aria-hidden="true"></i></button>
                    
                  </form>
                </div>
              </div>
            </div>
        </div>
      </div>

 @endsection
</x-app-layout>