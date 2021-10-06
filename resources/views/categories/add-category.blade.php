<x-app-layout>
@section('content')
  <div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
                <div class="card-body py-3 px-4 d-flex align-items-center justify-content-between flex-wrap">
                  <p class="mb-0 text-white font-weight-medium">Add New Category</p>
                  <a href="/all-categories" class="btn btn-light bold" id="back-button" onclick="history.go(-1);">Go Back</a>
                </div>
              </div>
            </div>
        </div>    
        <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Please Enter All the details</h4>
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
                <form method="POST" action="{{ route('category.insert') }}" enctype="multipart/form-data" role="form" data-toggle="validator">
                   @csrf
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
	                        <label>Category Name <span class="required-star">*</span></label>
	                      <input type="text"  value="{{ old('c_name') }}" class="form-control @error('c_name') is-invalid @enderror" name="c_name" placeholder="Enter Category Name" data-error="Please Enter Category Name." required>
                        <div class="help-block with-errors"></div>
	                      </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <span class="required-star">*</span><input type='file' name="image" id="img" onchange="readURL(this),validateImage()" accept="image/*" data-error="Please Upload Image for the Category" required/>
                          <img id="blah" src="{{asset('assets/img/upload.png')}}" width="200px" height="200px" alt="your image" style="background-color: #f2eeed"/>
                          <div class="help-block with-errors"></div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <label>Please Upload PNG Image with Transparent Background <span class="required-star">*</span></label>
                      <div class="form-group">
                        <div class="valid-message"></div>
                        <span>Click here and select <i class="fa fa-hand-o-right mr-2" aria-hidden="true"></i></span>
                        <input type="color" id="favcolor" name="bg_color" value="#ff0000" data-error="Please Select Background Color" required > 
                      </div>
                    </div>

                     <button type="submit" class="btn btn-primary mr-2">Submit</button>
                  </form>
                  <a href="/all-categories" class="btn btn-light">
                    Cancel</a>
                </div>
              </div>
            </div>
          </div>
        </div>

 @endsection
</x-app-layout>