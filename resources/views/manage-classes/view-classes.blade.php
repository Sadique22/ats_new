<x-app-layout>
 @section('content')
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
               @if($UserRole == 2 || $UserRole == 1)
                <div class="card-body py-1 px-4 d-flex justify-content-between flex-wrap d-flex">
                    @if(count($classes) > 0)
                    <p class="mt-3 text-white font-weight-medium">
                     All Published Classes 
                    </p>
                    @else
                    <h6 class="text-white mt-2">You have not Created any CLass yet!</h6>
                    @endif
                    <div class="mt-1">
                      <a href="/dashboard" class="btn btn-light bold" id="back-button" onclick="history.go(-1);">Go Back</a>
                    </div>
                </div>
                @else
                <div class="card-body py-1 px-4 d-flex justify-content-between flex-wrap d-flex">
                    @if(count($classes) > 0)
                    <p class="mt-3 text-white font-weight-medium">
                     All Enrolled Classes
                    </p>
                    <div class="mt-1">
                      <button type="button" id="clear_texts" class="btn btn-info hide_val_error" data-toggle="modal" data-target="#sendfeedback"> Give Feedback to Teacher </button>
                      <button type="button" class="btn btn-primary" id="clear_text" data-toggle="modal" data-target="#sendmessage"> Send Message/Ask Question </button>
                      <a href="/dashboard" class="btn btn-light bold" id="back-button" onclick="history.go(-1);">Go Back</a>
                    </div>
                    @else
                    <h6 class="text-white mt-2">You have not Enrolled to any Class yet!</h6>
                    <a href="/dashboard" class="btn btn-light bold" id="back-button" onclick="history.go(-1);">Go Back</a>
                    @endif
                </div>
                @endif
              </div>
            </div>
          </div>
          @if(session()->has('message'))
            <div class="alert alert-success" id="show_message">
              <div class="container">
                {{ session()->get('message') }}
              </div>
            </div>
            @elseif(session()->has('fault'))
            <div class="alert alert-danger hide_error" id="show_message">
              <div class="container">
                {{ session()->get('fault') }}
              </div>
            </div>
          @endif
          @if ($errors->any())
            <div class="alert alert-danger hide_error" id="show_message">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                 @endforeach
              </ul>
            </div>
          @endif
          @if(isset($custom_message))
          <div class="alert alert-success hide_error" id="show_message">
              <div class="container">
                {{ $custom_message }}
              </div>
          </div>
          @endif
          <div class="row">
            <div class="col-md-12 stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex">
                     <p class="card-title mt-2">Class Details</p>
                        <div class="ml-auto">
                          <ul class="nav nav-tabs sort_nav mr-4" id="myTab">
                              <li><a href="/view-classes" class="{{(Request::segment(1) == 'view-classes')?'mm-active':'' }}">All Classes</a></li>
                              <li><a href="/upcoming-classes" class="{{(Request::segment(1) == 'upcoming-classes')?'mm-active':'' }}">Upcoming Classes</a></li>
                              <li><a href="/completed-classes" class="{{(Request::segment(1) == 'completed-classes')?'mm-active':'' }}">Completed Classes</a></li>
                            @if($UserRole == '1' || $UserRole == '2')
                              <li><a href="/approved-classes" class="{{(Request::segment(1) == 'approved-classes')?'mm-active':'' }}">Approved Classes</a></li>
                              <li><a href="/declined-classes" class="{{(Request::segment(1) == 'declined-classes')?'mm-active':'' }}">Declined Classes</a></li>
                              <li><a href="/pending-classes" class="{{(Request::segment(1) == 'pending-classes')?'mm-active':'' }}">Pending Approval</a></li>
                            @endif
                            @if($UserRole == '2')
                              <li><a href="/saved-classes" class="{{(Request::segment(1) == 'saved-classes')?'mm-active':'' }}">Saved Classes</a></li>
                            @endif
                            @if($UserRole == '1')
                              <li><a href="/featured-classes" class="{{(Request::segment(1) == 'featured-classes')?'mm-active':'' }}">Featured Classes</a></li>
                            @endif
                          </ul>
                        </div>
                      @if($UserRole == 4)
                        <div>
                          <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Select Child <i class="fa fa-info-circle fa-lg text-white" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="View Enrolled class of selected children"></i></button>
                            <div class="dropdown-menu">
                              @foreach($childrens as $children)
                                <?php
                                  $child_id = base64_encode($children->child_id);
                                ?>
                                <a class="dropdown-item" href="/enrolled_classes/{{$child_id}}">{{$children->child_name}}</a>
                              @endforeach
                            </div>
                        </div>
                      @endif

                  </div>
                  <div class="table-responsive">
                    <table id="example" class="display nowrap" style="width:100%">
                      <thead class="head_color">
                        <tr>
                            <th>Class</th>
                            <th>Category</th>
                            <th> Date</th>
                            @if($UserRole == 2)
                            <th>Status</th>
                            <th>Links/Recordings</th>
                            <th>Manage Schedule</th>
                            <th>Action</th>
                            <th>Class Feedback</th>
                            @endif

                            @if($UserRole == 1)
                            <th>Teacher</th>
                            <th> Class Details </th>
                            <th>Class Feedback</th>
                            <th>Links/Recordings</th>
                            <th>Class Request</th>
                            <th>Edit</th>
                            <th>Featured Status</th>
                            @endif

                            @if($UserRole == 3 || $UserRole == 4)
                            <th>Guide</th>
                            <th>View</th>
                            <th>Links/Recordings</th>
                            <th>Give Feedback to Class</th>
                            <th>Subscription</th>
                            @endif
                        </tr>
                      </thead>
                        @foreach($classes as $class)
                      <tbody>
                         <tr>
                            <td class="bold text-primary">
                              <?php $c_id = base64_encode($class->id);
                                   $t_id = base64_encode($class->created_by);
                              ?>
                              <a href="class-detail/{{$c_id}}/{{$t_id}}" target="_blank"> {{$class->class_title}}</a>
                            </td>
                            <td>{{$class->c_name}}</td>
                            <td>
                              <?php
                                 $originalDate = $class->live_date ;
                                 $newDate = date("F d, Y", strtotime($originalDate));
                              ?>
                              {{$newDate}}
                            </td>
                            @if($UserRole == 2)
                              @if($class->status == 0)
                               <td class="text-warning bold">Pending</td>
                              @elseif($class->status == 2)
                               <td class="text-danger bold">Declined <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Please Review your class details and then send for approval again.(Click on Review & Submit Button)"></i></td>
                              @elseif($class->status == 3)
                               <td class="text-danger bold">Saved <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Saved Class, Click on Continue & Submit Button and submit Class for Approval."></i></td>
                              @else
                               <td class="text-success bold">Approved</td>
                              @endif
                               <td><a href="/class-links/{{$class->id}}" class="btn btn-info">Links</a></td>
                               <td><a href="/class-schedule/{{$class->id}}" class="btn btn-info">Schedule</a></td>
                               <td>
                                <a href="/view-details/{{$class->id}}" class="btn btn-primary"> Details</a>
                                @if($class->status == 3)
                                <a href="edit-class/{{$class->id}}"class="btn btn-danger">Continue & Submit </a>
                                @elseif($class->status == 2)
                                <a href="edit-class/{{$class->id}}"class="btn btn-danger"> Review & Submit  </a>
                                @else
                                <a href="edit-class/{{$class->id}}"class="btn btn-warning"> Edit  </a>
                                @endif
                                {{-- <a href="delete-class/{{$class->id}}" enctype="multipart/form-data">
                                <button class="btn btn-danger"> Delete </button>
                                </a> --}}
                               </td>
                               <td><a href="/class-feedbacks/{{$class->id}}" class="btn btn-primary">Feedbacks</a></td>
                            @endif

                            @if($UserRole == 1)
                            <td class="text-primary bold"><a href="/user-details/{{$class->created_by}}" target="_blank">{{$class->name}}</a></td>
                            <td><a href="/view-details/{{$class->id}}" class="btn btn-primary adjust_btn_size">Details</a></td>
                            <td><a href="/class-feedbacks/{{$class->id}}" class="btn btn-primary adjust_btn_size">Feedbacks</a></td>
                            <td><a href="/manage-links/{{$class->id}}" class="btn btn-primary adjust_btn_size">Manage Links</a></td>
                            <td>
                              @if($class->status == 1)
                                <a href="/decline-class/{{$class->id}}" enctype="multipart/form-data" class="btn btn-success adjust_btn_size" onclick="return confirm('Are you sure,you want to Decline the Class?')"> Approved </a>
                              @elseif($class->status == 0)
                                <a href="/approve-class/{{$class->id}}" enctype="multipart/form-data" class="btn btn-info adjust_btn_size"> Approve  </a>
                                <a href="/decline-class/{{$class->id}}" enctype="multipart/form-data" class="btn btn-danger adjust_btn_size" onclick="return confirm('Are you sure,you want to Decline the Class?')"> Decline </a>
                              @elseif($class->status == 2)
                                <a href="/approve-class/{{$class->id}}" enctype="multipart/form-data" class="btn btn-danger adjust_btn_size"> Declined </a>
                              @endif
                            </td>
                            <td><a href="edit-class/{{$class->id}}"class="btn btn-info adjust_btn_size"> Edit  </a></td>
                            <td>
                             @if($class->is_featured == 0)
                              <a href="/featured-status/{{$class->is_featured}}/{{$class->id}}" enctype="multipart/form-data" class="btn btn-warning adjust_btn_size">Not Featured</a>
                              @else
                                <a href="/featured-status/{{$class->is_featured}}/{{$class->id}}" enctype="multipart/form-data" class="btn btn-primary adjust_btn_size"> Featured</a>
                              @endif
                            </td>
                            @endif

                              @if($UserRole == 3 || $UserRole == 4)
                              <td>{{$class->name}}</td>
                              <td><a href="/view-details/{{$class->id}}" class="btn btn-primary">Class Details</a></td>
                              <td><a href="/class-links/{{$class->id}}" class="btn btn-warning">View</a></td>
                              <td><a href="/send-feedback/{{$class->id}}" class="btn btn-info">Send Feedback</a></td>
                              <td><a href="/class-unsubscribe/{{$class->enr_id}}/{{$class->id}}" onclick="return confirm('Are you sure,you want to Unsubscribe this Class?')" class="btn btn-danger">Unsubscribe Class</a></td>
                              @endif
                        </tr>
                      </tbody>
                    @endforeach
                  </table>
                  {{ $classes->links() }}

