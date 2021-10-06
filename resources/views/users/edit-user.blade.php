<x-app-layout>
@section('content')
  <div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
                <div class="card-body py-1 px-4 d-flex align-items-center justify-content-between flex-wrap">
                  <p class="mb-0 text-white font-weight-medium">Update User Data</p>
                  <a href="/all-users" class="btn btn-light bold" id="back-button" onclick="history.go(-1);">Go Back</a>
                </div>
              </div>
            </div>
        </div>    
        <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">UPDATE USER DETAILS</h4>
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

              @foreach($data as $user)
                <form method="POST" action="/update-user/{{$user->id}}" enctype="multipart/form-data" role="form" data-toggle="validator">
                   @csrf
                    <p class="card-description">
                      Update User Information
                    </p>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
	                      <label>User Name</label>
	                      <input type="text" value="{{ $user->name }}" class="form-control @error('name') is-invalid @enderror" name="name" data-error="Please Enter User Name" placeholder="Enter User Name" required>
                        <div class="help-block with-errors"></div>
	                    </div>
                    </div>
                     <div class="col-md-6">
                        <div class="form-group row">
                        <label>User Email</label>
                        <input type="email" value="{{ $user->email }}" class="form-control @error('email') is-invalid @enderror" name="email" data-error="Please Enter User Email" placeholder="Enter User Email" required>
                        <div class="help-block with-errors"></div>
                      </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                        <label>User Contact</label>
                        <input type="text" value="{{ $user->contact }}" class="form-control @error('contact') is-invalid @enderror" name="contact" data-error="Please Enter User Contact" placeholder="Enter User Contact" required>
                        <div class="help-block with-errors"></div>
                      </div>
                    </div>
                  </div>
                
                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    <a href="/redirect" class="btn btn-light">Cancel</a>
                  </form>
                  @endforeach
                </div>
              </div>
            </div>
        </div>
      </div>
 @endsection
</x-app-layout>