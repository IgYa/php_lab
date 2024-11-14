# php_lab3
Cookie. Session. Робота з файлами та каталогами 

Мета роботи: навчитися працювати з файлами: створювати, читати,
записувати і видаляти дані з файлів, навчитися працювати з каталогами, cookie,
session, передавати файли через форми і приймати файли у PHP-скрипті

**Завдання на лабораторну роботу**

**Завдання 1: Робота з cookie**

- Створіть на сторінці 3 посилання: «Великий шрифт», «Середній шрифт», «Маленький шрифт»
- В залежності від того, на яке посилання натисне користувач, ви повинні
	встановити певний розмір шрифту, при цьому він повинен зберігатися і при
	переході на інші сторінки поточного сайту

**Завдання 2: Робота з session**

-  Створіть форму авторизації з полями «Логін» та «Пароль»
-  Якщо був введений логін «Admin» та пароль «password», то вивести
	повідомлення «Добрий день, Admin!», інакше вивести повідомлення про
	евірність введення логіна або пароля
-  При кожному оновленні сторінки, якщо користувач вже пройшов успішно
	авторизацію, повинно виводитись привітання, а якщо користувач не
	пройшов авторизацію, або вийшов, то вивести форму авторизації

**Завдання 3: Робота з файлами (3 завдання)**

1. Створіть форму з полями Ім’я та Коментар.

- Опрацюйте форму, записавши введені дані в файл з коментарями.
- На цій же сторінці виведіть всі поточні коментарі в таблицю, зчитавши їх з
	файла (1 коментар – 1 рядок).
- Примітка1: ви повинні подумати, в якому вигляді треба зберігати
	коментарі в файл, та чим розділяти самі коментарі, щоб потім було
	легко розібрати цей файл і вивести необхідну інформацію на сторінку.
- Примітка2: Можете використати функції fgets() та fseek()

2. Дано два файли зі словами, розділеними пробілами.

- Створити 3 нових файли, які будуть містити:
	а) рядки, які зустрічаються тільки в першому файлі;
	б) рядки, які зустрічаються в обох файлах;
	в) рядки, які зустрічаються в кожному файлі більше двох разів.
- Створити форму, в яку користувач вводить ім’я файлу з тих що були
	створені вище, і цей файл має бути видалений

3. Дан файл зі словами. Впорядкувати слова за алфавітом.

**Завдання 4: Передача файлів через форми і прийом файлів у PHP-скрипті**

Розробіть форму для завантаження зображень на сервер. Після відправлення
форми, зображення повинні бути прийняті PHP-скриптом, завантажені на сервер
та збережені в певному каталозі.

**Завдання 5: Робота з каталогами**

- Створіть форму з наступними полями: Логін та Пароль
- Опрацюйте форму та створіть папку з ім’ям логіна, якщо такої папки
	ще немає. А якщо є, то вивести повідомлення про помилку
- Всередині створеної папки створіть підпапки video, music, photo, а
	також декілька файлів всередині цих папок.
- Створіть окрему сторінку delete.php з полями Логін та Пароль
- Якщо ім’я логіна співпадає з ім’ям створеної папки, що була створена
	з попередньої сторінки, то вилучити цю папку з усім вмістом
	