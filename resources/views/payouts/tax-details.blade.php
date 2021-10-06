<x-app-layout>
 @section('content')
 @foreach($tax_data as $data)
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
               <div class="card-body py-1 px-4 d-flex justify-content-between flex-wrap d-flex">
                  <h6 class="text-white mt-3">Any Time Study: Service Tax Management</h6>
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
                          <th>ATS Tax</th>
                          <th>Service Fees</th>
                          <th>Last Updated</th>
                          <th>Update Tax</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td class="bold text-info">{{$data->ats_tax}} %</td>
                          <td class="bold text-info">{{$data->service_fees}} %</td>
                          <td class="bold text-info">
                            <?php
                              $originalDate = $data->updated_at ;
                              $newDate = date("F d, Y", strtotime($originalDate));
                            ?>
                            {{$newDate}}
                          </td>
                          <td>
                            <button type="button" id="clear_text" class="btn btn-primary" data-toggle="modal" data-target="#updatedata"> Update </button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>

{{--Update Tax Details--}}                        
    <div class="modal fade" id="updatedata" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form method="post" action="/update-tax" data-toggle="validator" id="my_form" onsubmit="return checkTaxInputs()">
            @csrf
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Enter Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Enter New ATS Tax (%)</label> 
                <input type="number" value="{{$data->ats_tax}}" id="ats_tax_input" step="any" name="ats_tax" class="form-control">
                <div class="help-block with-errors"></div>
              </div>
              <div class="form-group">
                <label>Enter New Service Fees (%)</label> 
                <input type="number" value="{{$data->service_fees}}" id="service_fees_input" step="any" name="service_fees" class="form-control">
                <div class="help-block with-errors"></div>
              </div>
              <input type="hidden" name="ttd_id" value="{{$data->ttd_id}}">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <input type="submit" name="submit" value="Update Data" class="btn btn-info">
            </div>
          </form>
        </div>
      </div>
    </div> 

@endforeach               
@endsection
</x-app-layout>
