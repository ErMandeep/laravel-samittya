<a data-method="delete" data-trans-button-cancel="Cancel"
   data-trans-button-confirm="Report" data-trans-title="Are you sure?"
   class="btn btn-xs btn-danger text-white mb-1" style="cursor:pointer;"
   onclick="$(this).find('form').submit();">
    Block Messages
    <form action="{{$route}}"
          method="POST" name="delete_item" style="display:none">
        @csrf
    </form>
</a>
