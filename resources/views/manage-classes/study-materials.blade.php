<x-app-layout>
 @section('content')
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
               <div class="card-body py-1 px-4 d-flex justify-content-between flex-wrap d-flex">
                  @if(count($study_material) > 0)
                  <p class="mt-3 text-white font-weight-medium">
                  Study Materials
                  </p>
                  @else
                    @if($UserRole == 2)
                    <h6 class="text-white mt-3">You have not uploaded any Study Materials for Students yet!</h6>
                    @elseif($UserRole == 3 || $UserRole == 4)
                    <h6 class="text-white mt-3">No Study Material Available for your Enrolled Class</h6>
                    @else
                    <h6 class="text-white mt-3">No Study Material Available Yet!</h6>
                    @endif
                  @endif
                  <div>
                    @if($UserRole == 2)
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#studymaterial"> Upload Study Material </button>
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
                  <p class="card-title">All Uploaded Study Materials</p>
                  <div class="table-responsive">
                    <table id="example" class="display nowrap" style="width:100%">
                      <thead class="head_color">
                        <tr>
                          <th>Class Name</th>
                          <th>Material Topic</th>
                          <th>Study Material</th>
                          <th>Uploaded Date</th>
                          @if($UserRole == 2)
                          <th>Status</th>
                          @endif
                          @if($UserRole != 2)
                          <th>Guide</th>
                          @endif
                        </tr>
                      </thead>
                      @foreach($study_material as $material)
                      <tbody>
                        <tr>
                          <td class="text-primary bold">
                            <?php 
                              $c_id = base64_encode($material->class_id);
                              $t_id = base64_encode($material->teacher_id)
                            ?>
                              <a href="class-detail/{{$c_id}}/{{$t_id}}" target="_blank"> {{$material->class_title}}</a>
                          </td>
                          <td>{{$material->material_topic}}</td>
                         <td>
                          <a class="btn btn-info" href="{{url('/') . '/' .$material->study_material}}" target="_blank">View</a>
                         </td> 
                         <?php
                          $originalDate = $material->created_at ;
                          $newDate = date("F d, Y", strtotime($originalDate));
                          ?>
                          <td>{{$newDate}}</td>
                          @if($UserRole==2)
                          <td>
                            @if($material->sm_status == 1)
                             <a href="/material-status/{{$material->sm_status}}/{{$material->sm_id}}" onclick="return confirm('Are you sure,you want to Deactivate the Study Material? Student will not able to see this Material after Deactivating..')" class="btn btn-success">Activated</a>
                            @else
                             <a href="/material-status/{{$material->sm_status}}/{{$material->sm_id}}" class="btn btn-danger">Deactivated</a>
                            @endif
                          </td>
                          @endif
                          @if($UserRole == 3 || $UserRole == 4)
                          <td>
                            {{$material->name}}
                          </td>
                          @elseif($UserRole == 1)
                          <td class="text-primary bold">
                            <a href="/user-details/{{$material->teacher_id}}" target="_blank"> {{$material->name}}</a>
                          </td>
                          @endif
                        </tr>
                      </tbody>
                      @endforeach
                    </table>
                    {{$study_material->links()}}
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>   

{{--Study Material--}}
@if($UserRole == 2)
      <div class="modal fade" id="studymaterial" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
             <form method="post" action="/upload-studymaterial" data-toggle="validator" id="my_form" enctype="multipart/form-data" >
              @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Upload Study Material for the Enrolled Students</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                  <div class="form-group">
                    <select class="form-control mb-3 @error('category') is-invalid @enderror" name="class_id" data-style="select-with-transition" data-error="Please Select Class." required>
                         <option value="">Select Class</option>
                      @foreach ($approved_classes as $class)
                         <option value="{{$class->class_id}}">{{$class->class_title}}</option>
                      @endforeach 
                    </select>
                    <div class="help-block with-errors"></div>
                  </div>
                  <div class="form-group"> 
                    <input type="text" name="topic" placeholder="Enter Topic of the Material" class="form-control" maxlength="140" data-error="Please Enter Topic" required>
                      <div class="help-block with-errors"></div>
                  </div>
                  <div class="form-group"> 
                    <input type="file" name="study_material" onchange="validateDocFile();" class="form-control"data-error="Please Upload Material" accept=".xlsx,.xls,.doc, .docx,.ppt, .pptx,.txt,.pdf" id="doc_file" required>
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
@endsection
</x-app-layout>
