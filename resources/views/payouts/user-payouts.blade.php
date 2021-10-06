<x-app-layout>
 @section('content')
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
               <div class="card-body py-1 px-4 d-flex justify-content-between flex-wrap d-flex">
                  @if(count($payouts) > 0)
                  <p class="mt-3 text-white font-weight-medium">
                  All Payment Details
                  </p>
                  @else
                    <h6 class="text-white mt-3">You have not Enrolled to any class yet!</h6>
                  @endif
                    <a href="/dashboard" class="btn btn-light bold" id="back-button" onclick="history.go(-1);">Go Back</a>
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
          @elseif(session()->has('fault'))
            <div class="alert alert-danger" id="show_message">
              <div class="container">
                {{ session()->get('fault') }}
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
                  <div class="table-responsive">
                    <table id="example" class="display nowrap" style="width:100%">
                      <thead class="head_color">
                        <tr>
                          <th>Class Name</th>
                          <th>Payment Status</th>
                          {{--<th>Subscription Status</th>--}}
                          <th>Amount Paid</th>
                          <th>Payer ID</th>
                          <th>Payment Method</th>
                          <th>Payment Date</th>
                        </tr>
                      </thead>
                      @foreach($payouts as $data)
                      <tbody>
                        <tr>
                          <td class="text-primary bold">
                            <?php $c_id = base64_encode($data->class_id);
                                  $t_id = base64_encode($data->created_by);
                            ?>
                           <a href="/class-detail/{{$c_id}}/{{$t_id}}" target="_blank"> {{$data->class_title}} </a>
                          </td>
                          <td>
                            @if($data->status == "Success")
                             <h6 class="text-success bold">Successfull</h6>
                            @else
                             <h6 class="text-danger bold">Failed</h6>
                            @endif
                          </td>
                         {{--  <td>
                            @if($data->is_subscribed == 1)
                             <h6 class="text-success bold">Subscribed</h6>
                            @else
                             <h6 class="text-danger bold">Unsubscribed</h6>
                            @endif 
                          </td> --}}
                          <td class="bold">$ {{$data->amount_paid}}</td>
                          <td class="bold">{{$data->payer_id}}</td>
                          <td class="bold">{{$data->payment_method}}</td>
                          <td>
                            <?php
                              $originalDate = $data->created_at ;
                              $newDate = date("F d, Y", strtotime($originalDate));
                              ?>
                            {{$newDate}}
                          </td>
                        </tr>
                      </tbody>
                      @endforeach
                    </table>
                    {{$payouts->links()}}
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>   
@endsection
</x-app-layout>
