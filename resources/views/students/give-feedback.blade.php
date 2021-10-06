<x-app-layout>
 @section('content')
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
                <div class="card-body py-1 px-4 d-flex justify-content-between flex-wrap d-flex">
                  <p class="mt-3 text-white font-weight-medium">
                   Give feedback to Class
                  </p>
                  <a href="/view-classes" class="btn btn-light bold" id="back-button" onclick="history.go(-1);">Go Back</a>
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
            <div class="col-md-12">
              <div class="container feedback">
                <div class="card">
                  <div class="card-body">
                        <form method="post" action="/post-classfeedback/{{$id}}">
                          @csrf
                          <div class="heading">
                            <h1 class="text-center">Feedback to Class</h1>
                               <div class="star-rating">
                                  <input id="star-5" type="radio" name="rating"  value="5" />
                                  <label for="star-5" data-toggle="tooltip" data-placement="bottom" title="☆☆☆☆☆ Excellent">
                                    <i class="active fa fa-star" aria-hidden="true"></i>
                                  </label>
                                  <input id="star-4" type="radio" name="rating" value="4" />
                                  <label for="star-4" data-toggle="tooltip" data-placement="bottom" title="☆☆☆☆ Good">
                                    <i class="active fa fa-star" aria-hidden="true"></i>
                                  </label>
                                  <input id="star-3" type="radio" name="rating" value="3" />
                                  <label for="star-3" data-toggle="tooltip" data-placement="bottom" title="☆☆☆ Average">
                                    <i class="active fa fa-star" aria-hidden="true"></i>
                                  </label>
                                  <input id="star-2" type="radio" name="rating" value="2" />
                                  <label for="star-2" data-toggle="tooltip" data-placement="bottom" title="☆☆ Poor">
                                    <i class="active fa fa-star" aria-hidden="true"></i>
                                  </label>
                                  <input id="star-1" type="radio" name="rating" value="1" />
                                  <label for="star-1" data-toggle="tooltip" data-placement="bottom" title="☆ Terrible">
                                    <i class="active fa fa-star" aria-hidden="true"></i>
                                  </label>
                               </div>
                              <div class="textarea">
                                <input type="textarea" id="feedback_text" maxlength="190" name="feedback" id="textarea" placeholder="Type your words here..">
                                <div class="float-right">
                                <span id="tchars">0</span> /190
                                </div>
                              </div>
                              <input type="submit" name="submit" value="Send Feedback" class="btn btn-info btn-lg" id="purple_bg_corner">
                          </div>
                        </form>
                  </div>
                </div>
            </div>
          </div>
      </div>    
@endsection
</x-app-layout>
