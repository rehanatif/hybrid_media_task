<ol>
    @foreach($rows->users as $user)
        <li>{{$user->name}}</li>
    @endforeach
</ol>