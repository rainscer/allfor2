<?php
return [

    "accepted"             => "Вы должны принять :attribute.",
    "active_url"           => "Поле :attribute содержит недействительный URL.",
    "after"                => "В поле :attribute должна быть дата после :date.",
    "alpha"                => "Поле :attribute может содержать только буквы.",
    "alpha_dash"           => "Поле :attribute может содержать только буквы, цифры и дефис.",
    "alpha_num"            => "Поле :attribute может содержать только буквы и цифры.",
    "array"                => "Поле :attribute должно быть массивом.",
    "before"               => "В поле :attribute должна быть дата до :date.",
    "between"              => [
        "numeric" => "Поле :attribute должно быть между :min и :max.",
        "file"    => "Размер файла в поле :attribute должен быть между :min и :max Килобайт(а).",
        "string"  => "Количество символов в поле :attribute должно быть между :min и :max.",
        "array"   => "Количество элементов в поле :attribute должно быть между :min и :max.",
    ],
    "boolean"              => "Поле :attribute должно иметь значение логического типа.", // калька 'истина' или 'ложь' звучала бы слишком неестественно
    "confirmed"            => "Поле :attribute не совпадает с подтверждением.",
    "date"                 => "Поле :attribute не является датой.",
    "date_format"          => "Поле :attribute не соответствует формату :format.",
    "different"            => "Поля :attribute и :other должны различаться.",
    "digits"               => "Длина цифрового поля :attribute должна быть :digits.",
    "digits_between"       => "Длина цифрового поля :attribute должна быть между :min и :max.",
    "email"                => "Поле :attribute должно быть действительным электронным адресом.",
    "filled"               => "Поле :attribute обязательно для заполнения.",
    "exists"               => "Выбранное значение для :attribute некорректно.",
    "image"                => "Поле :attribute должно быть изображением.",
    "in"                   => "Выбранное значение для :attribute ошибочно.",
    "integer"              => "Поле :attribute должно быть целым числом.",
    "ip"                   => "Поле :attribute должно быть действительным IP-адресом.",
    'json'                 => "Поле :attribute должно быть JSON строкой.",
    "max"                  => [
        "numeric" => "Поле :attribute не может быть более :max.",
        "file"    => "Размер файла в поле :attribute не может быть более :max Килобайт(а).",
        "string"  => "Количество символов в поле :attribute не может превышать :max.",
        "array"   => "Количество элементов в поле :attribute не может превышать :max.",
    ],
    "mimes"                => "Поле :attribute должно быть файлом одного из следующих типов: :values.",
    "min"                  => [
        "numeric" => "Поле :attribute должно быть не менее :min.",
        "file"    => "Размер файла в поле :attribute должен быть не менее :min Килобайт(а).",
        "string"  => "Количество символов в поле :attribute должно быть не менее :min.",
        "array"   => "Количество элементов в поле :attribute должно быть не менее :min.",
    ],
    "not_in"               => "Выбранное значение для :attribute ошибочно.",
    "numeric"              => "Поле :attribute должно быть числом.",
    "regex"                => "Поле :attribute имеет ошибочный формат.",
    "required"             => "Поле :attribute обязательно для заполнения.",
    "required_if"          => "Поле :attribute обязательно для заполнения, когда :other равно :value.",
    "required_with"        => "Поле :attribute обязательно для заполнения, когда :values указано.",
    "required_with_all"    => "Поле :attribute обязательно для заполнения, когда :values указано.",
    "required_without"     => "Поле :attribute обязательно для заполнения, когда :values не указано.",
    "required_without_all" => "Поле :attribute обязательно для заполнения, когда ни одно из :values не указано.",
    "same"                 => "Значение :attribute должно совпадать с :other.",
    "size"                 => [
        "numeric" => "Поле :attribute должно быть равным :size.",
        "file"    => "Размер файла в поле :attribute должен быть равен :size Килобайт(а).",
        "string"  => "Количество символов в поле :attribute должно быть равным :size.",
        "array"   => "Количество элементов в поле :attribute должно быть равным :size.",
    ],
    "string"               => "Поле :attribute должно быть строкой.",
    "timezone"             => "Поле :attribute должно быть действительным часовым поясом.",
    "unique"               => "Такое значение поля :attribute уже существует.",
    "url"                  => "Поле :attribute имеет ошибочный формат.",


'custom' => array(
    'email' => array(
        'required' => 'E-mail не может быть пустым',
        'email' => 'Введите корректный e-mail',
    ),
    'password' => array(
        'required'   => 'Пароль не может быть пустым',
        'confirmed'  => 'Пароль не совпадает с подтверждением.',
        'min'        => 'Количество символов в пароле должно быть не менее :min.',
    ),
    'd_user_last_name' => array(
        'required' => 'Поле "Фамилия" обязательно'
    ),
    'd_user_address' => array(
        'required' => 'Поле "Адрес" обязательно',
        'regex' => 'Используйте латинские буквы',
    ),
    'd_user_city' => array(
        'required'  => 'Данное поле обязательно',
        'min'       => 'Выберите город',
    ),
    'd_user_region' => array(
        'required'  => 'Данное поле обязательно',
        'min'       => 'Выберите область',
    ),
    'd_user_index' => array(
        'required'  => 'Поле "Индекс" обязательно',
        'numeric'   => 'Индекс - только цифры',
    ),
    'name' => array(
        'required'  => 'Поле "Имя" обязательно',
    ),
    'last_name' => array(
        'required'  => 'Поле "Фамилия" обязательно',
    ),
    'd_user_phone' => array(
        'required'  => 'Поле "Телефон" обязательно'
    ),
    'subject' => [
        'required'  => 'Тема обязательна для заполнения'
    ],
    'message' => [
        'required'  => 'Заполните поле сообщения'
    ]
),
    ];