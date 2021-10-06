<x-app-layout>
 @section('content')
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
                <div class="card-body py-1 px-4 d-flex justify-content-between flex-wrap d-flex">
                  <p class="mt-3 text-white font-weight-medium">
                   Edit Keyword
                  </p>
                  <a href="/all-keywords" class="btn btn-light bold" id="back-button" onclick="history.go(-1);">Go Back</a>
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
                    <p class="card-title mt-3">Edit Keyword</p>
                  </div>
                  @foreach($keywords as $keyword)
                   <form method="POST" action="/update-keyword/{{$keyword->id}}" enctype="multipart/form-data">
                   @csrf
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Keyword</label>
                          <input type="text" value="{{$keyword->keywords}}" class="form-control @error('keywords') is-invalid @enderror" name="keywords" placeholder="Enter Keywords" data-role="tagsinput" >
                        </div>
                      </div> 
                    </div>
                     <button type="submit" class="btn btn-info">Update</button>
                  </form>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
      </div>    
@endsection
</x-app-layout>
