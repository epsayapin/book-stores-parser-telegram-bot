<h1>Books List</h1>
<ul>
	@foreach($bookslist as $book)
	<li>{{$book}}</li>
	@endforeach

</ul>

<h2>Crawler</h2>

{{ $crawler->html() }}