
<a data-method="ignore" data-trans-button-cancel="Cancel"
   data-trans-button-confirm="Ignore" data-trans-title="Are you sure?"
   class="btn btn-xs btn-success text-white mb-1" style="cursor:pointer;"
   onclick="$(this).find('form').submit();">
    Ignore
    <form action="{{$route}}"
          method="POST" name="ignore_user" style="display:none">
        @csrf
        {{method_field('DELETE')}}
    </form>
</a>
