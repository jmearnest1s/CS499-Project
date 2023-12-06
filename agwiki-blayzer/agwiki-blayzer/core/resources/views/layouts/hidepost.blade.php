//this file contains the the hide post functionality

<form action="{{ route('posts.hide', $post->id) }}" method="post">
    @csrf
    <p>{{ $post->content }}</p>
    <button type="submit">Hide Post</button>
</form>
