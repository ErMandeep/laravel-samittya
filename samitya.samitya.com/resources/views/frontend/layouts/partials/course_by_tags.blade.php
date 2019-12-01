
<section class="categories-tag-section">
  
  <div class="container">
    <div class="row mt40">
        @if($course_tags)
        @php $n= 1;  @endphp
        @foreach($course_tags->take(10) as $tags)
            <div class="tag @php echo ($n == 4 || $n == 5 ) ? 'col-md-6' : '';  @endphp"><a href="{{route('courses.tag',['tag'=>$tags->slug])}}">{{$tags->name}}</a></div>
            @php $n++; @endphp
        @endforeach

        @endif
    </div>
    <div class="samitya-button mt25">
      <a href="/courses">View All</a>
  </div>
  </div>
</section>

