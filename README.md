### Использование

#### Получение отформатированной даты

Выполняем скрипт с указанием даты для вывода отформатрованной даты

```{r, engine='bash', format}
composer run-script format 01.01.2018
> dostoevskiy\wdate\Runner::getFormattedDate
01 января
```
### Сравнение дат

Выполняем скрипт с указанием 2-х дат для вывода отформатрованного результата сравнения

```{r, engine='bash', compare}
composer run-script compare "14.02.2017" "01: 14.02.2017"
> dostoevskiy\wdate\Runner::compare
Вторая дата больше первой

composer run-script compare 14.02.2017 14.02.2017
> dostoevskiy\wdate\Runner::compare
Даты равны

composer run-script compare 14.02.2017 10:00:12
> dostoevskiy\wdate\Runner::compare
Первая дата больше второй
```

### Тесты

Всё настроено, нужно просто запустить 
```terminal
composer run-script test
```
в корне проекта

