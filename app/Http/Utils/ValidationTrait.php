<?php namespace StarterKit\Core\Http\Utils;

use Illuminate\Support\Facades\Validator;

trait ValidationTrait
{

    public function validateRequest(array $request, array $rules)
    {
        $validator = Validator::make($request, $rules);


        return Validator::make($request, $rules, $this->getMessages());
    }

    private function getMessages(): array
    {
        return [
            '*.required' => 'Поле обязательно к заполнению',
            '*.required_if' => 'Поле обязательно к заполнению',
            '*.email' => 'Поле должно содержать корректный e-mail',
            '*.integer' => 'Поле должно содержать целое число',
            '*.same' => 'Поле должно быть эквивалентно c предыдущим полем',
            '*.url' => 'Поле должно содержать корректный url',
            '*.unique' => 'Указанный e-mail уже зарегистрирован',
            '*.image*' => 'К загрузке допускаются только изображения',
            '*.numeric*' => 'Поле должно содержать цифры',
            '*.after*' => 'Вы ввели не корректную дату',

            'query.min' => 'Запрос должен содержать не менее :min символов',
            'query.regex' => 'Запрос содержит недопустимые символы',

            'content.min' => 'Поле должно содержать не менее :min символов',
            'content.regex' => 'Поле содержит недопустимые символы',

            'file.mimetypes' => 'Формат файла не поддерживается',
            'file.max' => 'Файл слишком большой (макс. :max kB)',
        ];
    }
}