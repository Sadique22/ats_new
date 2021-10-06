<x-app-layout>
 @section('content')
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
                <div class="card-body py-1 px-4 d-flex align-items-center justify-content-between flex-wrap">
                  <p class="mb-0 text-white font-weight-medium">Manage Categories</p>
                  <button class="btn btn-light bold" id="back-button" onclick="history.go(-1);">Go Back</button>
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
                  <p class="card-title">Categories</p>
                  <div class="table-responsive">
                    <table id="example" class="display nowrap" style="width:100%">
                      <thead class="head_color">
                        <tr>
                            <th>Image</th>
                            <th>Category Name</th>
                            <th>Action</th>
                        </tr>
                      </thead>
                        @foreach($categories as $category)
                      <tbody>
                        <tr>
                          <td>
                            @if($category->category_image == null && $category->c_status == 0 )
                            <span class="float-right new-category mr-1">Update</span>
                            @endif
                            @if($category->category_image != null)
                            <img class="img-fluid" src="{{$category->category_image}}" style="background-color: {{$category->bg_color}}; width: 80px;height: 50px;" />
                            @else
                            <img class="img-fluid" src="{{asset('assets/img/new-icon.png')}}" style="background-color: {{$category->bg_color}}; width: 80px;height: 50px;" />
                            @endif
                          </td>
                          <td>{{$category->c_name}}</td>
                          <td>
                            @if($category->c_status == 1)
                             <a href="/category-status/{{$category->c_status}}/{{$category->c_id}}" onclick="return confirm('Are you sure,you want to Deactivate the Category?')" class="btn btn-success">Activated</a>
                            @else
                            @if($category->category_image == null && $category->c_status == 0 )
                             <a href="/category-status/{{$category->c_status}}/{{$category->c_id}}" class="btn btn-danger disabled">Deactivated</a>
                            @else
                             <a href="/category-status/{{$category->c_status}}/{{$category->c_id}}" class="btn btn-danger">Deactivated</a>
                            @endif
                            @endif
                            <a href="edit-category/{{$category->c_id}}" enctype="multipart/form-data" class="btn btn-primary">Edit</a>
                            <a href="delete-category/{{$category->c_id}}" enctype="multipart/form-data" class="btn btn-danger">Delete</a>
                          </td>
                        </tr>
                      </tbody>
                      @endforeach
                    </table>
                    {{$categories->links()}}
                  </div>
                </div>
              </div>
            </div>
          </div>
       </div>
@endsection
</x-app-layout>
