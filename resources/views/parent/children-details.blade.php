<x-app-layout>
 @section('content')
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
                <div class="card-body py-1 px-4 d-flex justify-content-between flex-wrap d-flex">
                  @if(count($childrens) > 0)
                  <p class="mt-3 text-white font-weight-medium">
                    Children Details : {{$parent_name}}
                  </p>
                  @else
                  <h6 class="text-white mt-3">No Children has been added by the Parent yet!</h6>
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
                    <p class="card-title mt-3">All Childrens</p>
                   </div>
                  <div class="table-responsive">
                    <table id="example" class="display nowrap" style="width:100%">
                      <thead class="head_color">
                        <tr>
                           <th>Children Name</th>
                           <th>Children Age</th>
                           <th>Children Gender</th>
                        </tr>
                      </thead>
                        @foreach($childrens as $children)
                      <tbody>
                        <tr>
                          <td class="bold"> {{$children->child_name}} </td>
                          <td class="bold"> {{$children->child_age}} </td>
                          <td class="bold"> {{$children->child_gender}} </td>
                        </tr>
                      </tbody>
                      @endforeach
                    </table>
                    {{$childrens->links()}}
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>       
@endsection
</x-app-layout>
