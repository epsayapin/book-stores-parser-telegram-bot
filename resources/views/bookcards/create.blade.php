<h1>{{ $bookCard->title }}</h1>
<p>
	Автор: {{ $bookCard->author }}<br>
	Цена в локальном магазине: {{ $bookCard->localPrice  }} <br>
	Цена в интернет магазине: {{ $bookCard->internetPrice  }}<br>
	Количество страниц: {{ $bookCard->countPages  }}<br>
	Обложка: {{ $bookCard->coverFormat  }}<br>
	Код товара: {{ $bookCard->code  }}<br>

</p>