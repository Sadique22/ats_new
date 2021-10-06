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
                  All Payment Details of User
                  </p>
                  @else
                    <h6 class="text-white mt-3">User has not Enrolled to any class yet!</h6>
                  @endif
                  <div>
                    <input type='button' class="btn btn-primary" id='btn' value='Print' onclick='printDiv();'>
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
                  <div class="table-responsive" id='PrintData'>
                    <table border="1" cellpadding="2" id="example" class="display nowrap" style="width:100%">
                      <thead class="head_color">
                        <tr>
                          <th>Class Name</th>
                          <th>Payment Status</th>
                          <th>Amount Paid</th>
                          <th>Payer ID</th>
                          <th>Token</th>
                          <th>Payment Method</th>
                          <th>PayPal Account ID</th>
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
                          <td class="bold">$ {{$data->amount_paid}}</td>
                          <td>{{$data->payer_id}}</td>
                          <td>{{$data->token}}</td>
                          <td>{{$data->payment_method}}</td>
                          <td>
                            @if($data->payment_method == 'paypal')
                              {{$data->payer_paypal_acount_id}}
                            @else
                              <h6 class="text-center"><i class="fa fa-snowflake-o text-info" aria-hidden="true"></i></h6>
                            @endif
                          </td>
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
                  </div>
                  {{$payouts->links()}}
                </div>
              </div>
            </div>
          </div>
      </div> 
@endsection
</x-app-layout>