{{--Feedback Modal--}}
                        @if($UserRole == 3 || $UserRole == 4)
                        <div class="modal fade" id="sendfeedback" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              {{-- @if(session()->has('message'))
                                  <div class="alert alert-success hide_modal_error" id="show_message">
                                    <div class="container">
                                      {{ session()->get('message') }}
                                    </div>
                                  </div>
                                  @elseif(session()->has('fault'))
                                  <div class="alert alert-danger hide_modal_error" id="show_message">
                                    <div class="container">
                                      {{ session()->get('fault') }}
                                    </div>
                                  </div>
                                @endif
                                @if ($errors->any())
                                  <div class="alert alert-danger hide_modal_error" id="show_message">
                                    <ul>
                                      @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                       @endforeach
                                    </ul>
                                  </div>
                                @endif
                                @if(isset($custom_message))
                                <div class="alert alert-success hide_modal_error" id="show_message">
                                    <div class="container">
                                      {{ $custom_message }}
                                    </div>
                                </div>
                                @endif  --}}
                               <form method="post" action="/teacher-feedback" data-toggle="validator" onsubmit="return checkRating()">
                                @csrf
                                  <div class="modal-header">
                                     <h5 class="modal-title" id="exampleModalLabel">Please Enter Feedback for the Teacher</h5>
                                     <button type="button" class="close hide_val_error"  data-dismiss="modal" aria-label="Close">
                                     <span aria-hidden="true">&times;</span>
                                     </button>
                                  </div>
                                  <div class="modal-body">
                                    <div class="form-group">
                                      <select class="form-control mb-3 @error('category') is-invalid @enderror" name="teacher_name" data-style="select-with-transition" data-error="Please Select Teacher" required>
                                        <option value="">Select Teacher</option>
                                      @foreach ($teachers as $class)
                                        <option value="{{$class->id}}">{{$class->name}}</option>
                                      @endforeach 
                                      </select>
                                      <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="form-group">
                                      <textarea id="feedback_text" maxlength="190" name="feedback" placeholder="Enter Feedback" class="form-control" rows="3" data-error="Please Enter Feedback" required></textarea>
                                      <span id="tchars">0</span> /190
                                      <div class="help-block with-errors"></div>
                                    </div>
                                    <label class="text-dark">Select Star Rating:</label>
                                    <div class="star-rating-teacher">
                                        <input id="star-5" type="radio" name="rating" value="5" />
                                        <label for="star-5" title="5 stars">
                                          <i class="active fa fa-star" aria-hidden="true"></i>
                                        </label>
                                        <input id="star-4" type="radio" name="rating" value="4" />
                                        <label for="star-4" title="4 stars">
                                          <i class="active fa fa-star" aria-hidden="true"></i>
                                        </label>
                                        <input id="star-3" type="radio" name="rating" value="3" />
                                        <label for="star-3" title="3 stars">
                                          <i class="active fa fa-star" aria-hidden="true"></i>
                                        </label>
                                        <input id="star-2" type="radio" name="rating" value="2" />
                                        <label for="star-2" title="2 stars">
                                          <i class="active fa fa-star" aria-hidden="true"></i>
                                        </label>
                                        <input id="star-1" type="radio" name="rating" value="1" />
                                        <label for="star-1" title="1 star">
                                          <i class="active fa fa-star" aria-hidden="true"></i>
                                        </label>
                                      </div>
                                  </div>
                                  <div class="modal-footer">
                                     <button type="button" class="btn btn-danger hide_val_error" data-dismiss="modal">Close</button>
                                     <input type="submit" name="submit" value="Send" class="btn btn-info">
                                  </div>
                               </form>
                            </div>
                          </div>
                        </div>                      
{{--Send Message By Student--}}                        
                        <div class="modal fade" id="sendmessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                               <form method="post" action="/send-message" data-toggle="validator">
                                @csrf
                                  <div class="modal-header">
                                     <h5 class="modal-title" id="exampleModalLabel">Please Enter Message/ Ask Question to Teacher</h5>
                                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                     <span aria-hidden="true">&times;</span>
                                     </button>
                                  </div>
                                  <div class="modal-body">
                                    <div class="form-group">
                                      <select class="form-control mb-3 @error('category') is-invalid @enderror" name="sent_to" data-style="select-with-transition" data-error="Please Select Teacher." required>
                                        <option value="">Select Teacher</option>
                                      @foreach ($classes as $class)
                                        <option value="{{$class->created_by}}">{{$class->name}}</option>
                                      @endforeach 
                                      </select>
                                       <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="form-group"> 
                                     <textarea id="textarea" minlength="0" maxlength="240" name="message" placeholder="Enter Something to ask..." class="form-control" rows="4" data-error="Please Enter Message" required></textarea>
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
                        @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>  

