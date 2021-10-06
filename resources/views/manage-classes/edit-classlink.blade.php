<x-app-layout>
@section('content')
  <div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
                <div class="card-body py-1 px-4 d-flex align-items-center justify-content-between flex-wrap">
                  <p class="mb-0 text-white font-weight-medium">Update Class Link</p>
                  <a href="/all-users" class="btn btn-light bold" id="back-button" onclick="history.go(-1);">Go Back</a>
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

                @foreach($linkdata as $data)
                  <form method="POST" action="/update-classlink/{{$data->lc_id}}" enctype="multipart/form-data" role="form" data-toggle="validator">
                     @csrf
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group row">
    	                      <label>User Name</label>
    	                      <input type="text" value="{{ $data->class_link }}" class="form-control @error('class_link') is-invalid @enderror" name="class_link" data-error="Please Enter User Name" placeholder="Enter User Name" required>
                            <div class="help-block with-errors"></div>
  	                      </div>
                        </div>
                      </div>
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