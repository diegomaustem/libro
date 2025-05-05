Backend - API - Libro

Instruções Iniciais

1 - Clonar o projeto.
● git clone git@github.com:diegomaustem/libro.git

2 - Instalar o composer no projeto.
● composer install

3 - Crie o banco.
● CREATE DATABASE libro_db;

4 - Crie e configure o arquivo .env com as credenciais do banco.

5 - Gere a key da aplicação.
● php artisan key:generate

6 - Gerar e adicionar a chave secreta para autenticação por JWT no .env
● php artisan jwt:secret

7 - Execute as migrations.
● php artisan migrate:fresh --seed

8 - Sirva a aplicação.
● php artisan serve

Instruções Endpoints

Alunos

    GET – QueryParams

    Busca por nome: http://127.0.0.1:8000/libro/students?name=tiago

    Busca por email: http://127.0.0.1:8000/libro/students?email=tiago@com

    Buscar por nome e email junto: http://127.0.0.1:8000/libro/students?name=ana&email=tiago@com

    Buscar por filtro: http://127.0.0.1:8000/libro/students?query=filters

    A busca por filtro acima, exibe o total de alunos por faixa etária, curso e sexo.

    GET - http://127.0.0.1:8000/libro/students

    GET - http://127.0.0.1:8000/libro/students/id_student

    POST - http://127.0.0.1:8000/libro/students

    Exemplo JSON : { "name": "Carla Holff", "email": "carla@gmail.com", "gender": "F",
    "data_of_birth": "11-05-1995" }

    PATCH - http://127.0.0.1:8000/libro/students/id_student

    Exemplo JSON : { "name": "Carla Holff Edit", "email": "carla@gmail.com", "gender":
    "F", "data_of_birth": "11-05-1992" }

    DELETE - http://127.0.0.1:8000/libro/students/id_student

Cursos

    GET - http://127.0.0.1:8000/libro/courses

    GET - http://127.0.0.1:8000/libro/courses/id_course

    POST - http://127.0.0.1:8000/libro/courses
    Exemplo JSON: { "title": "Math", "description": "The best course."}

    PATCH - http://127.0.0.1:8000/libro/courses/id_course
    Exemplo JSON: {"title": "Math Edt", “description": "The best course."}

    DELETE - http://127.0.0.1:8000/libro/courses/id_course

Matrículas

    GET - http://127.0.0.1:8000/libro/registrations

    GET - http://127.0.0.1:8000/libro/registration/id_registration

    POST - http://127.0.0.1:8000/libro/registrations
    Exemplo JSON:{“course_id": 9,"student_id": 8}

    PUT - POST - http://127.0.0.1:8000/libro/registration/id_registration
    Exemplo JSON {"course_id": 1, "student_id": 2}

    DELETE - http://127.0.0.1:8000/libro/registration/id_registration

Listagem de alunos por curso

    GET - http://127.0.0.1:8000/libro/enrolledPerCourse/id_course

Listagem de professores

    GET - http://127.0.0.1:8000/libro/teachers

    GET - http://127.0.0.1:8000/libro/teachers/id_teacher

    POST - http://127.0.0.1:8000/libro/teachers
    Exemplo JSON : { "name": "Carla Holff", "email": "carla@gmail.com",
     "phone": "048983325854","formation": "The Science", "gender": "F", "data_of_birth": "11-05-1995"}

    PATCH - POST - http://127.0.0.1:8000/libro/teachers/id_teacher
    Exemplo JSON : { "name": "Carla Holff", "email": "carla@gmail.com",
     "phone": "048983325854","formation": "The Science", "gender": "F", "data_of_birth": "11-05-1995"}

    DELETE - http://127.0.0.1:8000/libro/teachers/id_teacher

Instruções Testes Unitários

Foram montados dois cenários de testes para cursos e três cenários para alunos.

Testes Cursos

1 – Testa se o retorno da requisição é uma lista de cursos.

2 – Testa a exclusão de um curso.

Rodar os testes : php artisan test tests/Feature/CourseControllerTest.php

Testes Alunos

1 – Testa o retorno de um aluno.

2 – Testa a inserção de um aluno.

3 – Testa a exclusão de um aluno que possui matrícula em algum curso.

Rodar os testes: php artisan test tests/Feature/StudentControllerTest.php
