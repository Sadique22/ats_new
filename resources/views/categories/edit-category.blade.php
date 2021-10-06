<x-app-layout>
@section('content')
  <div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
                <div class="card-body py-1 px-4 d-flex align-items-center justify-content-between flex-wrap">
                  <p class="mb-0 text-white font-weight-medium">Edit Category</p>
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

                @foreach($category as $data)
                <form method="POST" action="/update-category/{{$data->c_id}}" enctype="multipart/form-data" role="form" data-toggle="validator">
                   @csrf
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
	                        <label >Category Name</label>
                          <div class="valid-message"></div>
	                      <input type="text" value="{{ $data->c_name}}" class="form-control @error('c_name') is-invalid @enderror" name="c_name" placeholder="Enter Category Name"  data-error="Please Enter Category Name." required>
                        <div class="help-block with-errors"></div>
	                      </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          @if(isset($data->category_image))
                            <label >Category Image</label>
                            <img class="img-fluid  mb-2" src="{{ url('/') . '/' . $data->category_image }}" style="background-color: {{$data->bg_color}}" width="150px" height="150px" />
                            <label >Upload New Category Image</label>
                            <input type="file" name="image" class="form-control" id="img" onchange="readURL(this),validateImage()" accept="image/*" data-error="Please Upload image.">
                            <img id="blah" src="{{asset('assets/img/upload.png')}}" width="200px" height="200px" alt="your image" style="background-color: #f2eeed"/>
                          @else
                            <input type="file" name="image" class="form-control" id="img" onchange="readURL(this),validateImage()" accept="image/*" data-error="Please Upload image." required="">
                            <img id="blah" src="{{asset('assets/img/upload.png')}}" width="200px" height="200px" alt="your image" style="background-color: #f2eeed"/>
                          @endif
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          @if(isset($data->bg_color))
                            <span>Select New Background Color <i class="fa fa-hand-o-right mr-2" aria-hidden="true"></i></span><input type="color" id="favcolor" name="bg_color" value="{{$data->bg_color}}" data-error="Please Select Background Color">
                          @else
                            <span>Select Background Color <i class="fa fa-hand-o-right mr-2" aria-hidden="true"></i></span><input type="color" id="favcolor" name="bg_color" value="{{$data->bg_color}}" data-error="Please Select Background Color" required>
                          @endif
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          @if($data->c_status == 1)
                          <span class="text-primary bold"> Deactivate this category <i class="fa fa-hand-o-right mr-2" aria-hidden="true"></i></span>
                            <input type="checkbox" name="cat_status" value="1" checked="checked">
                          @else
                          <span class="text-primary bold"> Activate this category <i class="fa fa-hand-o-right mr-2" aria-hidden="true"></i></span>
                            <input type="checkbox" name="cat_status" value="1">
                          @endif
                        </div>
                      </div>
                    </div>
                     <button type="submit" class="btn btn-primary mr-2">Update</button>
                     <a href="/all-categories" class="btn btn-light">
                    Cancel</a>
                  </form>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
 @endsection
</x-app-layout>