<script type="text/javascript">
//Check Rating Input
function checkRating() {
  var a = $("input[name='rating']:checked").val();
    if (a == null || a == "" || a == undefined) {
      Swal.fire({
        text: "Please Select Rating!",
        icon: 'info',
        confirmButtonText: 'Okay, got it!'
      });
      return false;
    }else{
      return true;
    }
}
</script>         
    {{-- @if (count($errors) > 0 || session()->has('fault'))
      <script type="text/javascript">
        $(document).ready(function() {
          $('.hide_error').hide();
          $('#sendfeedback').modal('show');
        });
        $(document).ready(function(){
            $('.hide_val_error').click(function() {
              $('.hide_modal_error').hide();
            });
        });
      </script>
      @endif  --}} 
      
{{--  <script type="text/javascript">
        $(function(){
              
          $("#approve_form").on('submit', function(e){
              e.preventDefault();

              $.ajax({
                  url:$(this).attr('action'),
                  method:$(this).attr('method'),
                  data:new FormData(this),
                  processData:false,
                  dataType:'json',
                  contentType:false,
                  beforeSend:function(){
                      $(document).find('span.error-text').text('');
                  },
                  success:function(data){
                      if(data.status == 0){
                          $.each(data.error, function(prefix, val){
                              $('span.'+prefix+'_error').text(val[0]);
                          });
                      }else{
                         // $('#approve_form')[0].reset();
                          alert(data.msg);
                          //$('#example').dataTable().fnDraw();
                          //$('#exampleone').DataTable().ajax.reload();
                      }
                  }
              });
          });
      });

//decline
        $(function(){
              
          $("#decline_form").on('submit', function(e){
              e.preventDefault();

              $.ajax({
                  url:$(this).attr('action'),
                  method:$(this).attr('method'),
                  data:new FormData(this),
                  processData:false,
                  dataType:'json',
                  contentType:false,
                  beforeSend:function(){
                      $(document).find('span.error-text').text('');
                  },
                  success:function(data){
                      if(data.status == 0){
                          $.each(data.error, function(prefix, val){
                              $('span.'+prefix+'_error').text(val[0]);
                          });
                      }else{
                          //$('#decline_form')[0].reset();
                          alert(data.msg);
                          //$('#exampleone').DataTable().ajax.reload();
                      }
                  }
              });
          });
      });
      </script>  --}}
     
@endsection
</x-app-layout>
<style type="text/css">
  .mm-active {
    color: blue !important;
    font-weight: 700 !important;
  }
</style>
