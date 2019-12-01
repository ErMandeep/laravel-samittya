@if(count($teachers)> 0)                                
<section class="famous-teacher-section">
<div class="teacher-slider container">
  <div class="button shiftLeft" onclick="shiftLeft()">
    <i class="fas fa-angle-left"></i>
  </div>
  <div class="cards-wrapper">
      <ul class="cards__container">
        @foreach($teachers as $item)
          <li class="box"><img class="cloud9-item1" src="{{ asset('storage/'.$item->avatar_location) }}" alt="Subin"><h3>{{$item->full_name}}</h3></li>
          @endforeach           
      </ul>
  </div>
  <div class="button" onclick="shiftRight()">
    <i class="fas fa-angle-right"></i>
  </div>
</div> 
</section>
@endif