# Czy Ola jest Guru?

Pytanie zadawane od początków wszechświata lub nawet jeszcze wcześniej. Kto pierwszy je zadał? Kiedy? Istnieją zagadki, na które nigdy nie znajdziemy odpowiedzi. Ale jest szansa na poznanie prawdy:
*Czy Ola jest guru?*

http://czyolajestguru.tk


## Konfiguracja 

Konfigurację treści oraz reCAPTCHA v2 należy wykonać w pliku _/src/settings.php_
```
'captcha' => [
    'sitekey' => 'xxxxxxxxxxxxxxxxxxxxxxx',
    'secretkey' => 'xxxxxxxxxxxxxxxxxxxxxxx'
],
'site' => [
    'base_url' => base_url(),
    'title' => 'Czy Ola jest guru?',
    'question' => 'Czy Ola jest dzisiaj guru?',
    'description' => 'Na ile procent Ola jest dziś guru? Przedstaw swoją opinię i zobacz codzienną statystykę!',
    'yesbtn' => 'Tak!',
    'nobtn' => 'Zdecydowanie nie.',
    'results_text' => 'Dzisiaj Ola jest guru na ${value}%!',
    'empty_results_text' => 'Dzisiaj jeszcze nie wiadomo jak bardzo Ola jest guru. Bądź pierwszy i oddaj głos!',
]
```