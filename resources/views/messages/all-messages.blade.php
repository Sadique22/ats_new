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
                  <h6 class="text-white mt-3">No Information available yet!</h6>
                  @endif
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
                  <p class="card-title">All Messages</p>
                  <div class="table-responsive">
                    <table id="example" class="display nowrap" style="width:100%">
                      <thead class="head_color">
                        <tr>
                          <th>Sent To</th>
                          <th>Sent By</th>
                          <th>Received Message</th>
                          <th>Date</th>
                        </tr>
                      </thead>
                      @foreach($messageData as $message)
                      <tbody>
                         <tr>
                          @if($message->sent_to_usertype == 1)
                          <td class="bold">{{$message->sent_to_name}}</td>
                          @else
                          <td class="text-primary bold"><a href="/user-details/{{$message->sent_to}}" target="_blank"> {{$message->sent_to_name}}</a></td>
                          @endif
                          @if($message->sent_by_usertype == 1)
                          <td class="bold">{{$message->sent_by_name}}</td>
                          @else
                          <td class="text-primary bold"><a href="/user-details/{{$message->sent_by}}" target="_blank"> {{$message->sent_by_name}}</a></td>
                          @endif
                          <td class="text-success bold">{{$message->message}}</td>
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
@endsection
</x-app-layout>
