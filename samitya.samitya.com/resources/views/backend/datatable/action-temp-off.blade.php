<a data-method="delete" data-trans-button-cancel="Cancel"
   data-trans-button-confirm="Switch" data-trans-title="Are you sure?"
   class="btn  {{ $temp_off ? 'btn-danger' : 'btn-warning' }} btn-xs text-white mb-1" style="cursor:pointer;"
   onclick="$(this).find('form').submit();">
    <i class="fas fa-power-off"
       data-toggle="tooltip"
       data-placement="top" title="Switch Status"
       data-original-title="Switch Status"></i>
    <form action="{{$route}}"
          method="POST" name="Switch status" style="display:none">
        @csrf
        {{method_field('DELETE')}}
    </form>
</a>
