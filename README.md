# Тестовое задание
## Задача: Разработка простого API для управления задачами

Реализовано <b>REST API</b> для управления списком задач, методы:

    GET /api/tasks
    POST /api/tasks
    GET /api/tasks/{id}
    POST /api/tasks
    DELETE /api/tasks/{id}

Вывод всех задач реализован с пагинацией\
<img width="528" height="644" alt="image" src="https://github.com/user-attachments/assets/515e6e2c-40ed-44ce-a79c-cceb9f484355" />


В качестве базы данных использовалась MySQL, реализованы проверки данных через <a href="https://github.com/M1estere/To-Do-API/blob/main/app/Http/Requests/TaskRequest.php">TaskRequest</a>\
Также описана и сгенерирована swagger <a href="https://github.com/M1estere/To-Do-API/blob/main/public/api-docs.json">документация</a>, открывающаяся по маршруту /docs

<img width="1461" height="844" alt="image" src="https://github.com/user-attachments/assets/1fc63f8e-9ac8-4c09-9536-0e7a4cac71fd" />
