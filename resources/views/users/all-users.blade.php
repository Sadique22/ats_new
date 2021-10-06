<x-app-layout>
 @section('content')
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
                <div class="card-body py-1 px-4 d-flex align-items-center justify-content-between flex-wrap">
                  @if(count($users) > 0)
                  <p class="mb-0 text-white font-weight-medium">All Registered Users</p>
                  <div>
                    <button type="button" id="clear_text" class="btn btn-info" data-toggle="modal" data-target="#sendmessage"> Send Message to Users </button>
                    <input type='button' class="btn btn-primary" id='btn' value='Print' onclick='printDiv();'>
                    <a href="/dashboard" class="btn btn-light bold" id="back-button" onclick="history.go(-1);">Go Back</a>
                  </div>
                  @else
                  <h6 class="text-white mt-2">No user has been Registered Yet!</h6>
                  <a href="/dashboard" class="btn btn-light bold" id="back-button" onclick="history.go(-1);">Go Back</a>
                  @endif
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
                  <p class="card-title">All Users</p>
                  <div class="table-responsive" id='PrintData'>
                    <table border="1" cellpadding="2" id="users" class="display nowrap" style="width:100%">
                      <thead class="head_color">
                        <tr>
                          <th>Name</th>
                          <th>User Name</th>
                          <th>Credits</th>
                          <th>Role</th>
                          <th>Email</th>
                          <th>View Feedbacks</th>
                          <th>Payouts</th>
                          <th>Students/Childrens</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                       @foreach($users as $user)
                        <tr>
                          <td class="text-primary bold"><a href="/user-details/{{$user->id}}" target="_blank">{{$user->name}}</a></td>
                          <td class="text-primary bold">
                            <?php
                              $user_name = strstr($user->email,"@",true);
                            ?>
                            <a href="/user-details/{{$user->id}}" target="_blank">{{$user_name}}</a>
                          </td>
                          <td>{{$user->credit_points}}</td>
                          @if($user->user_type == 2)
                          <td class="text-primary bold"> Teacher </td>
                          @elseif($user->user_type == 3)
                          <td class="text-warning bold"> Student </td>
                          @elseif($user->user_type == 4)
                          <td class="text-danger bold"> Parent </td>
                          @endif
                          @if($user->email_verified_at == '')
                          <td class="text-danger bold">Not Verified <i class="fa fa-times-circle" aria-hidden="true"></i>
                          </td>
                          @else
                          <td class="text-success bold">Verified <i class="fa fa-check-circle" aria-hidden="true"></i>
                          </td>
                          @endif
                          <td><a href="/user-feedbacks/{{$user->id}}/{{$user->user_type}}" class="btn btn-warning">Feedbacks</a></td>
                          <td>
                            @if($user->user_type == 3 || $user->user_type == 4 || $user->user_type == 2)
                            <a href="/user-payouts/{{$user->id}}" target="_blank" class="btn btn-info">Payouts</a>
                            @endif
                          </td>
                          @if($user->user_type == 2)
                          <td><a href="/user-enrolled-student/{{$user->id}}" class="btn btn-info">Enrolled Students</a></td>
                          @elseif($user->user_type == 4)
                          <td><a href="/parent-childrens/{{$user->id}}" class="btn btn-info">View Childrens</a></td>
                          @else
                          <td class="text-center"><i class="fa fa-snowflake-o text-info" aria-hidden="true"></i></td>
                          @endif
                          <td>
                            <a href="/edit-user/{{$user->id}}" class="btn btn-primary">Edit</a>
                            <a href="delete-user/{{$user->id}}" class="btn btn-danger" onclick="return confirm('Are you sure,you want to Delete this User?')">Delete</a> 
                          </td>
                        </tr>
                      </tbody>
                     @endforeach
                    </table>
                  </div>
                   {{ $users->links() }}
                </div>
              </div>
            </div>
          </div>
        </div>
{{--Send Message to Users--}}                        
      <div class="modal fade" id="sendmessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form method="post" action="/admin-send" data-toggle="validator" id="my_form">
              @csrf
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Select Users to Send Message</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <select class="form-control mb-3 @error('category') is-invalid @enderror" name="sent_to[]" data-style="select-with-transition" data-error="Please Select User." multiple="multiple"  required>
                    @foreach ($verifiedUsers as $user)
                    <?php
                      $user_name = strstr($user->email,"@",true);
                    ?>
                      <option value="{{$user->id}}">{{$user->name}} || {{$user_name}}</option>
                    @endforeach 
                  </select>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group"> 
                  <textarea name="message" id="textarea" rows="4" maxlength="240" minlength="5" placeholder="Enter Something" class="form-control" data-error="Please Enter Message" required></textarea>
                    <span id="rchars">0</span> /240
                    <div class="help-block with-errors"></div>
                </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  <input type="submit" name="submit" value="Send" class="btn btn-info">
              </div>
            </form>
          </div>
        </div>
      </div>  

<script type="text/javascript">
//Form Reset
  $(document).ready(function(){
    $('#clear_text').click(function() {
       $('#my_form').trigger("reset");
    });
  });
</script>             
@endsection
</x-app-layout>
