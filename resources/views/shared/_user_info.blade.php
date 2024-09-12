<a href="{{ route('users.show', $user->id) }}">
{{--    <img src="{{ $user->gravatar('140') }}" alt="{{ $user->name }}" class="gravatar"/>--}}
    <img src="{{ asset('images/R-C.png') }}" alt="{{ $user->name }}" class="gravatar" width="80px"/>
</a>
<h1>{{ $user->name }}</h1>
