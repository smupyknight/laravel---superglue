@if(count($users)!==0)
    @foreach($users as $user)
        <tr>
            <td>{{ $user->first_name . ' ' . $user->last_name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ ucfirst($user->type) }}</td>
            <td>{{ $user->created_at->format('d M Y') }}</td>
            <td>{{ $user->last_login }}</td>
            <td>
                <div class="btn-group">
                    <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><a href="/admin/users/view/{{$user->id}}">View</a></li>
                        <li><a href="/admin/users/edit/{{$user->id}}">Edit</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Delete</a></li>
                    </ul>
                </div>
            </td>
        </tr>
    @endforeach
    @if ($users->total() > 2)
        <tr>
            <td colspan="7" align="right">
                {{$users->render()}}
            </td>
        </tr>
    @endif

@else
    <td class="text-center">No user found</td>
@endif
<script>
    $('.pagination li').click(function(e){
        e.preventDefault();
        var link = $(this).find('a').attr('href');
        var global_search = $('input[name="global_search"]').val();
        var type = $('#user_type').val();
        $.ajax({
            url: link,
            type: "post",
            data:
            {
                '_token': '{{csrf_token()}}',
                global_search :global_search,
                type:type

            },
            success: function(html){
                $('.table_body').html('');
                $('.table_body').html(html);
            }
        });
    });
</script>