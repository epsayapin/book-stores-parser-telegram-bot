
<ul>
@foreach($searchResult->bookList as $book)
<li>{{ $book["title"] . "  " . $book["code"] }}</li>
@endforeach
</ul>

<p>Total page is {{ $searchResult->countPages }} </p>
<p>Current page is {{ $searchResult->currentPage }} </p>