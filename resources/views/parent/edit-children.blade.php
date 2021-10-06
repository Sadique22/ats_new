<x-app-layout>
@section('content')
  <div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
                <div class="card-body py-1 px-4 d-flex align-items-center justify-content-between flex-wrap">
                  <p class="mb-0 text-white font-weight-medium">Manage Data</p>
                  <a href="/child-manage" class="btn btn-light bold">Go Back</a>
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

              @foreach($childrendata as $user)
                <form method="POST" action="/update-children" enctype="multipart/form-data" role="form" data-toggle="validator">
                   @csrf
                    <p class="card-description">
                      Update Child Information
                    </p>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
	                      <label>Child Name</label>
	                      <input type="text" value="{{ $user->child_name }}" class="form-control @error('child_name') is-invalid @enderror" max="50" name="child_name" data-error="Please Enter Child Name" placeholder="Enter Child Name" required>
                        <div class="help-block with-errors"></div>
	                    </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Child Age</label>
                        <input type="number" value="{{ $user->child_age }}" class="form-control @error('child_age') is-invalid @enderror" min="6" max="20" name="child_age" min="1" max="40" data-error="Please Enter Child Age" placeholder="Enter Child Age" required>
                        <div class="help-block with-errors"></div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Child Gender</label>
                        <select class="form-control @error('child_gender') is-invalid @enderror" name="child_gender" data-style="select-with-transition" data-error="Select Gender" required>
                          <option value="{{$user->child_gender}}">{{$user->child_gender}}</option>
                          <option value="Male">Male</option>
                          <option value="Female">Female</option>
                        </select>
                        <div class="help-block with-errors"></div>
                      </div>
                    </div>
                  </div>
                  <input type="hidden" name="user_id" value="{{$user->child_id}}">
                
                    <button type="submit" class="btn btn-primary mr-2">Update</button>
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