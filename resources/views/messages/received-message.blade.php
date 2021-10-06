<x-app-layout>
 @section('content')
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
               <div class="card-body py-1 px-4 d-flex justify-content-between flex-wrap d-flex">
                   @if(count($messageData) > 0)
                  <p class="mt-3 text-white font-weight-medium">
                   Received Messages
                  </p>
                  @else
                  <h6 class="text-white mt-3">You have not received any Message yet!</h6>
                  @endif
                  <div>
                    @if($UserRole !=1)
                      <button type="button" class="btn btn-info" id="clear_text" data-toggle="modal" data-target="#sendmessage"> Send Message to Admin</button>
                    @endif
                    <a href="/dashboard" class="btn btn-light bold" id="back-button" onclick="history.go(-1);">Go Back</a>
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
                  <p class="card-title">All Messages</p>
                  <div class="table-responsive">
                    <table id="example" class="display nowrap" style="width:100%">
                       
                      <thead class="head_color">
                        <tr>
                          <th>Sent By</th>
                          <th>Received Message</th>
                          <th>Date</th>
                        </tr>
                      </thead>
                      @foreach($messageData as $message)
                      <tbody>
                         <tr>
                          @if($UserRole == 1)
                          <td class="text-primary bold"><a href="/user-details/{{$message->sent_by}}" target="_blank"> {{$message->name}}</a></td>
                          @else
                          <td class="bold">{{ $message->name }}</td>
                          @endif
                          <td class="text-success bold">
                            {{$message->message}}
                            @if($message->flag == "Broadcast Message")
                            <span class="text-danger bold float-right">"Broadcast Message"</span>
                            @endif
                          </td>
                          <?php
                          $originalDate = $message->created_at ;
                          $newDate = date("F d, Y", strtotime($originalDate));
                          ?>
                          <td>{{$newDate}}</td>
                        </tr>
                      </tbody>
                      @endforeach
                    </table>
                    {{$messageData->links()}}
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
{{--Send Message to Admin--}}      
       <div class="modal fade" id="sendmessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form method="post" action="/send-message-admin" data-toggle="validator" id="my_form">
              @csrf
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Send Message to Admin</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                <div class="form-group"> 
                  <textarea name="message" id="textarea" minlength="3" maxlength="240" placeholder="Enter Message" class="form-control" rows="4" data-error="Please Enter Message" required></textarea>
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
@endsection
</x-app-layout>
