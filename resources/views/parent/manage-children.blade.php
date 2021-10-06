<x-app-layout>
 @section('content')
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
                <div class="card-body py-1 px-4 d-flex align-items-center justify-content-between flex-wrap">
                  @if(count($childrens) > 0)
                  <p class="mb-0 text-white font-weight-medium">Children Details</p>
                  @else
                  <h6 class="text-white mt-2">You have not added any children yet!</h6>
                  @endif
                  <div>
                    @if(count($childrens) == 3)
                    <a class="btn btn-primary disabled">Add Child</a>
                    <i class="fa fa-info-circle fa-lg text-white" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="You cannot add more than 3 Children"></i>
                    @else
                    <button type="button" class="btn btn-primary" id="add_child_button" data-toggle="modal" data-target="#addchildren"> Add Child </button>
                    @endif
                    <a href="/dashboard" class="btn btn-light bold">Go Back</a>
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
<!--                   <p class="card-title">Childrens</p>
 -->                  <div class="table-responsive">
                      <table id="users" class="display nowrap" style="width:100%">
                      <thead class="head_color">
                        <tr>
                          <th>Name</th>
                          <th>Age</th>
                          <th>Gender</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($childrens as $user)
                          <tr>
                            <td class="bold">{{$user->child_name}}</td>
                            <td class="bold">{{$user->child_age}}</td>
                            <td class="bold">{{$user->child_gender}}</td>
                            <td>
                              <?php
                                $child_id = base64_encode($user->child_id);
                              ?>
                              <a href="/edit-children/{{$child_id}}" class="btn btn-primary">Edit</a>
                              <a href="/delete-children/{{$user->child_id}}" class="btn btn-danger">Delete</a> 
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                    {{ $childrens->links() }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
{{-- Add New Children --}}                        
      <div class="modal fade" id="addchildren" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form method="post" action="/add-children" data-toggle="validator" id="my_form">
              @csrf
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Enter Details of your Child</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label>Child Name <span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Please Enter The Name of your Child"></i></label>
                  <input type="text" value="{{ old('child_name') }}" maxlength="50" minlength="2" class="form-control @error('child_name') is-invalid @enderror"  name="child_name" placeholder="Child Name" data-error="Enter Child Name." required>
                  <div class="help-block with-errors"></div>
                </div>
                <div class="form-group"> 
                  <label>Child Age <span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Please Enter Age of your Child."></i></label>
                  <input type="number" value="{{ old('child_age') }}" class="form-control @error('child_age') is-invalid @enderror" max="20" min="6"  name="child_age" placeholder="Enter Child Age." data-error="Enter Child Age." required>
                  <div class="help-block with-errors"></div>
                </div>
                <div class="form-group">
                  <label>Select Gender<span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Select Gender"></i></label>
                    <select class="form-control @error('child_gender') is-invalid @enderror" name="child_gender" data-style="select-with-transition" data-error="Select Gender" required>
                      <option value="">Select</option>
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                    </select>
                    <div class="help-block with-errors"></div>
                </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  <input type="submit" name="submit" value="Submit Data" class="btn btn-info">
              </div>
            </form>
          </div>
        </div>
      </div>  
<script type="text/javascript">
  $(document).ready(function(){
    $('#add_child_button').click(function() {
       $('#my_form').trigger("reset");
    });
});
</script>             
@endsection
</x-app-layout>
