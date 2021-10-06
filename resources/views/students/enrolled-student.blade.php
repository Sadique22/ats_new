<x-app-layout>
 @section('content')
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
                <div class="card-body py-1 px-4 d-flex justify-content-between flex-wrap d-flex">
                  @if(count($e_classes) > 0)
                  <p class="mt-3 text-white font-weight-medium">
                   All Enrolled Students
                  </p>
                  <div>
                    @if($UserRole == 1)
                      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sendmessagebyadmin" id="clear_text"> Send Message </button>
                    @elseif($UserRole == 2)
                      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sendmessagebyteacher" id="clear_text"> Send Message </button>
                    @endif
                    <a href="/dashboard" class="btn btn-light bold" id="back-button" onclick="history.go(-1);">Go Back</a>
                  </div>
                  @else
                  @if($UserRole == 2)
                  <h6 class="text-white mt-3">No student Enrolled to your class yet!</h6>
                  @else
                  <h6 class="text-white mt-3">No student Enrolled to this class yet!</h6>
                  @endif
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
                  <div class="d-flex">
                    <p class="card-title mt-3">Enrolled Student Details</p>
                   </div>
                  <div class="table-responsive">
                    <table id="example" class="display nowrap" style="width:100%">
                      <thead class="head_color">
                        <tr>
                           <th>Student Name</th>
                           <th>Enrolled Class</th>
                        </tr>
                      </thead>
                        @foreach($e_classes as $classes)
                      <tbody>
                        <tr>
                          @if($UserRole == 1)
                          <td class="text-primary bold"><a href="/user-details/{{$classes->student_id}}" target="_blank">{{$classes->name}}</a></td>
                          @else
                          <td class="bold">{{$classes->name}}</td>
                          @endif
                          <td class="text-primary bold">
                            <?php 
                              $c_id = base64_encode($classes->class_id);
                              $t_id = base64_encode($classes->teacher_id)
                            ?>
                              <a href="/class-detail/{{$c_id}}/{{$t_id}}" target="_blank"> {{$classes->class_title}}</a>
                          </td>
                        </tr>
                      </tbody>
                      @endforeach
                    </table>
                    {{$e_classes->links()}}
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
{{--Message By Teacher to Enrolled Students--}}                    
      <div class="modal fade" id="sendmessagebyteacher" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form method="post" action="/send-message" data-toggle="validator" id="my_form">
              @csrf
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Send Message Enrolled Students</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <select class="form-control mb-3 @error('category') is-invalid @enderror" name="sent_to" data-style="select-with-transition" data-error="Please Select Teacher." required>
                    @foreach ($e_classes as $class)
                    <?php
                      $user_name = strstr($class->email,"@",true);
                    ?>
                      <option value="{{$class->student_id}}">{{$class->name}} || {{$user_name}}</option>
                    @endforeach 
                  </select>
                  <span class="text-danger error-text sent_to_error"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group"> 
                  <textarea id="textarea" name="message" minlength="5" maxlength="240" placeholder="Enter Something" class="form-control" data-error="Please Enter Message" rows="4" required></textarea>
                    <span id="rchars">0</span> /240
                    <span class="text-danger error-text message_error"></span>
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
{{--Message By admin to Enrolled Students--}}
      <div class="modal fade" id="sendmessagebyadmin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form method="post" action="/admin-send" data-toggle="validator" id="my_form">
              @csrf
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Send Message Enrolled Students</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <select class="form-control mb-3 @error('category') is-invalid @enderror" name="sent_to[]" data-style="select-with-transition" data-error="Please Select Teacher." multiple="multiple" required>
                    @foreach ($e_classes as $class)
                    <?php
                      $user_name =  strstr($class->email,"@",true);
                    ?>
                      <option value="{{$class->student_id}}">{{$class->name}} || {{$user_name}}</option>
                    @endforeach 
                  </select>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group"> 
                  <textarea name="message" minlength="5" maxlength="240" placeholder="Enter Something" class="form-control" data-error="Please Enter Message" required></textarea>
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

{{-- <script type="text/javascript">
  $(function(){
              
    $("#my_form").on('submit', function(e){
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
                    $('#my_form')[0].reset();
                    alert(data.msg);
                 $('#sendmessagebyteacher').modal('hide');
                }
            }
        });
    });
});
</script>   --}}          
@endsection
</x-app-layout>
