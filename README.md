<p>
	book-stores-parser-telegram-bot
	
	Чтобы посмотреть как работает:
	1. git clone https://github.com/epsayapin/book-stores-parser-telegram-bot.git
    2. В env указываем данные для БД - название, логин и пароль
    3. В env указываем токен бота
    4. composer install
    5. php artisan migrate
    6. php artisan telegram-updates-cron:start
    7. Ввести поисковый запрос в чате с ботом
    
    Для работы с API телеграма используется https://github.com/irazasyed/telegram-bot-sdk
</p>

## 