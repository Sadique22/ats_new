<x-app-layout>
 @section('content')
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
               <div class="card-body py-1 px-4 d-flex justify-content-between flex-wrap d-flex">
                   @if(count($promo_data) > 0)
                  <p class="mt-3 text-white font-weight-medium">
                  Manage Promocodes
                  </p>
                  @else
                  <h6 class="text-white mt-3">You have not created any Promocode yet!</h6>
                  @endif
                  <div>
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#sendfeedback"> Add Promocode </button>
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
                  <p class="card-title"> All Promocodes</p>
                  <div class="table-responsive">
                    <table id="example" class="display nowrap" style="width:100%">
                      <thead class="head_color">
                        <tr>
                          <th>Promo Name</th>
                          <th>Promo Code</th>
                          <th>Offer</th>
                          <th>Type</th>
                          <th>Start Date / Time</th>
                          <th>Expiry Date / Time</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      @foreach($promo_data as $promo)
                      <tbody>
                         <tr>
                          <td class="bold">{{$promo->promo_name}}</td>
                          <td class="text-primary bold">{{$promo->promo_code}}</td>
                          <td class="bold">{{$promo->promo_offer}}</td>
                          <td class="bold">{{$promo->promo_type}}</td>
                          <?php
                          $start_datetime = $promo->promo_start; 
                          $s_date = date('d-m-Y', strtotime($start_datetime));
                          $s_time = date('h:i A', strtotime($start_datetime));
                          ?>
                          <td class="bold">{{$s_date}} || {{$s_time}}</td>
                          <?php
                          $start_datetime = $promo->promo_expiry; 
                          $e_date = date('d-m-Y', strtotime($start_datetime));
                          $e_time = date('h:i A', strtotime($start_datetime));
                          ?>
                          <td class="bold">{{$e_date}} || {{$e_time}}</td>
                          <td>
                            @if($promo->promo_status == 1)
                             <a href="/promo-status/{{$promo->promo_status}}/{{$promo->promo_id}}" onclick="return confirm('Are you sure,you want to Deactivate the Promocode?')" class="btn btn-success">Activated</a>
                            @else
                             <a href="/promo-status/{{$promo->promo_status}}/{{$promo->promo_id}}" class="btn btn-danger">Deactivated</a>
                            @endif
                          </td>
                        </tr>
                      </tbody>
                      @endforeach
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div> 
{{--Add Promocode Modal--}}      
      <div class="modal fade" id="sendfeedback" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <form method="post" action="/add-promocode" data-toggle="validator" id="my_form">
                    @csrf
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Please Enter Details to add Promo Code</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group"> 
                          <input type="text" name="promo_name" placeholder="Please Enter Promocode Name." class="form-control" data-error="Please Enter Promocode Name." required>
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group"> 
                          <input type="text" name="promo_code" placeholder="Please Enter Promocode." class="form-control" data-error="Please Enter Promocode." required>
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                          <select class="form-control mb-3 @error('category') is-invalid @enderror" name="promo_type" data-style="select-with-transition" data-error="Please Select Type." required onchange="if (this.value=='3'){this.form['promo_offer'].style.visibility='hidden'}else {this.form['promo_offer'].style.visibility='visible'};">
                                <option value="3">Free</option>
                                <option value="1">% discount</option>
                                <option value="2">Flat discount</option>
                          </select>
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group"> 
                          <input type="text" name="promo_offer" style="visibility: hidden; " placeholder="Please Enter Promocode Offer" class="form-control" data-error="Please Enter Offer.">
                        </div>
                         <label >Select Start Date/Time for Promocode</label>
                        <div class="form-group">
                          <input type="datetime-local" name="promo_start" class="form-control mb-3 first_date" data-error="Please Select Start Date/Time." required/>
                            <div class="help-block with-errors"></div>
                        </div>
                        <label >Select Expiry Date/Time for Promocode</label>
                        <div class="form-group">
                          <input type="datetime-local" name="promo_expiry" class="form-control mb-3 second_date" data-error="Please Select Expiry Date/Time." required/>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <input type="submit" name="submit" value="Add Promocode" class="btn btn-info">
                    </div>
                  </form>
              </div>
          </div>
      </div>   
<script type="text/javascript">
  $(document).ready(function () {
    $('.first_date').on('change', function() { 
      var datearray = $('.first_date').val();
      $('.second_date').attr('min',datearray); 
    });
  });
</script>
@endsection
</x-app-layout>
