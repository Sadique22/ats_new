<x-app-layout>
 @section('content')
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
                <div class="card-body py-1 px-4 d-flex justify-content-between flex-wrap d-flex">
                  @if(count($keywords) > 0)
                  <p class="mt-3 text-white font-weight-medium">
                   All Keywords
                  </p>
                  @else
                  <h6 class="text-white mt-3">No Keywords added yet!</h6>
                  @endif
                  <div>
                     <button class="btn btn-light bold" id="back-button" onclick="history.go(-1);">Go Back</button>
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
                  <div class="d-flex">
                    <p class="card-title mt-3">All Keyword Details</p>
                   </div>
                  <div class="table-responsive">
                    <table id="users" class="display nowrap" style="width:100%">
                      <thead class="head_color">
                        <tr>
                           <th>Class Name</th>
                           <th> Keyword </th>
                           <th>Action</th>
                        </tr>
                      </thead>
                        @foreach($keywords as $keyword)
                      <tbody>
                        <tr>
                          <td class="text-info bold">
                            <?php 
                              $c_id = base64_encode($keyword->id);
                              $t_id = base64_encode($keyword->created_by)
                            ?>
                            <a href="class-detail/{{$c_id}}/{{$t_id}}" target="_blank"> {{$keyword->class_title}}</a>
                          </td>
                          <?php 
                           $data =  preg_replace('/(,)(?=[^\s])/', ', ',  $keyword->keywords)
                          ?>
                          <td class="text-primary bold">{{ $data }}</td>
                          <td><a href="/edit-keyword/{{$keyword->id}}" class="btn btn-warning">Edit</a></td>
                        </tr>
                      </tbody>
                      @endforeach
                    </table>
                    {{$keywords->links()}}
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>    
@endsection
</x-app-layout>
