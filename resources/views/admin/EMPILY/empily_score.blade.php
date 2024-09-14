@extends('admin.layouts.app')
<style>
                          * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                  }

                  :root {
                    --progress-bar-width: 200px;
                    --progress-bar-height: 200px;
                    --font-size: 1.3rem;
                  }

                  /* body {
                    height: 100vh;
                    background-color: #100c08;
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                    place-items: center;
                    gap: 2rem;
                  } */
                  .circular-progress {
                    width: var(--progress-bar-width);
                    height: var(--progress-bar-height);
                    border-radius: 50%;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                  }
                  .inner-circle {
                    position: absolute;
                    width: calc(var(--progress-bar-width) - 50px);
                    height: calc(var(--progress-bar-height) - 50px);
                    border-radius: 50%;
                    background-color: white;
                  }

                  .percentage {
                    position: relative;
                    font-size: var(--font-size);
                    color: rgb(0, 0, 0, 0.8);
                  }

                  @media screen and (max-width: 800px) {
                    :root {
                      --progress-bar-width: 150px;
                      --progress-bar-height: 150px;
                      --font-size: 1rem;
                    }
                  }

                  @media screen and (max-width: 500px) {
                    :root {
                      --progress-bar-width: 120px;
                      --progress-bar-height: 120px;
                      --font-size: 0.5rem;
                    }
                  }
      </style>
  @section('content')
  @if(session('success'))                                     
      <script type="text/javascript">toastr.success("{{session('success')}}");</script> 
  @endif
  @if(session('error'))                                
      <script type="text/javascript">toastr.error("{{session('error')}}")</script> 
  @endif

    <div class="pxp-dashboard-content-details" >
      <div class="d-flex justify-content-between">
          <h4 class="text-themecolor">EMPILY Score</h4>  
      </div>
      <div class="alert">
        <div class="row">
             
            <div class="col-md-6 col-lg-6 col-sm-6" >
                <div class="circular-progress" data-inner-circle-color="white" data-percentage="{{round(($total_point/$max_total_point)*100)}}" data-progress-color="#fe8b10" data-bg-color="black">
                  <div class="inner-circle"></div>
                  <p class="percentage">0%</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-6">
                <p><strong>Name: {{$candidate->name}}</strong></p>
                <p><strong>Email: {{$candidate->email}}</strong></p>
                <p><strong>Phone: {{$candidate->phone}}</strong></p>
            </div>                
        </div>        
      </div>     
      
      <div class="pxp-dashboard-content-details"> 
        
        
        {{--<div class="progress mb-3" style="height: 30px;">
          <div class="progress-bar bg-success" role="progressbar" style="width: {{($total_point/$max_total_point)*100}}%;" aria-valuenow="{{$total_point}}" aria-valuemin="0" aria-valuemax="100">{{$total_point}} out of {{$max_total_point}}</div>
        </div>--}}
        <div class="candidate_List_box_inner">
          <div class="table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th>Attributes</th>
                  <th>Point</th>
                </tr>
              </thead>
              <tbody>    
                @foreach($points_detail as $key=>$point)            
                <tr>
                    <td>{{ucwords(str_replace('_',' ',$key))}}</td>
                    <td>{{$point}}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>                
        </div>
      </div>
    </div>

    
@endsection
@push('js')
<script>
      const circularProgress = document.querySelectorAll(".circular-progress");
                  let anu="{{$total_point}} out of {{$max_total_point}}" ;
        Array.from(circularProgress).forEach((progressBar) => {
          const progressValue = progressBar.querySelector(".percentage");
          const innerCircle = progressBar.querySelector(".inner-circle");
          let startValue = 0,
            endValue = Number(progressBar.getAttribute("data-percentage")),
            speed = 50,
            progressColor = progressBar.getAttribute("data-progress-color");

          const progress = setInterval(() => {
            startValue++;
            progressValue.textContent = `${anu}`;
            progressValue.style.color = `${progressColor}`;

            innerCircle.style.backgroundColor = `${progressBar.getAttribute(
              "data-inner-circle-color"
            )}`;

            progressBar.style.background = `conic-gradient(${progressColor} ${
              startValue * 3.6
            }deg,${progressBar.getAttribute("data-bg-color")} 0deg)`;
            if (startValue === endValue) {
              clearInterval(progress);
            }
          }, speed);
        });
</script>
@endpush