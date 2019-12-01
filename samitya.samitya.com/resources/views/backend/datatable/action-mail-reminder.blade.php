<a data-method="delete" data-trans-button-cancel="Cancel"
   data-trans-button-confirm="Send" data-trans-title="Are you sure?"
   class="btn btn-success btn-xs text-white mb-1" style="cursor:pointer;"
   onclick="$(this).find('form').submit();">
    <i class="fas fa-envelope"
       data-toggle="tooltip"
       data-placement="top" title="Send Mail"
       data-original-title="Send Mail"></i>
    <form action="{{$route}}"
          method="POST" name="Switch status" style="display:none">
        @csrf
        {{method_field('DELETE')}}
    </form>
</a>
